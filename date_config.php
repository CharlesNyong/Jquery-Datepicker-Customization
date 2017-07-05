<?php
require_once("connection.php");
require_once("constant.php");
require_once("common.php");

global $arrMONTHS;
$arrMonthToEnableWeekends = array();
$arrMonthToDisableWeekends = array();
$arrHiddenDates = array();
$arrHiddenDates = loadDateToHide();
$strVal = "";
//var_dump($_POST);

if($_POST["saveConfig"]){ // save setting has been pressed
	$arrPreviousMonthToEnableWknd = loadMonthToEnableWknd();
	foreach ($arrMONTHS as $intKey => $strMonths) {
		if(isset($_POST[$strMonths]) && !in_array($_POST[$strMonths], $arrPreviousMonthToEnableWknd) ){
			$arrMonthToEnableWeekends[] = $_POST[$strMonths];

			// $strVal = $_POST[$strMonths] . "\n";
			// echo "Check value: " . $strVal ;
		}
		// this means a previously checked option has been unchecked
		//&& in_array($_POST[$strMonths], $arrPreviousMonthToEnableWknd)
		else if(!isset($_POST[$strMonths]) && in_array($arrMONTHS_MAP[$strMonths], $arrPreviousMonthToEnableWknd)){
			//echo "Post: ". $arrMONTHS_MAP[$strMonths];
			$arrMonthToDisableWeekends [] = $arrMONTHS_MAP[$strMonths];
		}	
	}

	if(!empty($_POST["dateToHide"])){
		//$arrPreviousHiddenDates = loadDateToHide();
		saveDateToHide($_POST["dateToHide"]);
		//echo " Date: " . $_POST["dateToHide"];	
	}
	// if(isset($_POST["bucketHolder"])){

	// }
	//var_dump($arrMonthToDisableWeekends);
	enableWeekendForMonth($arrMonthToEnableWeekends);
	disableWeekendForMonth($arrMonthToDisableWeekends);
}
if(!empty($_POST) || empty($_POST)){ //load all the settings on the page
	$arrMonthToEnableWeekends = loadMonthToEnableWknd();
	//var_dump($arrMonthToEnableWeekends);
}

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Configuration Page</title>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">

	// window.onload = function(){
	// 	alert("Onload fired...");
	// }

	var arrMonthToEnableWeekendsJs = new Array();
	var currentVal = "";
	var strDatesToHide = "";
	var numOfItemsInPList = 0;
	$(document).ready(function() {	
		$("#bucketCheck").attr('checked', false);
		//alert("Doc ready fired...");
		<? 
			for($i=0; $i < count($arrHiddenDates); $i++) {
				if($i == (count($arrHiddenDates)-1)){?>
					strDatesToHide += "<? echo $arrHiddenDates[$i];?>";
				<?}
				else{?>
					strDatesToHide += "<? echo $arrHiddenDates[$i];?>";
					strDatesToHide += "\n";	
				<?}	
			}
		?>
		document.getElementById("hiddenDates").value = strDatesToHide; 
		//alert("Dates to hide: " + strDatesToHide);
	    $("#dtmpicker").datepicker({
	    	minDate: 0
	    });
	    <?
		    for($i = 0; $i< count($arrMonthToEnableWeekends); $i++) {?>
		    	//var monthValue = "<? //echo $arrMonthToEnableWeekends[$i];?>";
		  		arrMonthToEnableWeekendsJs.push("<? echo $arrMonthToEnableWeekends[$i];?>");	
		    <?}
	    ?>
	    var i = 0;
	    $('input[type=checkbox]').each(function(){
			// if(i < arrMonthToEnableWeekendsJs.length){
			// 	console.log("Month to show weekend: " + arrMonthToEnableWeekendsJs[i]);
			// 	i++;
			// }
			//console.log("id : " + $(this).attr("id"));
			if($(this).attr("id") != "bucketCheck"){
					//console.log("Val value: " + $(this).val());
				if($.inArray($(this).val(), arrMonthToEnableWeekendsJs) > -1 ){
					$(this).attr('checked', true);
				}
			}	
		});

	});

	function addToList(){
		currentVal = document.getElementById("bucketList").value;
		newVal = document.getElementById("dtmpicker").value;
		if(newVal == ""){
			alert("Please pick a date!");
			return false;
		}
		numOfItemsInPList ++;
		console.log("item number: " + numOfItemsInPList);
		if(currentVal != ""){
			currentVal = currentVal + "\n";
			currentVal = currentVal + newVal;
			//localStorage.setItem(index, newVal);
			document.getElementById("bucketList").value = currentVal;
		}
		else{
			//localStorage.setItem(index, newVal);
			document.getElementById("bucketList").value = newVal;
		}
		
		return false; // to prevent this from submitting the form
	}

	function showButton(){
		if(document.getElementById("bucketCheck").checked == true){
			// /document.getElementById("bucketCheck").value = 1;
			$("#bucketButton").removeClass("hidden");
			//document.getElementById("bucketButton").style.visibility = "visible";
		}
		else{
			//alert("bucket unchecked..");
			//document.getElementById("bucketCheck").value = 0;
			$("#bucketButton").addClass("hidden");	
		}

	}

	function removeFrombucketList(){
		console.log("item No in remove function: " + numOfItemsInPList);
		//alert("Item in plist: " + numOfItemsInPList);
		if (document.getElementById("bucketList").value == "") {
			alert("Bucket Empty!!");
			return;
		}

		if(numOfItemsInPList == 1){
			currentVal = "";
			numOfItemsInPList = 0; // reset the counter because the list has been cleared
		}
		else{
			for (var i = currentVal.length; i >=0; i--) {
				if(currentVal.charAt(i) == "\n"){ // find the first newline char counting from behind
					//itemToRemFromList = currentVal.substr(i);
					currentVal = currentVal.substr(0, i);
					-- numOfItemsInPList;
					break;
				}
			}
		}
		// itemToRemFromList = itemToRemFromList.replace(/(\n|\r)/gm,"");
		// key = inLocalStorage(itemToRemFromList);
		// if(key != -1){
		// 	localStorage.removeItem(key);
		// }
		document.getElementById("bucketList").value = currentVal;
		//console.log("Textarea value: " + currentVal + " Key = " + key + " item to remove = " + itemToRemFromList);
	}

	function submitForm(){
		strList = document.getElementById("bucketList").value;
		strdateField = document.getElementById("dtmpicker").value;

		if(strList != "" && strdateField != ""){
			document.getElementById("dateToHide").value = strList;
		}
		else{
			document.getElementById("dateToHide").value = strdateField;
		}
		//document.getElementById("dateToHide").value = document.getElementById("bucketList").value; 
		document.getElementById("saveConfig").value = 1;
		document.getElementById("configFrm").submit();
	}
