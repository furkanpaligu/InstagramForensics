 <?php
require_once ('Factory/databaseoperations.php'); 
require_once ('Factory/MakeDBConnect.php'); 
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 
require_once ('Factory/Helper_String.php'); 
header("content-type: text/html; charset=UTF-8");  


 //runPostAnalysis($con);


  
 function runPostAnalysis($con){
	  
 $p = runPostExtraction($con);
 
 // Clearing the return body and dividing it to lines
 $body = clearForPosts($p[0]);
 
 
 // Finding ids in the record
 $IdsofPosts = getAccountIdTranslation($con, $p[0]);
 $body = divideByIds($IdsofPosts, $body);
 $dividedbyids = DivideToLinesRaw($body);
 
 
 $accounts = GetAllDoubleAttribute('name', 'instagramid', 'Accounts', $con);
 $AccountIds = matchingIdswithLine($IdsofPosts, $con, $dividedbyids);
 $NameIdPos = filteraccountswithFound($accounts, $AccountIds, $dividedbyids);
 
 
 print_line("The account IDs discovered in the posts record:");
 print_numbered_together($NameIdPos[0], $NameIdPos[1], $NameIdPos[2]);
 
 
  // finding time elements
 $timesdetected = getTimeTranslation($con, $dividedbyids);
 $times = matchPhraseArraywithLine($timesdetected, $dividedbyids);
 print_line("Times detected in the record:");
 print_numbered_together($times[0], $times[1]);

 
 $locationdetected = getLocationTranslation($con, $dividedbyids);
 $locations = matchPhraseArraywithLine($locationdetected, $dividedbyids);
 print_line("Locations detected in the record:");
 print_numbered_together($locations[0], $locations[1]);
 

  
  }
  
  // ********************************************************
  // Functions of that help post detection start from here 
  // ********************************************************

       // Gets Id numbers present in a record entry
 function getTimeTranslation($con, $body){
	  
	  // go line by line and detect if ID or if username
	  
	  $times = array();
			  
	  $length = count($body); 
	  for($i=0; $i<$length; $i++){
		  
			if(preg_match_all('/(on)[a-zA-Z]{4,9}[0-9]{6}/', $body[$i], $matches) ){

			 foreach($matches[0] as $match)
			 array_push($times, $match);

		  }
		  
	  } // end of for 
	 return $times;
  } // end of function
  
  
  function getLocationTranslation($con, $body){
	  
	  $locations = array();
			  
	  $length = count($body); 
	  for($i=0; $i<$length; $i++){
		  
			if(preg_match_all('/(in)[a-zA-Z]{6,20}(with)/', $body[$i], $matches) ){

			 foreach($matches[0] as $match)
			 array_push($locations, $match);

		  }
		  
	  } // end of for 

	 return $locations;
	  
  } // end of function
  
  

 
  function runPostExtraction($con){
  $files = glob("*.sqlite");
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  print_line('Starting relationship construction for the file: '.$sfile);
  
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  
  // get the obtained name for users.usernameToId
  $obtainedtablearray = GetSingleAttribute('posts.byId', 'obtainedname', 'name', 'Records', $con);
  $obtainedtable = $obtainedtablearray[0];
  
  $relationships = getBlobsforUsersRaw($handle, $obtainedtable);
  return $relationships;
 
  } // end for going over each sqlite file
  } // end of function


   function divideByIds($AccountIds, $body){
	
	$length = count($AccountIds); 
	  for($i=0; $i<$length; $i++){
	 $body = str_replace($AccountIds[$i], '<br>'.$AccountIds[$i].'<br>', $body);
	  }
	 
	 
     return $body;
  } // end of function


 
   function clearForPosts($cleared){
	 
	 $cleared = preg_replace('/[^a-zA-Z0-9.+\/=]/', "", $cleared);  
	 $cleared = preg_replace('/(\.){1,}/', "<br>", $cleared); 

	 return $cleared;

  } // end of function
 
 
?> 