<?php
/*
    Xat Get Room Connecion Info(ip & port)
    Made By Techy
    Credit to llomgui(Jedi) for pickIP Function from OceanProject
    https://github.com/llomgui/OceanProject-Bot
*/
header('Content-Type: application/json');
if (array_key_exists('id', $_REQUEST)) {
    
    if (!is_numeric($_REQUEST['id'])) {
        die(json_encode(['error' => 'Chat ID must be numeric.'], JSON_PRETTY_PRINT));
    }
    
    $page = file_get_contents('http://xat.com/web_gear/chat/ip2.php?t=' . time(), false, stream_context_create(['http' => ['timeout' => 1]]));
    if (empty($page)) {
        die(json_encode(['error' => 'Xat is currently unreachable'], JSON_PRETTY_PRINT));
    }
    
    $ip2 = json_decode($page, true);
    
    $conn = pickIP($_REQUEST['id']);
    $json = (object) ['ip' => $conn[0], 'port' => $conn[1]];
    die(json_encode($json, JSON_PRETTY_PRINT));
} else {
    die(json_encode(['error' => 'Usage chatconn.php?id=ROOMID'], JSON_PRETTY_PRINT));
}

function getDom($id)
{
    global $ip2;
    if ($ip2['xFlag'] & 8) {
        return (rand(0, 3));
    }

    if (intval($id) == 8) {
        return 0;
    }

    return ((intval($id) < 8) ? 3 : (intval($id) & 96) >> 5);
}

function getPort($id)
{
    global $ip2;
    if ($ip2['xFlag'] & 8) {
        return ((10000 + 7) + rand(0, 31));
    }

    if (intval($id) == 8) {
        return 10000;
    }

    return ((intval($id) < 8) ? ((10000 - 1) + intval($id)) : ((10000 + 7) + intval($id) % 32));
}

function pickIP($id)
{
    global $ip2;
    $local3 = $ip2[$ip2['order'][0][0]];
    if ($local3[0] & 1 == 1) {
        $local5 = '0';
        $local5 = floor((mt_rand(0, 10) / 10) * (sizeof($local3) - 1)) + 1;

        if (!isset($local3[$local5])) {
            $local5--;
        }

        $local6 = floor((mt_rand(0, 10) / 10) * (sizeof($local3[$local5])));
        if (!isset($local3[$local5][$local6])) {
            $local6--;
        }

        $local7 = explode(':', $local3[$local5][$local6]);
        $local7[1] = $local7[1] ?? 10000;
        $local7[2] = $local7[2] ?? 39;

        $local8 = (intval($local7[1]) + floor((mt_rand(0, 10) / 10) * intval($local7[2])));
        $return = [$local7[0], $local8];
    } else {
        $local11 = $local3[1][(4 * getDom($id)) + floor((mt_rand(0, 10) / 10) * 4)];
        $return  = [$local11, getPort($id)];
    }
    return $return;
}
?>
