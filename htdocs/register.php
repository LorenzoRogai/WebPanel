<html>
<head>
<link href="./styles/style.css" rel="stylesheet" type="text/css">
</head>
<body> 
<div class="content" style="height: auto">
<div class="box">
<h2>Register</h2>
<?php
include "./includes/mysql.php";

session_start();

if(isset($_POST['uname']) && isset($_POST['pass']) && $_REQUEST['ch'] == "register"){
$username = mysql_real_escape_string($_POST['uname']);
$password = md5(mysql_real_escape_string($_POST['pass']));
mysql_query("insert into admins values('" . $username . "','" . $password . "')");
echo "Your account has been created<br><br><a href='index.php'>Return to login</a>";

}
else{
?>
<form name="login_form" method="post" action="">
<center><b>Username</b></center><input type="text" name="uname" id="uname">
<center><b>Password</b></center><input type="password" name="pass" id="pass">
<br>
<input type="submit" value="Register">
<input type="hidden" name="ch" value="register">
</form>
<?php } ?>
</div>
</div>
</body>
</html>