<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
if (!isset($_GET['liga']) || empty($_GET['liga']) || !is_numeric($_GET['liga']) || !isset($_GET['ronda']) || empty($_GET['ronda']) || !is_numeric($_GET['ronda'])) {
    header("Location: index.php");
}
$ligaId = $_GET['liga'];
$rondaId = $_GET['ronda'];

// Selecciona el nombre de la liga
$sql = "select nombre from liga where id=$ligaId";
$rst = mysqli_query($con,$sql);
$row = mysqli_fetch_row($rst);
$nombreLiga = $row[0];

$sql = "select distinct count(*) from partido where liga_id = $ligaId";
$rst = mysqli_query($con,$sql);
$row = mysqli_fetch_row($rst);
$numRondas = $row[0];

if($rondaId <= 0 || $rondaId > $numRondas){
    header("Location: index.php");
}

// Proporciona el título de la página en el formato
$title = $nombreLiga . " - Ronda $rondaId";
head($title);
echo "<h2>$title</h2>";

// Selecciona la información que necessitaremos
$sql = "select p.id, e1.nombre, e2.nombre, puntos1, puntos2, fecha from partido p 
inner join equipo e1 on e1.id = p.equipo1_id 
inner join equipo e2 on e2.id = p.equipo2_id 
where p.ronda_num = $rondaId and p.liga_id = $ligaId";
$rst = mysqli_query($con,$sql);
$par = 0;
while($row = mysqli_fetch_row($rst)){
    $partido[$par]['id'] = $row[0];
    $partido[$par]['e1'] = utf8_encode($row[1]);
    $partido[$par]['e2'] = utf8_encode($row[2]);
    $partido[$par]['p1'] = $row[3];
    $partido[$par]['p2'] = $row[4];
    $partido[$par]['fecha'] = $row[5];
    if ($partido[$par]['p1'] == null && $partido[$par]['p2'] == null) {
        $partido[$par]['p1'] = 0;
        $partido[$par]['p2'] = 0;
    }
    $par++;
}

if ($par > 0) {

?>
<!-- Comienza la tabla -->
<table>
    <tr>
        <th>Equipo 1</th>
        <th colspan="2">Puntuación</th>
        <th>Equipo 2</th>
        <?php

            //Si hay alguna fecha disponible, se mostrará la columna
            $mostrarFecha = false;
            for($i = 0; $i < $par;$i++){
                if ($partido[$i]['fecha'] != null || isset($_GET['partido'])) {
                    $mostrarFecha = true;
                }
            }

            if($mostrarFecha){
                echo "<th>Fecha del partido</th>";
            }
            ?>
    </tr>
    <?php
       //Si no tiene $_GET['partido'], se mostrarán todos los partidos. Si no, mostrará solo el partido seleccionado
        if (!isset($_GET['partido'])) {
            for ($i=0; $i < $par; $i++) {
                ?>
                <tr>
                    <td><?=$partido[$i]['e1']?></td>
                    <td><?= $partido[$i]['p1'] == null ? $partido[$i]['p1'] : 0; ?></td>
                    <td><?= $partido[$i]['p2'] == null ? $partido[$i]['p2'] : 0; ?></td>
                    <td><?=$partido[$i]['e2']?></td>
                    <?php
                        if ($partido[$i]['fecha'] == null) {
                        echo "<td>Fecha no disponible</td>";
                        } else {
                        echo "<td>" . transforma_datetime($partido[$i]['fecha']) . "</td>";
                        }
                    
                    if (isset($_SESSION['username'])) {
                        if ($partido[$i]['p1'] == null || $partido[$i]['p2'] == null || $partido[$i]['fecha'] == null) {?>
                            <td><a href=ver_ronda.php?liga=<?=$ligaId?>&ronda=<?=$rondaId?>&partido=<?=$i?>>Modifica</a></td>
                        <?php
                        }
                    }
                echo "</tr>";
            }
    } else {
        
        if (isset($_SESSION['username'])) {
            if ($_GET['partido'] == null || $_GET['partido'] > $par) {
                header("Location: ver_ronda.php?liga=$ligaId&ronda=$rondaId");
            }
        ?>
    <form action="update_ronda.php" method="get">
        <?php
        $partidoId = $_GET['partido'];
        $partId = $partido[$partidoId]['id'];

        //Si no se selecciona ningún puntuaje, se neviará null
        $p1 = $partido[$partidoId]['p1'] == null ? null : $partido[$partidoId]['p1'] ;
        $p2 = $partido[$partidoId]['p2'] == null ? null : $partido[$partidoId]['p2'] ;

        //Divide los apartados de la fecha
        $fecha_completa = $partido[$partidoId]['fecha'] == null ? null : $partido[$partidoId]['fecha'] ;
        if ($fecha_completa != null) {
            $fecha = date_format(date_create($fecha_completa), 'Y-m-d');
            $hora = date_format(date_create($fecha_completa), 'H:i:s');
        }
?>
        <tr>
            <td><?=$partido[$partidoId]['e1']?></td>
<?php
            if ($p1 > 0) {
                echo "<td>" . $p1 . "</td>";
                echo "<input type=\"hidden\" name=\"partido[$partId][p1]\" id=\"p1\ value=\"$p1\"/>";
            } else {
                echo "<td><input type=\"number\" name=\"partido[$partId][p1]\" id=\"p1\" min=\"0\" max=\"100\"></td>";
            }

            if ($p2 > 0) {
                echo "<td>" . $p2 . "</td>";
                echo "<input type=\"hidden\" name=\"partido[$partId][p2]\" id=\"p2\ value=\"$p2\"/>";
            } else {
                echo "<td><input type=\"number\" name=\"partido[$partId][p2]\" id=\"p2\" min=\"0\" max=\"100\"></td>";
            }
            echo "<td>" . $partido[$partidoId]['e2'] . "</td>";

            if ($fecha_completa == null) {
                echo "<td><input type=\"date\" name=\"partido[$partId][date]\" id=\"date\" value=\"". date('Y-m-d') . "\">";
                echo "<input type=\"time\" name=\"partido[$partId][time]\" id=\"time\" value=\"". date("H:i") ."\"></td>";
            } else {
                echo "<td>$fecha_completa</td>";
                echo "<input type=\"hidden\" name=\"partido[$partId][date]\" value=\"$fecha\">";
                echo "<input type=\"hidden\" name=\"partido[$partId][time]\" value=\"$hora\">";
            }

            if($p1 != null && $p2 != null && $fecha != null){
            header("Location: ver_ronda.php?liga=$ligaId&ronda=$rondaId");
        } else {
            echo "<input type=\"hidden\" name=\"partido[$partId][ronda]\" value=\"$rondaId\">";
            echo "<input type=\"hidden\" name=\"partido[$partId][liga]\" value=\"$ligaId\">";
            echo "<td><input type=\"submit\" value=\"Envia\"></td>";
        }
            
        echo "</tr>";
            ?>

    </form>
    <?php
        }
    }
}
    ?>
</table>
<a href="ver_liga.php?liga=<?=$ligaId?>">Atrás</a>
</main>
</body>
</html>