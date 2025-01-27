<?php
$token = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s"; // Замените на ваш токен
$apiURL = "https://api.telegram.org/bot$token/";
$chatId ="-1002358598239";
function sendInlineKeyboard($chatIdi) {
    $data = file_get_contents('php://input');
    $res = json_decode($data);
    if($res['callback_query']['data']=="/start_survey"){
    global $apiURL;
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'Запустить опрос', 'callback_data' => '/start_survey'],

            ]
            ]
    ];
    
    $encodedKeyboard = json_encode($keyboard);
    print_r(json_decode(file_get_contents($apiURL . "sendMessage?chat_id=$chatIdi&text=Запуск опроса занятости&reply_markup=$encodedKeyboard")));
}}
sendInlineKeyboard($chatId);