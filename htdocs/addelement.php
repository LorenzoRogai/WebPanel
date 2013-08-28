<html>
<head>
<link href="./styles/style.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script>
var paramcount = 1;
function AddParameter()
{
paramcount++;
$("#parameter1").clone().val("").attr("id", "parameter" + paramcount).attr("name", "parameter" + paramcount).insertAfter("#parameter" + (paramcount - 1)).before('<br>');
}

function selectOnChange(selectfield){  
  var selectedvalue = selectfield.options[selectfield.selectedIndex].value;
  if (selectedvalue == 0)
  {
	document.getElementById("refreshdiv").style.display = "";
  }
  else
  {
	document.getElementById("refreshdiv").style.display = "none";
  }
  if (selectedvalue == 2)
  {
	document.getElementById("parametersdiv").style.display = "";
  }
  else
  {
	document.getElementById("parametersdiv").style.display = "none";
  }
}
</script>
</head>
<body> 
<div class="content" style="height: auto">
<div class="box">
<h2>Add new element</h2>
<?php
include "./includes/mysql.php";
session_start();
//check logged in or not!
if(!isset($_SESSION['login_user'])){
header('Location:index.php?pagename='.basename($_SERVER['PHP_SELF'], ".php"));
}

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

if (isset($_POST['etitle']) && isset($_POST['mn']) && isset($_POST['etype']))
{
$etitle = mysql_real_escape_string($_POST['etitle']);
$methodname = mysql_real_escape_string($_POST['mn']);
$etype = mysql_real_escape_string($_POST['etype']);
$parameters = "";
if ($etype == 2)
{
	foreach($_POST as $key=>$val){
		if (startsWith($key, "parameter"))
		{
			$parameters .= $val . ",";
		}
	}
}

$refreshrate = 0;
if ($etype == 0 && isset($_POST['refresh']))
{
$refreshrate = $_POST['refreshrate'];
}

mysql_query("insert into elements (title,methodname,type,refreshrate,parameters) values('" . $etitle . "', '" . $methodname . "', '" . $etype . "', '" . $refreshrate . "', '" . $parameters . "')");
echo "Element successfully added<br><a href='panel.php'>Return to panel</a>";
}
else
{
?>
<form name="new_element_form" method="post" action="">
<center><b>Element title</b><input type="text" name="etitle" id="etitle">
<b>Method name</b><input type="text" name="mn" id="mn">
<b>Type</b><br>
<select onchange="selectOnChange(this)" id="etype" name="etype"> 
  <option value="0">Show value</option>
  <option value="1">Action</option> 
  <option value="2">With parameters</option>
</select>
<br>
<div id="refreshdiv">
<input type="checkbox" name="refresh" id="refresh" value="refresh"/><b>Refresh every
<input type="number" value="1" style="width: 50px" id="refreshrate" name="refreshrate"/> seconds</b>
</div>
<div id="parametersdiv" style="display:none">
Parameters names:
<input type="text" name="parameter1" id="parameter1">
<br>
<input onclick="AddParameter()" type="button" name="addparameter" value="Add Parameter" id="addparameter">
</div>
</center>
<br>
<input type="submit" value="Add Element">
</form>
<?php } ?>
</body>
</html>