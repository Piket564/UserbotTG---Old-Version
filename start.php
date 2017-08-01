<?php
require_once 'vendor/autoload.php';
if(!file_exists('session.madeline')){
    if (file_exists('.env')) {
        echo 'Loading .env...'.PHP_EOL;
        $dotenv = new Dotenv\Dotenv(getcwd());
        $dotenv->load();
    }else{
        \danog\MadelineProto\Logger::log(["Missing .env file."], \danog\MadelineProto\Logger::FATAL_ERROR);
        exit(0);
    }
    if (getenv('TEST_SECRET_CHAT') == '') {
        die('TEST_SECRET_CHAT is not defined in .env, please define it.'.PHP_EOL);
    }
    echo 'Loading settings...'.PHP_EOL;
    $settings = json_decode(getenv('MTPROTO_SETTINGS'), true) ?: [];
    var_dump($settings);
    if ($MadelineProto === false) {
        echo 'Loading MadelineProto...' . PHP_EOL;
        $MadelineProto = new \danog\MadelineProto\API($settings);
        if (getenv('TRAVIS_COMMIT') == '') {
            $checkedPhone = $MadelineProto->auth->checkPhone(// auth.checkPhone becomes auth->checkPhone
                [
                    'phone_number' => getenv('MTPROTO_NUMBER'),
                ]
            );
            \danog\MadelineProto\Logger::log([$checkedPhone], \danog\MadelineProto\Logger::NOTICE);
            $sentCode = $MadelineProto->phone_login(getenv('MTPROTO_NUMBER'));
            \danog\MadelineProto\Logger::log([$sentCode], \danog\MadelineProto\Logger::NOTICE);
            echo 'Enter the code you received in SMS, or in Telegram: ';
            $code = fgets(STDIN, (isset($sentCode['type']['length']) ? $sentCode['type']['length'] : 5) + 1);
            $authorization = $MadelineProto->complete_phone_login($code);
            \danog\MadelineProto\Logger::log([$authorization], \danog\MadelineProto\Logger::NOTICE);
            if ($authorization['_'] === 'account.noPassword') {
                throw new \danog\MadelineProto\Exception('2FA is enabled but no password is set!');
            }
            if ($authorization['_'] === 'account.password') {
                \danog\MadelineProto\Logger::log(['2FA is enabled'], \danog\MadelineProto\Logger::NOTICE);
                $authorization = $MadelineProto->complete_2fa_login(readline('Please enter your password (hint ' . $authorization['hint'] . '): '));
            }
            if ($authorization['_'] === 'account.needSignup') {
                \danog\MadelineProto\Logger::log(['Registering new user'], \danog\MadelineProto\Logger::NOTICE);
                $authorization = $MadelineProto->complete_signup(readline('Please enter your first name: '), readline('Please enter your last name (can be empty): '));
            }

            echo 'Serializing MadelineProto to session.madeline...' . PHP_EOL;
            echo 'Wrote ' . \danog\MadelineProto\Serialization::serialize('session.madeline', $MadelineProto) . ' bytes' . PHP_EOL;
        } else {
            $MadelineProto->bot_login(getenv('BOT_TOKEN'));
        }
    }
    echo "This script will be close soon, it create session.madeline, it is your userbot. Please use php7.0 start.php again.";
    sleep(10);
    exit(0);
}else {
    $settings = [];
    $MadelineProto = \danog\MadelineProto\Serialization::deserialize('session.madeline');

    function sm($ID, $text, $reply = false)
    {
        global $MadelineProto;
        if($reply)
        {
            $MadelineProto->messages->sendMessage(['peer' => $ID, 'message' => $text, 'parse_mode' => "HTML", 'reply_to_message_id' => $reply]);
        } else {
            $MadelineProto->messages->sendMessage(['peer' => $ID, 'message' => $text, 'parse_mode' => "HTML"]);
        }
    }
    /**/
    function mindate($min){ return date('i', $min);}
    function changeName($name){
        global $MadelineProto;
        $MadelineProto->account->updateProfile(['first_name' => $name]);
    }
    function changePropic($inputfile){
        global $MadelineProto;
        $inputFile = $MadelineProto->upload($inputfile);
        $MadelineProto->photos->uploadProfilePhoto(['file' => $inputFile]);
    }
    function reply($chatID,$text,$tomsgId){
        global $MadelineProto;
        $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'reply_to_msg_id' => $tomsgId]);
    }
    function join_chat($chat){
        global $MadelineProto;
        $MadelineProto->messages->importChatInvite(['hash' => $chat ]);
    }
    function pinMex($msgID,$channelID,$silent=false){
        global $MadelineProto;
        $MadelineProto->channels->updatePinnedMessage(['silent' => $silent, 'channel' => $channelID, 'id' => $msgID, ]);

    }
    function changeUsername($username){
        global $MadelineProto;
        $MadelineProto->account->updateUsername(['username' => $username, ]);
    }
    function leggimsg($chatID, $msgid) {
        global $update;
        global $MadelineProto;
        if (isset($chatID) and isset($msgid)) var_export($MadelineProto->messages->readHistory(['peer' => $chatID, 'max_id' => $msgid]));
    }
    function scrivendo($chatID) {
        global $update;
        global $MadelineProto;
        if (isset($chatID)) {
            $sendMessageTypingAction = ['_' => 'sendMessageTypingAction', ];
            var_export($MadelineProto->messages->setTyping(['peer' => $chatID, 'action' => $sendMessageTypingAction]));
        }
    }

    $offset = 0;
    while (true) {
        $updates = $MadelineProto->API->get_updates(['offset' => $offset, 'limit' => 100, 'timeout' => 0]);
        foreach ($updates as $update) {
            $offset = $update['update_id']+1;
            //file_put_contents("logs/logs1.log",var_dump($update));
            //file_put_contents("logs/logs2.log",var_dump($updates));
            try {
                $res = json_encode($update, JSON_PRETTY_PRINT);
                if ($res == '') {
                    $res = var_export($update, true);
                }
                include("config.php");
            } catch (\danog\MadelineProto\RPCErrorException $e) {
                \danog\MadelineProto\Logger::log(["Si e' verificato un errore di tipo RPCException: ".$e->getMessage()], \danog\MadelineProto\Logger::NOTICE);
                file_put_contents('logs/RPCException.log',$e->getMessage());
            } catch (\danog\MadelineProto\Exception $e) {
                \danog\MadelineProto\Logger::log(["Si e' verificato un errore di tipo Exception: ".$e->getMessage()], \danog\MadelineProto\Logger::NOTICE);
                file_put_contents('logs/Exception.log',$e->getMessage());
            }
        }
        echo 'Wrote ' . \danog\MadelineProto\Serialization::serialize('session.madeline', $MadelineProto) . ' bytes' . PHP_EOL;
    }
}





