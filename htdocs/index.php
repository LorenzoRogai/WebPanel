<?php
include "./includes/mysql.php";
//simple PHP login script using Session
//start the session * this is important
session_start();
 
//login script
if(isset($_REQUEST['ch']) && $_REQUEST['ch'] == 'login'){
 
//give your login credentials here
$username = mysql_real_escape_string($_REQUEST['uname']);
$password = md5(mysql_real_escape_string($_REQUEST['pass']));
$query = mysql_query("select * from admins where username = '" . $username . "' and password = '" . $password . "'");
if(mysql_num_rows($query))
$_SESSION['login_user'] = 1;
else
$_SESSION['login_msg'] = 1;
} 
//get the page name where to redirect
if(isset($_REQUEST['pagename']))
$pagename = $_REQUEST['pagename'];
 
//logout script
if(isset($_REQUEST['ch']) && $_REQUEST['ch'] == 'logout'){
unset($_SESSION['login_user']);
header('Location:index.php');
}
if(isset($_SESSION['login_user'])){
if(isset($_REQUEST['pagename']))
header('Location:'.$pagename.'.php');
else
header('Location:panel.php');
}else{
?>
<html>
<head>
<link href="./styles/style.css" rel="stylesheet" type="text/css">
</head>
<body> 
<div class="content" style="height: auto">
<div class="box">
<h2>Login</h2>
<form name="login_form" method="post" action="">
<center><b>Username</b></center><input type="text" name="uname" id="uname">
<center><b>Password</b></center><input type="password" name="pass" id="pass">
<?php
//display the error msg if the login credentials are wrong!
if(isset($_SESSION['login_msg'])){
echo '<center><b><font color="#FF3333">Wrong username and password!</font></b></center>';
unset($_SESSION['login_msg']);
}
?>
<br>
<input type="submit" value="Login">
<input type="hidden" name="ch" value="login">
</form>
</div>
</div>
</body>
</html>
<?php } ?>