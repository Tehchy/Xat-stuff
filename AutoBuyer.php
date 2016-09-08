<?php
/*
    Xat latest power autobuyer
    Made By Techy
    
    This is a rough script (somethings might not work)
    Not testing enough

*/

$ad = json_decode(file_get_contents('https://xat.com/json/ad.php?' . time()), true);
if ($ad["t"] < time()) {
    exit("Wait till next release to be updated");
    //will fix in the future to make it check every so often for a update
}
$maxAmount = 4;//Most of them time
if (!empty($ad['m2']) && strpos($ad['m2'], "Max:")) {
    //there is a max
    $maxAmount = intval(explode('Max:', $ad['m2'])[1]);
}
$powers = json_decode(file_get_contents('http://xat.com/json/powers.php?' . time()), true);
//powers.php should be more reliable then pow2 because the power is already added to store
$id = key($powers);

//Stuff to edit
$username = '';
$password = '';
$amount = '1';//can set to $maxAmount
$lc = 'something_something_something_something';//lc cookie you get from xat

$stream['http']['method']  = 'POST';
$stream['http']['header']  = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.57 Safari/537.36\r\n";
$stream['http']['header'] .= "Content-Type: application/x-www-form-urlencoded\r\n";
$stream['http']['header'] .= "Cookie: lc ={$lc};\r\n";
$stream['http']['content'] = "num{$id}={$amount}&YourEmail={$username}&password={$password}&agree=ON&BuyIt=";

while (time() < $ad['t']) {
    print "Waiting" . PHP_EOL;//testing
}

$res = file_get_contents('https://xat.com/web_gear/chat/GetPowers.php', false, stream_context_create($stream));
if (strpos($res, 'FlashVars="id=9&pw=##"')) {
    exit('Completed');
} else {
    exit('Failed');
}
?>