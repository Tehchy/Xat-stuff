<?php
/*
    Xat Set Scroller API
    Made by Techy
    This is a sample script
*/
$Query['Message'] = 'Techy is 1337'; // Scroller message
$Query['id'] = '1234567890'; // Chat id
$Query['pw'] = '1234567890'; // Chat pw

$data = file_get_contents('http://xat.com/web_gear/chat/SetScroller.php?' . http_build_query($Query));
print $data;
?>