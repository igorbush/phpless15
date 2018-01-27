<?php
$servername = "localhost";
$dbname = "bushenev";
$username = "bushenev";
$password = "neto1393";

//$str = "'mysql:host=" . $host . ";dbname=" . $database . ";charset=utf8', '" . $user. "', '" . $password . "'" ;
//echo $str;
try {
$dbh = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
	echo 'Ошибка подключения к БД: ' .$e->getMessage();
	die;
}
?>