<?php 
class Tokens {
	private const SECRET_KEY = 'th4345$1234g[09393%$]';
	private const ACCESS_EXP = 1800; // 30 минут
	private const REFRESH_EXP = 172800; //48 часов
	public static $table = 'sessions';
	 
	public static function packTokenData($data){
		$headers = base64_encode(json_encode(['typ' => 'jwt', 'alg' => 'SHA256']));
		$payload = base64_encode(json_encode($data));
		return $headers . '.' . $payload . '.' . Tokens::signToken($headers, $payload);
	}

	public static function signToken($headers, $payload){
		return hash('sha256', $headers . '.' . $payload . '.' . self::SECRET_KEY);
	}

	public static function createAccessToken($user){
		$iat = time();
		$exp = $iat + self::ACCESS_EXP;
		return Tokens::packTokenData([
			'id' => $user['id'],
			'login' => $user['login'],
			'email' => $user['email'],
			'admin' => $user['admin'],
			'super_admin' => $user['super_admin'],
			'is_ban' => $user['is_ban'],
			'exp' => $exp,
			'iat' => $iat
		]);
	}

	public static function createRefreshToken(){
		return substr(bin2hex(random_bytes(128)), 0, 64);
	}	

	public static function insertRefreshToken($id_user, $refresh_token){		 
		$user = self::getByIdUser($id_user);
		if ($user) {
			Utils::deleteById(self::$table, 'id_user', $id_user);			 
		}

		$now = time();
		$data = [
			'id_user' => $id_user,
			'refresh_token' => $refresh_token,
			'exp' => $now + self::REFRESH_EXP
		];
		
		setcookie('refreshToken', $refresh_token, $data['exp'], '/', '', false, false); 
		Utils::insertDataTable($data, self::$table);		 
	} 

	// получение данных пользователя из sessions по id
	public static function getByIdUser($id) {
		global $pdo;
		$query = "SELECT * FROM " . self::$table . " WHERE id_user = :id";
		$q = $pdo->prepare($query);
		$q->bindValue(':id', $id);
		$q->execute();
		return $user = $q->fetch();
	}

	public static function readToken(){
		$headers = apache_request_headers();
 
		if (isset($headers['Authorization'])) {
			$token = $headers['Authorization'];
		}else if (isset($headers['authorization'])) {
			$token = $headers['authorization'];
		}else{
			$token = null;
		}

		$schema = 'Bearer ';

		if (strpos($token, $schema) === 0) {
			$token = substr($token, strlen($schema));
		}else {
			$token = null;
		}

		return $token;
	}

	public static function getTokenData($token){
		$parts = explode('.', $token);

		if (count($parts) !== 3 || trim($parts[0]) === '' || trim($parts[1]) === '' || trim($parts[2]) === '') {
			throw new Exception("incorrect token format");		
		}

		$header = json_decode(base64_decode($parts[0]), true);
		$payload = json_decode(base64_decode($parts[1]), true);

		if ($parts[2] !== Tokens::signToken($parts[0], $parts[1])) {
			throw new Exception("incorrect sign");
		}

		return $payload;
	}

	public static function checkRefreshToken($token){
		try{			 
			$data = Tokens::getByRT($token);
		}catch(Exception $e){
			$data = [];
		}

		if(isset($data) && $data['exp'] > time()){
			return $data;
		}else{
			return false;
		}
	}

	// получение данных из sessions по RT
	public static function getByRT($rt) {
		global $pdo;
		$query = "SELECT * FROM " . self::$table . " WHERE refresh_token = :rt";
		$q = $pdo->prepare($query);
		$q->bindValue(':rt', $rt);
		$q->execute();
		return $data = $q->fetch();
	}
} 
?>