<?php
mb_internal_encoding('utf-8');


// ******* Encoding and Character Conversions *******  
  // bytesToBase64
  function binToAscii($bin) {
    $text = array();
    $bin = str_split($bin, 8);

    for($i=0; count($bin)>$i; $i++)
        $text[] = chr(bindec($bin[$i]));

    return implode($text);
}

    function decode64each($body){
	  
	  // go line by line and detect if ID or if username
	 // $AccountNames = preg_replace('/\.{2,}/', '<br>', $AccountNames);
			  print_divider();
	  $length = count($body); 
	  for($i=0; $i<$length; $i++){
		  $u = $i+1;
		  print_line($u."-) ".base64_decode($body[$i]));
		  
	  } // end of for 
	  
print_divider();

      for($i=0; $i<$length; $i++){
		  $u = $i+1;
		  print_line($u."-) ".iconv(mb_detect_encoding($body[$i], mb_detect_order(), true), "UTF-8",$body[$i]));
		  
	  } // end of for 


  } // end of function



 function binaryToString($binary)
{
    $binaries = explode(' ', $binary);
 
    $string = null;
    foreach ($binaries as $binary) {
        $string .= pack('H*', dechex(bindec($binary)));
    }
 
    return $string;    
}

function strigToBinary($string)
{
    $characters = str_split($string);
 
    $binary = [];
    foreach ($characters as $character) {
        $data = unpack('H*', $character);
        $binary[] = base_convert($data[1], 16, 2);
    }
 
    return implode(' ', $binary);    
}  


 function isBinary($str) {
    return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
}


  function bin2bstr($input)
// Convert a binary expression (e.g., "100111") into a binary-string
{
  if (!is_string($input)) return null; // Sanity check

  // Pack into a string
  return pack('H*', base_convert($input, 2, 16));
}
 // ******* Encoding and Character Conversions *******    
 
 
?>
