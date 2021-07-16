<?php
//kanai template engine


//-------------------------------
//Lectured by MR Nakata & MR Ochi 
//-------------------------------


//-------------------------------
//db connection
include('../config/db.php');
//include('../../db_common.php');
//$con = db_connect();
//-------------------------------

//-------------------------------
//setting charset
mysql_set_charset('utf8');
//------------------------------

 
//-------------------------------
//kanai engine parameters
include_once '../cmn/init.php';
//-------------------------------


//-----------------------------------------------------------------------------------------------
//Page Title
$title = "web";
//-----------------------------------------------------------------------------------------------

//leave a message insert db
if(isset($_POST['submit'])){
	$Name=trim($_POST['Name']);
	$Email=trim($_POST['Email']);
	$Message=trim($_POST['Comment']);
	$success="success";
	
	$sql=mysql_query("INSERT INTO comment (Name, Email, Comment	)values('$Name','$Email','$Message')");
	if($sql){
		
		echo $success;
	}
}


//--------------------------------------------------------------------------------------------------

// Set variable for showing after process htmlspecialchars
foreach(array('title') as $name) {
  convert_string($$name, 'htmlspecialchars', TRUE);
  $Page[$name] = $$name;
}
//--------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------
//to show page
$tpl = new HtmlTemplate();
$code = @$tpl->convert("tpl/index.tpl.html");
eval($code);
?>