 <?php
require_once ('Factory/databaseoperations.php'); 
require_once ('Factory/MakeDBConnect.php'); 
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 


//runSuspectAccountDetection($con);



function runSuspectAccountDetection($con){
  $files = glob("*.sqlite");
  
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  print_line('Starting suspect account detection for: '.$sfile);
  
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  // get obtained ids of all accounts
  $obtainedidarray = GetAllSingleAttribute('instagramid', 'Accounts', $con);
  
  // get the obtained name for users.usernameToId
  $obtainedtablearray = GetSingleAttribute('users.viewerId', 'obtainedname', 'name', 'Records', $con);
  $obtainedtable = $obtainedtablearray[0];
  
  $SuspectId = getBlobs($handle, $obtainedtable);
  
  $clearedSuspectIds = clearSuspectId($SuspectId);
  $clearedSuspectId = $clearedSuspectIds[0][0];
 
  print_line("Detected Suspect Id: ".$clearedSuspectId);
  $matchingTableIds = array();
  foreach($obtainedidarray as $obtainedid){
	  if(preg_match('/('.$obtainedid.')/', $clearedSuspectId, $matches) ){
			  array_push($matchingTableIds, $matches[0]);
		  }
	  
  }
  // ++ if there is only one return. Otherwise error.
  print_line("It was found as : ".$matchingTableIds[0]);
  
  print_line("Updating suspect account entry");
  
  SetSingleAttribute($clearedSuspectId, $matchingTableIds[0], 'ss',  'instagramid', 'instagramid', 'Accounts', $con);
  
  SetSingleAttribute(1, $matchingTableIds[0], 'is',  'issuspect', 'instagramid', 'Accounts', $con);
  SetSingleAttribute('suspectaccount', $matchingTableIds[0], 'ss',  'name', 'instagramid', 'Accounts', $con);
  
  $suspectnames = GetSingleAttribute($clearedSuspectId, 'name', 'instagramid', 'Accounts', $con);
  print_line("Suspect account is: ". $clearedSuspectId);

  } // end for going over each sqlite file
  
  }
 
  // ********************************************************
  // Functions of that help runSuspectAccountDetection start from here 
  // ********************************************************
 
  function clearSuspectId($SuspectId){
	 
	  $ids = array();
	   $parts = explode("<br>", $SuspectId[0]);
		  if($parts){
			  
	  $length = count($parts); 
	  for($i=0; $i<$length; $i++){
		  
	  	if(preg_match_all('/[0-9]{5,}/', $parts[$i], $matches) ){
			 foreach($matches[0] as $match)
			 array_push($ids, $match);
		  }
		  
	  } // end of for
	 
	  
	 return array($ids);
	  
	  
		  } // end of if parts
	  
  } // end of function


 
 
?> 