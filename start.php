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

    function join_chat($chat)
    {
        global $MadelineProto;
        $MadelineProto->messages->importChatInvite(['hash' => $chat]);
    }

    function pinMex($msgID, $channelID, $silent = false)
    {
        global $MadelineProto;
        $MadelineProto->channels->updatePinnedMessage(['silent' => $silent, 'channel' => $channelID, 'id' => $msgID,]);

    }

    function changeUsername($username)
    {
        global $MadelineProto;
        $MadelineProto->account->updateUsername(['username' => $username,]);
    }

    function readmsg($chatID, $msgid)
    {
        global $update;
        global $MadelineProto;
        if (isset($chatID) and isset($msgid)) var_export($MadelineProto->messages->readHistory(['peer' => $chatID, 'max_id' => $msgid]));
    }

    function scrivendo($chatID)
    {
        global $update;
        global $MadelineProto;
        if (isset($chatID)) {
            $sendMessageTypingAction = ['_' => 'sendMessageTypingAction',];
            var_export($MadelineProto->messages->setTyping(['peer' => $chatID, 'action' => $sendMessageTypingAction]));
        }
    }
    $offset = 0;
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






