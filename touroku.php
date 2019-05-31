<?php
$sttouroku = "";
$stname = "";
$stemail = "";
$stpass = "";
$resultname = "";
$resultemail = "";
session_start();
/*if(isset($_SESSION['user']) !== ""){ホームがないのでコメントアウト
	header("Location: home.php");
}*/
//接続
$dsn = 'mysql:dbname=データベース;host=localhost';
$user = 'ユーザー名';
$spassword = 'パスワード';
$pdo = new PDO($dsn,$user,$spassword);
//登録ボタンが押されたとき
if (isset($_POST['touroku'])) {
	$name = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['pass'];
	if ($name !== '' && $email !== '' && $password !== ''){//重複チェック
			$stmt = 'SELECT name FROM account';
			$result = $pdo -> query($stmt);
			foreach($result as $row){
				if($name == $row['name']){
					$resultname = 1;
				}
				else{
				}
			}
			$stmts = 'SELECT email FROM account';
			$results = $pdo -> query($stmts);
			foreach($results as $rows){
				if($email == $rows['email']){
					$resultemail = 1;
				}
				else{
				}
			}
			if($resultname == "" and $resultemail == ""){
						$pass = hash("sha256",$password);
						$statement = $pdo -> prepare("INSERT INTO account (name,email,password) VALUES (:name,:email,:password)");
            					$statement -> bindParam(':name', $name, PDO::PARAM_STR);
            					$statement -> bindParam(':email', $email, PDO::PARAM_STR);
            					$statement -> bindParam(':password', $pass, PDO::PARAM_STR);
            					$statement -> execute();

						$sttouroku = '登録しました。';
			}
			else{
				if ($resultname !== ""){
					$stname = '<font color="red">ご希望のuser名は既に登録されています</font>';
				}
				if ($resultemail !== ""){
					$stemail = '<font color="red">ご希望のメールアドレスは既に登録されています</font>';
				}
			}
	}	
	else {
		if($name === ''){
			$stname = '<font color="red">名前を入力してください。</font>';
		}		
		if($email === ''){
			$stemail = '<font color="red">メールアドレスを入力してください。</font>';
		}
		if($password === ''){
			$stpass = '<font color= "red">パスワードを入力してください。</font>';
		}
	}
}



?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>新規会員登録</title>
	</head>
<style type="text/css">
    /* エラー用 */
    div.alert{
        background-color:#FFEFEF;
        margin:0 0 1em 0; padding:10px;
        color:#C25338;
        border:1px solid #D4440D;
        line-height:1.5;
        clear:both;
        background-repeat:no-repeat;
        background-position:5px 5px;
    }
 
    /* OK用 */
    div.accepted {
        background-color:#e2ffaa;
	margin:0 0 1em 0; padding:10px;
        color:#3A9805;
    	border:1px solid #76CC0B;
        line-height:1.5;
        clear:both;
        background-repeat:no-repeat;
        background-position:5px 5px;
}



</style>
<?php
if($stname !== "" || $stemail !== "" || $stpass !== ""){ ?>
<div class='alert'>
エラーが発生しました。
</div>
<?php } 
if($sttouroku !== ""){ ?>
<div class='accepted'>
登録しました。
</div>
<?php } ?>
	<body>
	<div style="padding: 8px 19px margin: 2em 0;border-top:solid 5px #5989cf;border-bottom:solid 5px #5989cf;border-button: solid 5px #5989cf;background-color: #cde4ff">
	<p>
<b>いらっしゃいませ！<br />
こちらは新規登録フォームです。すでに登録済みの方は<a href="login.php">ログインはこちら</a>からログインしてください。</b>
<center>

	<h1>新規会員登録</h1>
	<form action="touroku.php" method="post"/>
	<input pattern="^[0-9A-Za-z]+$" name="username" placeholder="user名" title="半角英数字でご入力ください。"><br />
	<?php if($stname !== ""){ echo $stname; } ?><br />
	<input type="email" name="email" placeholder="メールアドレス"><br />
	<?php if($stemail !== ""){ echo $stemail; } ?><br />
	<input type="password" name="pass" placeholder="password"><br />
	<?php if($stpass !== ""){ echo $stpass; } ?><br />
	<button type="submit" name="touroku" value="b1">新規登録</button><br />
	<a href="login.php"><font size="2">ログインはこちら</font></a>
</center>
</p>
</div>
</body>
</html>
