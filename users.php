<?php 
require_once ('Factory/every.php'); // Get the side menue
require_once ('TopCode/SideMenue.php'); // Get the side menue
require_once ('TopCode/TopMenue.php');  // Get the top menue
require_once ('TableNames.php');  // Get the logic function
require_once ('Accounts.php');  // Get the logic function
require_once ('DetectSuspectAccount.php');  // Get the logic function
require_once ('ExctractRelationships.php');  // Get the logic function

require_once ('Factory/databaseoperations.php'); // Get the side menue
require_once ('Factory/MakeDBConnect.php'); // Get the side menue
require_once ('Factory/Helper.php'); 
require_once ('Factory/Helper_Print.php'); 
require_once ('Factory/Helper_Sqlite.php'); 
require_once ('Factory/Helper_Encoding.php'); 
require_once ('Factory/Helper_String.php'); 

/*
 Time analysis of the records where time analysis is possible
*/
$files = glob("*.sqlite");
  
  
  // In case there is more than one sqlite file
  foreach($files as $sfile) { 
  $handle = sqlite_open($sfile,'SQLITE3_OPEN_READWRITE');
  
  
  // get obtained ids of all accounts
  $recordnames = GetAllSingleAttribute('name', 'Records', $con);
  $accounts = GetAllDoubleAttribute('name', 'instagramid', 'Accounts', $con);

// get the obtained name for users.usernameToId
  $obtainedtablearray = GetSingleAttribute('users.users', 'obtainedname', 'name', 'Records', $con);
  $obtainedtable = $obtainedtablearray[0];
  
  $userdetails = getBlobsforUsers($handle, $obtainedtable);
  $userdetailsraw = getBlobsforUsersRaw($handle, $obtainedtable);
  $userslinedivided = DivideToLines($userdetails[0]);
  $userslinedividedraw = DivideToLines($userdetailsraw[0]);
  $countoflines = count($userslinedivided);
  
  $Idsofusers = getAccountIdTranslationT($con, $userdetails[0]);
  $AccountIds = matchingIdswithLine($Idsofusers, $con, $userslinedivided);

  $NameIdPos = filteraccountswithFound($accounts, $AccountIds, $userslinedivided);

  }
  
  
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BrowSwEx</title>

<link rel="icon" type="image/png" href="images/logo.png" />
<link href="mainCSS.css" rel="stylesheet" type="text/css" />
<link href="menu_assets/styles.css" rel="stylesheet" type="text/css">
<link href="menu_assets/stylesR.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src='SpryAssets/SpryCollapsiblePanel.js' type="text/javascript"></script>
<script src="js/jquery-1.7.1.js"></script>

<script>

