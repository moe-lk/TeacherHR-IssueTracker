<?php
require_once '../error_handle.php';
// include "connection.php";
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
if ($_SESSION['NIC'] == '') {
    header("Location: ../index.php");
    exit();
}/* if($_SESSION['loggedSchoolSearch']==''){$_SESSION["ses_expire"]="Session expired. Select a school again.";header("Location: index.php") ;exit() ;}*/
include '../db_config/DBManager.php';
$db = new DBManager();
$timezone = "Asia/Colombo";
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set($timezone);
}
if ($_SESSION['timeout'] + 60 * 60 < time()) {
    session_unset();
    session_destroy();
    session_start();
    header("Location: ../index.php");
    exit();
}
$_SESSION["timeout"] = time();
$replace_data = array("'", "/", "!", "&", "*", " ", "-", "@", '"', "?", ":", "“", "”");
$replace_data_new = array("'", "/", "!", "&", "*", " ", "-", "@", '"', "?", ":", "“", "”", ".");
$pageid = $_GET["pageid"];
$menu = $_GET['menu'];
$tpe = $_GET['tpe'];
$id = $_GET['id'];
//  var_dump($id);
$fm = $_GET['fm'];
$lng = $_GET['lng'];
$curPage = $_GET['curPage'];
$ttle = $_GET['ttle'];
$ttle = str_replace("_", " ", $ttle);
/* //str_replace(",","",$amount); */
if ($pageid == '') {
    $pageid = "0";
}
$NICUser = trim($_SESSION["NIC"]);
$loggedSchool = trim($_SESSION['loggedSchool']);
$loggedPositionName = $_SESSION['loggedPositionName'];
$loggedSchool = trim($_SESSION['loggedSchoolSearch']);
$sqlList = "SELECT InstitutionName FROM CD_CensesNo where CenCode='$loggedSchool'";
$stmt = $db->runMsSqlQuery($sqlList);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$InstitutionName = $row['InstitutionName'];
$sqlTName = "SELECT SurnameWithInitials FROM TeacherMast where NIC='$id'";
$stmtTn = $db->runMsSqlQuery($sqlTName);
$rowTn = sqlsrv_fetch_array($stmtTn, SQLSRV_FETCH_ASSOC);
$SurnameWithInitialsT = $rowTn['SurnameWithInitials'];/* $nicNO='791231213V'; */
$querySaveVal = "";
$theamPath = "../cms/images/";
$theam = "theam1";
if ($theam == "theam1") {
    $theamMenuFontColor = "#0888e2";
    $theamMenuButtonColor = "#3973b1";
}
if ($theam == "theam2") {
    $theamMenuFontColor = "#d98813";
    $theamMenuButtonColor = "#3a2a07";
}
if ($theam == "theam3") {
    $theamMenuFontColor = "#c2379b";
    $theamMenuButtonColor = "#8839b1";
}
$url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$exUrl = explode('/', $url);
$folderLocation = count($exUrl) - 2;
$ModuleFolder = $exUrl[$folderLocation];
if ($pageid == 1 || $pageid == 2) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM TeacherMast WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 4) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM StaffQualification WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 5) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM TeacherSubject WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 8) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM StaffServiceHistory WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 9) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM Passwords WHERE (NICNo='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 30) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM Passwords WHERE (NICNo='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
$dateNow = date("Y/m/d");

include '../db_config/connectionNEW.php';



// var_dump($_REQUEST);
$nicNO = trim($_REQUEST['id']);


// $MedTch2 = $_REQUEST["MedTch2"];
// $MedTch3 = $_REQUEST["MedTch3"];
// $GradTch1 = $_REQUEST["GradTch1"];
// $GradTch2 = $_REQUEST["GradTch2"];
// $GradTch3 = $_REQUEST["GradTch3"];

if ($_REQUEST["TempMedTch1"] != 'Select') {
    $MedTch1 = $_REQUEST["TempMedTch1"];
} else {
    $MedTch1 = "";
}

if ($_REQUEST["TempMedTch2"] != 'Select') {
    $MedTch2 = $_REQUEST["TempMedTch2"];
} else {
    $MedTch2 = "";
}

if ($_REQUEST["TempMedTch3"] != 'Select') {
    $MedTch3 = $_REQUEST["TempMedTch3"];
} else {
    $MedTch3 = "";
}

if ($_REQUEST["TempGradTch1"] != 'Select') {
    $GradTch1 = $_REQUEST["TempGradTch1"];
} else {
    $GradTch1 = "";
}

if ($_REQUEST["TempGradTch2"] != 'Select') {
    $GradTch2 = $_REQUEST["TempGradTch2"];
} else {
    $GradTch2 = "";
}

if ($_REQUEST["TempGradTch3"] != 'Select') {
    $GradTch3 = $_REQUEST["TempGradTch3"];
} else {
    $GradTch3 = "";
}

if ($_REQUEST["TempSubTch1"] != 'Select') {
    $SubTch1 = $_REQUEST["TempSubTch1"];
} else {
    $SubTch1 = "";
}

if ($_REQUEST["TempSubTch2"] != 'Select') {
    $SubTch2 = $_REQUEST["TempSubTch2"];
} else {
    $SubTch2 = "";
}

if ($_REQUEST["TempSubTch3"] != 'Select') {
    $SubTch3 = $_REQUEST["TempSubTch3"];
} else {
    $SubTch3 = "";
}
$otherTch1 = $_REQUEST["TempotherTch1"];
$otherTch2 = $_REQUEST["TempotherTch2"];
$otherTch3 = $_REQUEST["TempotherTch3"];

if ($_REQUEST["Tempotherspecial"] != 'Select') {
    $otherspecial = $_REQUEST["Tempotherspecial"];
} else {
    $otherspecial = "";
}
$state = '0';
// $otherspecial = $_REQUEST["otherspecial"];

$id = $_REQUEST["id"];
    // echo "gg" . $MedTch1;
    $today = date("Y/m/d");

    $SQL1 = "SELECT TOP(1) * FROM TeacherMast
            join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID
            join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
            WHERE StaffServiceHistory.NIC = '$nicNO' 
            ORDER BY StaffServiceHistory.AppDate DESC";

    $stmt1 = $db->runMsSqlQuery($SQL1);
    while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
        $SchType = Trim($row1['SchoolType']);
    }

