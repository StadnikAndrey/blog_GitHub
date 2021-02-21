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

//регистрация пользователя 
$error = [];
$user_data = [];			 
// логин 
if( isset($_POST['login']) && mb_strlen($_POST['login']) < 20 
	&& preg_match('/^[a-zA-Z0-9_-]{5,20}$/ius', trim($_POST['login']))) {
		$exist_login = Authors::checkExists('login', trim($_POST['login']));
		if ($exist_login == 'not_exists') {
			$user_data['login'] = htmlspecialchars(trim($_POST['login']), ENT_QUOTES);
		}else{
			$error[] = 'Пользователь с таким логином уже зарегистрирован!';
		}		 	 
}else {
	$error[] = 'Логин может содержать латинские буквы, цифры, нижнее подчеркивание, дефис!';	 	 
}
// e-mail
if(isset($_POST['email']) && mb_strlen($_POST['email']) < 50 && filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
	$exist_email = Authors::checkExists('email', trim($_POST['email']));
	if ($exist_email == 'not_exists') {
		$user_data['email'] = trim($_POST['email']);
	}else{
		$error[] = 'Пользователь с таким e-mail уже зарегистрирован!';
	}
}else{
	$error[] = 'Не правильный E-mail!';
}
// hash пароля
if(isset($_POST['password'])
	&& preg_match('/^[.a-zA-Z0-9_-]{6,20}$/ius', trim($_POST['password']))){
		if (trim($_POST['password']) === trim($_POST['password_verify'])) {
			$user_data['password'] = trim(password_hash($_POST['password'], PASSWORD_DEFAULT));
		}else{
			$error[] = 'Пароль и подтверждение пароля не совпадают!';
		}	 
} else {
	 $error[] = 'Пароль должен содержать от 6 до 20 символов, включая латинские буквы, нижнее или верхнее подчеркивание, точку!';
} 	

if (empty($error)) {
	$registration = Utils::insertDataTable($user_data, 'author');
	if ($registration) {
		echo json_encode(1);
		exit();
	}else{
 	  $error[] = "В данный момент регистрация не возможна по причине неполадок сервера.";
      echo json_encode( $error);
      exit();
	}
}else{	 
	echo json_encode( $error);
	exit();
} 
?>