$(document).ready(function() 
{

//*******************************************************************************************************************
	$("#ko").change(function()
	{
	var e = document.getElementById("ko");
    var val = e.options[e.selectedIndex].value;
	
			//alert(val);
					$("#ls").val("");
					$("#le").val("");
		// **************************** - Lines - ************************************
			$.get('get_lines.php', {inst: val }, function(data) 
			{
				$("#ls").val("");
				$("#le").val("");
		  		 	
				var rData = jQuery.parseJSON( data );
	  		 	//var rData = data;
	  		 	if(rData)
	  		 	{//alert(rData[0].a[0][1]);	
				
					$("#ls").val("");
		  		 	$("#ls").val(rData[0].b);
					
					$("#le").val("");
		  		 	$("#le").val(rData[0].c);
					
					$("#hiddenField").val("");
		  		 	$("#hiddenField").val(rData[0].a);
						
				
				}
	  		 	else
	  		 	{
					alert("Database fetch error!");
	  		 	}
	  		 	
	  		});	 	
			
		// **************************** - Lines - ************************************
		
});

});  // for main jquery area, do not delete !!!
//*******************************************************************************************************************

 
 //*************************************View Function***************************************
 function ViewImageBar()
   {
	   	   
	   var ls = document.getElementById("ls");       // Line Start
       var lsv = ls.value;
	   
	   var le = document.getElementById("le");       // Line End
       var lev = le.value;
	   
	   var hd = document.getElementById("hiddenField");       // Exact Line
       var hdv = hd.value;
	   
	   var se = document.getElementById("searcht");       // Search Word
       var sev = se.value;
	   
	   var hdt = document.getElementById("hiddenField2");       // Exact Line
       var hdtv = hdt.value;

	   if(sev){sev = sev;}
	   else{sev = "No Search Word";}
	   
	   if(lsv){lsv = lsv;}
	   else{lsv = 0;}
	   
	   if(hdv){hdv = hdv;}
	   else{hdv = 0;}
	   
	   if(lev){lev = lev;}
	   else{lev = hdtv;}
	   
	   if(lev > hdtv){lev = hdtv;}
	   if(lsv > lev){ lsv = 0;  lev = hdtv;  }
	   
	   
	   
	 
	  // **************************** - Lines - ************************************
			$.get('get_searched.php', {linestart: lsv, lineend: lev, exactline: hdv, searchword: sev }, function(data) 
			{
				$("#graph2").text("Search");
				

				var rData = jQuery.parseJSON( data );

	  		 	if(rData)
	  		 	{//alert(rData[0].a[0][1]);	
				
                // alert(rData[0].a + " - " + rData[0].b);
				 var strng = "";
				 var strng = strng + rData[0].a ;
				 var strng = strng + "<br/>";
				 for(i=0;i<rData[0].b.length;i++)
	  		 		{
                        var k = i+1;
						newadd = '<strong>'+k+'-) </strong>'+rData[0].b[i] + ' <br/><br/> ';
						strng = strng + newadd;
						
					}

				document.getElementById("graph2").innerHTML = strng;
				TabbedPanels1.showPanel(1);
				
				}
	  		 	else
	  		 	{
					alert("Database fetch error!");
	  		 	}
	  		 	
	  		});	 	
			
		// **************************** - Lines - ************************************
		
		

		  return false;	 
         
   }
//*************************************View Function***************************************	 
	  
 //*************************************Pdf Preview***************************************
 function PdfPreview()
   {
	
	document.getElementById('hiddenField').value = "p";
    document.form1.submit()     
   }
//*************************************Pdf Preview***************************************	

 //*************************************Word Preview***************************************
 function WordPreview()
   {
	      
	document.getElementById('hiddenField').value = "w";
    document.form1.submit()     
   }
//*************************************Word Preview***************************************	
</script>


 <body>
 
<div class="container">
<?php 
    // Top menue
    $Top = new TopMenue();
    $Top->construct();
    echo($Top->top); 
	
	echo('<div class="container">');
	
	// Side menue
    $Side = new SideMenue();
    $Side->construct();
    $Side->endd();
    echo($Side->side);
	
	echo('<div class="Scrollcontent">');
