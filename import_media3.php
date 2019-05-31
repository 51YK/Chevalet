<?php
    if(isset($_GET["target"]) && $_GET["target"] !== ""){
        $target = $_GET["target"];
    }
    else{
        header("Location: home.php");
    }
    $MIMETypes = array(
        'png' => 'image/png',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'mp4' => 'video/mp4'
    );
    try {
        // データベース接続
    $dsn = "mysql:データベース名;host=localhost;charset=utf8mb4";
    $db_user = 'ユーザー名';
    $db_pass = 'パスワード';
        $pdo = new PDO($dsn,$db_user,$db_pass
        );
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $sql = "SELECT * FROM boarding WHERE fname = :target;";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindValue(":target", $target, PDO::PARAM_STR);
        $stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        header("Content-Type: ".$MIMETypes[$row["extension"]]);
        echo ($row["raw_data"]);
    }
    catch (PDOException $e) {
        echo("<p>500 Inertnal Server Error</p>");
        exit($e->getMessage());
    }
?>