<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
const TG_TOKEN = "7796321296:AAGF3pL1raIZ1iIL7kA7iDLPXoqkOUS8X2s";
const TG_USER_ID = "1783624604";


$data = file_get_contents('php://input');
$data = json_decode($data, true);
function writeLogFile($string, $clear = false){
    $log_file_name = __DIR__."/message.txt";
    if($clear = false){
        $now = date("Y-m-d H:i:s");
        file_put_contents($log_file_name, $now ." ". print_r($string, true). "\r\n", FILE_APPEND);
    }
    else{
        file_put_contents($log_file_name, $now ." ". print_r($string, true). "\r\n", FILE_APPEND);
    }
}
$data = file_get_contents('php://input');
writeLogFile($data, true);
echo file_get_contents(__DIR__."/message.txt");


?>
