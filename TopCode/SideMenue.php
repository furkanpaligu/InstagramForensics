<?php 
class SideMenue{
	
public $side;

function construct(){
	
$addto = ""; // $_SESSION['addto'];
	
$this->side =  '<div class="sidebar" style="background-image: url(images/backgrounds/gray_background_22.jpg);">
  
      <img style="border-bottom: solid #ff0000" src="'.$addto.'images/backgrounds/2015-07-13_1234.png" alt="" width="200" height="136" />
    <p></p>
	<p align="center">BrowSwExLite v1.0</p><br />';
	
$this->side .=	
   "<p>&nbsp;</p>";
	
//$this->side =  <hr style="height: '10'; text-align: 'left'; color: '#FF0000'; width: '30%'" />
$this->side .=  
    "
    <div id='cssmenuR'>
      <ul>
        <li class='has-subR '><a href='index.php'><span>Initial Extraction</span></a></li>
      </ul>
	 
    </div>
	
	<div id='cssmenuR'>
      <ul>
        <li class='has-subR '><a href='users.php'><span>Analyze Users</span></a></li>
      </ul>
	 
    </div>
	
		
	<div id='cssmenuR'>
	<ul>
        <li class='has-subR '><a href='delete.php'><span>Reset Database</span></a></li>
     </ul>
	 </div>";
	
	
	
/*	*/


	
	
	
$this->side .=	
   "<p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
    <p>&nbsp;</p>";

   } // function consstruct
	
function endd(){$this->side .= "</div>";} // function endd
} // class  
?>