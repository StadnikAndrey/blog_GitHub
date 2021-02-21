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

//////////////////////////////////////////////////////////// 

//аутентификация пользователя  
$response = [];
if ( isset($_POST['email']) && !empty(trim($_POST['email'])) &&
	isset($_POST['password']) && !empty(trim($_POST['password'])) ) {
				$user = Authors::autentification(trim($_POST['email']), trim($_POST['password']));	 		 	 
				if(is_array($user) && isset($user['id']) && !empty($user['id']) && $user['is_ban'] == 1) {
					$response['res'] = false;
					$response['error'][] = 'Ваша учетная запись заблокирована администратором!';				 
				}else if(is_array($user) && isset($user['id']) && !empty($user['id']) && $user['is_ban'] == 0 ) { 
					  $accessToken = Tokens::createAccessToken($user);
					  $refreshToken = Tokens::createRefreshToken();
					  Tokens::insertRefreshToken($user['id'], $refreshToken);

					$response = [
						'res' => true,						 
						'accessToken' => $accessToken
					];
				}else {
					if ($user != 'exit') {
						$response['res'] = false;
						$response['error'][] = 'Неправильный логин или пароль. ';
						$response['error'][] = "Неудачная попытка входа. Количество оставшихся попыток " . (($settings['login_tries'] +1) - $user);						 
					}else{
						$response['res'] = false;
						$response['error'] = 'exit';						 
					}					 
				}
}else{	
	$response['res'] = false; 
	$response['error'][] = 'Введите логин и пароль! vvedite login i parol';	 
}
echo json_encode($response);
exit();  
?>