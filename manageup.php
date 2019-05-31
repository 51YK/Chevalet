<?php
    try{
        // データベース接続
    $dsn = "mysql:dbname=データベース名;host=localhost;charset=utf8mb4";
    $db_user = 'ユーザー名';
    $db_pass = 'パスワード';
        $pdo = new PDO($dsn,$db_user,$db_pass/*,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]*/
        );
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){
		switch ($_FILES['upfile']['error']) {
			case UPLOAD_ERR_OK;
				break;
			case UPLOAD_ERR_NO_FILE;
				throw new RuntimeException('ファイルが選択されていません', 400);
			case UPLOAD_ERR_INI_SIZE:
				throw new RuntimeException('ファイルサイズが大きすぎます', 400);
			default:
				throw new RuntimeException('その他のエラーが発生しました', 500);
		}

		$raw_data = file_get_contents($_FILES['upfile']['tmp_name']);
	
		$tmp = pathinfo($_FILES["upfile"]["name"]);
		$extension = $tmp["extension"];
		if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
			$extension = "jpeg";
		}
		elseif($extension === "png" || $extension === "PNG"){
			$extension = "png";
		}
		elseif($extension === "gif" || $extension === "GIF"){
			$extension === "gif";
		}
		elseif($extension === "mp4" || $extension === "MP4"){
			$extension = "mp4";
		}
		else{
			echo "非対応のファイルです. <br />";
			echo ("<a href=\"edit.php\">戻る</a><br />");
			exit(1);
		}

		$date = getdate();
		$fname = $_FILES["upfile"]["tmp_name"].$date["year"]. $data["mon"].$date["$mday"].$date["hours"] .$date["minutes"].$date["seconds"];
		$name = $_POST['name'];
		$fname = hash("sha256", $fname);

		$sql = "INSERT INTO mediamanage (fname, name, extension, raw_data) VALUES (:fname, :name, :extension, :raw_data);";
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
		$stmt -> bindValue(":name",$name, PDO::PARAM_STR);
		$stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
		$stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
		$stmt -> execute();
	}
}catch(PDOException $e){
	echo("<p>500 Inertnal Server Error</p>");
	exit($e->getMessage());
}
?>

<!DOCTYPE html>
	<head>
		<meta charset="UTF-8">
		<title>管理用画像UP</title>
	</head>
	<body>
		<form action="manageup.php" enctype="multipart/form-data" method="post">
			<label>管理用動画像UP</label>
			<input type="file" name="upfile">
			<br />
			<input type="text" name="name">
			<br />
			※画像＝＞jpeg png gof 動画＝＞mp4<br />
			<input type="submit" value="アップロード">
		</form>
<?php
	$sql = "SELECT * FROM mediamanage ORDER BY id;";
	$stmt = $pdo -> prepare($sql);
	$stmt -> execute();
	while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
		echo ($row["id"]."<br />");
		$target = $row["fname"];
		echo $row["name"]."<br />";
		if($row["extension"] == mp4){
			echo ("<video src=\"import_media2.php?target=$target\" width=\"400\" height=\"300\" controls></video>");
		}
		elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
			echo ("<img src=\"import_media2.php?target=$target\" width\"400\" height=\"300\">");
		}
		echo ("<br /><br />");
	}
?>
	</body>
</html>