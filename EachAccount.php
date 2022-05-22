 <?php
require_once ('Factory/databaseoperations.php');
require_once ('Factory/MakeDBConnect.php');
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 


// eachAccountDetection($con);
 

  
 function eachAccountDetection($con){
  $files = glob("*.sqlite");
  
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  print_line('Starting account detail detection for: '.$sfile);
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  // get the obtained name for users.usernameToId
  $obtainedtablearray = GetSingleAttribute('users.users', 'obtainedname', 'name', 'Records', $con);
  $obtainedtable = $obtainedtablearray[0];
  print_line($obtainedtable);
  
  print_divider();
  $userdetails = getBlobsforUsers($handle, $obtainedtable);
 
  $newarray = DivideToLines($userdetails[0]);
  
  $Idsofusers = getAccountIdTranslationT($con, $userdetails[0]);
  
  $AccountIds = matchingIdswithLine($Idsofusers, $con, $newarray);
  print_line("The following user ids are present in this record: ");
  print_numbered_together_n($AccountIds[0], $AccountIds[1], $AccountIds[2]);
  //print_r($AccountIds[2]);
  
   
   
   $Find = matchPhrasewithLine('has', $newarray);
   print_numbered_together($Find[0], $Find[1]);
   
   
   $inbetween = GetBetweenTwoIds(0, $Find[1][0], $newarray);
  
   print_divider();
   print_line(" ******** Between 0 abd given line  ******** ");
   print_numbered_seperated($inbetween);
   print_line(" ******** Between 0 abd given line  ******** ");
   print_divider();

   print_numbered_seperated($newarray);
  
  } // end of each file for loop 
  } // end of main function
  
  
  function GetBetweenTwoIds($Begin, $End, $body){
	 // Get Id positions
	 
	 $returnArray = array();
	 print_line($Begin." - ".$End);
	 for($i = $Begin; $i<$End; $i++){
		 array_push($returnArray, $body[$i]);
	 }
	  return $returnArray;
  }
  
  
   function DivideToLines($body){
	 // Get Id positions
	 
	 $parts = preg_split("/[<br>]/", $body);
     if($parts){ $newarray = array_filter($parts);
     $newarray = preg_replace('/\.{2,}/', '', $newarray);
     $parts = array_values($newarray); }
	  return $parts;
  }
  
  
  // Returns matching phrase found in records with lines
 function matchPhrasewithLine($phrase, $body){
  $count = 0;
  $matching = array();
  $lines = array();
  
  $length = count($body);
	 
	 foreach($body as $bodyline){
     $count++;
	 //print_line($bodyline. " - ".$count);
	  if(preg_match('/('.$phrase.')/', $bodyline, $matches) ){
			  array_push($matching, $matches[0]);
			  array_push($lines, $count);
			  
			  //print_line("================".$matches[0]. " - ".$count);
	  } 
	  
     } // every obtained id list foreach
	 
	 
	 return array($matching, $lines);
  } // end of function
	 
	
  
  
  
  // Returns matching Ids from database to the Ids found in records
 function matchingIdswithLine($clearedIds, $con, $body){
  $count = 0;
  $matchingTableIds = array();
  $databaseTableIds = array();
  $lines = array();
	 
  $obtainedidarray = GetAllSingleAttribute('instagramid', 'Accounts', $con);
	 
	 foreach($clearedIds as $clearedId){
	 foreach($obtainedidarray as $obtainedid){ $count++;
	  if(preg_match('/('.$obtainedid.')/', $clearedId, $matches) ){
			  array_push($matchingTableIds, $matches[0]);
			  array_push($databaseTableIds, $clearedId);
			  $pos = matchPhrasewithLine($matches[0], $body);
			  array_push($lines, $pos[1][0]);
	  } 
	  
     } // every obtained id list foreach
	 } // every cleared id list foreach
	 
	 return array($matchingTableIds, $databaseTableIds, $lines);
 } 
 
 
 
 
  
  
  function getBlobsforUsers($handle, $obtainedname){
  
  $query = "Select data From object_data where key like '%".$obtainedname."%' ";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array1 = $result->fetchArray()){
		  
		$print_Array = $array1;
        $print_Array2 = 
		preg_replace('/[^a-zA-Z0-9:_@\/\.\&\#\!\)\($|^+\"\'\~\{\`]/', "", $print_Array);  
		$print_Array3 = preg_replace('/\.{4,}/', '<br>', $print_Array2);
		array_push($resultArray, $print_Array3["data"]); 
		  
	  }
	  return $resultArray;
  }
  
  
  
   // Gets Id numbers present in a record entry
 function getAccountIdTranslationT($con, $AccountNames){
	  
	  // go line by line and detect if ID or if username
	  
	  $ids = array();
	  $names = array();
	 // $AccountNames = preg_replace('/\.{2,}/', '<br>', $AccountNames);
	  
	   $parts = explode("<br>", $AccountNames);
		  if($parts){
			  
	  $length = count($parts); 
	  for($i=0; $i<$length; $i++){
		  //print_line($i);
		  
	  	if(preg_match_all('/[0-9]{7,}/', $parts[$i], $matches) ){
			 foreach($matches[0] as $match)
			 array_push($ids, $match);
		  }
		  
	  } // end of for 
	   } // end of if where we find parts
	 return $ids;
	  
  } // end of function
  
  
  
  // ********************************************************
  // Functions of that help eachAccountDetection detection start from here 
  // ********************************************************
  
  
  function runRelationshipExtraction($con){
  $files = glob("*.sqlite");
  //print_readable($files);
  
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  
  
  //print_r($relationships);
  return $relationships;
 

  } // end for going over each sqlite file
  
  } // end of function
  
 
?> 