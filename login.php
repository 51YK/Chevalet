<?php
session_start();
    $dsn = "mysql:dbname=データベース名;host=localhost;charset=utf8mb4";
    $db_user = 'ユーザー名';
    $db_pass = 'パスワード';

$erroremail = "";
$errorpass = "";

if (isset($_POST["login"])) {
	$email = $_POST['email'];
	$pass = $_POST['logpass'] ;
		if ($email == "" && $pass == ""){
			if ($email == ""){
				$erroremail = '<font color="red">登録したメールアドレスを入力してください</font>';
			}
			if ($pass == ""){
				$errorpass = '<font color="red">パスワードを入力してください</font>';
			}
		}
		else {
			try{
				$pdo = new PDO($dsn,$db_user,$db_pass);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

				$sql = 'SELECT * FROM account WHERE email = :email';
				$stmt = $pdo -> prepare($sql);
				$stmt -> bindValue(":email", $email, PDO::PARAM_STR);
				$stmt -> execute();
				$password = hash("sha256",$pass);
				if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					
					if ($row['password'] == $password){
						session_regenerate_id(true);

						$name = $row['name'];
						$_SESSION["NAME"] = $name;
						header("Location: home.php");
						exit();
					}
					else{
						$errorpass = '<font color="red">パスワードが違います</font>';
					}
				}
			}catch (PDOException $e) {

  				  // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
    					header('Content-Type: text/plain; charset=UTF-8', true, 500);
   					exit($e->getMessage());
						}
		}
}
?>
<!DOCTYPE html>
<html lang="ja">

	<head>
		<meta charset="UTF-8">
		<title>Chevalet</title>
	</head>
	<body>
		<div style="height:582px; width:850px; padding: 8px 19px margin: 2em 0;border-top:solid 5px #5989cf;border-bottom:solid 5px #5989cf;border-button: solid 5px #5989cf;background-color: #cde4ff">
			<p>
				<font type=""><b>いらっしゃいませ！<br />
					初めての方は<a href="http://tt-207.99sv-coco.com/mission_6-1/touroku.php">新規登録はこちら</a>を押してアカウント登録を行ってください。<br />
					アカウントをお持ちの方はIDとパスワードを入力後ログインボタンを押してください。
				</b></font>
	<br />
	<br />
	<br />
	<br />
	<br />
<img src="http://tt-207.99sv-coco.com/mission_6-1/import_media2.php?target=ffcddd4959d58fe9" alt="サンプル" align="left" height="292px" width="292px">
<img src="http://tt-207.99sv-coco.com/mission_6-1/import_media2.php?target=817f59a80f4f954b" alt="サンプル" align="right" height="292px" width="292px">
	<center>
	<h1>ログインフォーム</h1>
<form action="login.php" method="post"/>
email:
<input type="email" name="email" placeholder="メールアドレス" title="半角英数字でご入力ください。"><br />
<?php if($erroremail !==  ""){ echo $erroremail; }?>
<br />
pass :
<input type="password" name="logpass" placeholder="password"/><br />
<?php if($errorpass !== ""){ echo $errorpass ; }?>
<br />
<button type="submit" name="login" value="b1">ログイン</button>
<br />
<a href="http://tt-207.99sv-coco.com/mission_6-1/touroku.php"><font size="2">新規登録はこちら</font></a>
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	</center>
	</p>
		</div>
	</body>
</html>