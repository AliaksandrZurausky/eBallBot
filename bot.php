<?php

require 'vendor/autoload.php'; // Подключаем автозагрузчик Composer

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('telegram_bot');
$logger->pushHandler(new StreamHandler('bot.log', Logger::DEBUG));

define('TOKEN', '7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s'); // Вставьте ваш токен

// Список авторизованных пользователей (ID)
$AUTHORIZED_USERS = [1783624604]; // Укажите ID пользователей, которые могут видеть кнопку

function sendTelegramMessage($chat_id, $text) {
    $url = "https://api.telegram.org/bot" . TOKEN . "/sendMessage";
    file_get_contents($url . "?chat_id=" . $chat_id . "&text=" . urlencode($text));
}

function sendPoll($chat_id, $question, $options) {
    $url = "https://api.telegram.org/bot" . TOKEN . "/sendPoll";
    $data = [
        'chat_id' => $chat_id,
        'question' => $question,
        'options' => json_encode($options),
    ];
    file_get_contents($url . '?' . http_build_query($data));
}

function processUpdate($update) {
    global $AUTHORIZED_USERS;

    $chat_id = $update['message']['chat']['id'];
    $text = $update['message']['text'] ?? '';

    if (strpos($text, '/start') === 0) {
        if (in_array($chat_id, $AUTHORIZED_USERS)) {
            sendTelegramMessage($chat_id, "Привет! Нажмите кнопку для создания опроса.");
            // Отправьте кнопки (вы можете использовать inline-клавиатуру)
        }
    } elseif ($text == 'Создать опрос' && in_array($chat_id, $AUTHORIZED_USERS)) {
        $question = "Ваш опрос?";
        $options = ["Вариант 1", "Вариант 2"];
        
        // Рассылка опроса всем подписчикам
        foreach ($AUTHORIZED_USERS as $user_id) {
            sendPoll($user_id, $question, $options);
        }
    }
}

// Основной цикл
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update['message'])) {
    processUpdate($update);
}

?>
