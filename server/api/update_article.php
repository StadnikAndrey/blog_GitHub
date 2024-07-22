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
		// Заголовок статьи
		if (!empty(trim($_POST['title'])) && mb_strlen($_POST['title'])<450) {
			$data['title'] = htmlentities(trim($_POST['title']), ENT_QUOTES) ;
		}else{
			$error[] = 'Заголовок не может быть пустым или быть длиннее 450 символов!';
		}
		// Подзаголовок статьи
		if (!empty(trim($_POST['subtitle']))) {
			$data['subtitle'] = htmlentities(trim($_POST['subtitle']), ENT_QUOTES);
		}else{
			$error[] = 'Подзаголовок не может быть пустым!';
		}
		// рубрика
		if (!empty(trim($_POST['rubric_id']))) {
			$data['rubric_id'] = htmlentities(trim($_POST['rubric_id']), ENT_QUOTES);
		}else{
			$error[] = 'Выберите рубрику!';
		}
		// автор
		if (!empty(trim($_POST['author']))) {
			$data['author'] = htmlentities(trim($_POST['author']), ENT_QUOTES) ;
		}else{
			$error[] = 'Автор не может быть пустым! Это системный сбой.';
		}
		// текст без фото
		$text = [];
		$post_text = $_POST['text'] ??  [];
		for ($i=0; $i < count($post_text); $i++) { 			 
			$text[] = [ "type" => "text", "value" => nl2br(trim($_POST["text"][$i]) ,false) ];		 
		}		 
		// фото		 
    	$img = [];
    	for ($i=0; $i < count($_FILES) ; $i++) { 
    		$img[] = ["type" => "img", "value" => []];	
    		foreach($_FILES[$i]["error"] as $key => $error_img) {  
    		$unic_id = uniqid();                      	 
		        //Проверяем значение ошибки и не превышен ли допустимый размер	 
				if(!$error_img && $_FILES[$i]["size"][$key] <= 3000000) { 
					//Адрес папки для сохранения
					// разработка
					// $dirPath =  "d:\blog\src\assets\img_articles\/" . $unic_id . "_". $_FILES[$i]['name'][$key]; 
					// prodaction
					$dirPath =  "./../img/" . $unic_id . "_". $_FILES[$i]['name'][$key]; 			 
					if(move_uploaded_file($_FILES[$i]["tmp_name"][$key], $dirPath)){
						$img[$i]["value"][] = $unic_id . "_" . $_FILES[$i]["name"][$key];
					}else{
						$error[] = "Ошибка при загрузке фото {$_FILES[$i]["name"][$key]}!";
					}					 						
				}else{
					$img[$i]["value"] = "";
				} 
        	}
    	}
    	 
    	$result =[]; //объединение $text и $img в порядке очередности без пустых значений
    	for($i=0;$i<count($img);$i++){
    		if ($img[$i]["value"] != "") {
    			$result[]=$img[$i];
    		}
    		if ($text[$i]["value"] != "") {
    			$result[]=$text[$i];
    		}	         
     	}
     	$data["text"] = json_encode($result);

     	if (empty($error) && Utils::updateDataTable($data, 'articles', $_POST['id'])) {    		 
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
