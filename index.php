<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$token = "";
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

// Функция для отправки гифок
function sendPhoto($chatIdi, $photo) {
	global $apiURL;
	$ch = curl_init($apiURL . 'sendAnimation');
	$arrayQuery = [
		'chat_id' => $chatIdi,
		'animation' => curl_file_create(__DIR__.$photo)
	];
	$setoptArray =array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => 1,
		CURLOPT_HEADER => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_POSTFIELDS =>$arrayQuery,
	);
    curl_setopt_array($ch, $setoptArray);
    $res = curl_exec($ch);
    print_r(json_decode($res));
    

    //file_get_contents($apiURL . "sendMessage?chat_id=$chatIdi&text=Выберите кнопку&reply_markup=$encodedKeyboard");
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
    	}elseif($status == "kicked"){
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
    	$bossesIds = readUserIds($filenameBosses);
    	// Добавляем новое число, если его нет в списке
    	if (!in_array($newUserId, $bossesIds) && $res['message']['new_chat_members']) {
        	$bossesIds[] = $newUserId;
    	}
		// Проверка заблокировал ли пользователь бота
		if($res['message']['left_chat_member']){
			$bossesIds = array_diff($bossesIds, [$newUserId]);
		}

    	// Записываем обновленный список обратно в файл
    	writeUserIds($filenameBosses, $bossesIds);
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
		$bossesIds = readUserIds($filenameBosses);
		if($userIds==true){
			foreach($userIds as $value){
				if(!in_array($value, $bossesIds)){
					file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) интересуется на сколько вы заняты&reply_markup={$encodedKeyboard}");
				}
			}
		}
	}
	//------------------------------------------------------------------------------------------------------//

	// Получен ответ о занятости
	switch($res['callback_query']['data']){
		case "busy":
			$gif = '/nice.gif';
			$bossesIds = readUserIds($filenameBosses);
			if($bossesIds==true){
				foreach($bossesIds as $value){
					file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) ЗАНЯТ!");
				}
				file_get_contents($apiURL . "deleteMessage?chat_id={$chat_id}&message_id={$message_id}");
				sendPhoto($chat_id, $gif);
			}
			break;
		case "supporting":
			$bossesIds = readUserIds($filenameBosses);
			if($bossesIds==true){
				foreach($bossesIds as $value){
					file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) ЗАНИМАЕТСЯ ТЕХПОДДЕРЖКОЙ!");
				}
				file_get_contents($apiURL . "deleteMessage?chat_id={$chat_id}&message_id={$message_id}");
			}
			break;
		case "free":
			$gif = '/bad.gif';
			$bossesIds = readUserIds($filenameBosses);
			if($bossesIds==true){
				foreach($bossesIds as $value){
					file_get_contents($apiURL . "sendMessage?chat_id={$value}&text={$sendUser}({$username}) НЕ ЗАНЯТ!");
				}
				file_get_contents($apiURL . "deleteMessage?chat_id={$chat_id}&message_id={$message_id}");
				sendPhoto($chat_id, $gif);
			}
			break;
	}
}
$data = file_get_contents('php://input');
writeLogFile($data, true);



?>

