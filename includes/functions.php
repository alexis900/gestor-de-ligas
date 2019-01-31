<?php
require_once("./db.php");
function DB ($host, $user, $passwd, $db) {
    return mysqli_connect($host, $user, $passwd, $db);
}

function head($title){
echo "<!DOCTYPE html>
<html lang=\"es\">
<head>
    <meta charset=\"UTF-8\">
    <link rel=\"stylesheet\" href=\"css/main.css\">
    <title>$title</title>
</head>
<body>
    <header>
        <nav>
            <h1>Gestor de ligas</h1>
        </nav>
    </header>
    <main>";
}

function enfrentamientos($nume) {
    $pares = (($nume % 2) == 0);
    if ($pares == false) {
        $nume++;
    }
    $numj = $nume - 1;
    $nump = $nume / 2;
    $t = array();
    $a = 1;
    for ($j = 0; $j < $numj; $j++) {
        for ($i = 0; $i < $nump; $i++) {
            $t[$j][$i] = array($a,0);
            $a = ($a==$nume-1)?1:$a+1;
        }
        for($i=$nump-1; $i>1; $i--){
            $t[$j][$i][1] = $a;
            $a = ($a==$nume-1)?1:$a+1;
        }
        $t[$j][1][1] = $a;
        $t[$j][0][1] = $nume;
    }
    if ($pares == false) {
        for ($jor = 0; $jor < $numj; $jor++) {
            array_shift($t[$jor]);
        }      
    }
    return $t;
}