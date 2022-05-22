 <?php
require_once ('Factory/databaseoperations.php');
require_once ('Factory/MakeDBConnect.php');
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 
require_once ('Factory/Helper_String.php'); 
header("content-type: text/html; charset=UTF-8");  


 //runRelationshipDetection($con);

  
 function runRelationshipDetection($con){
	  
 $p = runRelationshipExtraction($con);
 
 $IdsofRelationships = getAccountIdTranslation($con, $p[0]);
 
 
 $body = clearForRelationshipts($p[0]);
 $body = divideById($IdsofRelationships, $body);
 $relationshipsdivided = DivideToLinesRaw($body);
 
 
 $accounts = GetAllDoubleAttribute('name', 'instagramid', 'Accounts', $con);
 $AccountIds = matchingIdswithLine($IdsofRelationships, $con, $relationshipsdivided);
 $NameIdPos = filteraccountswithFound($accounts, $AccountIds, $relationshipsdivided);

 
 print_line("The account IDs discovered in the relationships record:");
 print_numbered_together($NameIdPos[0], $NameIdPos[1], $NameIdPos[2]);
 
 // return Ids with their line positions
 $NameIdPos = filteraccountswithFound($accounts, $AccountIds, $relationshipsdivided);
 print_line("Positions of account Ids in the record:");
 print_numbered_together($NameIdPos[0], $NameIdPos[2]);

 
 // take the area in between 2 ids
 $AreaBetweenIds = 
 GetBetweenTwoIdsJS($NameIdPos[2][0], $NameIdPos[2][1] - 1, $NameIdPos[2][0], $relationshipsdivided);
 
 // check that inbetween area for keywords of relationships
 print_line("The relationship between suspect and ".$NameIdPos[0][0]." (".$NameIdPos[1][0].") : ");
 
 $determinedRelationship = determineRelationship($AreaBetweenIds);
 
 $looking = array("BlockedBySuspect", "FollowedBySuspect", "RestrictedBySuspect");
 print_numbered_together($looking, $determinedRelationship);
 
 print_line("Database updated.");
 $res = AddRelationshipEntry($NameIdPos[0][0] , $determinedRelationship[0], $determinedRelationship[1], $determinedRelationship[2], $con);

	  
  }
  
  
  // ********************************************************
  // Functions of that help relationship detection start from here 
  // ********************************************************
  
  function determineRelationship($AreaBetweenIds){
	  $blocked = 1;
	  $followed = 1;
	  $restricted = 1;
	  
	  $length = count($AreaBetweenIds);
	  for($i = 0; $i<$length; $i++){
		  if(preg_match('/(BLOCK)/', $AreaBetweenIds[$i], $matches) ){
			  if(preg_match('/(UNED)/', $AreaBetweenIds[$i], $matches) ){
				  $blocked = 0;
			  }
			  else{
				  $blocked = 1;
			  }
		  }
		  if(preg_match('/(FOLLOW)/', $AreaBetweenIds[$i], $matches) ){
			  if(preg_match('/(NOT)/', $AreaBetweenIds[$i], $matches) ){
				  $followed = 0;
			  }
			  else{
				  $followed = 1;
			  }
		  }
		  if(preg_match('/(RESTRICT)/', $AreaBetweenIds[$i], $matches) ){
			  if(preg_match('/(UN)/', $AreaBetweenIds[$i], $matches) ){
				  $restricted = 0;
			  }
			  else{
				  $restricted = 1;
			  }
		  }

	  }
	  
	  return array($blocked, $followed, $restricted);
	  
  }
  
  
  function runRelationshipExtraction($con){
  $files = glob("*.sqlite");
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  print_line('Starting relationship construction for the file: '.$sfile);
  
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  
  // get the obtained name for users.usernameToId
  $obtainedtablearray = GetSingleAttribute('relationships', 'obtainedname', 'name', 'Records', $con);
  $obtainedtable = $obtainedtablearray[0];
  
  $relationships = getBlobsforUsers($handle, $obtainedtable);
  return $relationships;
  } // end for going over each sqlite file
  } // end of function


   function divideById($AccountIds, $body){
	
	$length = count($AccountIds); 
	  for($i=0; $i<$length; $i++){
	 $body = str_replace($AccountIds[$i], '<br>'.$AccountIds[$i].'<br>', $body);
	  }
	 
     return $body;
  } // end of function


  
   function clearForRelationshipts($cleared){
	 
	 $cleared = str_replace('8follow', "<br>8follow<br>" , $cleared);
	 $cleared = str_replace('state', "<br>state<br>" , $cleared);
	 $cleared = str_replace('8stable', "<br>8stable<br>" , $cleared);
	 $cleared = str_replace('8restrict', "<br>8restrict<br>" , $cleared);
	 
	 
	 $cleared = str_replace('follow6', "<br>follow6<br>" , $cleared);
	 $cleared = str_replace('state6', "<br>state6<br>" , $cleared);
	 $cleared = str_replace('stable6', "<br>stable6<br>" , $cleared);
	 $cleared = str_replace('restrict6', "<br>restrict6<br>" , $cleared);
	 
	 $cleared = str_replace('8blocked', "<br>8blocked" , $cleared);
	 $cleared = str_replace('8BLOCK', "<br>8BLOCK" , $cleared);
	 $cleared = str_replace('8RESTRICT', "<br>8RESTRICT" , $cleared);
	 $cleared = str_replace('RESTRICTUN', "RESTRICTUN<br>" , $cleared);
	 $cleared = str_replace('(', "<br>(<br>" , $cleared);
	 

	 return $cleared;

  } // end of function
  
 
?> 