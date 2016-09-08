<?php
/*
    Random Power Details
    Made by Techy
*/

$ctx = stream_context_create(['http' => ['timeout' => 1]]);
$json = json_decode(file_get_contents('http://xat.com/json/powers.php?' . time(), false, $ctx), true);
$powers = ['isNew' => 0, 'isLimited' => 0, 'isUnimited' => 0, 'isEpic' => 0, 'isGame' => 0, 'isGroup' => 0, 'isAllPower' => 0, 'total' => count($json)];

foreach ($json as $id => $power) {
    if (isset($power['f'])) {
        if ($power['f'] & 0x1000) {
            $powers['isNew']++;
        }        
        if ($power['f'] & 0x2000) {
            $powers['isLimited']++;
        }    
        if ($power['f'] & 0x8) {
            $powers['isEpic']++;
        }    
        if ($power['f'] & 0x80) {
            $powers['isGame']++;
        }  
        if ($power['f'] & 0x800) {
            $powers['isGroup']++;
        }    
        if ($power['f'] & 0x401) {
            $powers['isAllPower']++;
        }
    }
}
$powers['isUnlimited'] = $powers['total'] - $powers['isLimited'];

print "Total: {$powers['total']} | New: {$powers['isNew']} | Limited: {$powers['isLimited']} | Unlimited: {$powers['isUnlimited']} | Epic {$powers['isEpic']} | Game: {$powers['isGame']} | Group: {$powers['isGroup']} | Allpowers: {$powers['isAllPower']}";
?>