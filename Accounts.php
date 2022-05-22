 <?php
require_once ('Factory/databaseoperations.php'); 
require_once ('Factory/MakeDBConnect.php'); 
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 


//runAccountAnalysis($con);



function runAccountAnalysis($con){
  $files = glob("*.sqlite");
  //print_readable($files);
  
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  print_line('Starting account detection for: '.$sfile);
  
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  // get the obtained name for users.usernameToId
  $obtainednamearray = GetSingleAttribute('users.usernameToId', 'obtainedname', 'name', 'Records', $con);
  $obtainedname = $obtainednamearray[0];

  
  $AccountNames = getBlobs($handle, $obtainedname);
  $AccountNames = preg_replace('/\.{1,}/', '<br>', $AccountNames);
  //print_r($AccountNames);
  //print_divider();
  
  $AccountsFound = getAccountTranslation($handle, $con, $AccountNames);
  $AccountsFound = completeArrayIfDifferent($AccountsFound[0], $AccountsFound[1]);
  print_line('Accounts extracted from users.UsersToId');
  print_numbered_together($AccountsFound[0], $AccountsFound[1]);
  //print_r($AccountsFound);
 AddToAccounts($AccountsFound, $con);
  } // end for going over each sqlite file
  
  }
 
  // ********************************************************
  // Functions of that help runAccountAnalysis start from here 
  // ********************************************************
 function AddToAccounts($AccountNames, $con){
	 $lenaccountnames = count($AccountNames[0]);

	 for($i = 0; $i < $lenaccountnames; $i++){
		 $res = AddAccountsEntry($AccountNames[1][$i], $AccountNames[0][$i] , 0, $con);
	 }
	 print_line('Found accounts are added to database');
 }
 

  function getAccountTranslation($handle, $con, $AccountNames){
	  
	  // go line by line and detect if ID or if username
	  $ids = array();
	  $names = array();
	  
	   $parts = explode("<br>", $AccountNames[0]);
		  if($parts){
			  
	  $length = count($parts); 
	  for($i=0; $i<$length; $i++){
		  //print_line($i);
		  
	  	if(preg_match_all('/[0-9]{5,}/', $parts[$i], $matches) ){

			 foreach($matches[0] as $match)
			 array_push($ids, $match);

		  }
		  
	  } // end of for
	 
	  
	  
	  
	  for($j=0; $j<$length; $j++){
		
        $namessingles = preg_split('/[0-9]{5,}/', $parts[$j]) ;	
		foreach($namessingles as $namessingle){

		if(strlen($namessingle) > 5)
		array_push($names, $namessingle);  
	  }
	  } // end of for
	  
	  
	   } // end of if where we find parts
	   
	 return array($ids, $names);
	  
  } // end of function

  
   function completeArrayIfDifferent($array1, $array2){
	   
	  $len1 = count($array1);
	  $len2 = count($array2);
	  $maxlen = max($len1, $len2);
	  $minlen = min($len1, $len2);
	  $difflen = $maxlen - $minlen;
	  
	  if($difflen != 0){
		  $dif = $len1 - $len2;
		  if($dif < 0){
			  for($i=0; $i<$difflen; $i++) { array_push($array1, "Unknown"); }
		  }
		  else{
			  for($i=0; $i<$difflen; $i++) { array_push($array2, "Unknown"); } 
		  }
	  }
	  
	  return array($array1, $array2);
	  
   }
  
 
?> 