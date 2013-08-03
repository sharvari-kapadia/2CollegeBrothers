<?php 
if(!$_POST['mes']) 
{ 
?> 
<form method="post"> 
<textarea name="mes" cols=10 rows=10></textarea> 
<input type="submit" value="send"> 
</form> 
<?php 
} 
else 
{ 
 $_POST['mes']= str_replace('\r\n','****',$_POST['mes']); 
 echo $_POST['mes']; 
} 
?>