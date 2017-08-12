# UserbotTG
Questa è una base per MadelineProto, un client costruito per Telegram. 

**_[DOCS MadelineProto](https://daniil.it/MadelineProto/) Leggere Prima!_**

## Iniziamo
Prima di tutto, controlla di avere una VPS o un Raspberry Pi (anche un PC con distro Linux) 
con installato LAMP, php7.0, php7.0-dev, composer e git. Potrebbero volerci altri pacchetti ma Google 
è tuo amico.

Per Ubuntu:
```shell
sudo apt-get install python-software-properties software-properties-common
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get remove php5-common -y
sudo apt-get install php7.0 php7.0-fpm php7.0-mysql -y
sudo apt-get --purge autoremove -y
----------------------------------------------------------------------------
sudo add-apt-repository ppa:ondrej/php
sudo apt-get install php7.0-dev
sudo apt-get install curl git
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```
Per Debian 8.X JESSIE
```shell
sudo apt-get install curl
curl https://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -
echo 'deb http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list
echo 'deb-src http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list
sudo apt-get update
sudo apt-get install php7.0
----------------------------------------------------------------------------
sudo apt-get update
sudo apt-get install curl php5-cli git
sudo php7.0 /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
```shell
```
Testiamo con:
```shell
$ composer
```
Risultato:
```shell
Output
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 1.0-dev (9859859f1082d94e546aa75746867df127aa0d9e) 2015-08-17 14:57:00

Usage:
 command [options] [arguments]

Options:
 --help (-h)           Display this help message
 --quiet (-q)          Do not output any message
 --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
 --version (-V)        Display this application version
 --ansi                Force ANSI output
 --no-ansi             Disable ANSI output
 --no-interaction (-n) Do not ask any interactive question
 --profile             Display timing and memory usage information
 --working-dir (-d)    If specified, use the given directory as working directory.

. . .
```
Per facilitarti la vita [Daniil Gentili](https://daniil.it/ "Daniil Gentili") ha creato [php.sh](https://daniil.it/php.sh "php.sh") per installare tutto. 

Dopo aver installato tutto in una Directory vuota:
```
git clone https://github.com/danog/MadelineProto.git
cd MadelineProto
composer update
```
Ci ritroveremo con la cartella di MadelineProto.

## Creiamo l'userbot

Scaricate la mia Base e copiatela nella Cartella MadelineProto, per completare il .env file recatevi in [my.telegram.org](my.telegram.org):
```shell
MTPROTO_NUMBER= [NUMBER]
MTPROTO_SETTINGS={"app_info":{"api_id":[API_ID],"api_hash":"[API_HASH]"}} 
TEST_USERNAME= [USERNAME]
TEST_DESTINATION_GROUPS="[LINK]"
TEST_DESTINATION_GROUPS=["@pwrtelegramgroup","@pwrtelegramgroupita"]
TEST_SECRET_CHAT= @YOUR_USERNAME
BOT_TOKEN=
```

Ora, con [createnew.php]() possiamo creare una sessione di Madeline. (session.madeline).

```shell
$ php7.0 createnew.php
#LOGS#
MTProto:                We're in NL, current dc is 2, nearest dc is 4.
API:                    Running APIFactory...
API:                    MadelineProto is ready!
createnew:              {
    "_": "auth.checkedPhone",
    "phone_registered": true
}
Login:                  Sending code...
Login:                  Code sent successfully! Once you receive the code you should use the complete_phone_login                                                                                                 function.
createnew:              {
    "_": "auth.sentCode",
    "phone_registered": true,
    "type": {
        "_": "auth.sentCodeTypeApp",
        "length": 5
    },
    "phone_code_hash": "6e96fadd7775bbf24d",
    "next_type": {
        "_": "auth.codeTypeSms"
    },
    "phone_number": "+390000000"
}
Enter the code you received in SMS, or in Telegram: 18865
Login:                  Logging in as a normal user...
MTProto:                2
MTProto:                2
MTProto:                Copying authorization from dc 4 to dc 2...
MTProto:                1
MTProto:                1
MTProto:                Copying authorization from dc 4 to dc 1...
MTProto:                3
MTProto:                3
MTProto:                Copying authorization from dc 4 to dc 3...
MTProto:                5
MTProto:                5
MTProto:                Copying authorization from dc 4 to dc 5...
Login:                  Logged in successfully!
createnew:              {
    "_": "auth.authorization",
    "user": {
        "_": "user",
        "self": true,
        "contact": true,
        "mutual_contact": true,
        "deleted": false,
        "bot": false,
        "bot_chat_history": false,
        "bot_nochats": false,
        "verified": false,
        "restricted": false,
        "min": false,
        "bot_inline_geo": false,
        "id": 152500233,
        "access_hash": ,
        "first_name": "ℒᎾℛⅅ Piket",
        "last_name": "{LIMITED} NOT A ᵈᵉᵛᵉˡᵒᵖᵉʳ |Ҋ| ISR = Internet Society | 🃏 | Sterben |",
        "username": "Piket_564",
        "phone": "00000000000",
        "photo": {
            "_": "userProfilePhoto",
            "photo_id": ,
            "photo_small": {
                "_": "fileLocation",
                "dc_id": 4,
                "volume_id": ,
                "local_id":,
                "secret": 
            },
            "photo_big": {
                "_": "fileLocation",
                "dc_id": 4,
                "volume_id": ,
                "local_id": ,
                "secret": 
            }
        },
        "status": {
            "_": "userStatusOnline",
            "expires": 1501799608
        }
    }
}
Serializing MadelineProto to session.madeline...
Wrote 343232 bytes
This script will be close soon, it create session.madeline, it is your userbot. Please use php7.0 start.php again
#ENDLOGS#
```
Perfetto, abbiamo creato quello che ci interessava. (NB. tra una versione e l'altra di Madeline è bene ricreare la sessione, spesso si può corrompere).

Aggiungiamo il nostro codice in [main.php]().

```php
<?php
if ($msg == '.start') {
    isWriting($chatID);//function for is writing... on bar! Thx Danog!
    sm($chatID, "I'm working!\nBot Online!");//function on start.php
}
?>
```
## API Twitter Search

Esiste il comando [.twitter](), in $items possiamo trovare:
```
$items['created_at']
$items['text']
$items['user']['name']
$items['user']['followers_count']
$items['user']['friends_count']
$items['user']['listed_count']
```
## Calls

Ora .callMe e .call [Username,ID] funziona correttamente con il codice di [magna.php]:

```php
<?php
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
```
Per accettare le chiamate usare questo codice, o guardare in [start.php].
```php
<?php
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
```
## Tips

Potete scrivermi tutti i problemi a [@piketLimitato_bot](https://t.me/piketLimitato_bot), se ci sono gravi errori aprite un Issue.

Help page: http://telegra.ph/Help-Page-08-06

## Contributing
Leggere https://daniil.it/MadelineProto/ docs.

Un enorme grazie a: 
* ***Daniil Gentili* - [Danil](https://t.me/danogentili), per aver creato MadelinProto.

Tutti i gruppi ed utenti come PWRT, MadelineProto,[Lorenzo Maffii](https://t.me/WMD_Lorenzo),[Grizzly](https://t.me/Grizzly22),[Ganja](https://t.me/Ganjailcinese), Altervista Bot e gran parte di Telegram ITALIA.

## Authors

* ***luckymls* - *First Commit* - [luckymls](https://t.me/@luckymls)
* ***ZioAlb3r* - *start.php* - [Zio](https://t.me/ZioAlb3r)
* ***Piket_564* - *all other files and BugFix* - [Piket](https://t.me/Piket_564)
