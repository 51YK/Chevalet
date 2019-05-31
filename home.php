<?php

session_start();
if(!isset($_SESSION["NAME"])){
header("Location:login.php");
exit();
}
date_default_timezone_set('Japan');
$sendm = NULL;
$sendt = NULL;
$nameeror = "";
$commenterror = "";
$numerror = "";
$delerror = "";
$editerror = "";
$enameerror = "";
$ecommenterror = "";
$d_mode = "";
$e_mode = "";
$e_dataN = "";
$e_dataC = ""; 
$number = "";
$stateOK = "";

try{
	$dsn = "mysql:dbname=データベース名;host=localhost;charset=utf8mb4";
	$db_user = 'ユーザー名';
	$db_pass = 'パスワード';
	$pdo = new PDO($dsn,$db_user,$db_pass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	if(isset($_POST['sendt'])){
		$sendt = 1;
	}
	elseif(isset($_POST['sendm'])){
		$sendm = 1;
	}
	elseif(isset($_POST['back']) or isset($_POST['tback'])){
		$sendm = NULL;
		$sendt = NULL;
	}
	if(isset($_POST['deletecheck']) or isset($_POST['edit'])){
		if($_POST['number']!==""){
			if(isset($_POST['deletecheck'])){
				$d_mode = 1;
				$e_mode = 0;
				$number = $_POST['number'];
			}
			elseif(isset($_POST['edit'])){
				$number = $_POST['number'];
				$statement = "SELECT name, comment,username FROM boarding WHERE id = $number;";
				$result = $pdo -> query($statement);
				foreach($result as $erow){
				}
				if($erow['username'] === $_SESSION["NAME"]){
					$d_mode = 0;
					$e_mode = 1;
					$e_dataN = $erow["name"];
					$e_dataC = $erow["comment"];
				}
				else{
					$editerror = "<font color=\"red\">編集ができるのは投稿した本人のみです</font>";
				}
			}
			elseif(isset($_POST['no_del']) or isset($_POST['no_edit'])){
				$d_mode = "";
				$e_mode = "";
				$number = "";
			}
		}
		else{
			$numerror = "<font color=\"red\">削除、編集した投稿の投稿番号を入力してください</font>";
		}
	}
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
		$dates = date("Y-m-d H:i:s");
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$username = $_SESSION["NAME"];
		$date = getdate();
		$fname = $_FILES["upfile"]["tmp_name"].$date["year"]. $data["mon"].$date["$mday"].$date["hours"] .$date["minutes"].$date["seconds"];
		$fname = hash("sha256", $fname);
		if($name === "" || $comment === ""){
			if($name === ""){
				$nameerror = "<font color=\"red\">タイトルを入力してください</font>" ;
			}
			if($comment === ""){
				$commenterror = "<font color=\"red\">コメントを入力してください。</font>";
			}
		}
		else{
			$sql = "INSERT INTO boarding (fname, name, date, comment, username, extension, raw_data) VALUES (:fname, :name, :date, :comment, :username, :extension, :raw_data);";
			$stmt = $pdo -> prepare($sql);
			$stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
			$stmt -> bindValue(":name",$name, PDO::PARAM_STR);
			$stmt -> bindValue(":date",$dates, PDO::PARAM_STR);
			$stmt -> bindValue(":comment",$comment, PDO::PARAM_STR);
			$stmt -> bindValue(":username",$username, PDO::PARAM_STR);
			$stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
			$stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
			$stmt -> execute();
			
		}
	}
	elseif(isset($_POST['tsend'])){
		$name = $_POST['tname'];
		$comment = $_POST['tcomment'];
		$dates = date("Y-m-d H:i:s");
		$username = $_SESSION["NAME"];
		$date = getdate();
		$fname = NULL;
		if($name === "" || $comment === ""){
			if($name === ""){
				$nameerror = "<font color=\"red\">タイトルを入力してください</font>" ;
			}
			if($comment === ""){
				$commenterror = "<font color=\"red\">コメントを入力してください。</font>";
			}
		}
		else{
			$extension = NULL;
			$raw_data = NULL;
			
			$sql = "INSERT INTO boarding (fname, name, date, comment, username, extension, raw_data) VALUES (:fname, :name, :date, :comment, :username, :extension, :raw_data);";
			$stmt = $pdo -> prepare($sql);
			$stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
			$stmt -> bindValue(":name",$name, PDO::PARAM_STR);
			$stmt -> bindValue(":date",$dates, PDO::PARAM_STR);
			$stmt -> bindValue(":comment",$comment, PDO::PARAM_STR);
			$stmt -> bindValue(":username",$username, PDO::PARAM_STR);
			$stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
			$stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
			$stmt -> execute();
		}
	}
	elseif(isset($_POST['delete'])){
		$number = $_POST['delno'];
		$username = $_SESSION["NAME"];
		$statement = "SELECT username FROM boarding WHERE id = $number";
		$results = $pdo -> query($statement);
		foreach ($results as $drow){
		}
		if ($username === $drow['username']){
			$delsql = "DELETE FROM boarding WHERE id = $number";
			$result = $pdo -> query($delsql);

			$stateOK = "<font color=\"#00ff7f\">投稿を削除しました</font>";
		}
		else{
			$delerror="<font color=\"red\">削除できるのは投稿した本人のみです</font>";
		}
	}
	elseif(isset($_POST['editgo'])){
		$number = $_POST['enumber'];
		$name = $_POST['ename'];
		$date = date("Y/m/d");
		$comment = $_POST['ecomment']."(".$date."編集)";
			if ($name !== '' && $comment !== ''){
				$statement = $pdo -> prepare("UPDATE boarding SET name=:name, comment=:comment WHERE id = $number");
				$statement -> bindParam(':name', $name, PDO::PARAM_STR);
				$statement -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$statement -> execute();

				$stateOK = "<font color=#00ff7f>投稿を編集しました。</font>";
			}
			else{
				if ($name === ''){
					$enameerror = "<font color=\"red\">名前を入力してください</font>";
				}
				if ($comment === ''){
					$ecommenterror = "<font color=\"red\">コメントを入力してください</font>";
				}
			}
	}
}catch(PDOException $e){
	echo("<p>500 Inertnal Server Error</p>");
	exit($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>Chevalet</title>
	<body>
		<div style="background-color:#A9F5E1;color:#FFFFFF;padding:0px 20px 0px;margin: 0px;">
	<div style="text-align:left;font-size:70px;padding:55px 0px 40px 0px">Chevalet</div>
	<div style="text-align:right;font-size:medium;">MY PAGE[<a href="MYPAGE.php"><?php echo $_SESSION["NAME"]; ?></a>]&nbsp;&nbsp;&nbsp; [<a href="logout.php">ログアウト</a>]</div>
</div>	
	</body>
	</head>
<body>
<div style="background-color:#EFF5FB;color:#EA5858;text-align:center;width:600px;border-radius: 10px;margin:0px auto">
	<p>Chevaletへようこそ！<br/>
	こちらはアドバイスを送り合う掲示板です。以下の発言はお控えください。<br/>
	◇ 下手、うまくないのみ等アドバイスにならないような発言<br/>
	◇ 他者の好みを貶す発言<br/>
	◇ その他暴言や荒し行為とみなされるような発言<br/>
ご協力よろしくお願いします。</p>
</div>
</body>
<body>
<div style="padding: 10px; margin-bottom: 10px; margin-right: 10px; border: 5px double #333333;width:50%;float:right">
<?php
	$sql = "SELECT * FROM boarding ORDER BY id DESC;";
	$stmt = $pdo -> prepare($sql);
	$stmt -> execute();
	while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){ 
		$target = $row["fname"]; 
		echo "<div style=\"padding: 10px; margin-bottom: 10px; border: 1px solid #333333; border-radius: 10px; background-color: #fffacd;width:97%;float:left;\">";
		echo ($row["id"]." ".":".$row["name"]."<br />".$row["username"]." ".$row["date"]."<br />");
		if($row["extension"] == mp4){
			echo ("<div style=\"float:left;\"><video src=\"import_media3.php?target=$target\" width= 250 height= auto controls align=\"left\"></video></div>");
		}
		elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
			echo ("<div style=\"float:left;\"><img src=\"import_media3.php?target=$target\" width= 250 height=auto align=\"left\"></div>");
		}
		elseif($row["extentipn"] == NULL){
		}
		echo "<div style=\"padding: 10px; margin:5px 5px; float:left;\">".nl2br($row["comment"])."</div>";
		echo "</div>";
} ?>
</div></body>
<div style="padding: 10px; margin-bottom: 10px; border: 5px double #333333;float:left; background-color: #fffacd;margin:0px 15px">
	<?php 
	if($commenterror !== ""){ 
		echo $commenterror."<br />";
	}
	if($nameerror !== ""){ 
		echo $nameerror; 
	}
	if(is_null($sendm) && is_null($sendt)): ?>
		<form action="home.php" method="post" />
			<center>
			<br />
			投稿する際はこちらから！<br /><br />
			<input type="submit" name="sendm" value="投稿する(ファイル付)"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="sendt" value="投稿する(コメントのみ)"/>
			<br />
			<br />
			<br />
			</center>
		</form>

	<?php 
	elseif(!is_null($sendt)): ?>
		<form action="home.php" method="post" />
			タイトル<br />
			<input type="text" name="tname" value="" />
			<br />
			コメント
			<br />
			<textarea name="tcomment" cols="50" rows="10"></textarea>
			<br />
			<input type="submit" name="tsend" value="送信" />&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="tback" value="戻る" />
			<br />
		</form>

	<?php 
	elseif(!is_null($sendm)): ?>
		<form action="home.php" enctype="multipart/form-data" method="post"/>
			<input type="file" name="upfile"><br />
			※画像はjpeg,png,gifに動画はmp4のみ対応しています<br/>
			タイトル<br />
			<input type="text" name="name" value=""/><br />
			コメント
			<br />
			<textarea name="comment" cols="50" rows="10"></textarea>
			<br />
			<button type="submit" name="send" value="b1">送信</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="back" value="戻る" />
		</form>
	<?php endif; ?>
