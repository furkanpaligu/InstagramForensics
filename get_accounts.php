<?php 
require_once ('TableNames.php'); 
require_once ('Accounts.php');  
require_once ('DetectSuspectAccount.php'); 
require_once ('ExctractRelationships.php');  


$pData = $_GET["inst"];
$accounts = GetAllDoubleAttribute('name', 'instagramid', 'Accounts', $con);

if($pData == "relationships"){	
$applicants[] = array("a"=>$accounts, "b"=>"0");
echo json_encode($applicants); 
}
else{
	$applicants[] = array("a"=>$accounts, "b"=>"1");
     echo json_encode($applicants);          
}
$durum = 0;

?>
