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
    isWriting($chatID);
    sleep(2);
    sm($chatID, "I'm working!\nBot Online!");
    //reply
    sm($chatID,"I'm Workink!\nBot Online!",$msgID);
}
if ($msg == ".help") {
    isWriting($chatID);
    $text="Testing!";
    sm($chatID, $text, $msgID);
}
if($msg==".gitHub"){
    isWriting($chatID);
    $text="https://github.com/Piket564/UserbotTG/";
    sm($chatID, $text, $msgID);
}
if($msg==".callMe") {
    sm($userID, "I'm calling you!");
    $controller = $MadelineProto->request_call($userID)->play('audio/in.raw');
    $controller->configuration['log_file_path'] = 'logs/' . $controller->getOtherID() . '.log';
    $controller->configuration["stats_dump_file_path"] = "logs/stats" . $controller->getOtherID() . ".log";
    $controller->configuration["network_type"] = \danog\MadelineProto\VoIP::NET_TYPE_WIFI;
    $controller->configuration["data_saving"] = \danog\MadelineProto\VoIP::DATA_SAVING_NEVER;
    $controller->configuration['shared_config'] = [
        'audio_init_bitrate' => 40 * 1000,
        'audio_max_bitrate' => 50 * 1000,
        'audio_min_bitrate' => 15 * 1000,
        //'audio_bitrate_step_decr' => 0,
        //'audio_bitrate_step_incr' => 2000,
    ];
    $controller->parseConfig();
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_READY) {
        $MadelineProto->get_updates();
        if ($controller->getCallState() == \danog\MadelineProto\VoIP::CALL_STATE_READY) {
            $key = $controller->getVisualization();
            file_put_contents('logs/emojii.json', json_encode($key, JSON_PRETTY_PRINT));
            sm($userID, "Emoji: " . $key[0] . $key[1] . $key[2] . $key[3]);
        }
    }
    //var_dump($controller->getVisualization());
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
        $MadelineProto->get_updates();
    }
}
if(strpos($msg,".call")===0 and $isadmin and $msg!=".callMe") {
    sm($chatID, "I'm Calling!");
    $ids=explode(" ",$msg);
    $id=$ids[1];
    $controller = $MadelineProto->request_call($id)->play('audio/in.raw');
    $controller->configuration['log_file_path'] = 'logs/' . $controller->getOtherID() . '.log';
    $controller->configuration["stats_dump_file_path"] = "logs/stats".$controller->getOtherID().".log";
    $controller->configuration["network_type"] = \danog\MadelineProto\VoIP::NET_TYPE_WIFI;
    $controller->configuration["data_saving"] = \danog\MadelineProto\VoIP::DATA_SAVING_NEVER;
    $controller->configuration['shared_config'] = [
        'audio_init_bitrate' => 40 * 1000,
        'audio_max_bitrate'  => 50 * 1000,
        'audio_min_bitrate'  => 15 * 1000,
        //'audio_bitrate_step_decr' => 0,
        //'audio_bitrate_step_incr' => 2000,
    ];
    $controller->parseConfig();
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_READY) {
        $MadelineProto->get_updates();
        if ($controller->getCallState() == \danog\MadelineProto\VoIP::CALL_STATE_READY) {
            $key = $controller->getVisualization();
            file_put_contents('logs/emojii.json', json_encode($key, JSON_PRETTY_PRINT));
            sm($chatID, "Emoji: " . $key[0] . $key[1] . $key[2] . $key[3]);
        }
    }
    //var_dump($controller->getVisualization());
    while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_READY) {
        $MadelineProto->get_updates();
    }
}

if (strpos($msg, ".username ") === 0 and $isadmin) {
    $userName = explode(" ", $msg);
    isWriting($chatID);
    if(changeUsername($userName[1])===false)
        sm($chatID, "Username Impostato");
    else
        sm($chatID,"Errore Username già utilizzato!",$msgID);
}

if(strpos($msg,'.left')===0 and $isadmin){
    sm($chatID,"Sto Uscendo...");
    leave_chat($chatID);
}

if(strpos($msg,'.joinCh') === 0 and $isadmin){
    $link = explode('https://t.me/', $msg);
    isWriting($chatID);
    sm($chatID,"Sto Entrando...");
    join_channel($link[1]);
}
if(strpos($msg,'.leftCh') === 0 and $isadmin){
    $link = explode('https://t.me/', $msg);
    isWriting($chatID);
    sm($chatID,"Sto Uscendo...");
    leave_chat($link[1]);
}

if(strpos($msg,'.yt') === 0){
    $link = explode(' ', $msg);
    ytCall($chatID,$link[1],$userID);
}
