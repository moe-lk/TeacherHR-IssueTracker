<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
include '../db_config/connectionNEW.php';

$NICUser = $_SESSION["NIC"];
$accLevel = $_SESSION["accLevel"];
$loggedPositionName = $_SESSION['loggedPositionName'];
$accessRoleType = $_SESSION['AccessRoleType'];
$ProCode = $_SESSION['ProCodeU'];
$District = $_SESSION['DistCodeU'];
$ZONECODE = $_SESSION['ZoneCodeU'];

if ($_SESSION['NIC'] == '') {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['timeout'] + 30 * 60 < time()) {
    session_unset();
    session_destroy();
    session_start();
    header("Location: ../index.php");
    exit();
}

$checkAccessRol = "SELECT
	TeacherMast.NIC,
	StaffServiceHistory.PositionCode,
	StaffServiceHistory.InstCode,
	TeacherMast.CurServiceRef,
	TeacherMast.SurnameWithInitials,
	Passwords.AccessRole,
	Passwords.AccessLevel
FROM
	TeacherMast
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
INNER JOIN Passwords ON TeacherMast.NIC = Passwords.NICNo
WHERE
	TeacherMast.NIC = '$NICUser'";
$stmt = $db->runMsSqlQuery($checkAccessRol);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $loggedSchoolID = trim($row['InstCode']);
}


$sqlLevel = "SELECT [NICNo], [CurPassword], [LastUpdate], [AccessRole], [AccessLevel] FROM [dbo].[Passwords] where NICNo='$NICUser'";
$stmtTypL = $db->runMsSqlQuery($sqlLevel);
$rowL = sqlsrv_fetch_array($stmtTypL, SQLSRV_FETCH_ASSOC);
$loggedAccessLevel = trim($rowL['AccessLevel']);

$_SESSION['loggedSchool'] = $loggedSchoolID;
$loggedPositionName = $_SESSION['loggedPositionName'];


$menuIDs = explode("_", $menu);

if ($pageid == '0') {
    $activeMainMenuNo = "1";
    $activeSubMenu = "2";
} else {
    $activeMainMenuNo = $menuIDs[0];
    $activeSubMenu = $menuIDs[1];
}

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

// var_dump($_SESSION);
function calculation1($conn) {

    $sql = "SELECT CenCode, TchSubject1, Medium1, Count(TeachingDetails.NIC) AS AvailableTCH
    INTO #Table2$NICUser
    FROM TeachingDetails 
    INNER JOIN TeacherMast ON TeachingDetails.NIC = TeacherMast.NIC
    INNER JOIN StaffServiceHistory ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
    INNER JOIN CD_CensesNo ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
    GROUP BY CenCode, TchSubject1, Medium1";

    $sqlu = "UPDATE AvailableTeachers 
    SET 
    AvailableTeachers.AvailableTch = p.AvailableTCH,
    AvailableTeachers.RecordStatus = 1
    FROM AvailableTeachers av
    INNER JOIN #Table2 p
    ON av.CenCode = p.CenCode 
    Inner Join #Table2 q
    ON av.SubCode = q.TchSubject1
    Inner JOIN #Table2 r
    ON av.Medium = r.Medium1";

    // var_dump($conn);
    $stmt1 = sqlsrv_query($conn, $sql);
    $stmt2 = sqlsrv_query($conn, $sqlu);

    if( $stmt1 === false || $stmt2 === false) {
        die( print_r( sqlsrv_errors(), true) );
    }
    else{
        echo "<script LANGUAGE='JavaScript'>window.alert('Succesfully Updated');</script>";
    }
}

