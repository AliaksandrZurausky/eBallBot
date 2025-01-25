<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$token = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s";
$apiURL = "https://api.telegram.org/bot{$token}/";
// Функция для записи ID пользователей в файл
function readUserIds($filename) {
    if (!file_exists($filename)) {
        return [];
    }

    $content = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map('trim', $content); // Убираем пробелы и пустые строки
}

// Функция для записи ID пользователей в файл users.txt
function writeUserIds($filename, $userIds) {
    file_put_contents($filename, implode(PHP_EOL, $userIds));

}

// $data = file_get_contents('php://input');
// $data = json_decode($data, true);
function writeLogFile($string, $clear = false){
	//запись ответа от TG в файл message.txt
	global $apiURL;
    $log_file_name = __DIR__."/message.txt";
    $now = date("Y-m-d H:i:s");
	$res = json_decode($string,true);
    if($clear = false){
        file_put_contents($log_file_name, $now ." ". print_r($res, true). "\r\n", FILE_APPEND);
    }
    else{
        file_put_contents($log_file_name, $now ." ". print_r($res, true). "\r\n", FILE_APPEND);
    }
	//-------------------------------------------------------------------------------------------------//
	$chat_id = $res['callback_query']['message']['chat']['id'];
	$message_id = $res['callback_query']['message']['message_id'];
	$callbackFrom = $res['callback_query']['from']['id'];
	$sendUser = $res['callback_query']['from']['first_name'];
	$username = "@".$res['callback_query']['from']['username'];
	$status = $res["my_chat_member"]["new_chat_member"]["status"];

    $filename = __DIR__. '/users.txt';
	$filenameBosses = __DIR__. '/bosses.txt';


	// Кто-то подписался/отписался на бота
	if($status==true){
		$newUserId = $res["my_chat_member"]["from"]["id"];
    	// Читаем текущие ID пользователей
    	$userIds = readUserIds($filename);
    	// Добавляем новое число, если его нет в списке
    	if (!in_array($newUserId, $userIds) && $status!=='kicked') {
        	$userIds[] = $newUserId;
    	}
		// Проверка заблокировал ли пользователь бота
		if($status == "kicked"){
			$userIds = array_diff($userIds, [$newUserId]);
		}

    	// Записываем обновленный список обратно в файл
    	writeUserIds($filename, $userIds);
	}
	//----------------------------------------------------------------------------------------------------//

	// Добавлен новый пользователь в группу
	if($res['message']['left_chat_member']==true || $res['message']['new_chat_members']==true){
		$newUserId = $res["message"]["from"]["id"];
    	// Читаем текущие ID пользователей
    	$userIds = readUserIds($filenameBosses);
    	// Добавляем новое число, если его нет в списке
    	if (!in_array($newUserId, $userIds) && $res['message']['new_chat_members']) {
        	$userIds[] = $newUserId;
    	}
		// Проверка заблокировал ли пользователь бота
		if($res['message']['left_chat_member']){
			$userIds = array_diff($userIds, [$newUserId]);
		}

    	// Записываем обновленный список обратно в файл
    	writeUserIds($filenameBosses, $userIds);
	}
	//-----------------------------------------------------------------------------------------------------//

	//Запущена рассылка сообщений
	if($res['callback_query']['data']=="/start_survey"){
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
		$userIds = readUserIds($filename);
		if($userIds==true){
			foreach($userIds as $value){
				file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) интересуется на сколько вы заняты&reply_markup={$encodedKeyboard}");

			}
		}
	}
	//------------------------------------------------------------------------------------------------------//

	// Получен ответ о занятости
	switch($res['callback_query']['data']){
		case "busy":
			$userIds = readUserIds($filenameBosses);
			if($userIds==true){
				foreach($userIds as $value){
					file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) ЗАНЯТ!");
				}
				file_get_contents($apiURL . "deleteMessage?chat_id={$chat_id}&message_id={$message_id}");
			}
			break;
		case "supporting":
			$userIds = readUserIds($filenameBosses);
			if($userIds==true){
				foreach($userIds as $value){
					file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) ЗАНИМАЕТСЯ ТЕХПОДДЕРЖКОЙ!");
				}
				file_get_contents($apiURL . "deleteMessage?chat_id={$chat_id}&message_id={$message_id}");
			}
			break;
		case "free":
			$userIds = readUserIds($filenameBosses);
			if($userIds==true){
				foreach($userIds as $value){
					file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) НЕ ЗАНЯТ!");
				}
				file_get_contents($apiURL . "deleteMessage?chat_id={$chat_id}&message_id={$message_id}");
			}
			break;
	}
}
$data = file_get_contents('php://input');
writeLogFile($data, true);



?>

