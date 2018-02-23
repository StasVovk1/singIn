<?php 
$db_name = 'db.xml';
error_reporting( E_ERROR );

if (isset($_POST['exit'])){
	setcookie("login", $login, -1, "/");
	setcookie("password", $password, -1, "/");
	unset($_SESSION);
}


// провера на содержание Cookie и авторизация
if (isset($_POST['checkCookie'])){
	$messedg = array(
		'error' => 1,
		'mess'  => 'Что то неподошло!'
	);
	if ($_COOKIE['login']){
		$answer = searchUser($_COOKIE['login'],$_COOKIE['password'],'',$db_name);
		if ($answer['conect']){
			if (!$_SESSION['login']){
				$_SESSION['login'] = $_COOKIE['login'];
				$_SESSION['password'] = $_COOKIE['password'];
				$_SESSION['name'] = $answer['name'];
			}
			$messedg['mess'] .= $answer['name'];
			$messedg['error'] = 0;
 			$messedg['mess'] = 'Привет '.$answer['name'];		
		}
	}

	echo json_encode($messedg);
	exit();
}


// регистрация
if (isset($_POST['login'])){
	$messedg = array(
		'error' => 0,
		'mess'  => 'Привет '
	);

	$login 					= FormChars (trim($_POST['login']));
	$firstPassword  = trim($_POST['firstPassword']);
	$secondPassword = trim($_POST['secondPassword']);
	$email 					= FormChars (trim($_POST['email']));
	$name 					= FormChars (trim($_POST['name'])); 

	if ((strlen($login) == 0) || (strlen($firstPassword) == 0) || (strlen($secondPassword) == 0) || (strlen($email) == 0) || (strlen($name) == 0)){
		$messedg['error'] = 1;
		$messedg['mess'] = 'Заполните все поля!';
		echo json_encode($messedg);	
		exit();
	}

	$ansver = searchUser($login,'',$email,$db_name);
	if ($ansver['login_b']){
		$messedg['error'] = 1;
		$messedg['mess'] = 'Данный логин занят, попробуйте другой!';
		echo json_encode($messedg);	
		exit();
	}else if ($ansver['email_b']){
		$messedg['error'] = 1;
		$messedg['mess'] = 'Данный email занят, попробуйте другой!';
		echo json_encode($messedg);	
		exit();
	}



	if ($firstPassword != $secondPassword){
		$messedg['error'] = 1;
		$messedg['mess'] = 'Повторите правильно пароль! 1)'.$firstPassword.', 2)'.$secondPassword;
	}else {
		$password = GenPass ($login,$firstPassword);
	}


	if ($messedg['error'] == 0){
		$data = array (
			'login' 		=>	$login,
			'password' 	=>	$password,
			'email' 		=>	$email,
			'name' 			=>	$name,
		);


		if (addUserBd ($data, $db_name)){
			$messedg['mess'] .= $name;

			setcookie("login", $login, time()+3600, "/");
			setcookie("password", $password, time()+3600, "/");
			$_SESSION['login'] = $login;
			$_SESSION['password'] = $password;
			$_SESSION['name'] = $name;

			echo json_encode($messedg);		

		}else {
			$messedg['error'] = 1;
			$messedg['mess'] = 'Произошла ошибка при сохранение.';
		}
	}else {
		echo json_encode($messedg);	
	}
	exit();
}


// авторизация
if (isset($_POST['loginIn'])){
	$messedg = array(
		'error' => 0,
		'mess'  => 'Привет '
	);

	$login = FormChars (trim($_POST['loginIn']));
	$password = GenPass ($login,$_POST['password']);
	$answer = searchUser($login,$password,'',$db_name);

	if ($answer['conect']){
		$messedg['mess'] .= $answer['name'];

		setcookie("login", $login, time()+3600, "/");
		setcookie("password", $password, time()+3600, "/");
		$_SESSION['login'] = $login;
		$_SESSION['password'] = $password;
		$_SESSION['name'] = $answer['name'];
	}else {
		if (!$answer['login']){
			$messedg['error'] = 1;
			$messedg['mess'] = 'Пользователь с данным Логином отсутствует! ';
		}else {
			$messedg['error'] = 1;
			$messedg['mess'] = 'Неверно введет логин или пароль ! ';
		}
	}

	echo json_encode($messedg);
	exit();
}





// Добовление в бд записей
function addUserBd ($mass, $db_name){
	if (!file_exists($db_name)) {

    $xml = new XMLWriter();
    $xml->openURI($db_name);
    $xml->setIndent(true);
    
    $xml->startDocument('1.0', 'UTF-8');
	    $xml->startElement('users');
	    	$xml->startElement("user");
	    		foreach ($mass as $key => $value) {
	    			$xml->writeElement($key, $value);
	    		}
	    	$xml->endElement();
	    $xml->endElement();
    $xml->flush(true);
	}else {


		$str = file($db_name);
		$str = implode('',$str);
		$sXML = new SimpleXMLElement($str); // загрузка в XML
		$newchild = $sXML->addChild("user");
		//Добавление параметров записи
		foreach ($mass as $key => $value) {
			$newchild->addChild($key, $value);
		}
		$sXML->asXML($db_name);
	}

	return true;
}


// Поиск информации по бд 
function searchUser($login,$password,$email,$db_name){
	$answer = array(
		'email_b' => false,
		'login_b' => false,
		'pass_b' => false,
		'conect' => false,
		'name' => '',
	);

	if (!file_exists($db_name)){		
		return $answer;
	}

	$sxml = simplexml_load_file($db_name);
	
	foreach ($sxml->user as $user) {
		if ($user->login == $login){
			$answer['login_b'] = true;
		}
		if ($user->email == $email){
			$answer['email_b'] = true;
		}
		if ($user->password == $password){
			$answer['pass_b'] = true;
		}
		if (($user->login == $login) && ($user->password == $password)){
			$answer['conect'] = true;
			$answer['name'] = $user->name;
		}
	}
	return $answer;
}

// Заменяет пробелы на <br>, спец символы на html код и убирает лишнии пробелы
function FormChars ($p1){
  return nl2br(htmlspecialchars(trim($p1),ENT_QUOTES),false);
}

// Генерирование пароля
function GenPass ($p1,$p2){
  return md5('te213xt'.md5('123'.$p1.'312').md5('321'.$p2.'123'));
}


?>