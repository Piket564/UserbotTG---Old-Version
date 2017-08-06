<?php
require_once '../vendor/autoload.php';
if (file_exists('.env')) {
    echo 'Loading .env...'.PHP_EOL;
    $dotenv = new Dotenv\Dotenv(getcwd());
    $dotenv->load();
}else{
    \danog\MadelineProto\Logger::log(["Missing .env file."], \danog\MadelineProto\Logger::FATAL_ERROR);
    exit(0);
}
if (getenv('TEST_SECRET_CHAT') == '') {
    \danog\MadelineProto\Logger::log(['TEST_SECRET_CHAT is not defined in .env, please define it.'.PHP_EOL],\danog\MadelineProto\Logger::FATAL_ERROR);
    die('');
}
echo 'Loading settings...'.PHP_EOL;
$settings = json_decode(getenv('MTPROTO_SETTINGS'), true) ?: [];
var_dump($settings);
$MadelineProto=false;
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
sleep(20);
exit(0);