</div>

<div style="padding: 10px; margin-bottom: 10px; border: 5px double #333333;float:left; background-color: #fffacd;margin:20px 15px">
	<center>
		<?php 
		if($numerror !== ""){ 
			echo $numerror."<br />";
		}
		if($delerror !== ""){
			echo $delerror."<br />";
		}
		if($editerror !== ""){
			echo $editerror."<br />";
		}
		if($ecommenterror !== ""){ 
			echo $ecommenterror."<br />";
		}
		if($enameerror !== ""){ 
			echo $enameerror."<br />"; 
		}
		if($stateOK !== ""){
			echo $stateOK."<br />" ;
		}
		?></center>
		<?php 
		if($d_mode === "" && $e_mode === ""):?>
			削除、編集はこちらから！<br />
			該当する投稿番号を記入してください<br />
			<form action="home.php" method="post">
				<center>
				<input pattern="^\d+$" name="number" value="" title="数字でご入力ください。">
				<br />
				<input type="submit" name="edit" value="編集">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="deletecheck" value="削除">
				</center>
			</form>
		<?php 
		elseif($d_mode ===1 && $e_mode ===0 ): ?>
			<form action="home.php" method="post">
				<?php echo $number ;?>を削除してもよろしいですか？<br />
				<input type="text" name="delno" value="<?php echo $number ;?>"hidden>
				<input type="submit" name="delete" value="はい">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="no_del" value="いいえ">
			</form>
		<?php
		elseif($d_mode===0 && $e_mode ===1): ?>
			<form action="home.php" method="post">
				タイトル
				<br />
				<input type="text" name="ename" value="<?php echo $e_dataN; ?>">
				<br />
				コメント
				<br />
				<textarea name="ecomment" cols="50" rows="10"><?php echo $e_dataC; ?></textarea>
				<br />
				<input type="text" name="enumber" value="<?php echo $number; ?>"hidden>
				<br />
				<input type="submit" name="editgo" value="編集する">
				<input type="submit" name="no_edit" value="戻る" >
			</form>
		<?php endif; ?>
</div>
</html>