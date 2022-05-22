<?php
  function GetBetweenTwoIds($Begin, $End, $body){
	 // Get Id positions
	 
	 $returnArray = array();
	// print_line($Begin." - ".$End);
	 for($i = $Begin; $i<$End; $i++){
		 array_push($returnArray, $body[$i]);
	 }
	  return $returnArray;
  }
  
  
    function GetBetweenTwoIdsJS($Begin, $End, $Exact, $body){
	 // Get Id positions
	 
	 $returnArray = array();
	// print_line($Begin." - ".$End);
	 for($i = $Begin; $i<$End; $i++){
		 $bold = '<strong>'.$body[$i].'</strong>';
		
		 if($i == $Exact){ array_push($returnArray, $bold); }
		else{ array_push($returnArray, $body[$i]); }
	 }
	  return $returnArray;
  }
  

     function DivideToLinesRaw($body){
	 // Get Id positions
	 
	 $parts = preg_split("/(<br>)/", $body);
     if($parts){ 
	 $newarray = $parts;
	 }
	  return $parts;
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
	 
	 if(count($matching) < 1) { array_push($matching, "0 Instance Found"); array_push($lines, 0); }
	 return array($matching, $lines);
  } // end of function
  
  
  
    // Returns matching phrase found in records with lines
 function matchPhraseArraywithLine($phrases, $body){
  $count = 0;
  $matching = array();
  $lines = array();
  
  $length = count($body);
	 
	 foreach($body as $bodyline){ $count++;
		 foreach($phrases as $phrase){
     
	 //print_line($bodyline. " - ".$count);
	  if(preg_match('/('.$phrase.')/', $bodyline, $matches) ){
			  array_push($matching, $matches[0]);
			  array_push($lines, $count);
			  
			  //print_line("================".$matches[0]. " - ".$count);
	  }	  
	 
     }	 
     } // every obtained id list foreach
	 
	 if(count($matching) < 1) { array_push($matching, "0 Instance Found"); array_push($lines, 0); }
	 return array($matching, $lines);
  } // end of function
	 
	
  
  
  
  // Returns matching Ids from database to the Ids found in records
 function matchingIdswithLineold($clearedIds, $con){
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
			  array_push($lines, $count);
			  //print_line($TableNames[$i]);
	  } 
	  
     } // every obtained id list foreach
	 } // every cleared id list foreach
	 
	 return array($matchingTableIds, $databaseTableIds, $lines);
 } 
 
 
 
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
			  //print_line($TableNames[$i]);
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
		preg_replace('/[^a-zA-Z0-9:_@\/\.\&\#\!\)\($\s+|^+\"\'\~\{\`]/', "", $print_Array);  
		$print_Array3 = preg_replace('/\.{4,}/', '<br>', $print_Array2);
		array_push($resultArray, $print_Array3["data"]); 
		  
	  }
	  return $resultArray;
  }
  
  
  function getBlobsforPosts($handle, $obtainedname){
  
  $query = "Select data From object_data where key like '%".$obtainedname."%' ";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array1 = $result->fetchArray()){
		  
		$print_Array = $array1;
        $print_Array2 = 
		preg_replace('/[^a-zA-Z0-9]/', "", $print_Array);  
		$print_Array3 = preg_replace('/\.{4,}/', '<br>', $print_Array2);
		array_push($resultArray, $print_Array3["data"]); 
		  
	  }
	  return $resultArray;
  }
  
  
   function getBlobsforUsersRaw($handle, $obtainedname){
  
  $query = "Select data From object_data where key like '%".$obtainedname."%' ";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array1 = $result->fetchArray()){
		  
		$print_Array = $array1;
       // $print_Array2 = 
		//preg_replace('/[^a-zA-Z0-9:_@\/\.\&\#\!\)\($|^+\"\'\~\{\`]/', "", $print_Array);  
		//$print_Array3 = preg_replace('/\.{4,}/', '<br>', $print_Array2);
		array_push($resultArray, $print_Array["data"]); 
		  
	  }
	  return $resultArray;
  }
  
  
  
     function getBlobsforUsersHex($handle, $obtainedname){
  
  $query = "Select hex(data) From object_data where key like '%".$obtainedname."%' ";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array1 = $result->fetchArray()){
		  
		$print_Array = $array1;
       // $print_Array2 = 
		//preg_replace('/[^a-zA-Z0-9:_@\/\.\&\#\!\)\($|^+\"\'\~\{\`]/', "", $print_Array);  
		//$print_Array3 = preg_replace('/\.{4,}/', '<br>', $print_Array2);
		$print_Array = chunk_split($print_Array["hex(data)"], 2, ' ');
		array_push($resultArray, $print_Array); 
		  
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
 

 
 function filteraccountswithFound($accounts, $AccountIds, $body){
	    
  $matchingTableIds = array();
  $matchingNames = array();
  $lines = array();
	 
	 foreach($AccountIds[1] as $clearedId){
		 $count = 0;
	 foreach($accounts[1] as $obtainedid){
	  if(preg_match('/('.$obtainedid.')/', $clearedId, $matches) ){
			  array_push($matchingTableIds, $matches[0]);
			  array_push($matchingNames, $accounts[0][$count]);
			  $pos = matchPhrasewithLine($matches[0], $body);
			  array_push($lines, $pos[1][0]);
			  //print_line($TableNames[$i]);
	  } 
	   $count++;
     } // every obtained id list foreach
	 } // every cleared id list foreach
	// print_r($matchingTableIds);
	// print_divider();
	// print_r($matchingNames);
	// print_divider();
	// print_r($lines);
	// print_divider();
	 return array($matchingTableIds, $matchingNames, $lines);
	  
  }
 // ******* Printing Functions *******
 
     function give_numbered_together($data1, $data2){
	  $u = 1;
	  $rstring = "";
	  $rstring .= "<br>";
	  $len1 = count($data1);
	  $len2 = count($data2);
	  $len = min($len1, $len2);

	  $data1max = max(array_map('strlen', $data1));
	  $data2max = max(array_map('strlen', $data2));
	  $totalmax = $data1max + $data2max;
	  $neededlength = $totalmax + 27;
    
   $Seperator =	str_pad("-", $neededlength, "-", STR_PAD_RIGHT);
   $rstring .= "<br>".$Seperator."<br>";
   for($i = 0; $i<$len; $i++) {
	   $First = str_pad($data1[$i], $data1max, "|",  STR_PAD_RIGHT);
	   $First = str_replace("|", "&nbsp;&nbsp;", $First);
	   if($u < 10){$First = $First."&nbsp;&nbsp;";}
   $rstring .=  $u. "&nbsp;|&nbsp;". $First . "&nbsp;|&nbsp;".$data2[$i]."&nbsp;<br>";
   $rstring .=  $Seperator."<br>";
   $u++;
   }
   return $rstring;
 }
?>
