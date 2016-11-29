<?php
/*
    Blackfriday Offers
    Made by Techy
*/

$file = file_get_contents('bfjson.php');
$json = explode(chr(10), explode('?>', $file)[1]);
$powers = [];
foreach ($json as $j) {
    $j = json_decode($j, true);
    foreach ($j['p'] as $p) {
        $powers[] = $p;
    }
}
$arr = array_count_values($powers);
arsort($arr);

function h2dh($h) {
    return $h < 24 ? "{$h} hours":(fmod($h, 24) > 0 ? intdiv($h, 24) . " days " . fmod($h, 24) . " hours":intdiv($h, 24) . " days");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> BlackFriday Power Listing </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container-fluid">
            <center><h2>Total Offers: <?=count($powers) / 4;?> | Total Powers: <?=count($powers);?> | Unique Powers: <?=count($arr);?> | Running for <?=h2dh(intdiv(count($powers), 4));?>.</h2></center><br>
            <div class="content-wrapper">
                <center><h3>Latest Offered Powers</h3></center>
                <div class="row">
                <div class="col-xs-4"></div>
                <?php
                    $j = json_decode($json[count($json) - 2], true);
                    foreach ($j['p'] as $p) {
                        print '<div class="col-xs-1"><div class="thumbnail"><img width="30" height="30" src="http://xat.com/images/smw/' . strtolower($p) . '.png"><div class="caption"><center><h5>' . $p . '</h5></center></div></div></div>';
                    }
                ?>
                <div class="col-xs-4"></div>
                </div>
                <center><h3>All Offered Powers</h3></center>
                <div class="row">
                <?php
                    foreach ($arr as $key => $value){
                        print '<div class="col-xs-1"><div class="thumbnail"><img width="30" height="30" src="http://xat.com/images/smw/' . strtolower($key) . '.png"><div class="caption"><center><h5>' . $key . '</h5><h6>Offered ' . $value . ' time(s)</h6></center></div></div></div>';
                    }
                ?>
                </div>
            </div>
        </div>
    </body>
</html>