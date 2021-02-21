<?php 
class Articles{
	public static $table = 'articles';

	// получение статей для Home.vue
	public static function getArticles($get = [],$countProductsPage = 100000){		 
		global $pdo;	
		// постраничная навигация
	    if (isset($get['page'])&&!empty($get['page'])) {
	   	$start = ((int)($get['page']))*$countProductsPage;		   	 
		    }else{
		   		$start = 0;
		    }
	    // рубрика
	    if ( isset($get['rubric'])&&!empty($get['rubric']) ) {
	    		$get_rubric = (int)$get['rubric'] ;
	     		$rubric = "AND  `articles`.`rubric_id` IN({$get_rubric})";
	     	}else{ 
	     		$rubric = "";
	     	}	     
	    // автор
	    if ( isset($get['author'])&&!empty($get['author']) ) {	    		 
	     		$author = " AND  `articles`.`author` IN({$get['author']}) ";
	     	}else{ 
	     		$author = "";
	     	}		

		$query = "SELECT  `articles`.*, `rubrics`.`name` AS rubric_name , `rubrics`.`url` AS rubric_url ,
		`author`.`login` AS author_login FROM  `articles`
   		INNER JOIN `rubrics` ON `articles`.`rubric_id`= `rubrics`.`id` 
   		INNER JOIN `author` ON `articles`.`author`= `author`.`id`   
		WHERE  `articles`.`visibility`= 1 {$rubric} {$author} ORDER BY  `articles`.`id` DESC LIMIT {$start}, {$countProductsPage}";
		$q = $pdo->prepare($query);		 		 
		$q->execute();
		$result = $q->fetchAll();
		if ($result != null) {
			foreach ($result as $key => $value) {
				$result[$key]['title'] =  html_entity_decode($result[$key]['title']);
				$result[$key]['subtitle'] =  html_entity_decode($result[$key]['subtitle']);
			}				 			 
		}		 
		return $result;		 
	}

	// получение рубрик для Home
	public static function getRubrics(){		 
		global $pdo;
		$query = "SELECT DISTINCT `articles`.`rubric_id`, `rubrics`.`name` AS rubric_name,
		 `rubrics`.`url` AS rubric_url    FROM  `articles`
   		INNER JOIN `rubrics` ON `articles`.`rubric_id`= `rubrics`.`id`   		   
		WHERE  `articles`.`visibility`= 1 ORDER BY `articles`.`id` DESC ";
		$q = $pdo->prepare($query);		 		 
		$q->execute();
		return $category = $q->fetchAll();		  		 
	}

	// получение количества статей для постраничной навигации (при скролле)
	public static function getCountPages($get){		 
		global $pdo;		 
	    // рубрика
	    if ( isset($get['rubric'])&&!empty($get['rubric']) ) {
	    		$get_rubric = (int)$get['rubric'] ;
	     		$rubric = "AND  `articles`.`rubric_id` IN({$get_rubric})";
	     	}else{ 
	     		$rubric = "";
	     	}	     
	    // автор
	    if ( isset($get['author'])&&!empty($get['author']) ) {	    		 
	     		$author = " AND  `articles`.`author` IN({$get['author']}) ";
	     	}else{ 
	     		$author = "";
	     	}		

		$query = "SELECT COUNT(`articles`.`id`) AS count  FROM  `articles`   		    		    
		WHERE  `articles`.`visibility`= 1 {$rubric} {$author}  ";
		$q = $pdo->prepare($query);		 		 
		$q->execute();
		$category = $q->fetch();
		return  $category['count'];		 
	}
	// получение одной статьи по id
	public static function getArticle($id){			 
		global $pdo;
		$query = "SELECT `articles`.*,		 
		`rubrics`.`name` AS rubric_name,
		`author`.`login` AS author_login,
		`rubrics`.`url` AS rubric_url FROM  articles
		INNER JOIN `author` ON `articles`.`author`= `author`.`id`
		INNER JOIN `rubrics` ON `articles`.`rubric_id`= `rubrics`.`id`
		WHERE  `articles`.`id` = :id ";
		$q = $pdo->prepare($query);
		$q->bindValue(':id', $id );		 
		$q->execute();
		$result = $q->fetch();
		if ($result != null) {		
			// $result['text']	= str_replace(" ", "&nbsp;", $result['text']);	 
			$result['text']	= json_decode($result['text']);
			$result['title'] =  html_entity_decode($result['title']);
			$result['subtitle'] =  html_entity_decode($result['subtitle']);
		}		 
		return $result;	  		 
	}

	// ЛИЧНЫЙ КАБИНЕТ:
	// получение всех статей автора
	public static function getArticlesUser($id_user){
		global $pdo;		 
		$sql = "SELECT * FROM " . self::$table . " WHERE author = :id_user ORDER BY id DESC ";
		$query = $pdo->prepare($sql);
		$query->bindValue(':id_user', $id_user );		 
		$query->execute();
		$result = $query->fetchAll();
		if ($result != null) {
			foreach ($result as $key => $value) {
				$result[$key]['title'] =  html_entity_decode($result[$key]['title']);
			}				 			 
		}		 
		return $result;		 
	}

	// получение статьи для редактирования
	public static function getArticleUserForUpdate($id_article, $id_user){
		global $pdo;		 
		$sql = "SELECT * FROM " . self::$table . " WHERE id = :id_article AND author = :id_user ";
		$query = $pdo->prepare($sql);
		$query->bindValue(':id_article', $id_article );
		$query->bindValue(':id_user', $id_user );		 
		$query->execute();
		$result = $query->fetch();
		if ($result != null) {
			$result['text']	= preg_replace('/(<br>)/iu','', $result['text']);
			$result['text']	= json_decode($result['text']);
			$result['title'] =  html_entity_decode($result['title']);
			$result['subtitle'] =  html_entity_decode($result['subtitle']);
		}		 
		return $result;	
	}

	// получение всех рубрик  
	public static function getRubricsAll(){		 
		global $pdo;
		$query = "SELECT * FROM  rubrics ORDER BY id DESC ";
		$q = $pdo->prepare($query);		 		 
		$q->execute();
		return $category = $q->fetchAll();		  		 
	}

	// АДМИНПАНЕЛЬ:
	// получение всех статей для админпанели
	public static function getArticlesForAdmin(){
		global $pdo;		 
		$sql = "SELECT * FROM " . self::$table . " ORDER BY id DESC ";
		$query = $pdo->prepare($sql);		 		 
		$query->execute();
		$result = $query->fetchAll();
		if ($result != null) {
			foreach ($result as $key => $value) {
				$result[$key]['title'] =  html_entity_decode($result[$key]['title']);
			}				 			 
		}		 
		return $result;		 
	}

	// получение рубрики по названию
	public static function getRubricForName($name){
		global $pdo;		 
		$sql = "SELECT * FROM rubrics WHERE name = :name ";
		$query = $pdo->prepare($sql);	
		$query->bindValue(':name', $name );	 		 
		$query->execute();
		return $result = $query->fetch();		 
	}

	// получение рубрики по id
	public static function getRubricId($id){
		global $pdo;		 
		$sql = "SELECT * FROM rubrics WHERE id = :id ";
		$query = $pdo->prepare($sql);	
		$query->bindValue(':id', $id );	 		 
		$query->execute();
		return $result = $query->fetch();		 
	}	 	 
}
?>