$sqlCheck = "SELECT * FROM TeachingDetails WHERE NIC = '$nicNO' AND RecStatus = '1'";
$TotalRows = $db->rowCount($sqlCheck);

    /* Begin transaction. */  
if( sqlsrv_begin_transaction($conn) === false )   
{   
     echo "Could not begin transaction.\n";  
     die( print_r( sqlsrv_errors(), true));  
}

    $sql = "UPDATE [dbo].[Temp_TeachingDetails]
    SET [TchSubject1] = ?
       ,[TchSubject2] = ?
       ,[TchSubject3] = ?
       ,[Other1] = ?
       ,[Other2] = ?
       ,[Other3] = ?
       ,[Medium1] = ?
       ,[Medium2] = ?
       ,[Medium3] = ?
       ,[GradeCode1] = ?
       ,[GradeCode2] = ?
       ,[GradeCode3] = ?
       ,[OtherSpecial] = ?
       ,[SchoolType] = ?
       ,[RecStatus] = ?
       ,[RecordLog] = ?
       ,[LastUpdate] = ?
  WHERE NIC = '$nicNO'";

    // $stmt = $db->runMsSqlQuery($sql);
    $params = array( $SubTch1, $SubTch2, $SubTch3, $otherTch1, $otherTch2, $otherTch3, $MedTch1, $MedTch2, $MedTch3, $GradTch1, $GradTch2, $GradTch3, $otherspecial, $SchType,$state,$NICUser,$dateNow);
    $stmt = sqlsrv_query( $conn, $sql, $params );
    // var_dump($stmt);
    if($stmt){
        sqlsrv_commit($conn);
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('Succesfully Updated');
        window.location.href='teaching_subj-12--$nicNO.html';
        </script>");
    } else {
        sqlsrv_rollback( $conn );
        echo "Updates rolled back.<br />";
        var_dump($params);
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('Update Failed!, Please try again.');
        window.location.href='teaching_subj-12--$nicNO.html';
        </script>");
    }

