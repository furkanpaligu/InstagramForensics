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

$pData = $_GET["inst"];

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


$NameIdPos = filteraccountswithFound($accounts, $AccountIds, $newarray);
$totallines = count($newarray);

for($t=0; $t<count($NameIdPos[0]); $t++){
	if($NameIdPos[1][$t] == $pData){
		$Find = $NameIdPos[2][$t];
	}
}

$FindFirst = $Find - 10;
$FindLast = $Find + 10;

if($Find < 10){$FindFirst = 0;}
if($totallines - $Find < 0){$FindLast = $totallines;}


	$applicants[] = array("a"=>$Find, "b"=>$FindFirst, "c"=>$FindLast);
    echo json_encode($applicants);      
$durum = 0;


?>
