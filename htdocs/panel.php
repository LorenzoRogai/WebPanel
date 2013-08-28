<?php
include "./includes/mysql.php";
include "./includes/element.php";
include "./includes/webServiceConnector.php";
session_start();
//check logged in or not!
if(!isset($_SESSION['login_user'])){
header('Location:index.php?pagename='.basename($_SERVER['PHP_SELF'], ".php"));
}
if (isset($_GET['invoke']))
{
Invoke($_GET['invoke']);
echo '<script type="text/javascript">
     alert("Method name: ' . $_GET['invoke'] . ' successfully invoked");   
     </script>';
}
if (isset($_GET['getmethod']))
{
echo json_encode(objectToArray(Invoke($_GET['getmethod'])));
exit();
}
if (isset($_GET['methodname']) && isset($_GET['parameters']))
{
echo json_encode(objectToArray(InvokeWithParameters($_GET['methodname'], $_GET['parameters'])));
exit();
}
?>
<html>
<head>
<link href="./styles/style.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript">
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}
function AJAX(){
try{
xmlHttp=new XMLHttpRequest(); // Firefox, Opera 8.0+, Safari
return xmlHttp;
}
catch (e){
try{
xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
return xmlHttp;
}
catch (e){
try{
xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
return xmlHttp;
}
catch (e){
alert("Your browser does not support AJAX.");
return false;
}
}
}
}
function InvokeWithParameters(methodname, parametersfields)
{
	var xmlhttp = AJAX();
				
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4){
			var obj = jQuery.parseJSON(xmlhttp.responseText);   		
			if (obj[methodname.toLowerCase() + "result"] == null)
				document.getElementById(methodname + "result").innerHTML = "Not found";
			else
				document.getElementById(methodname + "result").innerHTML = obj[methodname + "result"];			
		}
	}
	var split = parametersfields.split(',');
	var parameters = "";
	for (var i = 0; i < split.length - 1; i++) {
		parameters += split[i] + "|" + document.getElementById(split[i]).value + ",";
	}	
	
	xmlhttp.open("GET","?methodname=" + methodname + "&parameters=" + parameters,true);
	xmlhttp.send(null);	
}
</script>
</head>
<body> 
<div class="content" style="height: auto">
<div style="float:left"><a href="addelement.php">Add new element</a></div><div style="float:right"><a href="index.php?ch=logout">Logout</a></div>
<br>
<?php
$query = mysql_query("select * from elements");  
echo "Current elements: " . mysql_num_rows($query) . "<br>";
while ($result = mysql_fetch_array($query)) {  
$elem = new Element($result['id'], $result['title'], $result['methodname'], $result['type'], $result['refreshrate'], $result['parameters']);
echo $elem->getHtml();
} 
?> 
</div>
</body>
</html>
