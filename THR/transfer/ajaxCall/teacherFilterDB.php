<?php

session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include '../../db_config/DBManager.php';
$db = new DBManager();

$nicNO = $_SESSION["NIC"];
$accLevel = $_SESSION["AccessLevel"];
$RequestType = $_REQUEST["RequestType"];

if ($RequestType == "getQuliDetails") {
    $str = "";
    $str.= "<tr id=\"q1\"><td><select onchange=\"loadQulification(this,this.value);\" name=\"cmbQuliCateogry[]\"><option></option>";
    $sql = "SELECT Code, Description, [Level], RecordLog
FROM CD_QualificationCategory
ORDER BY [Level] DESC";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["Level"] . ">" . $row["Description"] . "</option>";
    }

    $str.="</select></td>";


    $str.="<td><select name=\"cmbQuliSymbol[]\"><option></option>
        <option value=\">\">></option>
        <option value=\"=\">=</option>
        <option value=\"<\"><</option>
        </select></td>";
    $str.="<td><select name=\"cmbQulification[]\"><option></option>";
    $sql = "SELECT Qcode, Description
FROM CD_Qualif
WHERE (Qcode != '') ORDER BY Description";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["Qcode"] . ">" . $row["Description"] . "</option>";
    }


    $str.="</select></td>";
    $str.="<td><a href=\"javascript:void(0);\" onclick=\"addQulificationRow();\"><img src=\"images/add.png\" width=\"14\" height=\"14\"/></a></td>
           </tr>";
    echo $str;
}

if ($RequestType == "getTecDetails") {
    $str = "";
    $str.= "<tr><td><select name=\"cmbSType[]\"><option value=\"\">-Select-</option>";

    $sql = "SELECT SubType, SubTypeName
FROM CD_SubjectTypes
ORDER BY SubTypeName";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["SubType"] . ">" . $row["SubTypeName"] . "</option>";
    }
    $str.="</select></td>";


    $str.= "<td><select name=\"cmbSubject[]\"><option value=\"\">-Select-</option>";
    $sql = "SELECT SubCode, SubjectName
FROM CD_Subject
ORDER BY SubjectName";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["SubCode"] . ">" . $row["SubjectName"] . "</option>";
    }
    $str.= "</select></td>";


    $str.= "<td><select name=\"cmbSGrade[]\"><option value=\"\">-Select-</option>";
    $sql = "SELECT GradeCode, LTRIM(GradeName) AS GradeName
FROM CD_SecGrades
WHERE (GradeCode <> N'')
ORDER BY GradeName";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["GradeCode"] . ">" . $row["GradeName"] . "</option>";
    }
    $str.= "</select></td>";

    $str.= "<td><a href=\"javascript:void(0);\" onclick=\"addTeachingRow();\"><img src=\"images/add.png\" width=\"14\" height=\"14\"/></a></td>
           </tr>";
    echo $str;
}

if ($RequestType == "getSerDetails") {
    $str = "";
    $str.= "<tr>";
    $str.= "<td><select name=\"cmbPosition[]\"><option value=\"\">-Select-</option>";
    $sql = "SELECT Code, PositionName
FROM CD_Positions
WHERE (Code <> N'')
ORDER BY PositionName";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["Code"] . ">" . $row["PositionName"] . "</option>";
    }
    $str.= "</select></td>";


    $str.= "<td><select name=\"cmbServiceType[]\"><option value=\"\">-Select-</option>";
    $sql = "SELECT ServCode, LTRIM(ServiceName) AS serviceName
FROM CD_Service
WHERE (ServCode <> N'')";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["ServCode"] . ">" . $row["serviceName"] . "</option>";
    }
    $str.= "</select></td>";


    $str.= "<td><select name=\"cmbWrkStatus[]\"><option value=\"\">-Select-</option>";
    $sql = "SELECT Code, WorkStatus
FROM CD_WorkStatus
WHERE (Code <> N'')
ORDER BY WorkStatus";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["Code"] . ">" . $row["WorkStatus"] . "</option>";
    }
    $str.= "</select></td>";


    $str.= "<td><a href=\"javascript:void(0);\" onclick=\"addServiceRow();\"><img src=\"images/add.png\" width=\"14\" height=\"14\"/></a></td>
           </tr>";
    echo $str;
}

