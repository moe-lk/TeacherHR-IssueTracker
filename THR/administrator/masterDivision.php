<!----><link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
if(isset($_POST["FrmSrch"]) || $fm==''){
	$InstitutionNameSrc=$_REQUEST['InstitutionNameSrc'];
	$sqlSrch="SELECT * FROM CD_Division where InstitutionName!=''";  
	if($InstitutionNameSrc)$sqlSrch.=" and InstitutionName like '%$InstitutionNameSrc%'";
	$sqlSrch.=" order by InstitutionName asc";
	$stmtP = $db->runMsSqlQuery($sqlSrch);
	$TotaRows=$db->rowCount($sqlSrch);
	//if($TotaRows==0)$fm="A";
	//$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
	 //echo $TotaRows=$db->rowCount($stmtP);echo $sqlSrch;
}
if($fm=='E'){
	$sqlSrch="SELECT * FROM CD_Division where CenCode='$id'";  
	$stmtE= $db->runMsSqlQuery($sqlSrch);
	$rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
	$CenCode = trim($rowE['CenCode']);
	$InstitutionName = trim($rowE['InstitutionName']);
	$DistrictCode= trim($rowE['DistrictCode']);
	$ZoneCode= trim($rowE['ZoneCode']);
}

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$addEdit=$_REQUEST['AED'];
	$CenCode=$_REQUEST['CenCode'];
	$InstitutionName=trim($_REQUEST['InstitutionName']);
	$DistrictCode=trim($_REQUEST['DistrictCode']);
	$ZoneCode=trim($_REQUEST['ZoneCode']);
	$dateU=date('Y-m-d H:i:s');
	if($addEdit=="A")$RecordLog="Add by $NICUser on $dateU";
	if($addEdit=="E")$RecordLog="Edit by $NICUser on $dateU";
	
    if ($InstitutionName == "") {
        $msg.= "Please enter the Division Name.<br>";
    }
	if($msg==''){
		if($addEdit=='A'){
			$countSql="SELECT * FROM CD_Division where CenCode='$CenCode'";
			$isAvailable=$db->rowAvailable($countSql);
			if($isAvailable==1){
				$msg.= "Duplicate Censes Code.<br>";
			}else{
				$queryMainSave = "INSERT INTO CD_Division
			   (CenCode,InstitutionName,DistrictCode,ZoneCode)
		 VALUES
			   ('$CenCode','$InstitutionName','$DistrictCode','$ZoneCode')";
				$db->runMsSqlQuery($queryMainSave);	
			}
		}else if($addEdit=='E'){
			$queryMainUpdate = "UPDATE CD_Division SET CenCode='$CenCode',InstitutionName='$InstitutionName', DistrictCode='$DistrictCode',ZoneCode='$ZoneCode' WHERE CenCode='$CenCode'";
			   
			$db->runMsSqlQuery($queryMainUpdate);
		}
	}
	$fm="";
	$sqlSrch="SELECT * FROM CD_Division where InstitutionName!='' order by InstitutionName asc";  
	$stmtP = $db->runMsSqlQuery($sqlSrch);
}

?>
<form method="post" action="<?php echo $ttle ?>-11-<?php echo $menu ?>.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  
  <div class="mcib_middle1" style="width:700px;">
    <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
    </div>
    <?php }?>
<table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
			    <td valign="top" style="border-bottom:1px; border-bottom-style:solid;"><table width="100%" cellspacing="2" cellpadding="2">
                    
                    <tr>
                      <td width="24%">Division Name :</td>
                      <td width="36%"><input name="InstitutionNameSrc" type="text" class="input2_n" id="InstitutionNameSrc" value="<?php echo $InstitutionNameSrc ?>"/></td>
                      <td width="13%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                      <td width="14%" align="right" valign="middle" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>--A.html"><img src="../cms/images/addnew.png" alt="" width="90" height="26" /></a></td>
                      <td width="13%" align="right" valign="middle" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>.html"><img src="../cms/images/clearN.png" alt="" width="80" height="26" /></a></td>
                    </tr>
                    </table></td>
      </tr>
			  <tr>
			    <td valign="top"><span style="color:#090; font-weight:bold;"><?php if($fm=='A')echo "Insert the data"; if($fm=='E') echo "Modify the existing details";?></span>&nbsp;</td>
      </tr>
     
	  <tr>
                  <td width="56%" valign="top">
                  <?php if($fm=='E' || $fm=='A'){?>
                  <table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="25%">Code <span class="form_error_sched">*</span></td>
                      <td width="2%">:</td>
                      <td width="73%">
                      <input name="CenCode" type="text" class="input3" id="CenCode" value="<?php echo $CenCode ?>" <?php if($fm=='E'){?>readonly="readonly"<?php }?>/>
                      <input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm; ?>" />
                      <input type="hidden" name="id" value="<?php echo $id; ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                      <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                      <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                      <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Division Name<span class="form_error_sched"> *</span></td>
                      <td>:</td>
                      <td><input name="InstitutionName" type="text" class="input2" id="InstitutionName" value="<?php echo $InstitutionName ?>"/></td>
                    </tr>
                    <tr>
                      <td>District</td>
                      <td>:</td>
                      <td><select class="select2a_n" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');">
                        <!--<option value="">School Name</option>-->
                        <?php
                            $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['DistCode']);
								$DistNamed=$row['DistName'];
								$seltebr="";
								if($DistCoded==$DistrictCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Zone</td>
                      <td>:</td>
                      <td><div id="txt_zone"><select class="select2a_n" id="ZoneCode" name="ZoneCode" onchange="Javascript:show_division('divisionlst', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);">
                        <!--<option value="">School Name</option>-->
                        <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCode' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$ZoneCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
                      </select></div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
                    <?php }?>
        </td>
        </tr>
        <?php if(isset($_POST["FrmSrch"]) || $fm==''){ ?>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="7%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="17%" align="center" bgcolor="#999999">Code</td>
                        <td width="56%" align="center" bgcolor="#999999">Division Name</td>
                        <td width="20%" align="center" bgcolor="#999999">Modify</td>
                      </tr>
                      <?php 
					  $i=1;
                      while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
							$CenCode=trim($rowP['CenCode']);
							$InstitutionName=trim($rowP['InstitutionName']);
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $CenCode ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="<?php echo "$ttle-$pageid-$menu-$CenCode-E.html";?>">Click</a></td>
                      </tr>
                      <?php }?>
                    </table></td>
          </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
               <?php }?>
          </table>
           </div>
    
    </form>