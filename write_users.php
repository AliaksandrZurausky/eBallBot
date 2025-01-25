<?php
// Функция для чтения ID пользователей из файла
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

// Основной код
$filename = 'users.txt';
$newUserId = 124; // Здесь вы можете задать новое значение пользователя

// Читаем текущие ID пользователей
$userIds = readUserIds($filename);

// Добавляем новое число, если его нет в списке
if (!in_array($newUserId, $userIds)) {
    $userIds[] = $newUserId;
}
// Записываем обновленный список обратно в файл
writeUserIds($filename, $userIds);

echo "Обновленный список ID пользователей сохранен в $filename\n";

if($res['my_chat_member']['new_chat_member']['status'] == "kicked"){
    $userIds = array_diff($userIds, [$newUserId]);
}
