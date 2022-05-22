<?php
mb_internal_encoding('utf-8');


 // ******* SQLite Functions *******
  function sqlite_open($location,$mode)
{
    $handle = new SQLite3($location);
    return $handle;
}
function sqlite_query($dbhandle,$query)
{
    $array['dbhandle'] = $dbhandle;
    $array['query'] = $query;
    $result = $dbhandle->query($query);
    return $result;
}
function sqlite_fetch_array(&$result)
{
    #Get Columns
    $i = 0;
    while ($result->columnName($i))
    {
        $columns[ ] = $result->columnName($i);
        $i++;
    }
   
    $resx = $result->fetchArray(SQLITE3_ASSOC);
    return $resx;
}
 // ******* SQLite Functions *******
 
 
   function getBlobs($handle, $obtainedname){
  
  $query = "Select data From object_data where key like '%".$obtainedname."%' ";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array1 = $result->fetchArray()){
		  
		$print_Array = $array1;
        $print_Array2 = preg_replace('/[^a-zA-Z0-9:_@\/]/', ".", $print_Array);  
		$print_Array3 = preg_replace('/\.{3,}/', '<br>', $print_Array2);
		array_push($resultArray, $print_Array3["data"]); 
		  
	  }
	  return $resultArray;
  }
  
  
  
  
       function getViewerBlobs($handle, $useridlist){
  
  $query = "Select data From object_data where key like '%".$obtainedname."%' ";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array1 = $result->fetchArray()){
		  
		$print_Array = $array1;
        $print_Array2 = preg_replace('/[^a-zA-Z0-9:_@\/]/', ".", $print_Array);  
		$print_Array3 = preg_replace('/\.{3,}/', '<br>', $print_Array2);
		array_push($resultArray, $print_Array3["data"]); 
		  
	  }
	  return $resultArray;
  }
  
  
  
  // Experimental Function for the future
  function getViewerBlobs2($handle, $obtainedname){
  
  $query = "Select data From object_data where key like '%".$obtainedname."%' ";
  $result = sqlite_query($handle,$query);
  
  $resultArray = array();
  
	  while($array = $result->fetchArray(SQLITE3_ASSOC)){
		  
		 $resultArray = array();
  $objectType = NULL;
	 if(is_null($objectType)) {
        $object = new stdClass();
    } else {
        // does not call this class' constructor
        $object = unserialize(sprintf('O:%d:"%s":0:{}', strlen($objectType), $objectType));
    }
   
    $reflector = new ReflectionObject($object);
    for($i = 0; $i < $result->numColumns(); $i++) {
        $name = $result->columnName($i);
        $value = $array[$name];
       
        try {
            $attribute = $reflector->getProperty($name);
           
            $attribute->setAccessible(TRUE);
            $attribute->setValue($object, $value);
        } catch (ReflectionException $e) {
            $object->$name = $value;
        }
    }
    } // while
    return $object;
		  
	 
	//  return $resultArray;
  }
 
   //$Blobs = getBlobs($handle); Hook Up Hot Shot
  //echo("<br><br>");
  //print_readable($Blobs);
  //echo("<br><br>");
  //}
 
 
?>
