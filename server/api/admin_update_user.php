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

$token = Tokens::readToken(); 
  
if ($token) {
	$tokenData = Tokens::getTokenData($token);
	if ((int) $tokenData['exp'] >= time()) {		 				 
		$data = [];		 
		$data['admin'] = $_POST['admin'];
		$data['super_admin'] = $_POST['super_admin'];
		$data['is_ban'] = $_POST['is_ban'];
		$id = $_POST['id_user'];
		if (Utils::updateDataTable($data, 'author', $id)) {
		    $response = ["res" => true];
		}else{
			$response = ["res" => 'изменения не внесены'];
		}     	 
     	      		 
	}
	else{
		http_response_code ( 401 );		 
	}
}
 
echo json_encode($response);
exit(); 
 
?>