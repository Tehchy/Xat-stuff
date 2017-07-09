<?php
/*
    Xat latest power assets downloader
    Made By Techy
    
    This script is used to download the latest power's assets
    such as the power, smilies, pawns, hugs and png image of the power
    
    Last Modified 7/6/17
*/
$domain = "http://xat.com";
$pow2 = json_decode(file_get_contents($domain . '/web_gear/chat/pow2.php?' . time()), true);
$powers = json_decode(file_get_contents($domain . '/json/powers.php?' . time()), true);
$id = end($pow2[6][1]) > $pow2[0][1]['id'] ? end($pow2[6][1]):$pow2[0][1]['id'];//find latest
$id = $id >= key($powers) ? $id:key($powers);
$id = count(array_keys($pow2[4][1], $id + 1)) > 0 ? $id + 1:$id;
$smw = array_search($id, $pow2[6][1]);
$sm2 = array_merge(array($smw), array_keys($pow2[4][1], $id));//add power and smilies
$hugs = array();
foreach($pow2[3][1] as $name => $hid) if($hid % 10000 == $id) $hugs[] = $name;//add hugs
foreach($pow2[7][1] as $pawn) if($pawn[0] == $id) $sm2[] = $pawn[1];//add pawns

print "Grabbing Assets for '{$smw}'" . PHP_EOL;

if(count($sm2) > 0) {
	foreach($sm2 as $sm) {
		print "Downloading " . (substr($sm,0, 2) == "p1" ? "Pawn":"Smilie") . ": {$sm} - ";
		$swf = @file_get_contents("{$domain}/images/sm2/{$sm}.swf?" . time());
		if($swf) {
			if(file_exists("sm2/{$sm}.swf")) {
				print "Already Exist" . PHP_EOL;
			} else {
				if (!is_dir('sm2')) {
					mkdir("sm2", 0777, true);
				}
				file_put_contents("sm2/{$sm}.swf", $swf);
				print "Success" . PHP_EOL;
			}
		} else {
			print "Failed" . PHP_EOL;
		}
	}	
}
if(count($hugs) > 0) {
	foreach($hugs as $hug) {
		print "Downloading Hug: {$hug} - ";
		$swf = @file_get_contents("{$domain}/images/hug/{$hug}.swf?" . time());
		if($swf) {
			if(file_exists("hug/{$hug}.swf")) {
				print "Already Exist" . PHP_EOL;
			} else {
				if (!is_dir('hug')) {
					mkdir("hug", 0777, true);
				}
				file_put_contents("hug/{$hug}.swf", $swf);
				print "Success" . PHP_EOL;
			}
		} else {
			print "Failed" . PHP_EOL;
		}
	}
}
if($smw) {
	print "Downloading Smilie Png: {$smw} - ";
	$png = @file_get_contents("{$domain}/images/smw/{$smw}.png?" . time());
	if($png) {
		if(file_exists("smw/{$smw}.png")) {
			print "Already Exist" . PHP_EOL;
		} else {
			if (!is_dir('smw')) {
				mkdir("smw", 0777, true);
			}
			file_put_contents("smw/{$smw}.png", $png);
			print "Success" . PHP_EOL;
		}
	} else {
		print "Failed" . PHP_EOL;
	}
}
die("Finished");
?>