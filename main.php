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
