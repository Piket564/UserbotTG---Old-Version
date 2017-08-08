<?php
$isadmin   = false;
$are_admin = array(152500233);//Insert here admin_ids
$isadmin   = false;
if($update){
    file_put_contents("update.json", json_encode($update));
}
if (is_array($are_admin) && in_array($userID, $are_admin)) {
    $isadmin = true;
}
date_default_timezone_set('Europe/Rome');//TimeZones
//commands
if ($msg == '.status') {
    isWriting($chatID);
    $date = date('m/d/Y h:i:s a', time());
    sm($chatID, "I'm working!\nBot Online!\n$date");
}
if ($msg == '.ping') {
    isWriting($chatID);
    sm($chatID, "pong");
}
if ($msg == '.srv' and $isadmin) {
    //Get info of Server
    $info      = sys_getloadavg();
    $last_line = system('temp', $retval);
    //for Raspberry Pi $last_line is Temp add command in Linux
    $text      = "Informazioni server:\nCPU[0]:$info[0]\nCPU[1]:$info[1]\nCPU[3]:$info[2]\nCPU[4]:$info[1]\n$last_line";
    isWriting($chatID);
    sm($chatID, $text);
}
if (strpos($msg, ".name ") === 0 and $isadmin) {
    $nome = explode(" ", $msg);
    changeName($nome[1]);
    isWriting($chatID);
    sm($chatID, "Sto Cambiando nome!");
}
if (substr($msg, 0, 6) == '.join ' and $isadmin) {
    $link = explode('joinchat/', $msg);
    isWriting($chatID);
    sm($chatID,"Sto Entrando...");
    join_chat($link[1]);
}
if ($msg == ".updateJSON" and $isadmin) {
    file_put_contents("update.json", json_encode($update));
    isWriting($chatID);
    sm($chatID, "JSON generato corettamente!");
}
if ($msg == ".saluta" and $isadmin) {
    isWriting($chatID);
    sm($chatID, "Ciao Mondo!");
}
if (substr($msg, 0, 3) == '.ls' and $isadmin) {
    isWriting($chatID);
    $msg = str_replace('.', '', $msg);
    sm($chatID, "root$-#: 
    " . shell_exec($msg));
}
if ($msg == ".id") {
    isWriting($chatID);
    sm($chatID, $userID);
}
if ($update and $isadmin) {
    file_put_contents("update.json", json_encode($update, JSON_PRETTY_PRINT));
}
if ($msg == ".setPropic" and $isadmin) {
    changePropic('propic.jpg');
    isWriting($chatID);
    sm($chatID, "Propic impostata...");
}
if (strpos($msg, ".ipInfo ") === 0 and $isadmin) {
    $ip = explode(" ", $msg);
    isWriting($chatID);
    if (!isset($ip[1])) {
        sm($chatID, "Error IP");
    } else {
        $ip       = $ip[1];
        $api      = "https://ipinfo.io/$ip/json";
        $json     = file_get_contents($api);
        $array    = json_decode($json, true);
        $ip       = $array['ip'];
        $hostname = $array['hostname'];
        $city     = $array['city'];
        $region   = $array['region'];
        $country  = $array['country'];
        $loc      = $array['loc'];
        $text     = "IP: $ip\nHostname: 
    $hostname\nCity: $city\nRegion: 
    $region\nCountry: $country";
        sm($chatID, $text);
    }
}
if ($msg == ".getMeProxy") {
    isWriting($chatID);
    $api   = "https://gimmeproxy.com/api/getProxy";
    $json  = file_get_contents($api);
    $array = json_decode($json, true);
    $ip    = $array['ip'];
    $port  = $array['port'];
    $type  = $array['type'];
    $speed = $array['speed'] . "ms";
    $text  = "IP/Port: $ip:$port\nTipo: 
    $type\nVelocità: $speed";
    isWriting($chatID);
    sm($chatID, $text);
}
if ($msg == ".pin" and $isadmin and isset($replymsg_id)) {
    pinMex($replymsg_id, $chatID, true);
    isWriting($chatID);
    sm($chatID, "Message $msgID Pinned");
}
if (strpos($msg, ".username ") === 0 and $isadmin) {
    $userName = explode(" ", $msg);
    isWriting($chatID);
    changeUsername($userName[1]);
    sm($chatID, "Username Impostato");
}
if ($msg == "@Piket_564" or $msg == "@luckymls") {
    isWriting($chatID);
    sm($chatID, "Who is?", $msgID);
}
if ($msg == ".help") {
    isWriting($chatID);
    $text="<a href='http://telegra.ph/Help-Page-08-06'>HELP!</a>";
    sm($chatID, $text, $msgID);
}
require_once('API/twitterApi.php');
if(strpos($msg,".twitter")===0){
    $e=explode(" ",$msg);
    $users=$e[1];
    $user=explode("@",$users);
    if(isset($user[0]) and isset($user[1])){
        /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
        /*https://github.com/J7mbo/twitter-api-php*/
        $settings = array(
            'oauth_access_token' => "",
            'oauth_access_token_secret' => "",
            'consumer_key' => "",
            'consumer_secret' => ""
        );
        $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
        $requestMethod = "GET";
        $getfield = '?screen_name='.$user[1].'&count=1';
        $twitter = new TwitterAPIExchange($settings);
        $string = json_decode($twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest(),$assoc = TRUE);
        foreach($string as $items) {
            if (isset($items['text'])) {
                $text = "Last Tweet of <b>" . $items['user']['name'] . "</b>\n\xF0\x9F\x93\x8B \n" . $items['text'];//see Docs for $items
                isWriting($chatID);
                sm($chatID, $text);
            }
        }
    }else{
        sm($chatID,"Error!\n.twitter @USER");
    }
}