if ($RequestType == "getQulification") {
    $level = $_GET["level"];
    $str = "";

    $str.= "<select name=\"cmbQulification[]\"><option value=\"\">-Select-</option>";
    $sql = "SELECT Qcode, Description
FROM CD_Qualif
WHERE (Qcode != '') AND (Level = N'" . $level . "') ORDER BY Description";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $str.="<option value=" . $row["Qcode"] . ">" . $row["Description"] . "</option>";
    }
    $str.="</select>";
    echo $str;
}


if ($RequestType == "getDataAccordingToSCType") {
    $cmbSchoolType = $_POST["cmbSchoolType"];
    $LOGGEDUSERID = $nicNO; // 172839946V
    $ACCESSLEVEL = $accLevel; //     3000
    $DIVCODE = null;
    $ZONECODE = null;
    $DISTCODE = null;
    $ProCode = null;
    $District = null;
    $Division = null;

    if ($cmbSchoolType == "")
        $cmbSchoolType = null;


    $params1 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($ProCode, SQLSRV_PARAM_IN),
        array($DISTCODE, SQLSRV_PARAM_IN),
        array($ZONECODE, SQLSRV_PARAM_IN),
        array($Division, SQLSRV_PARAM_IN)
    );

//    foreach ($params1 as $name => $locations) {
//        foreach ($locations as $location) {
//            echo "ArrName {$name} and title {$location}<br />";
//        }
//    }

    $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }




    $result = array();
    $result[0] = $dataSchool;
    echo json_encode($result);
}

if ($RequestType == "getDataAccordingToProvince") {
    //$cmbSchoolType = $_POST["cmbSchoolType"];
    $cmbProvince = trim($_POST["cmbProvince"]);
   // $LOGGEDUSERID = $nicNO; // 172839946V
    //$ACCESSLEVEL = $accLevel; //     3000
    $DIVCODE = null;
    $ZONECODE = null;
    $DISTCODE = null;
    $ProCode = null;
    $District = null;
    $Division = null;

    if ($cmbProvince == "")
        $cmbProvince = null;
    if ($cmbSchoolType == "")
        $cmbSchoolType = null;
	
	$params = array(
        //array($LOGGEDUSERID, SQLSRV_PARAM_IN),
       // array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN)
    );
	
	$params1 = array(
        //array($LOGGEDUSERID, SQLSRV_PARAM_IN),
       // array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN)
    );
	/* 
    $params1 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),        
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($DISTCODE, SQLSRV_PARAM_IN),
        array($ZONECODE, SQLSRV_PARAM_IN),
        array($Division, SQLSRV_PARAM_IN)
    );

    $params2 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($District, SQLSRV_PARAM_IN)
    );
    
    
    $params4 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($DISTCODE, SQLSRV_PARAM_IN),
        array($ZONECODE, SQLSRV_PARAM_IN),
        array($Division, SQLSRV_PARAM_IN)
    ); */

    //$sql = "{call SP_TG_GetDistrictFor_LoggedUser( ?, ?, ?)}";
	$sql = "{call SP_TG_GetDistrictFor_SelectedProvince(?)}";
    $dataDistrict = "<option value=\"\">Select District</option>";
    $stmt = $db->runMsSqlQuery($sql, $params);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataDistrict.= '<option value=' . $row['DistCode'] . '>' . $row['DistName'] . '</option>';
    }

    /* $sql = "{call SP_TG_GetZonesFor_LooggedUser( ?, ?, ? ,?)}";
    $dataZone = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params2);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataZone.= '<option value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
    }


    $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }

    $sql = "{call SP_TG_GetDivisionFor_LooggedUser( ?, ?, ? , ?, ?)}";
    $dataDivision = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params4);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataDivision.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }

    //$dataSchool =  "<option value=\"\">All</option>";

    $result = array();
    $result[0] = $dataDistrict;
    $result[1] = $dataZone;
    $result[2] = $dataSchool;
    $result[3] = $dataDivision;
    echo json_encode($result); */
	
	
	
	/**/ $sql = "{call SP_TG_GetSchoolsFor_SelectedProvince(?)}";
    $dataSchools = "<option value=\"\">Select</option>";
	
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$cenCOdee=trim($row['CenCode']);
		$InstitutionName=$row['InstitutionName'];
		
        $dataSchools.= "<option value=\"$cenCOdee\">$InstitutionName</option>";
       // $dataSchools.= '<option value=' . $row['CenCode'] . '>' . addslashes($row['InstitutionName']) . '</option>';
    } 
	
	//$dataSchools = "<option value=\"\">Select</option>";
	
	$result = array();
    $result[0] = $dataDistrict;
	$result[1] = "";
    $result[2] = "";
    $result[3] = "";
	$result[4] = $dataSchools;
	//$result[5] = $dataSchool;
	//$result[6] = $dataSchool;
	//$result[7] = $dataSchool;
	//$result[8] = $dataSchool;
    echo json_encode($result);
}


