<?php
$token = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s"; // Замените на ваш токен
$apiURL = "https://api.telegram.org/bot$token/";
$chatId ="-1002358598239";
function sendInlineKeyboard($chatIdi) {
    global $apiURL;
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'Запустить опрос', 'callback_data' => '/start_survey'],

            ]
            ]
    ];
    
    $encodedKeyboard = json_encode($keyboard);
    file_get_contents($apiURL . "sendMessage?chat_id=$chatIdi&text=Запуск опроса занятости&reply_markup=$encodedKeyboard");
}
sendInlineKeyboard($chatId);