function calculation2($conn){
    $sqlc2 = "UPDATE ExcessDeposit
    SET ExcDep = a.ApprCardre - p.AvailableTch
    FROM ExcessDeposit ed
    INNER JOIN AvailableTeachers p
    ON ed.CenCode = p.CenCode 
    Inner Join AvailableTeachers q
    ON ed.SubCode = q.SubCode
    Inner JOIN AvailableTeachers r
    ON ed.Medium = r.Medium
    Inner Join ApprovedCardre a
    ON ed.CenCode = a.CenCode
    INNER JOIN ApprovedCardre b
    ON ed.SubCode = b.SubCode
    INNER JOIN ApprovedCardre c
    ON ed.Medium = c.Medium";
    $sqlc2 = sqlsrv_query($conn, $sqld);
    if( $sqlc2 === false) {
        // var_dump($conn);
        die( print_r( sqlsrv_errors(), true));
    }

    $sqld = "DROP TABLE  #Table2$NICUser";
    $stmtd = sqlsrv_query($conn, $sqld);
    if( $stmtd === false) {
        // var_dump($conn);
        die( print_r( sqlsrv_errors(), true) );
    }
}

if (isset($_GET['calc'])) {
    calculation1($conn);
}

if(isset($_GET['calc2'])){
    calculation2($conn);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/png" href="images/favicon.png">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- <title>National Education Management Information System | Ministry of Education Sri Lanka</title>  -->
        <title>National Education Management Information System | Ministry of Education Sri Lanka</title> 
        <!--<link href="css/emis.css" rel="stylesheet" type="text/css">-->
        <link href="css/emis.css" rel="stylesheet" type="text/css">
        <link href="../css/mStyle.css" rel="stylesheet" type="text/css" />
        <link href="css/category_tab.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/main_menu1.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/grid_style.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/flexigrid.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <style type="text/css">

        /*menu style*/
         .mcib_top{
            width:960px;
            height:33px;
            float:left;
            padding:1px 10px 1px 10px;
            font-size:12px;
            color:#FFF;
            font-weight:bold;
            line-height:34px;
            background:url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/block_inner_back.png);
        }
        .link1{
            color:<?php echo $theamMenuFontColor ?>;
            cursor: pointer;
        }

        .bton{
            background-color: #05B3FE;
            padding: 5px;
            border-radius: 5px;
            color: white;
        }
    </style>
    <body>
        <!-- Begin Page Content -->
        <form id="form1" name="form1" action="" method="POST">
            <div class="container">

                <div id="header_outer" style="background:url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/menu_back.gif) repeat-x">
                    <div id="header_inner">
                        <div id="header_top">
                            <div class="header_top_left">&nbsp;&nbsp; </div>
                            <div class="header_top_right">
                                <div id="admin_button" style="cursor:default;"><a href="#" id="admin_link" style="cursor:default;"><span style="cursor:default;"><?php echo $loggedPositionName; ?></span></a></div>
                                <!-- <div id="mail_button"><a href="#"></a><div id="mail_alert" class="alert">05</div></div>-->
                                <div id="user_welcome">Welcome <?php echo $_SESSION["fullName"]; ?>, &nbsp;&nbsp; <span class="link1" onClick="logoutForm('mail');">Logout?</span></div>
                            </div>
                        </div>
                        <div id="header_logo" style="margin-top:0px;"><img src="../images/header.png" width="960" height="150" /></div>

                        <div style="clear:both"></div>
                    </div>
                </div>
            </div>
        </form>

        <!--header end-->
        <div id="main_content_outer">
            <div id="main_content_inner">
                <div class="main_content_inner_block">
                    <?php include('../mainmenu.php') ?>
                    <div class="mcib_middle">
                        <div class="containerHeaderOne">
                            <div class="midArea"> 
                                <div class="productsAreaRight">
                                    <form>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">School Category</label>
                                        <select class="form-control" id="exampleFormControlSelect1">
                                        <?php
                                            $sql = "SELECT * FROM [MOENational].[dbo].[CD_CensesCategory]";
                                            $stmt = $db->runMsSqlQuery($sql);
                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                $AppId = trim($row['ID']);
                                                $AppName = $row['Category'];
                                                $seltebr = "";
                                                if($AppId == $AppCategory){
                                                    $seltebr = "selected";
                                                }
                                                echo "<option value=" . $AppId . ">". $AppName ."</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <a href='index.php?calc=true' class="bton">Caculate Available Teachers</a>
                                    <!-- <a href='index.php?hello=true'>Run PHP Function</a> -->
                                    <br>
                                    <br>
                                    <a href = "index.php?calc2=true" class="bton">Caculate Cardre</a>
                                    <br>
                                    <br>
                                    <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </body>
</html>