?>

 
 
    <div id="TabbedPanels2" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Selection Box</li>
        <p>&nbsp;</p>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
                                   <form id="form1" name="form1" method="post" action="KOTRPreview.php">
                            <h2><br />
                              User Account Analysis Based on Chosen Record</h2>
                            <table width="1011" border="0">
                              <tr>
                                <td width="8">&nbsp;</td>
                                <td width="993"><hr style="height: '10'; text-align: 'left'; color: '#FF0000'; width: '30%'" /></td>
                              </tr>
                            </table>
                            
							
							
							<table width="1011" border="0">
                              
							  
							  <!-- First Line -->
							  <tr>
                                <td width="4">&nbsp;</td>
								
								
								<td width="369">
								<table width="348" border="0">
                                  <tr>
                                    <td width="79">Line Start</td>
                                    <td width="269">
									<input type="number" name="ls" id="ls" style="width:135px"></input>
									</td>
                                  </tr>
                                </table>
								</td>
								
								
                                <td width="624">
								<table width="500" border="0">
                                  <tr>
                                    <td width="20">Search</td>
                                    <td width="280">
									<input name="searcht" id="searcht" style="width:175px"></input>
									</td>
                                  </tr>
                                </table>
								</td>
								
								
                              </tr>
							  <!-- First Line -->
							  
							  
							  
							  
							  
							  
							  <!-- Second Line -->
                              <tr>
                                <td>&nbsp;</td>
								
								
                                <td>
								<table width="348" border="0">
                                  <tr>
                                    <td width="79"><div align="left">Line End<br><br></div></td>
                                    <td width="269">
									<input type="number" name="le" id="le" style="width:135px" > 
									<div id="totalline"> Total:(<?php echo $countoflines; ?>)</div>
									</td>
                                  </tr>
                                </table>
								</td>
								
								
                                <td>
								<table width="330" border="0">
                                  <tr>
                                    <td width="79"></td>
                                    <td width="241"></td>
                                  </tr>
                                </table>
								</td>
								
                              </tr>
                              <!-- Second Line -->
							  
							  
							  
							  <!-- Third Line -->
                              <tr>
                                <td>&nbsp;</td>
                                
								
								<td>
								<table width="388" border="0">
                                  <tr>
                                    <td width="79">Accounts</td>
                                    <td width="309">
                                  <select name="ko" id="ko" size="6" <!-- multiple="MULTIPLE" --> id="ko" style="width:269px" >
                                   <?php 
			                          for($t=0; $t<count($NameIdPos[0]); $t++){
echo '<option value='.$NameIdPos[1][$t].'>'.$NameIdPos[1][$t].' ('.$NameIdPos[0][$t].')</option>  '; }
			                           ?>
                                    </select>
									</td>
                                  </tr>
                                </table>
								</td>
                                
								
								<td></td>
								
                              </tr>
                              <!-- Third Line -->
							  
							  
							  
							  
							  
                            </table>
                            <table width="1012" border="0">
                              <tr>
                                <td width="9">&nbsp;</td>
                                <td width="993"><hr style="height: '10'; text-align: 'left'; color: '#FF0000'; width: '30%'" />
                                  <input name="Goster2" type="button" id="Goster2" onClick="ViewImageBar();" value="Show"/>
                                <input type="hidden" name="hiddenField" id="hiddenField" />
								<input type="hidden" name="hiddenField2" id="hiddenField2" value=
                                 <?php echo($countoflines-1); ?> /></td>
                              </tr>
                            </table>
                            </form>
        </div>
      </div>
    </div>
    <table width="1034" height="95" border="0">
      <tr>
        <td width="43" height="91">&nbsp;</td>
        <td width="571"><div id="TabbedPanels1" class="TabbedPanels">
          <ul class="TabbedPanelsTabGroup">
            <li class="TabbedPanelsTab" tabindex="0">Users and Their Line Positions</li>
            <li class="TabbedPanelsTab" tabindex="1">Searched and Selected</li>
            <li class="TabbedPanelsTab" tabindex="2">Filtered and Numbered</li>
            <li class="TabbedPanelsTab" tabindex="3">Raw and Numbered</li>
          </ul>
          <div class="TabbedPanelsContentGroup">
            <div class="TabbedPanelsContent" style="font-size:14px;"> 
			<?php 
			echo("<br>Information Order: Line Position (Filtered) | Found Id | Recorded Name");
			print_numbered_together_n($NameIdPos[0], $NameIdPos[1], $NameIdPos[2]); ?>
			</div>
            <div id ="graph2" name ="graph2"  class="TabbedPanelsContent" style="font-size:14px;"> 
			No search made yet.
			</div>
            <div class="TabbedPanelsContent" style="font-size:14px;"> 
			<?php 
			//echo("<br>Information Order: Line Position | Found Id | Recorded Id");
			print_line("Special Characters Removed with Lines Assigned Data<br>");
			print_numbered_seperated($userslinedivided); ?></div>
            <div class="TabbedPanelsContent" style="font-size:14px;"> 
			<?php 
			print_line("Raw with Lines Assigned Data<br>");
			print_numbered_seperated($userslinedividedraw);?>
			</div>
          </div>
        </div></td>
        <td width="64"><p>&nbsp;</p></td>
        <td width="338">&nbsp;</td>
      </tr>
    </table>
    
<!-- A Group End It -->   
<p align="left" class="block" style="font-weight: bold; font-size: large; font-family: Verdana, Arial, Helvetica, sans-serif;">&nbsp;</p>
  </div>
  <div class="footer" style="color: #CCC"><!-- end .footer -->
    <div align="center">SHSU Cyber Forensics and Intelligence Center Huntsville/TX</div>
  </div>
<!-- end .container --></div>

<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
</script>
</body> 
</html>
