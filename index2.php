<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
const TG_TOKEN = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s";
const TG_USER_ID = "1783624604";

function readUserIds($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    
    $content = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map('trim', $content); // Убираем пробелы и пустые строки
}

// Функция для записи ID пользователей в файл
function writeUserIds($filename, $userIds) {
    file_put_contents($filename, implode(PHP_EOL, $userIds));
}

// $data = file_get_contents('php://input');
// $data = json_decode($data, true);
function writeLogFile($string, $clear = false){
    $log_file_name = __DIR__."/message.txt";
    $now = date("Y-m-d H:i:s");
    if($clear = false){
        file_put_contents($log_file_name, $now ." ". print_r($string, true). "\r\n", FILE_APPEND);
    }
    else{
        file_put_contents($log_file_name, $now ." ". print_r($string, true). "\r\n", FILE_APPEND);
    }

    $filename = 'users.txt';
    $newUserId = 1200; // Здесь вы можете задать новое значение пользователя

    // Читаем текущие ID пользователей
    $userIds = readUserIds($filename);

    // Добавляем новое число, если его нет в списке
    if (!in_array($newUserId, $userIds)) {
        $userIds[] = $newUserId;
    }

    // Записываем обновленный список обратно в файл
    writeUserIds($filename, $userIds);

}
$data = file_get_contents('php://input');
writeLogFile($data, true);



?>
