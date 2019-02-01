<?php
require_once("db.php");
echo "<pre>";
print_r($_GET);
echo "</pre>";

foreach($_GET as $get => $partido){
    foreach ($partido as $partidos => $valor) {
        $partidoId = $partidos;
    }
}

$pt1 = (!empty($_GET['partido'][$partidoId]['p1'])) ? $_GET['partido'][$partidoId]['p1'] : "null";
$pt2 = (!empty($_GET['partido'][$partidoId]['p2'])) ? $_GET['partido'][$partidoId]['p2'] : "null";
$fechac = $_GET['partido'][$partidoId]['date'] . " " . $_GET['partido'][$partidoId]['time'];
echo $sql = "update partido set puntos1 = $pt1, puntos2 = $pt2, fecha = '$fechac' where id = $partidoId";
if (mysqli_query($con, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($con);
}