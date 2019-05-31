<?php
try {
    // データベース接続
    $dsn = "mysql:データベース名;host=localhost;charset=utf8mb4";
    $db_user = 'ユーザー名';
    $db_pass = 'パスワード';
    $pdo = new PDO($dsn,$db_user,$db_pass
    );
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // テーブル作成のSQLを作成
    $sql = 'CREATE TABLE boarding (
        id INT(4) AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(16),
	name VARCHAR(16),
	date DATETIME,
	comment TEXT,
	username varchar(20),
        extension VARCHAR(5),
        raw_data LONGBLOB
    )';

    // SQLを実行
    $result = $pdo -> query($sql);

} catch (PDOException $e) {

    // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}