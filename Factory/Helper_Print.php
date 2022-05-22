<?php
mb_internal_encoding('utf-8');

// ******* Printing Functions *******

  function print_line($data){
   echo("<br>".$data."<br>");
 }
 
   function print_divider(){
   echo("<br><br>");
 }


 function print_readable($data){
   foreach($data as $child) {
   echo $child . "<br>";
   }
 }
 
 
  function print_numbered($data){
	  $u = 1;
	  //echo "<br>";
   foreach($data as $child) {
   echo $u. " ". $child . "<br>";
   $u++;
   }
   echo "<br>";
 }
 
 
   function print_numbered_seperated($data){
	     
	  $u = 1;
	  //echo "<br><br><br>";
   foreach($data as $child) {
   echo "<strong>".$u. "-)</strong> ". $child . "<br><br>";
   $u++;
   }
   echo "<br>";
 }
 
 
   function print_numbered_together($data1, $data2){
	  $u = 1;
	  //echo "<br>";
	  $len1 = count($data1);
	  $len2 = count($data2);
	  $len = min($len1, $len2);

	  $data1max = max(array_map('strlen', $data1));
	  $data2max = max(array_map('strlen', $data2));
	  $totalmax = $data1max + $data2max;
	  $neededlength = $totalmax + 27;
    
   $Seperator =	str_pad("-", $neededlength, "-", STR_PAD_RIGHT);
   print_line($Seperator);
   for($i = 0; $i<$len; $i++) {
	   $First = str_pad($data1[$i], $data1max, "|",  STR_PAD_RIGHT);
	   $First = str_replace("|", "&nbsp;&nbsp;", $First);
	   if($u < 10){$First = $First."&nbsp;&nbsp;";}
   echo $u. "&nbsp;|&nbsp;". $First . "&nbsp;|&nbsp;".$data2[$i]."&nbsp;<br>";
   echo($Seperator."<br>");
   $u++;
   }
   echo "<br>";
 }
 
 
  function print_numbered_together_n($data1, $data2, $lines){
	  //echo "<br>";
	  $len1 = count($data1);
	  $len2 = count($data2);
	  $len = min($len1, $len2);

	  $data1max = max(array_map('strlen', $data1));
	  $data2max = max(array_map('strlen', $data2));
	  $totalmax = $data1max + $data2max;
	  $neededlength = $totalmax + 27;
    
   $Seperator =	str_pad("-", $neededlength, "-", STR_PAD_RIGHT);
   print_line($Seperator);
   for($i = 0; $i<$len; $i++) {
	   $First = str_pad($data1[$i], $data1max, "|",  STR_PAD_RIGHT);
	   $First = str_replace("|", "&nbsp;&nbsp;", $First);

	   
	   $lineString = $lines[$i]; 
	   if($lines[$i] < 10){$lineString = $lineString."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
	   else if($lines[$i] < 100){$lineString = $lineString."&nbsp;&nbsp;&nbsp;&nbsp;";}
	   else if($lines[$i] < 1000){$lineString = $lineString."&nbsp;&nbsp;";}
	   
   echo $lineString. "&nbsp;|&nbsp;". $First . "&nbsp;|&nbsp;".$data2[$i]."&nbsp;<br>";
   echo($Seperator."<br>");
   }
   echo "<br>";
 }
 
 

 // ******* Printing Functions *******
 
?>
