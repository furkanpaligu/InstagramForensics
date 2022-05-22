 <?php
require_once ('Factory/databaseoperations.php'); // Get the side menue
require_once ('Factory/MakeDBConnect.php'); // Get the side menue
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 


// runRecordAnalysis($con);



function runRecordAnalysis($con){
  $files = glob("*.sqlite");
  
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  
  print_line('Starting records detection for: '.$sfile);
  
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  $TableNames = getTableNames($handle);
  print_line('The Record names have been obtained in their recorded names.');
  
  getNameTranslation($handle, $con);
  print_line('The Record names have been translated and saved to database.');
  
  print_line('Records in this file: ');
  $RecordEntries = GetRecordsEntries($con);
  print_numbered_together($RecordEntries[0], $RecordEntries[1]);
  } // end for going over each sqlite file
  
  }
 
  // ********************************************************
  // Functions of that help runRecordAnalysis start from here 
  // ********************************************************
 
 
  // Get table names in the raw format then strip non-readable characters
  function getTableNames($handle){
  
  $query = "Select key From object_data";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array1 = $result->fetchArray()){
		  
		$print_Array = $array1;
        $print_Array2 = preg_replace('/[^a-zA-Z0-9@\/]/', ".", $print_Array);  
		array_push($resultArray, $print_Array2["key"]); 
		  
	  }
	  return $resultArray;
  }
  
  

  // Translate table names and add to database
  function getNameTranslation($handle, $con){
  
  $TableNames = getTableNames($handle);
  $comments = "";
  $users = "";
  $byId = "";
  $feed = "";
  $stories = "";
  $storyTables = array();
  $foundTables = array();
  $foundTableTranslations = array();
  
  $length = count($TableNames); // must be 12, but just in case
	  for($i=0; $i<$length; $i++){
		  
		  // 1
		  // ************* if no '/' then it is relationships  ************* 
		  if(strpos($TableNames[$i], "/") == false){ 
          array_push($foundTables, $TableNames[$i]);
		  array_push($foundTableTranslations, 'relationships');
		  AddRecordsEntry('relationships', $TableNames[$i], $con);
		  }
		  // ************* if no '/' then it is relationships  ************* 
		  
		  
		  // 2
		  // *************  if 3 count, then it is users  ************* 
		  $parts = preg_split("/[\/]/", $TableNames[$i]);
		  if($parts){
		  
		  $countparts = count($parts);
		  if($countparts == 2){
			  
			  $firstpartrepeat = substr_count( $parts[0],$parts[1]);
			  if($firstpartrepeat > 0){
				  //print_line($TableNames[$i]." -> users.users <br>");
				  array_push($foundTables, $TableNames[$i]);
		          array_push($foundTableTranslations, 'users.users');
			      AddRecordsEntry('users.users', $TableNames[$i], $con);
			  $users = $parts[0]; // finding the string: users
			  }
			  
		  } // if there is more than one word divided by '/'
		  } // if there is any parts obtained
		   // *************  if 3 count, then it is users  ************* 
	  

	  } // for loop
  
  
    $TableNames = deleteAlreadyFound($foundTables, $TableNames);
  
    // 3 and 4 (because finding 3 leaves only one user table left)
	// *************  longest one with the users string is users.usernameToId  *************
    $userTables = array();
    $length = count($TableNames); 
	  for($i=0; $i<$length; $i++){
  
    
		  $parts = preg_split("/[\/]/", $TableNames[$i]);
		  if($parts){
		  
		  $countparts = count($parts);
		  if($countparts == 2){
	  
		  if(preg_match('/('.$users.')/', $TableNames[$i], $matches) ){
			  array_push($userTables, $TableNames[$i]);
		  }
		  //print_readable($matches2);
		
		  }
		  }
		  
	  } // end of for
	      // find the ones with users string
  
      $maxlen = max(array_map('strlen', $userTables));
	  for($i=0; $i<count($userTables); $i++){
		  if(strlen($userTables[$i]) == $maxlen)
		  {
			  //print_line($userTables[$i]." -> users.usernameToId <br>");
			  array_push($foundTables, $userTables[$i]);
		      array_push($foundTableTranslations, 'users.usernameToId');
			  AddRecordsEntry('users.usernameToId', $userTables[$i], $con);
			  }
	      else
		  {
			 if(strcmp($userTables[$i], $users.'/'.$users )){
				 array_push($foundTables, $userTables[$i]);
		         array_push($foundTableTranslations, 'users.viewerId');
				 AddRecordsEntry('users.viewerId', $userTables[$i], $con);
				 } 
		  }
	  }
      // *************  longest one with the users string is users.usernameToId  ************* 

	  
	  // extract found tables from the TablesArray
       $TableNames = deleteAlreadyFound($foundTables, $TableNames);
	   
	   
	  // Among the remaining, find the ones with repeating end.
	  $length = count($TableNames); // must be 12, but just in case
	  for($i=0; $i<$length; $i++){
		  
		  // 5 and 6
		  // ************* if ending repeats then and there is 2 instances of first   ************* 
		  // if ending repeats, then it is byId
	      $parts = preg_split("/[\/]/", $TableNames[$i]);
		  if($parts){
		  
		  $countparts = count($parts);
		  if($countparts == 2){  
		  
		  $grepped = preg_grep('/('.$parts[1].')/', $TableNames);
		  if(count($grepped) > 1){
			  $byId = $parts[1];
			  
			  $grepped2 = preg_grep('/('.$parts[0].')/', $TableNames);
			  if(count($grepped2) > 1){
				    
					$comments = $parts[0];
					
				    array_push($foundTables, $parts[0]."/".$parts[1]);
		            array_push($foundTableTranslations, 'comments.byId');
					AddRecordsEntry('comments.byId', $parts[0]."/".$parts[1], $con);
			  }
			  else{
				 array_push($foundTables, $parts[0]."/".$parts[1]);
		         array_push($foundTableTranslations, 'posts.byId');
				 AddRecordsEntry('posts.byId', $parts[0]."/".$parts[1], $con);
			  }
			  
			  }
		  
		  } // if there is more than one word divided by '/'
		  } // if there is any parts obtained
		  // ************* if ending repeats then and there is 2 instances of first   *************
	  
	  } // for loop
	 
	        //print_divider();
       $TableNames = deleteAlreadyFound($foundTables, $TableNames);
       
	  
	  // Among the remaining, find the ones with repeating end.
	  $length = count($TableNames); // must be 12, but just in case
	  for($i=0; $i<$length; $i++){
		  
		  // 7
		  // ************* the rest of the comments is comments.byPostId   ************* 
		  // if ending repeats, then it is byId
	      $parts = preg_split("/[\/]/", $TableNames[$i]);
		  if($parts){
		  
		  $countparts = count($parts);
		  if($countparts == 2){  

			  if( $grepped3 = substr_count( $TableNames[$i],$comments))
			  if($grepped3 > 0){
		        
		         //print_line($TableNames[$i]." -> comments.byPostId <br>");
				 array_push($foundTables, $TableNames[$i]);
		         array_push($foundTableTranslations, 'comments.byPostId');
				 AddRecordsEntry('comments.byPostId', $TableNames[$i], $con);
			  }
		  } // if there is more than one word divided by '/'
		  } // if there is any parts obtained		  
	  }
	  // ************* the rest of the comments is comments.byPostId   ************* 
	  
$TableNames = deleteAlreadyFound($foundTables, $TableNames);

        
		// 8
	$maxlen = max(array_map('strlen', $TableNames));
	  for($i=0; $i<count($TableNames); $i++){
		  if(strlen($TableNames[$i]) == $maxlen)
		  {
			  $parts = preg_split("/[\/]/", $TableNames[$i]);
			  $feed = $parts[0];
			  array_push($foundTables, $TableNames[$i]);
		      array_push($foundTableTranslations, 'feed.visibleCount');
			  AddRecordsEntry('feed.visibleCount', $TableNames[$i], $con);
			  
		  }

	   }
	   
	   
	   // 9
	   $TableNames = deleteAlreadyFound($foundTables, $TableNames);
	   
	   	  for($i=0; $i<count($TableNames); $i++){
		  {
	          if( $grepped3 = substr_count( $TableNames[$i],$feed)){
				  if($grepped3 > 0)
			    array_push($foundTables, $TableNames[$i]);
		        array_push($foundTableTranslations, 'feed.items');
				AddRecordsEntry('feed.items', $TableNames[$i], $con);
			      }
			  }
		  }
		  
		  
		  
		  $TableNames = deleteAlreadyFound($foundTables, $TableNames);

	   
	   	// 10 and 11
    $userTables = array();
    $length = count($TableNames); 
	  for($i=0; $i<$length; $i++){
  
    
		  $parts = preg_split("/[\/]/", $TableNames[$i]);
		  if($parts){
		  
		  $countparts = count($parts);
		  if($countparts == 2){
	  
		   $grepped = preg_grep('/('.$parts[0].')/', $TableNames);
		  if(count($grepped) > 1){
			  $stories = $parts[0];		
					array_push($storyTables, $TableNames[$i]); 
			  }
		
		  } // if there is more than one word divided by '/'
		  } // if there is any parts obtained		  
		  
	  } // end of for

	  
	  $maxlen = max(array_map('strlen', $storyTables));
	  for($i=0; $i<count($storyTables); $i++){
		  if(strlen($storyTables[$i]) == $maxlen)
		  {
			  array_push($foundTables, $storyTables[$i]);
		      array_push($foundTableTranslations, 'stories.feedTray');
			  AddRecordsEntry('stories.feedTray', $storyTables[$i], $con);
			  }
	      else
		  {
			 if(strcmp($storyTables[$i], $users.'/'.$users )){
				 array_push($foundTables, $storyTables[$i]);
		         array_push($foundTableTranslations, 'stories.reels');
				 AddRecordsEntry('stories.reels', $storyTables[$i], $con);
				 } 
		  }
	  }
	   
	   
	   
	   $TableNames = deleteAlreadyFound($foundTables, $TableNames);
	   
	   // 12
	   array_push($foundTables, $TableNames[0]);
	   array_push($foundTableTranslations, 'direct.emojis');
	   AddRecordsEntry('direct.emojis', $TableNames[0], $con);

  
      return array($foundTables, $foundTableTranslations);
  }
  
 
?> 