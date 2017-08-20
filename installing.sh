#!/bin/bash
PURPLE='\033[1;35m'
NC='\033[0m' # No Color

echo -e "
${PURPLE}#     #                                                      ######  #     # ######
#     #  ####  ###### #####  #####   ####  #####             #     # #     # #     #
#     # #      #      #    # #    # #    #   #               #     # #     # #     #
#     #  ####  #####  #    # #####  #    #   #      #####    ######  ####### ######
#     #      # #      #####  #    # #    #   #               #       #     # #
#     # #    # #      #   #  #    # #    #   #               #       #     # #
 #####   ####  ###### #    # #####   ####    #               #       #     # #       ${NC}

 ";

mkdir /root/userBot
cd /root/userBot
git init .
git submodule add https://github.com/danog/MadelineProto
cd MadelineProto
composer update
PURPLE='\033[1;35m'
NC='\033[0m' # No Color
echo -e "${PURPLE}Installazione di Madeline Conclusa, scarico la base$!";
cd /root/userBot/
git clone https://github.com/Piket564/UserbotTG.git
cd UserbotTG
cp -r API/ /root/userBot/MadelineProto
cp -r audio/ /root/userBot/MadelineProto
cp -r logs/ /root/userBot/MadelineProto
cp -r start.php /root/userBot/MadelineProto
cp -r main.php /root/userBot/MadelineProto
cp -r config.php /root/userBot/MadelineProto
cp -r createNew/createnew.php /root/userBot/MadelineProto
cp -r createNew/.env /root/userBot/MadelineProto

echo -e "${PURPLE}Installazione Conclusa!${NC}";
