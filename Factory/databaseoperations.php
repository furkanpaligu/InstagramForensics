<?php


// ******************************************
// Records Functions 
// ******************************************

// Add an entry for found record
function AddRecordsEntry($Name, $ObtainedName , $con){
    
	$checker = DoesItExist($Name, 'name', 'Records', $con);
	if($checker == 0){
	
    
    if ($stmt = $con->prepare('INSERT INTO Records (name, obtainedname) VALUES ( ? , ? )')) {
        $stmt->bind_param('ss', $Name, $ObtainedName);
	    $stmt->execute();
		$stmt->close();
	    return 0; // 0 means success
    } // end of if
	else {
		return 2; // 2 means database statement error
	} // statement if

    
	} // checker if
	else{ 
	return 1; // 1 means it already exists
	} 
} // end of function DoesItExist


// Display all records with their obtained names
function GetRecordsEntries($con){
    
    $rebacparam = array(); // make array.
	$rebacparam1 = array(); // make array.
	$rebacparam2 = array(); // make array.
    
    if ($stmtDIE2 = $con->prepare('SELECT name, obtainedname FROM Records')) {
	
	$stmtDIE2->execute();
	$stmtDIE2->store_result();
	
	$names = null; // Make variables ready
	$obtainednames = null; // Make variables ready
    $stmtDIE2->bind_result($names, $obtainednames); // Bind result to variables
} // end of if
    //array_push($rebacparam,  "-----------------------------------------------------------------------");
while ($stmtDIE2->fetch()) {
	array_push($rebacparam1, $names);
	array_push($rebacparam2, $obtainednames);
	//"| ".$names." | - <strong>Found as</strong> - | ".$obtainednames." |<br>
	//-----------------------------------------------------------------------") ;
	
}
    $rebacparam = array($rebacparam1, $rebacparam2);
    $stmtDIE2->close();
    return $rebacparam;
} // end of function GetSingleAttribute

// ******************************************
// Records Functions 
// ******************************************


// ******************************************
// Accounts Functions 
// ******************************************
// Add an entry for found record
function AddAccountsEntry($Name, $Id , $IsSuspect, $con){
    
	
	$checker = DoesItExist($Name, 'name', 'Accounts', $con);
	if($Name == "Unknown"){$checker = DoesItExistLike($Id, $con); }
	if($Name == "undefined"){$checker = DoesItExistLike($Id, $con); }
	//print_line($checker);
	//print_line($Id);
	if($checker == 0){
	
    $Id = filter_var($Id, FILTER_SANITIZE_NUMBER_INT);
    if ($stmt = $con->prepare('INSERT INTO Accounts (name, instagramid, issuspect) VALUES ( ? , ? , ? )')) {
        $stmt->bind_param('ssi', $Name, $Id, $IsSuspect);
	    $stmt->execute();
		//print_divider();
		print_line( "Query run. Inserted UserID " . $stmt->insert_id);
		//print_divider();
		$stmt->close();
		
		return 0; // 0 means success
	    
    } // end of if
	else {
		return 2; // means database error
	} // statement if

    
	} // checker if
	else{ 
	return 1; // 1 means it already exists
	} 
} // end of function DoesItExist


// ******************************************
// Accounts Functions 
// ******************************************


// ******************************************
// Relationship Functions 
// ******************************************
// Add an entry for found record
function AddRelationshipEntry($Id , $IsBlocked, $IsFollowed, $IsRestricted, $con){
    
	
	$checker = DoesItExist($Id, 'instagramid', 'Relationships', $con);
    
	if($checker == 0){
	
    $Id = filter_var($Id, FILTER_SANITIZE_NUMBER_INT);
    if ($stmt = $con->prepare('INSERT INTO Relationships ( instagramid, isblocked, isfollowed, isrestricted) VALUES ( ? , ? , ?, ? )')) {
        $stmt->bind_param('siii', $Id , $IsBlocked, $IsFollowed, $IsRestricted);
	    $stmt->execute();
		//print_divider();
		print_line( "Query run. Inserted UserID " . $stmt->insert_id);
		//print_divider();
		$stmt->close();
		
		return 0; // 0 means success
	    
    } // end of if
	else {
		return 2; // means database error
	} // statement if

    
	} // checker if
	else{ 
	return 1; // 1 means it already exists
	} 
} // end of function DoesItExist


// ******************************************
// Relationship Functions 
// ******************************************










// ******************************************
// Generic Database Functions From This Point
// ******************************************

function DoesItExist($Param, $Attribute, $TableName, $con){
    
    $rebacparam = 1;
    
    if ($stmtDIE = $con->prepare('SELECT '.$Attribute.' FROM '.$TableName.' WHERE '.$Attribute.' = ?')) {
    $stmtDIE->bind_param('s', $Param);
	$stmtDIE->execute();
	
	$stmtDIE->store_result();
} // end of if

    if ($stmtDIE->num_rows < 1) {$rebacparam = 0;}
    else{$rebacparam = $stmtDIE->num_rows;}
    
    $stmtDIE->close();
    return $rebacparam;
    
} // end of function DoesItExist


