<?php
/*
    Image to doodle
    Made by Techy
    
    This was striped from my testing bot
*/
case "draw":
    //test image - https://s-media-cache-ak0.pinimg.com/originals/87/fd/02/87fd021b7184580473615ba7b416bfa5.jpg
    if (!isset($message[2])) {
        return $bot->network->sendMessageAutoDetection($who, "No Image found", $type);
    }
    /*
        TODO
        better image detection
    */
    $url = $message[2];
    $info = getimagesize($url);
    if($info[0] > 425 || $info[1] > 425) {
        return $bot->network->sendMessageAutoDetection($who, "This image is too big, max = 425x425", $type);
    }
    
    $image = null;
    switch($info['mime']) {
        case "image/gif": $image = imagecreatefromgif($url); break;
        case "image/jpeg": $image = imagecreatefromjpeg($url); break;
        case "image/png": $image = imagecreatefrompng($url); break;
        default: return $bot->network->sendMessageAutoDetection($who, "This image must be png, jpg or gif", $type); break;
    }
    $bot->network->sendMessageAutoDetection($who, "Analyzing " . substr($info['mime'], 6), $type);
    $strokes = [];
    for ($y = 0; $y < $info[1]; $y++) {
        for ($x = 0; $x < $info[0]; $x++) {
            $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));
            if ($rgb['red'] . $rgb['green'] . $rgb['blue'] != '255255255') {
                if ($rgb['alpha'] != 127) {
                    if (count($strokes) > 0) {
                        $prev = $strokes[count($strokes) - 1];
                        $currcol = $rgb['red'] . $rgb['green'] . $rgb['blue'] . $rgb['alpha'];
                        $prevcol = $prev['red'] . $prev['green'] . $prev['blue'] . $prev['alpha'];
                        if ($prev['y'] == $y && $currcol == $prevcol && ($prev['x'] + $prev['distance']) == $x) {
                            $strokes[count($strokes) - 1]['distance']++;//stroke bunching for faster drawing
                        } else {
                            $strokes[] = ['red' => $rgb['red'], 'green' => $rgb['green'], 'blue' => $rgb['blue'], 'alpha' => $rgb['alpha'], 'x' => $x, 'y' => $y, 'distance' => 1];
                        }
                    }  else {   
                        $strokes[] = ['red' => $rgb['red'], 'green' => $rgb['green'], 'blue' => $rgb['blue'], 'alpha' => $rgb['alpha'], 'x' => $x, 'y' => $y, 'distance' => 1];
                    }
                }
            }
        }
    }
    if (count($strokes) < 1) {
        return $bot->network->sendMessageAutoDetection($who, "Image is all white or is blank", $type);
    }
    $estimated = floor((count($strokes) / 500) * 30);//about 500 strokes per 30 seconds
    $bot->network->sendMessageAutoDetection($who, "Drawing image. Strokes required: " . count($strokes) . ", Estimated time: " . $estimated . " - " . ($estimated + 2) . " seconds (old estimate)", $type);
    $startTime = time();
    $num = 0;
    foreach($strokes as $stroke) {
        if ($info[0] < 425) {//ghetto image centering
            $stroke['x'] = (212 - floor($info[0] / 2)) + $stroke['x'];
        }
        if ($info[1] < 425) {//ghetto image centering
            $stroke['y'] = (212 - floor($info[1] / 2)) + $stroke['y'];
        }
        $strke = [2, 0, $stroke['red'], $stroke['green'], $stroke['blue'], 100 - $stroke['alpha'], $stroke['x'] >> 8 & 255, $stroke['x'] & 255, $stroke['y'] >> 8 & 255, $stroke['y'] & 255];
        for($i = 1;$i <= $stroke['distance'];$i++) {//stroke bunching for faster drawing
            $strke[] = 128;
            $strke[] = 127;
        }
        $strke[] = 0;
        $strke[] = 0;
        $bot->network->write('x', ['i'	=> 10000, 'd' => $bot->network->logininfo['i'], 't' => base64_encode(pack("C*", ...$strke))]);//everyone out her using a ported as3 fucntion for this ahahahahahah
        if ($num % 40 == 0 || $num % 500 == 0) {
            usleep(60000);  
        }
        $num++;
    }
    $bot->network->sendMessageAutoDetection($who, "Drawing Finished, " . (time() - $startTime) . " seconds", $type);
    break;
?>