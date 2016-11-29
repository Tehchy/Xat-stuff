<?php
/*
    Blackfriday Offer Saver
    Made by Techy
*/

$page = file_get_contents('http://xat.com/json/ad.php?c=24666594&t=' . time());
$json = json_decode($page);
$lastSaved = 1479999600;
while(1){
    if (!isset($json->p) || !isset($json->t) || $json->t == 1) {
        die('Blackfriday is over.');
    }
    if($lastSaved != $json->t) {
        $lastSaved = $json->t;
        print "New Offer Saved - {$page}" .PHP_EOL;
        file_put_contents('bfjson.php', $page . PHP_EOL,FILE_APPEND);
    } else {
        if($json->t - time() <= 0) {
            print "New Offer Grabbed" .PHP_EOL;
            $page = file_get_contents('http://xat.com/json/ad.php?c=24666594&t=' . time());
            $json = json_decode($page);
            print_r($json);
        }
    }
}
?>