</script>
<style type="text/css">
	#configSegment{
		width: 400px;
		float: left;
	}

	#InstructionSegment{
		width: 400px;
		margin-top: 60px;
		clear: both; 
		float: left;
	}

	#monthOptlist{
		border: solid 3px black;
		width: 290px;
	}

	.fltLeft{
		float: left;
	}

	.fltRight{
		float: right;
	}

	.hidden{
		visibility: hidden;
	}

	#bucketList{
		overflow: scroll;
	}

	#previewPage{
		margin-left: 740px;
		margin-top: -10px;
		float: left;
	}

	#dateList{
		/*margin-left: 1500px;*/
		margin-top: 70px;
		overflow-y: scroll;
		/*float: left;*/
	}

	#hiddenDates{
		background-color: #F5DEB3;
	}

	li{
		margin: 10px 0;
	}

</style>
</head>
<body>
	<center><h3>Date Configuration Page</h3></center>
	<fieldset id="configSegment">
		<legend>Settings</legend>
		<form action="<?php echo ($_SERVER["PHP_SELF"]);?>" method="post" id="configFrm">
			<label class="fltLeft"> Create bucket list</label>&nbsp;&nbsp;<input type="checkbox" id="bucketCheck" name="bucketCheck" value="" onchange="showButton()"></input><br/><br/>
			<label class="fltLeft"> Date to hide:</label><input type="text" class="fltLeft" id="dtmpicker"/>
			&nbsp;&nbsp;<button id="bucketButton" class="hidden" onclick="return addToList()">Add to bucket</button><br/><br/>
			<label>Enable weekends for:</label> 
			<div id="monthOptlist">
				<input type="checkbox" name="Jan" value="0">January</input>&nbsp;&nbsp;<input type="checkbox" name="Feb" value="1">February</input>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="2" name="Mar">March</input> <br/>
				<input type="checkbox" value="3" name="Apr">April</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox" value="4" name="May">May</input> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="5" name="Jun">June</input>
				<br/>
				<input type="checkbox" value="6" name="Jul">July</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox" value="7" name="Aug">August</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="11" name="Dec">December</input>
				<br/>
				<input type="checkbox" value="9" name="Oct">October</input>&nbsp;&nbsp;<input type="checkbox" value="10" name="Nov">November</input>&nbsp;&nbsp; <input type="checkbox" value="8" name="Sept">September</input>
			</div> <br/>
			<button onclick="submitForm()" name="saveConfig" id="saveConfig" value="">Save Settings</button> &nbsp;&nbsp;&nbsp; <button onclick="clearStorage()">Clear Storage</button>
			<input type="hidden" value="" name="dateToHide" id="dateToHide" />
		</form>	
	</fieldset>
	<div class="fltLeft">
		<label class="fltLeft"> &nbsp;&nbsp;&nbsp;BucketList: &nbsp;</label> <textarea rows="7" id="bucketList" cols="22" value="" name="bucketHolder"></textarea><br/><button  style="margin-left: 95px;" onclick="removeFrombucketList()">Remove item</button>
	</div>
	<div id="previewPage">
		<?include("index.html");?>
	</div><br/><br/><br/><br/><br/><br/>
	<div id="dateList">
		<label class="fltLeft"> &nbsp;&nbsp;&nbsp;Hidden Dates: &nbsp;</label> <textarea rows="7" id="hiddenDates" cols="22" value="" onfocus="this.blur()" readonly="readonly"></textarea>
	</div>
