<?php
/*codice*/
//require_once 'funzioni.php';
$isadmin   = false;
$are_admin = array(152500233); //Insert here admin_ids
$isadmin   = false;
if($update){
    file_put_contents("update.json", json_encode($update));
}
if (is_array($are_admin) && in_array($userID, $are_admin)) {
    $isadmin = true;
}
//COMANDI:
if ($msg == '.status') {
    $last_line = system('temp', $retval);
    sm($chatID, "I'm working!\nBot Online!\n$last_line");
}
if ($msg == '.ping') {
    sm($chatID, "pong");
}
if ($msg == '.srv' and $isadmin) {
    //Get info of Server
    $info      = sys_getloadavg();
    $last_line = system('temp', $retval);
    $text      = "Informazioni server:\nCPU[0]:$info[0]\nCPU[1]:$info[1]\nCPU[3]:$info[2]\nCPU[4]:$info[1]\n$last_line";
    sm($chatID, $text);
}
if (strpos($msg, ".name ") === 0 and $isadmin) {
    $nome = explode(" ", $msg);
    changeName($nome[1]);
    sm($chatID, "Sto Cambiando nome!");
}
if (substr($msg, 0, 6) == '.join ' and $isadmin) {
    $link = explode('joinchat/', $msg);
    join_chat($link[1]);
}
if ($msg == ".updateJSON" and $isadmin) {
    file_put_contents("update.json", json_encode($update));
    sm($chatID, "JSON generato corettamente!");
}
if ($msg == ".saluta" and $isadmin) {
    sm($chatID, "Ciao Mondo!");
}
if (substr($msg, 0, 3) == '.ls' and $isadmin) {
    $msg = str_replace('.', '', $msg);
    sm($chatID, "root$-#: 
    " . shell_exec($msg));
}
if ($msg == ".id") {
    sm($chatID, $userID);
}
if ($update and $isadmin) {
    file_put_contents("update.json", json_encode($update, JSON_PRETTY_PRINT));
}
if ($msg == ".setPropic" and $isadmin) {
    changePropic('propic.jpg');
    sm($chatID, "Propic impostata...");
}
if (strpos($msg, ".ipInfo ") === 0 and $isadmin) {
    $ip = explode(" ", $msg);
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
    sm($chatID, "Message $msgID Pinned");
}
if (strpos($msg, ".username ") === 0 and $isadmin) {
    $userName = explode(" ", $msg);
    changeUsername($userName[1]);
    sm($chatID, "Username Impostato");
}
if ($msg == "@Piket_564" or $msg == "@luckymls") {
    reply($chatID, "Chi mi chiama? Sono 
    indisposto", $msgID);
}
if ($msg == ".help") {
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
    {Pagina Telegraph}.";
    sm($chatID, $text, $msgID);
}
if($msg==".callMe"){
    $controller = $MadelineProto->request_call($userID)->play('audio/in.raw');
    $controller->configuration['log_file_path'] = 'logs/'.$controller->getOtherID().'.log';
    //$controller->configuration["stats_dump_file_path"] = "stats".$controller->getOtherID().".log"; // Default is /dev/null
    //$controller->configuration["network_type"] = \danog\MadelineProto\VoIP::NET_TYPE_WIFI; // Default is NET_TYPE_ETHERNET
    //$controller->configuration["data_saving"] = \danog\MadelineProto\VoIP::DATA_SAVING_NEVER; // Default is DATA_SAVING_NEVER
    //$controller->parseConfig(); // Always call this after changing settings
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_READY) {
        $MadelineProto->get_updates();
        if($controller->getCallState() == \danog\MadelineProto\VoIP::CALL_STATE_READY){
            $key=$controller->getVisualization();
            file_put_contents('logs/emojii.json',json_encode($key,JSON_PRETTY_PRINT));
            sm($chatID,"Emoji: ".$key[0].$key[1].$key[2].$key[3]);
        }
    }
    //var_dump($controller->getVisualization());
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
        $MadelineProto->get_updates();
    }
}
/*$howmany = 300;
$offset = 0;
while ($howmany > 255) {
    $updates = $MadelineProto->API->get_updates(['offset' => $offset, 'limit' => 50, 'timeout' => 0]);
    foreach ($updates as $update) {
        \danog\MadelineProto\Logger::log([$update]);
        $offset = $update['update_id'] + 1;
        switch ($update['update']['_']) {
            case 'updatePhoneCall':
                if (is_object($update['update']['phone_call']) && $update['update']['phone_call']->getCallState() === \danog\MadelineProto\VoIP::CALL_STATE_INCOMING) {
                    $update['update']['phone_call']->accept()->play('audio/in.raw');
                    //$howmany--;
                }
        }
    }
    echo 'Wrote '.\danog\MadelineProto\Serialization::serialize('session.madeline', $MadelineProto).' bytes'.PHP_EOL;
}*/