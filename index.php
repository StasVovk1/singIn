<?php 
	session_start();
	include 'main.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Тестовое задание</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<form action="" method="POST" id="helloUser" class="hide">
	
	<input type="hidden" name="exit" value = "1">
	<button type="submit" class="btn btn-info">Выход</button>
	<h1 class="text-center messedg"></h1>
</form>


<div class="container">
	<div class="row">
		<div class="col-md-5">
			<h2 class="text-center">Авторизация</h2>
			<form method="POST" id = "loginIn" action="main.php">
				<p class = "alert alert-danger" id = "error-messedg"></p>
				<div class="form-group">
					<label for="exampleInputEmail1">Логин</label>
					<input type="text" class="form-control" id="exampleInputEmail1"  name = "loginIn" placeholder="Введите логин" required="required">
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Пароль</label>
					<input type="password" class="form-control" id="exampleInputPassword1" name = "password" placeholder="Пароль" required="required"></div>
					<button type="button" class="btn btn-primary">Войти</button>
			</form>
		</div>
		<div class="col-md-5 col-md-offset-1">
			<h2 class="text-center">Регистрация</h2>			
			<form method="POST" id = "registracia" action="">
				<p class = "alert alert-danger" id = "error-messedg"></p>
				<div class="form-group">
					<label for="login">Логин</label>
					<input type="text" class="form-control" id="login" name = "login" placeholder="Логин" required="required">
				</div>
				<div class="form-group">
					<label for="password1">Пароль</label>
					<input type="password" class="form-control" id="password1" name = "firstPassword" placeholder="Пароль" required="required">
				</div>
				<div class="form-group">
					<label for="password2">Повторите пароль</label>
					<input type="password" class="form-control" id="password2" name = "secondPassword" placeholder="Повторите пароль" required="required">
				</div>
				<div class="form-group">
					<label for="email">Email адрес</label>
					<input type="email" class="form-control" id="email" name = "email" placeholder="Email" required="required">
				</div>
				<div class="form-group">
					<label for="name">Имя</label>
					<input type="text" class="form-control" id="name" name = "name" placeholder="Имя" required="required">
				</div>

				<button type="button" class="btn btn-primary">Зарегистрироваться</button>
			</form>
		</div>
	</div>
</div>

	<script src="js/jquery-1.12.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/main.js"></script>
</body>
</html>