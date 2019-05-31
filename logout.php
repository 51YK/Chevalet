<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Chevalet</title>
<div style="padding: 8px 19px margin: 2em 0;border-top:solid 5px #5989cf;border-bottom:solid 5px #5989cf;border-button: solid 5px #5989cf;background-color: #cde4ff">
		<center>
<?php

// セッション開始
session_start();

if(!isset($_SESSION["NAME"])){
header("Location:login.php");
exit();
}

if(isset($_POST['YES'])){
	// セッション変数を全て削除
	$_SESSION = array();
	// セッションクッキーを削除
	if (isset($_COOKIE["PHPSESSID"])) {
		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	// セッションの登録データを削除
	session_destroy();
	print "ログアウトしました";
echo "<META http-equiv=\"Refresh\" content=\"3;URL=login.php\">
</HEAD>
<BODY>
<P>約3秒後にジャンプします</P>
</BODY>";
}
elseif(isset($_POST['NO'])){
	header("Location:home.php");
	exit();
}

?>
</head>
		<div style="padding: 10px; margin-bottom: 10px; border: 1px solid #333333; border-radius: 10px;margin:20px;width: 300px">
			<br />
			<br />
			<br />
			ログアウトしてもよろしいですか？
			<br />
			<br />
			<br />
			<form action="logout.php" method="post">
				<input type="submit" name="YES" value="はい">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="NO" value="いいえ">
			</form>
			<br />
			<br />
			<br />
		</div>
	</center>
</div>
</html>