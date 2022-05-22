<?php
mb_internal_encoding('utf-8');
// Deletes an arrays element if it exists in another one.
function deleteAlreadyFound($found, $Table){
	  for($i=0; $i<count($found); $i++){
		  
		  if (($key = array_search($found[$i], $Table)) !== false) {
          unset($Table[$key]);
          }
		  
	  }
	  $array = array_values($Table);
	  return $array;
  }


  
  
  // ******* Error Handling **********
function ReturnErrorText($code){
	if ($code == 1){ return "It Already Exists"; }
	elseif ($code == 0) { return "Added Without Problem"; }
	else{ return $code; }
	
}
// ******* Error Handling **********
 
 
 
// Gets Id numbers present in a record entry
 function getAccountIdTranslation($con, $AccountNames){
	  
	  // go line by line and detect if ID or if username
	  
	  $ids = array();
	  $names = array();
	 // $AccountNames = preg_replace('/\.{2,}/', '<br>', $AccountNames);
	  
	   $parts = explode("<br>", $AccountNames);
		  if($parts){
			  
	  $length = count($parts); 
	  for($i=0; $i<$length; $i++){
		  //print_line($i);
		  
	  	if(preg_match_all('/[0-9]{5,}/', $parts[$i], $matches) ){
			 // array_push($userTables, $TableNames[$i]);
			 //print_line($parts[$i]);
			 //print_divider();
			 //print_r($matches);
			 foreach($matches[0] as $match)
			 array_push($ids, $match);
			  //print_r($matches);
		  }
		  
	  } // end of for 
	   } // end of if where we find parts
	 // print_numbered($ids);
	 // print_numbered($names);
	 return $ids;
	  
  } // end of function
  
 
 
 // Returns matching Ids from database to the Ids found in records
 function matchingIds($clearedIds, $con){
  $matchingTableIds = array();
  $databaseTableIds = array();
	 
  $obtainedidarray = GetAllSingleAttribute('instagramid', 'Accounts', $con);
	 
	 foreach($clearedIds as $clearedId){
	 foreach($obtainedidarray as $obtainedid){
	  if(preg_match('/('.$obtainedid.')/', $clearedId, $matches) ){
			  array_push($matchingTableIds, $matches[0]);
			  array_push($databaseTableIds, $clearedId);
			  //print_line($TableNames[$i]);
	  }
	  
     } // every obtained id list foreach
	 } // every cleared id list foreach
	 
	 return array($matchingTableIds, $databaseTableIds);
 } 

 
 // Updates database entries with found better ids
   function UpdateDBwithIds($databaseTableIds, $matchingTableIds, $con){
	  
	  //print_numbered_together($matchingTableIds, $databaseTableIds);
	  
	  $length = count($matchingTableIds); 
	  //print_line("Count is : ".$length);
	  for($i=0; $i<$length; $i++){
		//  print_line($matchingTableIds[$i]." vs ".$databaseTableIds[$i]);
		  if(strlen($matchingTableIds[$i]) >strlen($databaseTableIds[$i])){
	  SetSingleAttribute($matchingTableIds[$i], $databaseTableIds[$i], 'ss',  'instagramid', 'instagramid', 'Accounts', $con); }
	  }
	  
	  
  }
 
 
?>
