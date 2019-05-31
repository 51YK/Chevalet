<?php
$dsn = 'mysql:dbname=データベース;host=localhost';
$user = 'ユーザー';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

$sql= "CREATE TABLE account"
. "("
. "id INT(11) AUTO_INCREMENT PRIMARY KEY,"
. "name varchar(20) NOT NULL,"
. "email varchar(256) NOT NULL,"
. "password varchar(256) NOT NULL,"
. "UNIQUE (email)"
. ");";
$stmt = $pdo->query($sql);
?>
