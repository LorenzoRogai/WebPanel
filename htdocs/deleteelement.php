<?php
include "./includes/mysql.php";
session_start();
//check logged in or not!
if(!isset($_SESSION['login_user'])){
header('Location:index.php?pagename='.basename($_SERVER['PHP_SELF'], ".php"));
}
if (isset($_GET['id']))
{
$id = mysql_real_escape_string($_GET['id']);
mysql_query("delete from elements where id = '" . $id . "'");
?>
<html>
<head>
<link href="./styles/style.css" rel="stylesheet" type="text/css">
</head>
<body> 
<div class="content" style="height: auto">
<div class="box">
<h2>Element successfully deleted</h2>
<a href='panel.php'>Return to panel</a>
</div>
</div>
</body>
</html>
<?php
}
?>