</body>
</html>
<?$strHTML .= ob_get_contents();
	ob_end_clean();
	echo $strHTML;

function enableWeekendForMonth($arrMonths){
	global $connection;
	//$arrDatesToHide = array();
	for ($i=0; $i <count($arrMonths); $i++) { 
		$strSQL = "INSERT INTO asikpo_270Project.tblMonthCustomization 
				(intMonthToEnableWeekend)
				VALUES ($arrMonths[$i])";
		$rsResult = mysqli_query($connection, $strSQL);				
	}			
}

function disableWeekendForMonth($arrMonths){
	global $connection;
	$strParam = "";
	for($i = 0; $i < count($arrMonths); $i++) {
		if($i != (count($arrMonths)-1)){
			$strParam .= $arrMonths[$i] . ",";
		}
		else{
			$strParam .= $arrMonths[$i];				
		}
	}
	//echo "Parameter: " . $strParam;
	$strSQL = "DELETE FROM 
				tblMonthCustomization
				WHERE intMonthToEnableWeekend IN (".$strParam.")";
	$rsResult = mysqli_query($connection, $strSQL);
	//echo "Query: ". $strSQL;
	// use the param in a "where in" statement for the query here
}

function loadMonthToEnableWknd(){
	global $connection;
	$arrReturn = array();
 
	$strSQL = "SELECT DISTINCT (intMonthToEnableWeekend) FROM 
				tblMonthCustomization";
	$rsResult = mysqli_query($connection, $strSQL);

	while ($arrRow = mysqli_fetch_assoc($rsResult)) {
		$arrReturn[] = $arrRow["intMonthToEnableWeekend"];
	}

	return $arrReturn;				
}

function loadDateToHide(){
	global $connection;
	$arrReturn = array();
 
	$strSQL = "SELECT DISTINCT (dtmDateToHide) FROM 
				tblDateCustomization";
	$rsResult = mysqli_query($connection, $strSQL);

	while ($arrRow = mysqli_fetch_assoc($rsResult)) {
		$arrReturn[] = $arrRow["dtmDateToHide"];
	}

	return $arrReturn;				
}

function saveDateToHide($strDatesToHide){
	global $connection;
	$arrDatesToHide = explode("\n", $strDatesToHide);
	foreach ($arrDatesToHide as $key => $value) {
		$arrDatesToHide[$key] = trim($value);
	}
	$arrPreviousHiddenDates = loadDateToHide();
	$arrDatesToSave = array_diff($arrDatesToHide, $arrPreviousHiddenDates);
	var_dump($arrDatesToSave);
	// echo "array item: " . $arrDatesToHide[0];
	foreach ($arrDatesToSave as $key => $value) {
		// echo "Dates: ". type$value;
		var_dump($value);
		$strSQL = "INSERT INTO asikpo_270Project.tblDateCustomization 
				(dtmDateToHide)
				VALUES ('".$value."')";
		$rsResult = mysqli_query($connection, $strSQL);
	}
}

?>	
