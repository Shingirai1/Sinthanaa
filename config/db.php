<?php
//mysql_set_charset('utf8');
$con = mysqli_connect("localhost","root","");
if($con){
   mysqli_select_db($con,"fellow_db") or die("database can not select");
   //mysql_set_charset('utf8');
}else{
   echo"connection not successful";
}   
?>