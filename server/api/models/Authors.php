<?php
class Authors {	 
	public static $table = 'author';
	// проверка существования юзера по параметрам
	public static function checkExists($fild, $value) {
		global $pdo;
		$query = "SELECT * FROM " . self::$table . " WHERE $fild = :" .$fild;
		$q = $pdo->prepare($query);		 
		$q->bindValue(':' . $fild, $value);
		$q->execute();
		$user = $q->fetch();
		if(empty($user)) {
			return 'not_exists';
		} else {			 
			if($user[$fild] == $value) {
				return 'exists';
			}			 
		}
	}
	// аутентификация
	public static function autentification($email, $password) {
		$user = self::getByEmail($email);
		if(!empty($user)) {
			$auth = password_verify($password, $user['password']);
		} else {
			$auth = false;
		}

		if($auth === true) {
			self::resetTries();
			return $user;
		} else {
			return self::badAuth();
		}		 
	}
	// получение всех данных пользователя по email
	public static function getByEmail($email) {
		global $pdo;
		$query = "SELECT * FROM " . self::$table . " WHERE email = :email";
		$q = $pdo->prepare($query);
		$q->bindValue(':email', $email);
		$q->execute();
		return $user = $q->fetch();
	}
	// получение всех данных пользователя по id
	public static function getById($id) {
		global $pdo;
		$query = "SELECT * FROM " . self::$table . " WHERE id = :id";
		$q = $pdo->prepare($query);
		$q->bindValue(':id', $id);
		$q->execute();
		return $user = $q->fetch();
	}
	// количество неудачных попыток входа и запись в файл
	public static function badAuth() {
		global $settings;
		$ip = $_SERVER['REMOTE_ADDR'];
		$file_url = ROOT . '/logs/' . $ip . '.txt';
		if(file_exists($file_url)) {
			$con = file_get_contents($file_url);
		} else {
			$con = 1;
		}
		
		if($con == $settings['login_tries']) {
			return 'exit';
		} else {
			$con++;
			file_put_contents($file_url, $con);
		}

		return $con;
	}
	// удаление файла с количеством неудачных попыток после авторизации
	public static function resetTries() {
		global $setting;
		$ip = $_SERVER['REMOTE_ADDR'];
		$file_url = ROOT . '/logs/' . $ip . '.txt';
		if(file_exists($file_url)) {
			unlink($file_url);
		}
	}

	// получение пользователей (для админпанели)
	public static function getAuthors($data = []){
		global $pdo;
		$where = '';
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$where = " WHERE $key = $value ";
			}
		}		 
		$sql = "SELECT id, login, email, admin, super_admin, is_ban, date_add FROM " . self::$table . " {$where} ORDER BY id DESC ";
		$query = $pdo->prepare($sql);		 		 
		$query->execute();
		return $result = $query->fetchAll();		 
	}	 
}
?>