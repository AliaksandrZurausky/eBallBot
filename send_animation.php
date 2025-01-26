<?php
$token = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s"; // Замените на ваш токен
$apiURL = "https://api.telegram.org/bot$token/sendAnimation";
$chatId ="-1002358598239";
$ch = curl_init($apiURL);
$arrayQuery = [
    'chat_id' => $chatId,
    'animation' => curl_file_create(__DIR__.'/bad.gif')
];
$setoptArray =array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POSTFIELDS =>$arrayQuery,
);

function sendPhoto($chatIdi) {
    global $ch;
    global $setoptArray;
    curl_setopt_array($ch, $setoptArray);
    $res = curl_exec($ch);
    print_r(json_decode($res));
    

    //file_get_contents($apiURL . "sendMessage?chat_id=$chatIdi&text=Выберите кнопку&reply_markup=$encodedKeyboard");
}
sendPhoto($chatId);