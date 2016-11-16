<?php
/*
    Xat Basic Room Info API
    Made By Techy
*/
header('Content-Type: application/json');
if (array_key_exists('chat', $_REQUEST)) {
    $chat = mytrim($_REQUEST['chat'], 128);
    $chat = is_numeric($chat) ? 'xat' . $chat : $chat;
    
    $json = (object)['id' => 0, 'name' => $chat, 'bot' => 'Not set', 'keywords' => 'Not set', 'description' => 'Not set'];
    
    $file = file_get_contents('http://xat.com/' . $chat, false, stream_context_create(['http' => ['timeout' => 1]]));
    preg_match_all('/\<meta.property="(.*)".content="(.*)"/', $file, $meta);
    
    if (count($meta[0]) < 1) {
        die(json_encode(['error' => 'Chat not found'], JSON_PRETTY_PRINT));
    }
    
    if (in_array('xat:bot', $meta[1])) {
       $json->bot = (int) $meta[2][0];
        if (!empty($meta[2][2])) {
            $json->description = $meta[2][2];
        }
    } else {
        if (!empty($meta[2][1])) {
            $json->description = $meta[2][1];
        }
    }
    if (substr_count($file, 'class="foot"') > 1) {
        $json->keywords = stribet($file, '<td class="foot" align=center>', '</td>');
    }
    
    parse_str(stribet($file, 'name="chat" FlashVars="', '"'), $FlashVars);
    
    $json->id = (int) $FlashVars['id'];
    
    if (array_key_exists('gn', $FlashVars)) {
        $json->name = $FlashVars['gn'];
    }
    
    die(json_encode($json, JSON_PRETTY_PRINT));
} else {
    die(json_encode(['error' => 'Usage roominfo.php?chat=CHATNAME'], JSON_PRETTY_PRINT));
}
function mytrim($s, $len)
{
	$s = preg_replace('/[^0-9A-Za-z]/', '', $s);

	if(strlen($s) > ($len-1))
		$s = substr ($s, 0, $len-1);
	return $s;
}
function stribet($inputstr, $delimiterLeft, $delimiterRight) 
{
    $posLeft = stripos($inputstr, $delimiterLeft) + strlen($delimiterLeft);
    $posRight = stripos($inputstr, $delimiterRight, $posLeft);
    return substr($inputstr, $posLeft, $posRight - $posLeft);
}
?>