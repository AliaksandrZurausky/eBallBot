<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
const TG_TOKEN = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s";
const TG_USER_ID = "1783624604";

$getQuery = [
    "url" => "https://github.com/AliaksandrZurausky/eBallBot/blob/0d6e9f0c7b62cd657f22c37fd55bb42baa102625/index.php" //Ссылка на обработчик будучих сообщений в бота
];
$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN."/setWebhook?".http_build_query($getQuery));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$resultQuery = curl_exec($ch);
curl_close($ch);
print_r(json_decode($resultQuery));

if(file_exists(__DIR__ . "/user_chats.php")){
    require_once(__DIR__ . "/user_chats.php");
};

?>