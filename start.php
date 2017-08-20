<?php
require_once 'vendor/autoload.php';
$admin=array(152500233);//admin ID's for error!
if(!file_exists('session.madeline')){
    \danog\MadelineProto\Logger::log(["NO SESSION LOAD! PLEASE READ DOCS!"],\danog\MadelineProto\Logger::FATAL_ERROR);
    exit (0);
}else {
    $settings = [];
    $MadelineProto = \danog\MadelineProto\Serialization::deserialize('session.madeline');
    function sm($ID, $text, $reply = false)
    {
        global $MadelineProto;
        if ($reply) {
            $MadelineProto->messages->sendMessage(['peer' => $ID, 'message' => $text, 'parse_mode' => "HTML", 'reply_to_message_id' => $reply]);
        } else {
            $MadelineProto->messages->sendMessage(['peer' => $ID, 'message' => $text, 'parse_mode' => "HTML"]);
        }
    }
    /**/
    function mindate($min)
    {
        return date('i', $min);
    }
    function changeName($name)
    {
        global $MadelineProto;
        $MadelineProto->account->updateProfile(['first_name' => $name]);
    }
    function changePropic($inputfile)
    {
        global $MadelineProto;
        $inputFile = $MadelineProto->upload($inputfile);
        $MadelineProto->photos->uploadProfilePhoto(['file' => $inputFile]);
    }
    function reply($chatID, $text, $tomsgId)
    {
        global $MadelineProto;
        $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'reply_to_msg_id' => $tomsgId]);
    }
    function join_channel($chat){
        global $MadelineProto;
        $MadelineProto->channels->joinChannel(['channel' => $chat, ]);
    }
    function join_chat($chat)
    {
        global $MadelineProto;
        $MadelineProto->messages->importChatInvite(['hash' => $chat]);
    }
    function leave_chat($chat){
        global $MadelineProto;
        $MadelineProto->channels->leaveChannel(['channel' => $chat, ]);
    }
    function pinMex($msgID, $channelID, $silent = false)
    {
        global $MadelineProto;
        $MadelineProto->channels->updatePinnedMessage(['silent' => $silent, 'channel' => $channelID, 'id' => $msgID,]);

    }
    function changeUsername($username)
    {
        global $MadelineProto;
        if($MadelineProto->account->checkUsername(['username' => $username, ])===true){
            $MadelineProto->account->updateUsername(['username' => $username,]);
            return true;
        }else{
            return false;
        }
    }
    function readmsg($chatID, $msgid)
    {
        global $update;
        global $MadelineProto;
        if (isset($chatID) and isset($msgid)){
            var_export($MadelineProto->messages->readHistory(['peer' => $chatID, 'max_id' => $msgid]));
        }
    }
    function isWriting($chatID)
    {
        global $update;
        global $MadelineProto;
        if (isset($chatID)) {
            $sendMessageTypingAction = ['_' => 'sendMessageTypingAction',];
            var_export($MadelineProto->messages->setTyping(['peer' => $chatID, 'action' => $sendMessageTypingAction]));
        }
    }


    function ytCall($link,$chat,$userID){
        global $MadelineProto;
        isWriting($chat);
        sm($chat,"Start converting!");
        if(strpos($link,"https://www.youtube.com/watch?v=")===0){
            $hash=explode("https://www.youtube.com/watch?v=",$link);
        }else if(strpos($link,"https://youtu.be/")===0){
            $hash=explode("https://youtu.be/",$link);
        }else if(strpos($link,"https://y2u.be/")===0){
            $hash=explode("http://y2u.be/",$link);
        }
        /*TODO ->Change with your path AND pay attention to 600s max lenght*/
        if (preg_match("/((http\:\/\/){0,}(www\.){0,}(youtube\.com){1} || (youtu\.be){1}(\/watch\?v\=[^\s]){1})/", $link) == 1 and shell_exec("/root/MadelineProto/API/youtube-dl -j ".$hash[1]." | jq .duration 2>&1")<600){
            shell_exec('/root/MadelineProto/audio/youtube-dl -f bestaudio --output /root/MadelineProto/audio/'.$userID.'".%(ext)s" '.$link);
            $bitRate="48000";
            shell_exec("ffmpeg -i /root/MadelineProto/audio/".$userID.".webm -f s16le -ac 1 -ar ".$bitRate." -acodec pcm_s16le /root/MadelineProto/audio/".$userID.".raw");
            if(file_exists("/root/MadelineProto/audio/".$userID.".raw")===true){
                shell_exec("rm -r /root/MadelineProto/audio/".$userID.".webm");
            }

            if(file_exists("/root/MadelineProto/audio/".$userID.".raw")===true){
                isWriting($chat);
                sleep(0.5);
                sm($chat,"I'm Calling");
                $controller = $MadelineProto->request_call($userID)->play('/root/MadelineProto/audio/'.$userID.'.raw');
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
                        sm($chat, "Emoji: " . $key[0] . $key[1] . $key[2] . $key[3]);
                    }
                }
                while ($controller->getCallState() < \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
                    $MadelineProto->get_updates();
                    if ($controller->getCallState() == \danog\MadelineProto\VoIP::CALL_STATE_ENDED) {
                        shell_exec("rm -r /root/MadelineProto/audio/".$userID.".raw");
                    }
                }
            }
        }else{
            isWriting($chat);
            sleep(1);
            sm($chat,"Errore nel Link! Oppure la Canzone è più grande di 10:00!");
        }
    }
    $offset = 0;
    while (true) {
        $updates = $MadelineProto->API->get_updates(['offset' => $offset, 'limit' => 100, 'timeout' => 0]);
        foreach ($updates as $update) {
            $offset = $update['update_id'] + 1;
            switch ($update['update']['_']) {
                case 'updateNewMessage':
                case 'updateNewChannelMessage':
                    try {
                        $res = json_encode($update, JSON_PRETTY_PRINT);
                        if ($res == '') {
                            $res = var_export($update, true);
                        }
                        include("config.php");
                    } catch (\danog\MadelineProto\RPCErrorException $e) {
                        \danog\MadelineProto\Logger::log(["Si e' verificato un errore di tipo RPCException: " . $e->getMessage()], \danog\MadelineProto\Logger::NOTICE);
                        foreach ($admin as $ad) {
                            sm($ad, "RPCE Exception:\n" . $e->getMessage());
                        }
                        file_put_contents('logs/RPCException.log', $e->getMessage());
                    } catch (\danog\MadelineProto\Exception $e) {
                        foreach ($admin as $ad) {
                            sm($ad, "Exception:\n" . $e->getMessage());
                        }
                        \danog\MadelineProto\Logger::log(["Si e' verificato un errore di tipo Exception: " . $e->getMessage()], \danog\MadelineProto\Logger::NOTICE);
                        file_put_contents('logs/Exception.log', $e->getMessage());
                    }
                    break;
                case 'updatePhoneCall':
                    include("config.php");
                    if (is_object($update['update']['phone_call']) && isset($update['update']['phone_call']->madeline) && $update['update']['phone_call']->getCallState() === \danog\MadelineProto\VoIP::CALL_STATE_INCOMING) {
                        $update['update']['phone_call']->configuration['enable_NS'] = false;
                        $update['update']['phone_call']->configuration['enable_AGC'] = false;
                        $update['update']['phone_call']->configuration['enable_AEC'] = false;
                        $update['update']['phone_call']->configuration['shared_config'] = [
                            'audio_init_bitrate' => 40 * 1000,
                            'audio_max_bitrate' => 50 * 1000,
                            'audio_min_bitrate' => 15 * 1000,
                            //'audio_bitrate_step_decr' => 0,
                            //'audio_bitrate_step_incr' => 2000,
                        ];
                        $update['update']['phone_call']->parseConfig();
                        if ($update['update']['phone_call']->accept() === false) {
                            echo 'DID NOT ACCEPT A CALL';
                        }
                        $calls[$update['update']['phone_call']->getOtherID()] = $update['update']['phone_call'];
                        $update['update']['phone_call']->play('audio/in.raw');

                    }
                    break;
            }
            echo 'Wrote ' . \danog\MadelineProto\Serialization::serialize('session.madeline', $MadelineProto) . ' bytes' . PHP_EOL;
        }
    }
}






