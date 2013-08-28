<?php
class Element { 
  private $id;
  private $title;  
  private $methodname;  
  private $type;
  private $refreshrate;
  private $parameters;
  
  public function Element($id, $title, $methodname, $type, $refreshrate, $parameters) {
    $this->id = $id;
    $this->title = $title;
    $this->methodname = $methodname;
    $this->type = $type;
    $this->refreshrate = $refreshrate;
    $this->parameters = $parameters;
  } 
  
  public function getHtml() {
    echo '<div class="box" style="margin:8px; display:inline-block;"><div name="title"><h3 style="margin: 0; float:left;">'.$this->title .'</h3>';
    echo '<a href="deleteelement.php?id='.$this->id.'"><img style="margin-left: 2px; float: right;" src="./images/delete.png"></img></a></div><br><br>';
    if($this->type == 0)
    {
      echo '<div id="' . $this->methodname . 'result">';
      echo objectToArray(Invoke($this->methodname))[$this->methodname . "result"];
      echo '</div>';
    }
    if ($this->type == 1)
    {
      echo '<a href="?invoke=' . $this->methodname . '"><button type="button">Invoke</button></a>';
    }
    if ($this->refreshrate > 0)
    {
      echo '<script type="text/javascript">';    
      echo 'function ' . $this->methodname . '_read()
      {      
        var ' . $this->methodname .'xmlhttp = AJAX();
        
        ' . $this->methodname .'xmlhttp.onreadystatechange=function(){
        if(' . $this->methodname .'xmlhttp.readyState==4){
        var obj = jQuery.parseJSON(' . $this->methodname .'xmlhttp.responseText);    
        document.getElementById("' . $this->methodname . 'result").innerHTML= obj["' . strtolower($this->methodname) .'result"];        
        setTimeout("' . $this->methodname .'_read()",' . $this->refreshrate * 1000 . ');
        }
        }
        ' . $this->methodname .'xmlhttp.open("GET","panel.php?getmethod=' . $this->methodname .'",true);
        ' . $this->methodname .'xmlhttp.send(null);

      }';  
      echo 'addLoadEvent(' . $this->methodname . '_read())';
      echo '</script>';          
    }
    if ($this->type == 2 && $this->parameters != "")
    {
      $parameter = explode(',', $this->parameters);
      
      foreach($parameter as $key) {
        if ($key != "")
        {
          echo $key . "<br>";
          echo '<input type="text" name="' . $key . '" id="' . $key . '">';
        }
      }  
      echo "<button onClick='InvokeWithParameters(\"" . $this->methodname . "\",\"" . $this->parameters . "\")' type='button'>Invoke</button>";
      echo '<div id="' . $this->methodname . 'result">';
      
      echo '</div>';
    }
    echo '</div>';
  }
}
?>