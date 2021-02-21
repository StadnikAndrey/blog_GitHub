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

$response = ["res" => false];
$token = $_COOKIE['refreshToken'] ?? null;
$data = Tokens::checkRefreshToken($token);
if (!empty($data) && $data !== false) {
	$user = Authors::getById($data['id_user']);
	if ((int) $user['is_ban'] === 0) {
		$accessToken = Tokens::createAccessToken($user);
		$refreshToken = Tokens::createRefreshToken();
		Tokens::insertRefreshToken($data['id_user'], $refreshToken);
		$response = [
					'res' => true,						 
					'accessToken' => $accessToken
					];
	}	 
}
 
echo json_encode($response);
exit(); 
?>