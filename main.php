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

//commands
if ($msg == '.status') {
    $last_line = system('temp', $retval);
    scrivendo($chatID);
    sm($chatID, "I'm working!\nBot Online!\n$last_line");
}
if ($msg == '.ping') {
    scrivendo($chatID);
    sm($chatID, "pong");
}
if ($msg == '.srv' and $isadmin) {
    //Get info of Server
    $info      = sys_getloadavg();
    $last_line = system('temp', $retval);
    //for Raspberry Pi $last_line is Temp add command in Linux
    $text      = "Informazioni server:\nCPU[0]:$info[0]\nCPU[1]:$info[1]\nCPU[3]:$info[2]\nCPU[4]:$info[1]\n$last_line";
    scrivendo($chatID);
    sm($chatID, $text);
}
if (strpos($msg, ".name ") === 0 and $isadmin) {
    $nome = explode(" ", $msg);
    changeName($nome[1]);
    scrivendo($chatID);
    sm($chatID, "Sto Cambiando nome!");
}
if (substr($msg, 0, 6) == '.join ' and $isadmin) {
    $link = explode('joinchat/', $msg);
    scrivendo($chatID);
    sm($chatID,"Sto Entrando...");
    join_chat($link[1]);
}
if ($msg == ".updateJSON" and $isadmin) {
    file_put_contents("update.json", json_encode($update));
    scrivendo($chatID);
    sm($chatID, "JSON generato corettamente!");
}
if ($msg == ".saluta" and $isadmin) {
    scrivendo($chatID);
    sm($chatID, "Ciao Mondo!");
}
if (substr($msg, 0, 3) == '.ls' and $isadmin) {
    scrivendo($chatID);
    $msg = str_replace('.', '', $msg);
    sm($chatID, "root$-#: 
    " . shell_exec($msg));
}
if ($msg == ".id") {
    scrivendo($chatID);
    sm($chatID, $userID);
}
if ($update and $isadmin) {
    file_put_contents("update.json", json_encode($update, JSON_PRETTY_PRINT));
}
if ($msg == ".setPropic" and $isadmin) {
    changePropic('propic.jpg');
    scrivendo($chatID);
    sm($chatID, "Propic impostata...");
}
if (strpos($msg, ".ipInfo ") === 0 and $isadmin) {
    $ip = explode(" ", $msg);
    scrivendo($chatID);
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
    scrivendo($chatID);
    $api   = "https://gimmeproxy.com/api/getProxy";
    $json  = file_get_contents($api);
    $array = json_decode($json, true);
    $ip    = $array['ip'];
    $port  = $array['port'];
    $type  = $array['type'];
    $speed = $array['speed'] . "ms";
    $text  = "IP/Port: $ip:$port\nTipo: 
    $type\nVelocità: $speed";
    sm($chatID, $text);
}
if ($msg == ".pin" and $isadmin and isset($replymsg_id)) {
    pinMex($replymsg_id, $chatID, true);
    scrivendo($chatID);
    sm($chatID, "Message $msgID Pinned");
}
if (strpos($msg, ".username ") === 0 and $isadmin) {
    $userName = explode(" ", $msg);
    scrivendo($chatID);
    changeUsername($userName[1]);
    sm($chatID, "Username Impostato");
}
if ($msg == "@Piket_564" or $msg == "@luckymls") {
    scrivendo($chatID);
    reply($chatID, "Chi mi chiama? Sono indisposto", $msgID);
}
if ($msg == ".help") {
    //this help is in Italian, sorry for that
    scrivendo($chatID);
    $text="->Messaggio di Aiuto:
    .ping--> pong,
    .status--> Stato dell' Userbot,
    .srv --> Stato core del Server,
    .name <nome> --> Cambio nome,
    .join <link> --> Joina nel gruppo indicato (solo privati),
    .saluta --> Saluta tutti nel gruppo,
    .ls --> Ls nella directory  dell' Userboto,
    .id --> Tuo ID,
    .setPropic --> Inserisci l'immagine 'propic.jpg',
    .ipInfo <0.0.0.0> --> Info dettagliate dell'IP,
    .getMeProxy --> Proxy random da API,
    .pin [msg_reply] --> Pinna un messaggio,
    .username <username> --> Cambio  Username,
    [Sviluppo]
    .callMe --> Ti chiama (Danke @danogentili),
    .twitter [username NO @] --> Ultimo Tweet,
    {Pagina Telegraph}.";
    sm($chatID, $text, $msgID);
}
require_once('API/twitterApi.php');
if(strpos($msg,".twitter")===0){
    $e=explode(" ",$msg);
    $user=$e[1];
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
    $getfield = '?screen_name='.$user.'&count=1';
    $twitter = new TwitterAPIExchange($settings);
    $string = json_decode($twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest(),$assoc = TRUE);
    foreach($string as $items)
    {
        if(isset($items['text'])){
            $text="Last Tweet of <b>".$items['user']['name']."</b>\n\xF0\x9F\x93\x8B \n".$items['text'];
            scrivendo($chatID);
            sm($chatID,$text);
        }
        /*echo "Time and Date of Tweet: ".$items['created_at']."<br />";
        echo "Tweet: ". $items['text']."<br />";
        echo "Tweeted by: ". $items['user']['name']."<br />";
        echo "Screen name: ". $items['user']['screen_name']."<br />";
        echo "Followers: ". $items['user']['followers_count']."<br />";
        echo "Friends: ". $items['user']['friends_count']."<br />";
        echo "Listed: ". $items['user']['listed_count']."<br /><hr />";*/
        //All $items in Echo
    }
}
/*
if($msg==".callMe") {
    sm($chatID, "I'm Calling you!");
    $controller = $MadelineProto->request_call($userID)->play('audio/in.raw');
    $controller->configuration['log_file_path'] = 'logs/' . $controller->getOtherID() . '.log';
    $controller->configuration["stats_dump_file_path"] = "stats".$controller->getOtherID().".log";
    $controller->configuration["network_type"] = \danog\MadelineProto\VoIP::NET_TYPE_WIFI;
    $controller->configuration["data_saving"] = \danog\MadelineProto\VoIP::DATA_SAVING_NEVER;
    $controller->parseConfig();
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
        $MadelineProto->get_updates();
        if ($controller->getCallState() == \danog\MadelineProto\VoIP::CALL_STATE_READY) {
            $key = $controller->getVisualization();
            file_put_contents('logs/emojii.json', json_encode($key, JSON_PRETTY_PRINT));
            sm($chatID, "Emoji: " . $key[0] . $key[1] . $key[2] . $key[3]);
        }
    }
    //var_dump($controller->getVisualization());
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
        $MadelineProto->get_updates();
    }
}


if(strpos($msg,".call")===0 and $isadmin) {
    sm($chatID, "I'm Calling!");
    $ids=explode(" ",$msg);
    $id=$ids[1];
    $controller = $MadelineProto->request_call($id)->play('audio/in.raw');
    $controller->configuration['log_file_path'] = 'logs/' . $controller->getOtherID() . '.log';
    $controller->configuration["stats_dump_file_path"] = "stats".$controller->getOtherID().".log";
    $controller->configuration["network_type"] = \danog\MadelineProto\VoIP::NET_TYPE_WIFI;
    $controller->configuration["data_saving"] = \danog\MadelineProto\VoIP::DATA_SAVING_NEVER;
    $controller->parseConfig();
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
        $MadelineProto->get_updates();
        if ($controller->getCallState() == \danog\MadelineProto\VoIP::CALL_STATE_READY) {
            $key = $controller->getVisualization();
            file_put_contents('logs/emojii.json', json_encode($key, JSON_PRETTY_PRINT));
            sm($chatID, "Emoji: " . $key[0] . $key[1] . $key[2] . $key[3]);
        }
    }
    //var_dump($controller->getVisualization());
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
        $MadelineProto->get_updates();
    }
}
/*Incoming Calls*/
/*switch ($update['update']['_']) {
    case 'updatePhoneCall':
        if (is_object($update['update']['phone_call']) && $update['update']['phone_call']->getCallState() === \danog\MadelineProto\VoIP::CALL_STATE_INCOMING) {
            $update['update']['phone_call']->accept()->playOnHold('audio/in.raw');
            while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
                $MadelineProto->get_updates();
                if ($controller->getCallState() == \danog\MadelineProto\VoIP::CALL_STATE_READY) {
                    $key = $controller->getVisualization();
                    file_put_contents('logs/emojii.json', json_encode($key, JSON_PRETTY_PRINT));
                    sm($userID, "Emoji: " . $key[0] . $key[1] . $key[2] . $key[3]);
                }
            }

        }
}*/