function DoesItExistLike($clearedSuspectId, $con){
    
    $rebacparam = 1;
	
	$matchingTableIds = array();
	$obtainedidarray = GetAllSingleAttribute('instagramid', 'Accounts', $con);
	foreach($obtainedidarray as $obtainedid){
		if(preg_match('/('.$clearedSuspectId.')/', $obtainedid , $matches) ){ 
		array_push($matchingTableIds, 1);}
		else{array_push($matchingTableIds, 0);}
			  //print_line($TableNames[$i]);
	 }
    //print_numbered($matchingTableIds);
   if(max($matchingTableIds)> 0) return 1;
   else return 0;
    
} // end of function DoesItExistLike


// Must be a function that checks more than one param, like where a = 1 and b = 2; Also there needs to be a function that returns multiple attributes back, like select a, b, c from instead of just a.
function GetSingleAttribute($ParamCheck, $AttributeReturn, $AttributeCheck, $TableName, $con){
    
    $rebacparam = array(); // make array.
    
    if ($stmtDIE2 = $con->prepare('SELECT '.$AttributeReturn.' FROM '.$TableName.' WHERE '.$AttributeCheck.' = ?')) {
    $stmtDIE2->bind_param('s', $ParamCheck);
	
	$stmtDIE2->execute();
	$stmtDIE2->store_result();
	
	$returningParam = null; // Make variables ready
    $stmtDIE2->bind_result($returningParam); // Bind result to variables
} // end of if
while ($stmtDIE2->fetch()) {
	array_push($rebacparam, $returningParam) ;
}
    
    $stmtDIE2->close();
    return $rebacparam;
} // end of function GetSingleAttribute


function GetSingleLikeAttribute($ParamCheck, $AttributeReturn, $AttributeCheck, $TableName, $con){
    
    $rebacparam = array(); // make array.
    
    if ($stmtDIE2 = $con->prepare('SELECT '.$AttributeReturn.' FROM '.$TableName.' WHERE '.$AttributeCheck.' LIKE  %{'.$ParamCheck.'}%')) {

	
	$stmtDIE2->execute();
	$stmtDIE2->store_result();
	
	$returningParam = null; // Make variables ready
    $stmtDIE2->bind_result($returningParam); // Bind result to variables

while ($stmtDIE2->fetch()) {
	array_push($rebacparam, $returningParam) ;
}
    
    $stmtDIE2->close();
    return $rebacparam;
} // end of if
else{return mysqli_error($con);}

} // end of function GetSingleAttribute



function GetAllSingleAttribute($AttributeReturn, $TableName, $con){
    
    $rebacparam = array(); // make array.
    
    if ($stmtDIE2 = $con->prepare('SELECT '.$AttributeReturn.' FROM '.$TableName)) {
	
	$stmtDIE2->execute();
	$stmtDIE2->store_result();
	
	$returningParam = null; // Make variables ready
    $stmtDIE2->bind_result($returningParam); // Bind result to variables
} // end of if
while ($stmtDIE2->fetch()) {
	array_push($rebacparam, $returningParam) ;
}
    
    $stmtDIE2->close();
    return $rebacparam;
} // end of function GetSingleAttribute



function GetAllDoubleAttribute($AttributeReturnOne, $AttributeReturnTwo, $TableName, $con){
    
    $rebacparamone = array(); // make array.
	$rebacparamtwo = array(); // make array.
    
    if ($stmtDIE2 = $con->prepare('SELECT '.$AttributeReturnOne.', '.$AttributeReturnTwo.'  FROM '.$TableName)) {
	
	$stmtDIE2->execute();
	$stmtDIE2->store_result();
	
	$returningParamOne = null; // Make variables ready
	$returningParamTwo = null;
    $stmtDIE2->bind_result($returningParamOne, $returningParamTwo); // Bind result to variables
} // end of if
while ($stmtDIE2->fetch()) {
	array_push($rebacparamone, $returningParamOne) ;
	array_push($rebacparamtwo, $returningParamTwo) ;
}
    
    $stmtDIE2->close();
    return array($rebacparamone, $rebacparamtwo);
} // end of function GetSingleAttribute



function SetSingleAttribute($ParamSet, $ParamCheck, $ParamTypes,  $AttributeSet, $AttributeCheck, $TableName, $con){
	
if($stmtA = mysqli_prepare($con,'UPDATE '.$TableName.' SET '.$AttributeSet.' = ? WHERE '.$AttributeCheck.' = ?')){
mysqli_stmt_bind_param($stmtA, $ParamTypes, $ParamSet, $ParamCheck);

//echo($TableName.' SET '.$AttributeSet.' = '.$ParamSet.' WHERE '.$AttributeCheck.' = '.$ParamCheck);
// execute prepared statement 
$stmtA->execute();

// close statement and connection 
$stmtA->close();

//echo("Successful");
}else{echo($con->error);
 // if there is an error,it should terminate here
} // end of else
    
} // end of function SetSingleAttribute


function DeleteSingleAttribute($ParamCheck, $ParamTypes, $AttributeCheck, $TableName, $con){
	
if($stmtDe = mysqli_prepare($con,'DELETE FROM '.$TableName.' WHERE '.$AttributeCheck.' = ?')){
mysqli_stmt_bind_param($stmtDe, $ParamTypes, $ParamCheck);

//echo($TableName.' SET '.$AttributeSet.' = '.$ParamSet.' WHERE '.$AttributeCheck.' = '.$ParamCheck);
// execute prepared statement 
$stmtDe->execute();

// close statement and connection 
$stmtDe->close();

//echo("Successful");
}else{echo($con->error);}
    
} // end of function SetSingleAttribute


?>