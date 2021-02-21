<?php 
// Общие настройки
define('DEBUG_MODE', true);

if(DEBUG_MODE === true) {
	ini_set('display_errors',1);
	error_reporting(E_ALL);
} 

// Подключение файлов системы
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');
require_once(ROOT.'/config/settings.php'); 

$pdo = Connection::connect();  

/////////////////////////////////////////////////////////////////

// удаление сессии пользователя из sessions при нажатии "Выход" по RT
$rt = $_COOKIE['refreshToken'] ?? null;
if (isset($rt)) {
	Utils::deleteById('sessions', "refresh_token", $rt);
}

// удаление куки с RT
setcookie('refreshToken', '', -1000, '/', '', false, false);  

?>