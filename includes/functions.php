<?php
function head($title){
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/3.4.93/css/materialdesignicons.min.css">
    <title><?=$title?></title>
</head>
<body>
    <header class="accent-color">
        <h1><a href="index.php">Gestor de ligas</a></h1>
        <nav>
            <ul>
<?php
        if (!isset($_SESSION['username'])) {
?>
                <li>
                    <a href="login.php" class="mdi mdi-account">
                        <span>Iniciar sesión</span>
                    </a>
                </li>
<?php
        } else { 
?>              <li>
                    <a href="#">
                        <span><?= $_SESSION['username']?></span>
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="mdi mdi-account">
                        <span>Cerrar sesión</span>
                    </a>
                </li>
<?php
                }
?>
            </ul>
        </nav>
    </header>
    <main>

<?php
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

function transforma_date($fecha){
    $t = date_create($fecha);
    return date_format($t, 'd/m/Y');
}

function transforma_datetime($fecha){
    $t = date_create($fecha);
    return date_format($t, 'd/m/Y H:i:s');
}

function isSession(){
    $session = false;
    if (isset($_SESSION['username'])) {
        $session = true;
    }
    return $session;
}