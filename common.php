<?php
header("Access-Control-Allow-Origin: *"); // this allows any site/application to access this webservice
//echo "<div> Common </div>";
include_once("connection.php");
// this file holds generic functions
if($_POST["action"]){
	//session_start();
	$_POST["action"]();
}

function getMonthWeekendToEnable(){
	global $connection;
	$arrMonthToEnableWeekends = array();

	$strSQL = "SELECT intMonthToEnableWeekend FROM
				tblMonthCustomization";
	$rsResult = mysqli_query($connection, $strSQL);
	
	while ($arrRow = mysqli_fetch_assoc($rsResult)) {
		$arrMonthToEnableWeekends[] = $arrRow["intMonthToEnableWeekend"];
	}

	echo json_encode($arrMonthToEnableWeekends);
}


function getDateToHide(){
	global $connection;
	$arrDatesToHide = array();

	$strSQL = "SELECT dtmDateToHide FROM
				tblDateCustomization";
	$rsResult = mysqli_query($connection, $strSQL);
	
	while ($arrRow = mysqli_fetch_assoc($rsResult)) {
		$arrDatesToHide[] = $arrRow["dtmDateToHide"];
	}

	echo json_encode($arrDatesToHide);				
}

?>
