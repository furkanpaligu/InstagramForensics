<?php 
require_once ('Factory/every.php'); // Get the side menue
require_once ('TopCode/SideMenue.php'); // Get the side menue
require_once ('TopCode/TopMenue.php');  // Get the top menue
require_once ('TableNames.php');  // Get the logic function
require_once ('Accounts.php');  // Get the logic function
require_once ('DetectSuspectAccount.php');  // Get the logic function
require_once ('ExctractRelationships.php');  // Get the logic function


  // get obtained ids of all accounts
  $recordnames = GetAllSingleAttribute('name', 'Records', $con);
  $accounts = GetAllDoubleAttribute('name', 'instagramid', 'Accounts', $con)


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
	$("#HomeInst").change(function()
	{
	var e = document.getElementById("HomeInst");
    var val = e.options[e.selectedIndex].value;
	
			
					//$("#ZamanS").html("");
					//$("#ZamanBi").html("");
		// **************************** - Time - ************************************
			$.get('get_accounts.php', {inst: val }, function(data) 
			{
				//$("#ZamanS").html("");
			//	$("#ZamanBi").html("");
		  		 	
				var rData = jQuery.parseJSON( data );
	  		 	
	  		 	if(rData)
	  		 	{//alert(rData[0].a[0][1]);	
				//	$("#ZamanS").html("");
				//	$("#ZamanBi").html("");
				$("#ko").html("");
				
				
				if(rData[0].b == "0"){
					$("#ko").append("<option value='0'>No account for relationships</option>");
				}
				else{
					
	  		 		var i=0;
					var uu = rData.length - 1;
		  		 	for(i=0;i<rData[0].a[0].length;i++)
	  		 		{   
					    //$accounts[1][$t].'">'.$accounts[1][$t].' ('.$accounts[0][$t].')
		  		 		var str = '<option value="'+rData[0].a[1][i]+'">'+rData[0].a[1][i]+' ('+rData[0].a[0][i]+')</option>';
		  		 		$("#ko").append(str);
						//alert(str);
						//if(i == uu)  var str = '<option value="'+rData[i].a+'", "selected=/"selected/">'+rData[i].a+'</option>';
						//$("#ZamanBi").append(str);
	  		 		}  
	  		 	}
				
				}
	  		 	else
	  		 	{
					alert("Database fetch error!");
					$("#ZamanS").html("");
					$("#ZamanBi").html("");
					var strt =  '<option value="Fetch-Error">Fetch-Erro</option>';   $("#arac").append(strt);
	  		 	}
	  		 	
	  		});	 	
			
		// **************************** - Time - ************************************
		
});

});  // for main jquery area, do not delete !!!
//*******************************************************************************************************************

 
 //*************************************View Function***************************************
 function ViewImageBar()
   {
	   	   
	   var t = document.getElementById("HomeInst");       // Analiz Tipi
       var tool = t.options[t.selectedIndex].value;
	   
	   var c = document.getElementById("element_2");      // Dil
       var criteria = c.options[c.selectedIndex].value;
	   
	   var ts = document.getElementById("arac");
       var timeS = ts.options[ts.selectedIndex].value;
	   
	   var te = document.getElementById("kriter");
       var timeE = te.options[te.selectedIndex].value;
	   
		  
		  var selectedArray = new Array();
          var selObj = document.getElementById('ko');
          var i; var c= ""; var d= "";
          var count = 0;
    for (i=0; i<selObj.options.length; i++) {
		  d = d + selObj.options[i].value + ".";
      if (selObj.options[i].selected) {
		  c = c + selObj.options[i].value + ".";
      selectedArray[count] = selObj.options[i].value;
      count++;
      }
   } 	  
		 c = c + "a";
		 d = d + "a";
		  
	      var Gph = document.getElementById("graph");
		  var Gph2 = document.getElementById("graph2");
		  var Gph3 = document.getElementById("graph3");
		  var Gph4 = document.getElementById("graph4");
		 
		  Gph.src="deneme.php?grph=line&timeS="+timeS+"&timeE="+timeE+"&c="+c+"&criteria="+criteria+"&tool="+tool+"";
		  Gph2.src="deneme.php?grph=bar&timeS="+timeS+"&timeE="+timeE+"&c="+c+"&criteria="+criteria+"&tool="+tool+"";
		//  Gph3.src="deneme.php?grph=line&timeS="+timeS+"&timeE="+timeE+"&c="+d+"&criteria="+criteria+"&tool="+tool+"";
		//  Gph4.src="deneme.php?grph=bar&timeS="+timeS+"&timeE="+timeE+"&c="+d+"&criteria="+criteria+"&tool="+tool+"";

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
                              <tr>
                                <td width="4">&nbsp;</td>
                                <td width="369">
								
								<table width="348" border="0">
                                  <tr>
                                    <td width="55">Record</td>
                                    <td width="283"><select name="HomeInst" id="HomeInst" style="width:200px">                                     
                                      <?php 
			                          for($t=0; $t<count($recordnames); $t++){
echo '<option value="'.$recordnames[$t].'">'.$recordnames[$t].'</option>  '; }
			                           ?>
                                    </select></td>
                                  </tr>
                                </table>
								
								</td>
                                <td width="624"><table width="540" border="0">
                                  <tr>
                                    <td width="79"><div align="left">Line Start</div></td>
                                    <td width="240"><input name="arac" id="arac" style="width:135px" >
                                    </select></td>
                                    <td width="138"><div align="right">
                                      <input type="submit" name="methodbutton3" id="methodbutton3" value="PDF Raporu" onClick="PdfPreview();" style="width:95px"/>
                                      <br />
                                      <input type="submit" name="methodbutton4" id="methodbutton4" value="Word Raporu" onClick="WordPreview();" style="width:95px"/>
                                    </div></td>
                                    <td width="65"><span style="font-weight: bold; font-size: large; font-family: Verdana, Arial, Helvetica, sans-serif;"><img src="images/New/Download-icon.png" alt="" width="50" height="50" /></span></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><table width="348" border="0">
                                  <tr>
                                    <td width="55">Accounts</td>
                                    <td width="264">
                                  <select name="ko[]" size="6" multiple="MULTIPLE" id="ko" style="width:275px" >
                                   <?php 
			                          for($t=0; $t<count($accounts[0]); $t++){
echo '<option value="'.$accounts[1][$t].'">'.$accounts[1][$t].' ('.$accounts[0][$t].')</option>  '; }
			                           ?>
                                    </select></td>
                                  </tr>
                                </table></td>
                                <td><table width="330" border="0">
                                  <tr>
                                    <td width="79"><div align="left">Line End</div></td>
                                    <td width="241"><input name="kriter" id="kriter"  style="width:135px">
                                    </select></td>
                                  </tr>
                                </table></td>
								
                              </tr>

                            </table>
                            <table width="1012" border="0">
                              <tr>
                                <td width="9">&nbsp;</td>
                                <td width="993"><hr style="height: '10'; text-align: 'left'; color: '#FF0000'; width: '30%'" />
                                  <input name="Goster2" type="button" id="Goster2" onClick="ViewImageBar();" value="Show"/>
                                <input type="hidden" name="hiddenField" id="hiddenField" /></td>
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
            <li class="TabbedPanelsTab" tabindex="0">Filtered and Numbered</li>
            <li class="TabbedPanelsTab" tabindex="0">Raw and Numbered</li>
            <li class="TabbedPanelsTab" tabindex="0">Filtered</li>
            <li class="TabbedPanelsTab" tabindex="0">Raw</li>
          </ul>
          <div class="TabbedPanelsContentGroup">
            <div class="TabbedPanelsContent"> <img src="" alt="" id ="graph" /></div>
            <div class="TabbedPanelsContent"> <img src="" alt="" id ="graph2" /></div>
            <div class="TabbedPanelsContent"> <img src="" alt="" id ="graph3" /></div>
            <div class="TabbedPanelsContent"> <img src="" alt="" id ="graph4" /></div>
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
var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
</script>
</body> 
</html>
