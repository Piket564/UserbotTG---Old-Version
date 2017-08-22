
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

mkdir userBot
cd userBot
git init .
git submodule add https://github.com/danog/MadelineProto
cd MadelineProto
composer update
PURPLE='\033[1;35m'
NC='\033[0m' # No Color
echo -e "${PURPLE}Installazione di Madeline Conclusa, scarico la base$!";
cd ..
git clone https://github.com/Piket564/UserbotTG.git
cd UserbotTG
cp -r API/ ../MadelineProto
cp -r audio/ ../MadelineProto
cp -r logs/ ../MadelineProto
cp -r start.php ../MadelineProto
cp -r main.php ../MadelineProto
cp -r config.php ../MadelineProto
cp -r createNew/createnew.php ../MadelineProto
cp -r createNew/.env ../MadelineProto
echo -e "${PURPLE}Installazione Conclusa!${NC}";
