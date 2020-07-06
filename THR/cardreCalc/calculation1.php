<!DOCTYPE html>
<html>
<style>
#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 1%;
  height: 30px;
  background-color: #4CAF50;
}
.img-box{
    text-align: center;
    padding: 50px; 
}
</style>
<body onload="move()">

<div class="img-box"> 
    <img src="images/unnamed.gif";>
<div>

<!-- <h1>Processing...</h1>

<div id="myProgress">
  <div id="myBar"></div>
</div> -->

<script>
// var i = 0;
// function move() {
//   if (i == 0) {
//     i = 1;
//     var elem = document.getElementById("myBar");
//     var width = 1;
//     var id = setInterval(frame, 10);
//     function frame() {
//       if (width >= 100) {
//         clearInterval(id);
//         i = 0;
//       } else {
//         width++;
//         elem.style.width = width + "%";
//       }
//     }
//   }
// }
</script>

</body>
</html>
<?php
    include '../db_config/connectionNEW.php';
    $SchType = $_REQUEST['SchType'];
    $NICUser = $_REQUEST['NICUser'];

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
    INNER JOIN #Table2$NICUser p
    ON av.CenCode = p.CenCode 
    Inner Join #Table2$NICUser q
    ON av.SubCode = q.TchSubject1
    Inner JOIN #Table2$NICUser r
    ON av.Medium = r.Medium1";

    // var_dump($conn);
    $stmt1 = sqlsrv_query($conn, $sql);
    $stmt2 = sqlsrv_query($conn, $sqlu);

    if( $stmt1 === false || $stmt2 === false) {
        die( print_r( sqlsrv_errors(), true) );
    }
    else{
        echo "<script LANGUAGE='JavaScript'>
        window.alert('Succesfully Updated');
        window.location.href='index.php';
        </script>"; 
    }
    
?>