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

/////////////////////////////////////////////////////////////

$response = ["res" => false];

$token = Tokens::readToken(); 
  
if ($token) {
	$tokenData = Tokens::getTokenData($token);
	if ((int) $tokenData['exp'] >= time()) {		 				 
		$visibility = isset($_POST['check']) ? 1 : 0;
		$data = ['visibility' => $visibility];
		$id = $_POST['id_article'];
		if (Utils::updateDataTable($data, 'articles', $id)) {
		    $response = ["res" => true];
		}else{
			$response = ["res" => 'видимость статьи не удалось изменить'];
		}      	      		 
	}
	else{
		http_response_code ( 401 );		 
	}
}
 
echo json_encode($response);
exit();  
?>