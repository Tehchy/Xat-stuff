<?php
/*
    Auto win snakeban
    Made by Techy
    
    Xat doesnt check if you really played so yolo
    if admins want me to remove this contact me on the forums ;)
    
    This was striped from my testing bot
*/
$userid = '1234567890';
$chatid = '1234567890';
/*
    BanTime is required or this wont work
    BanTime is the numbers after the /g in the ban packet you receive
*/
$t = 0;//BanTime
$StartTime = time();

$hours = $t == 0 ? (100 * 3600) : ($t == 1 ? 1 : ($t - $StartTime));
$hours = min(max(($hours / 3600), 0.1), 100);

$calories = (6 - floor(((6 * ($hours - 1)) / 100)));
$apples = floor(((((0.05 * ((($hours < 1)) ? $hours : 1)) + (0.002 * $hours)) * (32 * 24)) / $calories));
$apples = max($apples, 1);
$calories = min($calories, 6);

$stream['http']['method'] = 'POST';
$stream['http']['header'] = 'Content-Type: application/octet-stream';
$stream['http']['content'] = pack("N8", 1, $userid, $chatid, $t, '134', $apples, $calories, $StartTime);

$res = file_get_contents('http://xat.com/web_gear/chat/snakeban.php', false, stream_context_create($stream));

print_r($res);
if ($res == "OUT OF DATA") {
    exit('RIP');
}
?>