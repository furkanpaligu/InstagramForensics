 <?php
require_once ('Factory/databaseoperations.php');
require_once ('Factory/MakeDBConnect.php');
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 
require_once ('Factory/Helper_String.php'); 
header("content-type: text/html; charset=UTF-8");  


//runEmojiAnalysis($con);

  
 function runEmojiAnalysis($con){
	  
 $p = runStoryExtraction($con);
 
 $regular = $p[0];
 $hex = $p[1];
 // Clearing the return body and dividing it to lines
 $body = clearForStories($regular[0]);

 
 // For text
 $IdsofPosts = getAccountIdTranslation($con, $regular[0]);
 $body = divideByIdsE($IdsofPosts, $body);
 $dividedbyids = DivideToLinesRaw($body);
           


 // For hex
 $bodyhexcleared = clearForHexStories($hex[0]);
 $bodyhex = DivideToLines($bodyhexcleared);
 $cleanedhexnoempty = hexclearcase($bodyhex);
 
 $matchesofsections = array_values(matchPhrasewithLine("00 FF FF", $cleanedhexnoempty));
 
 $desiredpart = GetBetweenTwoIds($matchesofsections[1][1], $matchesofsections[1][2]-1, $bodyhex);
 $desiredpart = findnumbersclear($desiredpart);
 
 $cleanedhexform = hexclearcase($desiredpart);
 
 $foundnumber = hexfindcase($cleanedhexform);
 
 $foundemoji = hexfindemoji($cleanedhexform);
 
 print_line("Found emojis and number of times they were used: ");
 print_numbered_together($foundemoji, $foundnumber);
 
  }
  
  // ********************************************************
  // Functions of that help emoji detection start from here
  // ********************************************************

       // Gets Id numbers present in a record entry
 function getHexTimeTranslation($body){
	  
	  // go line by line and detect if ID or if username
	  $times = array();
			  
	  $length = count($body); 
	  for($i=0; $i<$length; $i++){
		  
			if(preg_match_all('/[6]{1}[1]{1}[a-zA-Z0-9]{6}/', $body[$i], $matches) ){

			 foreach($matches[0] as $match)
			 array_push($times, $match);
		  }
		  
	  } // end of for 

	 return $times;
	  
  } // end of function
  
 
  
 
  function runStoryExtraction($con){
  $files = glob("*.sqlite");
  
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  print_line('Starting emoji construction for the file: '.$sfile);
  
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  
  // get the obtained name for users.usernameToId 
  $obtainedtablearray = GetSingleAttribute('direct.emojis', 'obtainedname', 'name', 'Records', $con);
  $obtainedtable = $obtainedtablearray[0];
  
  $relationships = getBlobsforUsersRaw($handle, $obtainedtable);
  $relationshipsHex = getBlobsforUsersHex($handle, $obtainedtable);
  
  return array($relationships,$relationshipsHex);
 

  } // end for going over each sqlite file 
  } // end of function


   function divideByIdsE($AccountIds, $body){
	
	$length = count($AccountIds); 
	  for($i=0; $i<$length; $i++){
	 $body = str_replace($AccountIds[$i], '<br>'.$AccountIds[$i].'<br>', $body);
	  }
	 
	 
     return $body;
  } // end of function


 
   function clearForStories($cleared){
	 
	 $cleared = preg_replace('/(@)/', "<br>@<br>", $cleared);
	 $cleared = preg_replace('/(seen)/', "<br>seen<br>", $cleared);
	 $cleared = preg_replace('/(\.){1,}/', "<br>", $cleared); 

	 return $cleared;

  } // end of function
  
  function findnumbersclear($cleared){
	  return $cleared;
  }
  
  
   function clearForHexStories($cleared){
	 
	 $cleared = preg_replace('/(00 FF FF)/', "<br>00 FF FF<br>", $cleared);
	 $cleared = preg_replace('/(00 03)/', " END<br>", $cleared);
	
	 return $cleared;

  } // end of function
  
  
  function hexeach($body){
	  $hexform = array();
	  // go line by line and detect if ID or if username
			  print_divider();
	  $length = count($body); 
	  for($i=0; $i<$length; $i++){
		  $u = $i+1;
		  array_push($hexform, json_decode($body[$i]));
	  } // end of for 
print_divider();

      for($i=0; $i<$length; $i++){
		  $u = $i+1;
		  $uyt = bin2hex($body[$i]);
		  print_line($u."-) ".chunk_split($uyt, 2, ' '));
		  
		  
	  } // end of for 

return $hexform;
  } // end of function
  
  
    function hexclearcase($body){
	  $hexform = array();
	  
	  $body = preg_replace('/( END)/', "", $body);
	  // get the part before END
	  foreach($body as $line){
	  if(strlen($line) > 3){
			array_push($hexform, $line) ;
  
  
  } // if parts
  } // foreach
  
return $hexform;
  } // end of function
 


function hexfindcase($body){
$result_set = array();	
	foreach($body as $line){
		
		// **** make it array ****
		$array_form = array();
		$parts = explode(" ", $line);
	   if($parts){
		   foreach($parts as $part) { 
		   if(strlen($part)>1)array_push($array_form, $part); }
		   } // if parts
		  // **** make it array **** 
		   

if(count($array_form) > 2){	   
$lastelement = end($array_form);

$removed = array_pop($array_form);
$lastbeforeelement = end($array_form);

if($lastbeforeelement == '3C'){
	
	$numbertwo = substr($removed, -1);
	array_push($result_set, $numbertwo);
}

elseif($lastbeforeelement == '2C'){
	$removed2 = array_pop($array_form);
	$threebefore = end($array_form);
	$numbertwo = substr($threebefore, -2, 1);

	$numbertwo = (int)$numbertwo + 1;

	array_push($result_set, $numbertwo);

}

else{
	$numbertwo = substr($lastelement, -1);
	array_push($result_set, $numbertwo);
}
	

} // if more than 2 elements in array_form
} // foreach
return $result_set;
 } // end of function



 
 function hexfindemoji($body){
 $emojireturn = array();
	foreach($body as $line){
		
		// **** make it array ****
		$array_form = array();
		$parts = explode(" ", $line);
	   if($parts){
		   foreach($parts as $part) { 
		   if(strlen($part)>1)array_push($array_form, $part); }
		   } // if parts
		  // **** make it array **** 
		   

if(count($array_form) > 2){	   
$third = $array_form[2];
$emojitoadd = emojinumbertranslation($third);
array_push($emojireturn, $emojitoadd);
} 
	} // foreach
return $emojireturn;
 } // end of function

 
function emojinumbertranslation($code){
	if($code == '00'){return "1st emoji in instagram selection list";}
	if($code == '03'){return "2st emoji in instagram selection list";}
	if($code == '04'){return "3st emoji in instagram selection list";}
}
 
 
?> 