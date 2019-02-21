<?php
require_once("db.php");
require_once("includes/functions.php");
session_start();
if (isSession()) {
    // Coge la ID del partido
    foreach($_GET as $get => $partido){
        foreach ($partido as $partidos => $valor) {
            $partidoId = $partidos;
        }
    }
    
    //Si el parametro está vacio, cambialo a null
    $pt1 = (!empty($_GET['partido'][$partidoId]['p1'])) ? $_GET['partido'][$partidoId]['p1'] : "null";
    $pt2 = (!empty($_GET['partido'][$partidoId]['p2'])) ? $_GET['partido'][$partidoId]['p2'] : "null";
    $fechac = $_GET['partido'][$partidoId]['date'] . " " . $_GET['partido'][$partidoId]['time'];
    
    $ligaId = $_GET['partido'][$partidoId]['liga'];
    $rondaId = $_GET['partido'][$partidoId]['ronda'];
    
    $sql = "update partido set puntos1 = $pt1, puntos2 = $pt2, fecha = '$fechac' where id = $partidoId";
    if (mysqli_query($con, $sql)) {
        header("Location: ver_ronda.php?liga=$ligaId&ronda=$rondaId");
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    header("Location: index.php");
}