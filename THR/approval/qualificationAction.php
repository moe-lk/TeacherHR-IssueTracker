<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$vID = $_REQUEST['vID'];
$vDes = $_REQUEST['vDes'];
$tblName = $_REQUEST['tblName'];
$mainID = $_REQUEST['mainID'];
$redirect_page = $_REQUEST['redirect_page'];
$status = $_REQUEST['AED'];	
$cat = $_REQUEST['cat'];

if($cat=='teacherQualification'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['Remarks'];
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$approveForID=$_REQUEST['approveForID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='TeacherQualification' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		   $sqlInsertTTApp="UPDATE TG_Request_Approve
           SET ApprovedStatus='P'
     WHERE RequestType='TeacherQualification' and RequestID='$approveForID' and ApproveProcessOrder='$nextOrder'";
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
		$sqlCountPending="Select id from TG_Request_Approve WHERE RequestType='TeacherQualification' and RequestID='$approveForID' and (ApprovedStatus='P' or ApprovedStatus='R')";
		$TotaRowsP=$db->rowCount($sqlCountPending);
		if($TotaRowsP==0){
			$sqlInsertTTApp="UPDATE TG_TeacherQualification
           SET IsApproved='Y' WHERE ID='$approveForID'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:teacherQualification-12.html");	
     exit() ;
}

?>