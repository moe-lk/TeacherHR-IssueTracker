<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
//header("Cache-control: private"); //IE 6 Fix
$q = $_REQUEST['q'];
$iCID = $_REQUEST['iCID'];
$srch = $_REQUEST['srch'];
$ctgry = $_REQUEST['ctgry'];
$brnd = $_REQUEST['brnd'];
$usr = $_REQUEST['usr'];
$id1=$_SESSION['uID_cp'];
$replace_data_new=array("'","/","!","&","*"," ","-","@",'"',"?",":","�","�",".");
if($q=='availabaleS'){//Check if NIC is in TeacherMast
	$sqlTmast = "SELECT * FROM TeacherMast where NIC='$iCID'";
	$isAvailablePmast=$db->rowAvailable($sqlTmast);
	
	$sqlTmastTemp = "SELECT * FROM ArchiveUP_TeacherMast where NIC='$iCID'";
	$isAvailableTemp=$db->rowAvailable($sqlTmastTemp);

	if(strlen($iCID) == 10){
		$nici = "19". $iCID[0] . $iCID[1]. $iCID[2]. $iCID[3]. $iCID[4]."0". $iCID[5] .$iCID[6]. $iCID[7]. $iCID[8];
		
		$sqlTmast = "SELECT * FROM TeacherMast where NIC='$nici'";
		$isAvailablePmast2=$db->rowAvailable($sqlTmast);	
		$sqlTmastTemp = "SELECT * FROM ArchiveUP_TeacherMast where NIC='$nici'";
		$isAvailableTemp2=$db->rowAvailable($sqlTmastTemp);

		if($iCID[9] == "v"){
			$nicix = $iCID[0] . $iCID[1]. $iCID[2]. $iCID[3]. $iCID[4]. $iCID[5] .$iCID[6]. $iCID[7]. $iCID[8]. "x";
			$sqlTmast = "SELECT * FROM TeacherMast where NIC='$nici'";
			$isAvailablePmastxx=$db->rowAvailable($sqlTmast);
		}else if($iCID[9] == "x"){
			$niciv = $iCID[0] . $iCID[1]. $iCID[2]. $iCID[3]. $iCID[4]. $iCID[5] .$iCID[6]. $iCID[7]. $iCID[8]. "v";
			$sqlTmast = "SELECT * FROM TeacherMast where NIC='$niciv'";
			$isAvailablePmastvv=$db->rowAvailable($sqlTmast);
		}
		

	}else if(strlen($iCID) == 12){
		$nici = $iCID[2]. $iCID[3]. $iCID[4].$iCID[5] .$iCID[6].$iCID[8].$iCID[9].$iCID[10].$iCID[11]."v";
		$nici2 = $iCID[2]. $iCID[3]. $iCID[4].$iCID[5] .$iCID[6].$iCID[8].$iCID[9].$iCID[10].$iCID[11]."x";

		$sqlTmast = "SELECT * FROM TeacherMast where NIC='$nici'";
		$sqlTmastx = "SELECT * FROM TeacherMast where NIC='$nici2'";

		$isAvailablePmast2=$db->rowAvailable($sqlTmast);

		$isAvailablePmastx=$db->rowAvailable($sqlTmastx);
	
		$sqlTmastTemp = "SELECT * FROM ArchiveUP_TeacherMast where NIC='$nici'";
		$isAvailableTemp2=$db->rowAvailable($sqlTmastTemp);

		$sqlTmastTempx = "SELECT * FROM ArchiveUP_TeacherMast where NIC='$nici2'";
		$isAvailableTempx=$db->rowAvailable($sqlTmastTempx);

	}
	if($isAvailablePmast==1){//green 060 //blue 03C //red 900
		echo "<span style=\"color:#900;\">Already registered.</span>";
	}else if($isAvailableTemp==1){
		echo "<span style=\"color:#03C;\">Pending for approval.</span>";
	}else if($isAvailablePmast2==1){//green 060 //blue 03C //red 900
		echo "<span style=\"color:#900;\">Already registered as ".$nici.".</span>";
	}else if($isAvailableTemp2==1){
		echo "<span style=\"color:#03C;\">Pending for approval as ".$nici.".</span>";
	}else if($isAvailablePmastx == 1){
		echo "<span style=\"color:#03C;\">Pending for approval as ".$nici2.".</span>";
	}else if($sqlTmastTempx == 1){
		echo "<span style=\"color:#03C;\">Pending for approval as ".$nici2.".</span>";
	}else if($isAvailablePmastvv == 1){
		echo "<span style=\"color:#03C;\">Pending for approval as ".$niciv.".</span>";
	}else if($isAvailablePmastxx == 1){
		echo "<span style=\"color:#03C;\">Pending for approval as ".$nicix.".</span>";
	}


	else{
		echo "<span style=\"color:#060;\">Not available.</span>";
	}
}
if($q=='zonelist'){
	$details="<select class=\"select2a_n\" id=\"ZoneCode\" name=\"ZoneCode\" onchange=\"Javascript:show_division('divisionList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>";
}
if($q=='divisionList'){
	$details="<select class=\"select2a_n\" id=\"DivisionCode\" name=\"DivisionCode\" onchange=\"Javascript:show_cences('censesList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Division where ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>";
}
if($q=='censesList'){
	$params1 = array(
        array($iCID, SQLSRV_PARAM_IN)
    );
	$sql = "{call SP_TG_GetSchoolsFor_SelectedDivision(?)}";
    $details="<select class=\"select2a_n\" id=\"InstCode\" name=\"InstCode\">
			  <option value=\"\">-Select-</option>";
	
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$CenCode=trim($row['CenCode']);
		$InstitutionName=$row['InstitutionName'];
		
        $details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
    }  
	echo $details.="</select>";
	
	/* $details="<select class=\"select2a_n\" id=\"InstCode\" name=\"InstCode\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_CensesNo where ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			if($DSCoded==$DSCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>"; */
}
/*------------First Start--------*/

