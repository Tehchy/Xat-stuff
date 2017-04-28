<?php
/*
    Xat Room Info API
    Made By Techy
    0.2
*/
header('Content-Type: application/json');
if (array_key_exists('chat', $_REQUEST)) {
    $chat = mytrim($_REQUEST['chat'], 128);
    $chat = is_numeric($chat) ? 'xat' . $chat : $chat;
    
    $json = new StdClass();
    
    $file  = file_get_contents('http://xat.com/' . $chat, false, stream_context_create(['http' => ['timeout' => 1]]));
    $file2 = file_get_contents('http://xat.com/web_gear/chat/roomid.php?v2&d=' . $chat, false, stream_context_create(['http' => ['timeout' => 1]]));
    
    parse_str(stribet($file, 'name="chat" FlashVars="', '"'), $FlashVars);
    preg_match_all('/\<meta.property="(.*)".content="(.*)"/', $file, $meta);
    
    if (count($meta[0]) < 1) {
        die(json_encode(['error' => 'Chat not found'], JSON_PRETTY_PRINT));
    }
    
    $meta[2] = array_filter($meta[2]);
    $json->bot         = in_array('xat:bot', $meta[1]) ? (int) $meta[2][0] : false;
    $json->description = in_array('xat:bot', $meta[1]) ? $meta[2][2] ?? false : $meta[2][1] ?? false;
    $json->keywords    = substr_count($file, 'class="foot"') > 1 ? stribet($file, '<td class="foot" align=center>', '</td>') : false;
    $json->id          = (int) $FlashVars['id'];
    $json->name        = $FlashVars['gn'] ?? $chat;
    $json->flags       = (int) $FlashVars['xc'] ?? false;
    
    if (substr($file2, 0, 1) !== '-') {
        $file2 = array_filter(json_decode($file2, true));
        $json->name = $file2['g'] ?? $json->name; // double check
        $json->id = (int) $file2['id'] ?? $json->id; // double check
        if (isset($file2['a'])) {
            $ChatVars = array_filter(explode(';=', $file2['a']));
            $json->background   = $ChatVars[0] ?? false;
            $json->tabbedChat   = $ChatVars[1] ?? false;
            $json->tabbedChatId = (int) $ChatVars[2] ?? false;
            $json->language     = $ChatVars[3] ?? 'English';
            $json->radio        = $ChatVars[4] ?? false;
            $json->buttoncolor  = $ChatVars[5] ?? false;
        }
    }

    die(json_encode($json, JSON_PRETTY_PRINT));
} else {
    die(json_encode(['error' => 'Usage roominfo.php?chat=CHATNAME'], JSON_PRETTY_PRINT));
}
function mytrim($s, $len) {
	$s = preg_replace('/[^0-9A-Za-z]/', '', $s);

	if(strlen($s) > ($len-1))
		$s = substr ($s, 0, $len-1);
	return $s;
}
function stribet($inputstr, $delimiterLeft, $delimiterRight) {
    $posLeft = stripos($inputstr, $delimiterLeft) + strlen($delimiterLeft);
    $posRight = stripos($inputstr, $delimiterRight, $posLeft);
    return substr($inputstr, $posLeft, $posRight - $posLeft);
}
?>