<?php
// Токен вашего бота
$botToken = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s";
$chatId = "1783624604"; // Укажите свой Chat ID
$message = "Отправка рассылки";
file_get_contents("https://api.telegram.org/bot7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s/sendMessage?chat_id=1783624604&text=Отправка рассылки");

?>