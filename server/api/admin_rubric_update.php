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
		$error = [];				 
		$data = [];		 
		// Название рубрики
		if (!empty(trim($_POST['name'])) && mb_strlen($_POST['name'])<450) {
			$data['name'] = htmlentities(trim($_POST['name']), ENT_QUOTES) ;
		}else{
			$error[] = 'Название рубрики не может быть пустым или быть длиннее 450 символов!';
		}
		// url
		if ( preg_match('/^[a-zA-Z0-9-]{1,}$/ius', trim($_POST['url'])) ) {
			$data['url'] = htmlentities(trim($_POST['url']), ENT_QUOTES);
		}else{
			$error[] = 'URL может содержать символы латинского алфавита и цифры без пробелов!';
		}
		 
     	if (empty($error)) {
     		$insert_id = Utils::updateDataTable($data, 'rubrics', $_POST['id']);
     		$response = ["res" => true];
     	}else{
     		$response = ["res" => true, "error" => $error];
     	}     	 
	}
	else{
		http_response_code ( 401 );		 
	}
}
 
echo json_encode($response);
exit(); 
 
?>