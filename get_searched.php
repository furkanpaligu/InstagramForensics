<?php 
require_once ('TableNames.php'); 
require_once ('Accounts.php');  
require_once ('DetectSuspectAccount.php'); 
require_once ('ExctractRelationships.php');  

require_once ('Factory/databaseoperations.php'); 
require_once ('Factory/MakeDBConnect.php'); 
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 
require_once ('Factory/Helper_String.php');

$linestart = $_GET["linestart"];
$lineend = $_GET["lineend"];
$exactline = $_GET["exactline"];
$searchword = $_GET["searchword"];


// ******************************************************************************
$recordnames = GetAllSingleAttribute('name', 'Records', $con);
$accounts = GetAllDoubleAttribute('name', 'instagramid', 'Accounts', $con);


$files = glob("*.sqlite");
$handle = sqlite_open($files[0],'SQLITE3_OPEN_READWRITE');
$obtainedtablearray = GetSingleAttribute('users.users', 'obtainedname', 'name', 'Records', $con);
$obtainedtable = $obtainedtablearray[0];

$userdetails = getBlobsforUsers($handle, $obtainedtable);
$newarray = DivideToLines($userdetails[0]);


$Idsofusers = getAccountIdTranslationT($con, $userdetails[0]);
$AccountIds = matchingIdswithLine($Idsofusers, $con, $newarray);
// ******************************************************************************

$inbetween = GetBetweenTwoIdsJS($linestart, $lineend, $exactline, $newarray);
$Find = matchPhrasewithLine($searchword."", $inbetween);

$ReturnStr = array();
if(strpos($Find[0][0], "0 Instance Found")!== false){
	$ReturnStr = "<br>0 Instance of the keyword: <strong>".$searchword."</strong> was found.<br>";
}
else{
	$ReturnStr = "<br>Information Order: Found Instance Number | Keyword | Line Position";
	$ReturnStr .= give_numbered_together($Find[0], $Find[1]);
}

$inbetween = str_replace($searchword, "<strong>".$searchword."</strong>", $inbetween);

	$applicants[] = array("a"=>$ReturnStr, "b"=>$inbetween);
    echo json_encode($applicants);      

?>
