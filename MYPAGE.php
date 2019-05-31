<?php
session_start();
if(!isset($_SESSION["NAME"])){
header("Location:login.php");
exit();
}
date_default_timezone_set('Japan');
$sendm = NULL;
$sendt = NULL;
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
	<div style="text-align:right;font-size:medium;">[<a href="home.php">TOP PAGE</a>]&nbsp;&nbsp;&nbsp;&nbsp;[<a href="logout.php">ログアウト</a>]</div>
</div>	
	</body>
	</head>
<div style="padding: 10px; margin-bottom: 10px; margin-top: 20px; border: 5px double #333333;width:50%;float:right">
<?php
	$sql = "SELECT * FROM boarding ORDER BY id DESC;";
	$stmt = $pdo -> prepare($sql);
	$result = $stmt -> execute();
	while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){ 
		if($row["username"] === $_SESSION["NAME"]){
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
		}
		else{
		}
	}
?>
</div></body>
<div style="padding: 10px; margin-bottom: 10px; border: 5px double #333333;float:left; background-color: #fffacd;margin:20px 15px; width:30%">
ユーザー情報<br />
<?php
	$username = $_SESSION["NAME"];
	$sql = 'SELECT * FROM account WHERE name = :username';
	$stmt = $pdo -> prepare($sql);
	$stmt -> bindValue(":username", $username, PDO::PARAM_STR);
	$stmt -> execute();
	if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo "user名:".$row["name"]."<br />"."email:".$row["email"]."<br />";
	}
?>

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
			<form action="MYPAGE.php" method="post">
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
			<form action="MYPAGE.php" method="post">
				<?php echo $number ;?>を削除してもよろしいですか？<br />
				<input type="text" name="delno" value="<?php echo $number ;?>"hidden>
				<input type="submit" name="delete" value="はい">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="no_del" value="いいえ">
			</form>
		<?php
		elseif($d_mode===0 && $e_mode ===1): ?>
			<form action="MYPAGE.php" method="post">
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