if ($RequestType == 'getDataAccordingToDistrict') {
  //  $cmbSchoolType = $_POST["cmbSchoolType"];
    $cmbProvince = $_POST["cmbProvince"];
    $cmbDistrict = $_POST["cmbDistrict"];
    //$LOGGEDUSERID = $nicNO; // 172839946V
   // $ACCESSLEVEL = $accLevel; // 
    $ZONECODE = null;
    $Division = null;


    if ($cmbProvince == "")
        $cmbProvince = null;
    if ($cmbDistrict == "")
        $cmbDistrict = null;
    if ($cmbSchoolType == "")
        $cmbSchoolType = null;
	
	$params = array(
        array($cmbDistrict, SQLSRV_PARAM_IN)
    );
	
	$params1 = array(
        array($cmbDistrict, SQLSRV_PARAM_IN)
    );
	
	$sql = "{call SP_TG_GetZoneFor_SelectedDistrict(?)}";
    $dataZone = "<option value=\"\">Select Zone</option>";
    $stmt = $db->runMsSqlQuery($sql, $params);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataZone.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }
	
	$sql = "{call SP_TG_GetSchoolsFor_SelectedDistrict(?)}";
    $dataSchools = "<option value=\"\">Select</option>";
	
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$cenCOdee=trim($row['CenCode']);
		$InstitutionName=$row['InstitutionName'];
		
        $dataSchools.= "<option value=\"$cenCOdee\">$InstitutionName</option>";
       // $dataSchools.= '<option value=' . $row['CenCode'] . '>' . addslashes($row['InstitutionName']) . '</option>';
    } 
	
	//$dataSchools = "<option value=\"\">Select</option>";
	
	$result = array();
    $result[0] = "";
	$result[1] = $dataSchools;
	$result[2] = $dataZone;
    echo json_encode($result);
	
    /* $params5 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN)
    );

    $params1 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($ZONECODE, SQLSRV_PARAM_IN),
        array($Division, SQLSRV_PARAM_IN)
    );
    
    $params4 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($ZONECODE, SQLSRV_PARAM_IN),
        array($Division, SQLSRV_PARAM_IN)
    );

    $sql = "{call SP_TG_GetDivisionFor_LooggedUser( ?, ?, ? , ?, ?)}";
    $dataDivision = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params4);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataDivision.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }

    $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }

    $sql = "{call SP_TG_GetZonesFor_LooggedUser( ?, ?, ? ,?)}";
    $dataZone = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params5);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataZone.= '<option value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
    } */
    //$dataSchool = "<option value=\"\">All</option>";

    /* $result = array();

    $result[0] = $dataDivision;
    $result[1] = $dataSchool;
    $result[2] = $dataZone;
    echo json_encode($result); */
}

