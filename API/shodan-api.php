<?php
require_once 'Shodan.php';
$key = '';
$client = new Shodan($key, TRUE);

function getShodanIp($ip,$history=false,$minify=false){
    global $client;
    try {
        return $client->ShodanHost(array('ip' => $ip,'history' => $history,'minify' => $minify,));
    } catch (Exception $e) {
        return "Errore Ingestito Shodan:\n".$e->getMessage()."\n";
    }
}