if($q=='zonelistF'){
	$details="<select class=\"select2a_n\" id=\"ZoneCodeF\" name=\"ZoneCodeF\" onchange=\"Javascript:show_divisionF('divisionListF', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>";
}
if($q=='divisionListF'){
	$details="<select class=\"select2a_n\" id=\"DivisionCodeF\" name=\"DivisionCodeF\" onchange=\"Javascript:show_cencesF('censesListF', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Division where ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>";
}
if($q=='censesListF'){
	$params1 = array(
        array($iCID, SQLSRV_PARAM_IN)
    );
	$sql = "{call SP_TG_GetSchoolsFor_SelectedDivision(?)}";
    $details="<select class=\"select2a_n\" id=\"InstCodeF\" name=\"InstCodeF\">
			  <option value=\"\">-Select-</option>";
	
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$CenCode=trim($row['CenCode']);
		$InstitutionName=$row['InstitutionName'];
		
        $details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
    }  
	echo $details.="</select>";
	
	/* $details="<select class=\"select2a_n\" id=\"InstCode\" name=\"InstCode\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_CensesNo where ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			if($DSCoded==$DSCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>"; */
}

/*-----------First End--------*/

if($q=='divisionlst'){
	$details="<select class=\"select2a_n\" id=\"DSCode\" name=\"DSCode\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$iCID' order by DSName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['DSCode']);
			$DSNamed=$row['DSName'];
			$seltebr="";
			if($DSCoded==$DSCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		  echo $details.="</select>";
	
}
if($q=='divisionlstTmp'){
	$details="<select class=\"select2a_n\" id=\"DSCodeT\" name=\"DSCodeT\" tabindex=\"20\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$iCID' order by DSName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['DSCode']);
			$DSNamed=$row['DSName'];
			$seltebr="";
			if($DSCoded==$DSCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		  echo $details.="</select>";
	
}

if($q=='divisionlstCurrent'){
	$details="<select class=\"select2a_n\" id=\"DSCodeC\" name=\"DSCodeC\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$iCID' order by DSName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['DSCode']);
			$DSNamed=$row['DSName'];
			$seltebr="";
			if($DSCoded==$DSCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		  echo $details.="</select>";
	
}
if($q=='change_pw'){
	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                          <td width=\"39%\"><strong><span class=\"form_error\">*</span>Password</strong></td>
                          <td width=\"3%\">:</td>
                          <td width=\"58%\"><input name=\"CurPassword\" type=\"text\" class=\"input3\" id=\"CurPassword\" value=\"\"/></td>
                        </tr>
                        <tr>
                          <td><strong><span class=\"form_error\">*</span>Re-type Password</strong></td>
                          <td>:</td>
                          <td><input name=\"CurPasswordRT\" type=\"text\" class=\"input3\" id=\"CurPasswordRT\" value=\"\" /><input type=\"hidden\" name=\"chngepw\" value=\"Y\"/></td>
                        </tr>
                      </table>";
}

if($q=='captha'){
	function genRandomString() {
		$length = 8;
		$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
	   	for ($p = 0; $p < $length; $p++) {
			//echo $p;
			$string .= $characters[mt_rand(0, strlen($characters))];
		}	
		return $string;
		//echo $string;
	}
	$iRand=genRandomString();
	echo $val=chunk_split($iRand);
	echo "<input type=\"hidden\" name=\"captch\" value=\"$iRand\">";	
}
if($q=='dealerDis'){
 echo  $dealer="<label for=\"input3\" class=\"sideholderlbl\" style=\"width:230px;\">Old Password</label>
  <input name=\"vPasswordOld\" type=\"password\" id=\"vPasswordOld\" class=\"sideholderinput\" />
  <label for=\"input3\" class=\"sideholderlbl\" style=\"width:230px;\">New Password </label>
  <input name=\"vPassword1\" type=\"password\" id=\"vPassword1\" class=\"sideholderinput\" />
  <label for=\"input3\" class=\"sideholderlbl\" style=\"width:230px;\">Re-type Password </label>
  <input name=\"vPassword2\" type=\"password\" id=\"vPassword2\" class=\"sideholderinput\" /><br><br><br><br>";
  echo "<input type=\"hidden\" name=\"changedPasswordYes\" value=\"Y\" >";
}
?> 