if ($RequestType == "getDataAccordingToZone") {
    //$cmbSchoolType = $_POST["cmbSchoolType"];
    //$cmbProvince = $_POST["cmbProvince"];
    //$cmbDistrict = $_POST["cmbDistrict"];
    $cmbZone = $_POST["cmbZone"];

    if ($cmbProvince == "")
        $cmbProvince = null;
    if ($cmbDistrict == "")
        $cmbDistrict = null;
    if ($cmbZone == "")
        $cmbZone = null;
    if ($cmbSchoolType == "")
        $cmbSchoolType = null;

    ///$LOGGEDUSERID = $nicNO; // 172839946V
   // $ACCESSLEVEL = $accLevel; // 
   // $Division = null;

	$params = array(
        array($cmbZone, SQLSRV_PARAM_IN)
    );
	
	$params1 = array(
        array($cmbZone, SQLSRV_PARAM_IN)
    );
	
	$sql = "{call SP_TG_GetDivisionFor_SelectedZone(?)}";
    $dataZone = "<option value=\"\">Select Division</option>";
    $stmt = $db->runMsSqlQuery($sql, $params);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataZone.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }
	
	$sql = "{call SP_TG_GetSchoolsFor_SelectedZone(?)}";
    $dataSchools = "<option value=\"\">Select</option>";
	
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$cenCOdee=trim($row['Expr1']);
		$InstitutionName=$row['Expr2'];
		
        $dataSchools.= "<option value=\"$cenCOdee\">$InstitutionName</option>";
    }  
	
	//$dataSchools = "<option value=\"\">Select</option>";
	$result = array();
    $result[0] = $dataZone;
	$result[1] = $dataSchools;
    echo json_encode($result);

   /*  $params1 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($cmbZone, SQLSRV_PARAM_IN),
        array($Division, SQLSRV_PARAM_IN)
    );
    
    $params2 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($cmbZone, SQLSRV_PARAM_IN),
        array($Division, SQLSRV_PARAM_IN)
    );

    $sql = "{call SP_TG_GetDivisionFor_LooggedUser( ?, ?, ? , ?, ?)}";
    $dataDivision = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params2);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataDivision.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }

    $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }


    $result = array();

    $result[0] = $dataDivision;
    $result[1] = $dataSchool;
    echo json_encode($result); */
}


if ($RequestType == 'getDataAccordingToSchool') {
    //$cmbSchoolType = $_POST["cmbSchoolType"];
    //$cmbProvince = $_POST["cmbProvince"];
    //$cmbDistrict = $_POST["cmbDistrict"];
    //$cmbZone = $_POST["cmbZone"];
    $cmbDivision = $_POST["cmbDivision"];

    if ($cmbProvince == "")
        $cmbProvince = null;
    if ($cmbDistrict == "")
        $cmbDistrict = null;
    if ($cmbZone == "")
        $cmbZone = null;
    if ($cmbDivision == "")
        $cmbDivision = null;
    if ($cmbSchoolType == "")
        $cmbSchoolType = null;

    //$LOGGEDUSERID = $nicNO; // 172839946V
  //  $ACCESSLEVEL = $accLevel; // 



    $params1 = array(
       /*  array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($cmbZone, SQLSRV_PARAM_IN), */
        array($cmbDivision, SQLSRV_PARAM_IN)
    );

    $sql = "{call SP_TG_GetSchoolsFor_SelectedDivision( ?)}";
    $dataSchool = "<option value=\"\">Select</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }


    $result = array();

    $result[0] = $dataSchool;
    echo json_encode($result);
}


if ($RequestType == 'getBiographicalDetail') {
    $bioItemVal = $_POST["bioItemVal"];

    switch ($bioItemVal) {
        case "G":
            $sql = "SELECT
  GenderCode AS feildCode,
  [Gender Name] AS feildName
FROM CD_Gender
WHERE (GenderCode <> N'')";
            break;
        case "E":
            $sql = "SELECT
  Code AS feildCode,
  EthnicityName AS feildName
FROM CD_nEthnicity
WHERE (Code <> N'')";
            break;
        case "R":
            $sql = "SELECT
  Code AS feildCode,
  ReligionName AS feildName
FROM CD_Religion
WHERE (Code <> N'')";
            break;
        case "C":
            $sql = "SELECT
  Code AS feildCode,
  CivilStatusName AS feildName
FROM CD_CivilStatus
WHERE (Code <> N'')";
            break;
    }
    $bioDeatails = array();

    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $bioDeatails[] = $row;
    }
    echo json_encode($bioDeatails);
}
?>
