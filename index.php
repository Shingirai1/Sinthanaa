<?php
//kanai template engine


//-------------------------------
//Lectured by MR Nakata & MR Ochi 
//-------------------------------


//-------------------------------
//db connection
include('config/db.php');
//include('../../db_common.php');
//$con = db_connect();
//-------------------------------

//-------------------------------
//setting charset
mysqli_set_charset($con, 'utf8');
//------------------------------

 
//-------------------------------
//kanai engine parameters
/*include_once 'cmn/init.php';*/
//-------------------------------

//-------------------------------
//smarty tpl engine
require_once 'smarty-3.1.33/libs/Smarty.class.php';
//-------------------------------

//-----------------------------------------------------------------------------------------------
//Page Title
$title = "Sinthana";
//-----------------------------------------------------------------------------------------------

//content query
$content=array();

$query=mysqli_query($con,"SELECT * FROM content");
while($row=mysqli_fetch_array($query)){
	$tmp=array("icon"=>$row["Icon"],"heading"=>$row["Heading"],"paragraph"=>$row["paragraph"],"element"=>$row["Element_name"]);
	$content[]=$tmp;
}

//leave a message insert db
if(isset($_POST['submit'])){
	$Name=trim($_POST['Name']);
	$Email=trim($_POST['Email']);
	$Message=trim($_POST['Comment']);
	echo $Message;
	$success="success";
	
	/*$sql=mysql_query("INSERT INTO comment (Name, Email, Comment	)values('$Name','$Email','$Message')");
	if($sql){
		
		echo $success;
	}*/
}


//--------------------------------------------------------------------------------------------------
// Set variable for showing after process htmlspecialchars
/*foreach(array('title','$content') as $name) {
  convert_string($$name, 'htmlspecialchars', TRUE);
  $Page[$name] = $$name;
}*/
//--------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------
//to show page
/*$tpl = new HtmlTemplate();
$code = @$tpl->convert("tpl/index.tpl.html");
eval($code);*/

$smarty = new Smarty();
$smarty->template_dir = 'tpl';
$smarty->compile_dir = 'tpm';

//asigning valiable
$smarty->assign('title', $title);
$smarty->assign('content', $content);


//display page
$smarty->display('index.tpl');


?>