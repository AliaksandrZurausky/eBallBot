<?php
$token = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s"; // Замените на ваш токен
$apiURL = "https://api.telegram.org/bot$token/";
$chatId ="1783624604";
function sendInlineKeyboard($chatIdi) {
    global $apiURL;
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '⌛Занят', 'callback_data' => 'busy'],
                ['text' => '💁🏼‍♂️Только техподдержка', 'callback_data' => 'supporting'],
                ['text' => '✔Свободен', 'callback_data' => 'free'],

            ]
            ]
    ];
    
    $encodedKeyboard = json_encode($keyboard);
    //file_get_contents($apiURL . "sendMessage?chat_id=$chatIdi&text=Выберите кнопку&reply_markup=$encodedKeyboard");
    file_get_contents("https://api.telegram.org/bot7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s/sendMessage?chat_id=1783624604&text=Выберите кнопку&reply_markup=$encodedKeyboard");
}
sendInlineKeyboard($chatId);