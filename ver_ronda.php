<?php
require_once("includes/functions.php");
require_once("db.php");
if (!isset($_GET['liga']) || empty($_GET['liga']) || !isset($_GET['ronda']) || empty($_GET['ronda'])) {
    header("Location: index.php");
}
$ligaId = $_GET['liga'];
$rondaId = $_GET['ronda'];

// Selecciona el nombre de la liga
$sql = "select nombre from liga where id=$ligaId";
$rst = mysqli_query($con,$sql);
$row = mysqli_fetch_row($rst);
$nombreLiga = $row[0];

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
    $partido[$par]['e1'] = $row[1];
    $partido[$par]['e2'] = $row[2];
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
                if ($partido[$i]['fecha'] != null) {
                    $mostrarFecha = true;
                }
            }

            if($mostrarFecha){
                echo "<th>Fecha del partido</th>";
            }
            ?>
        </tr>
        <?php
        
       //S
        if (!isset($_GET['partido'])) {
            for ($i=0; $i < $par; $i++) { 
                echo "<tr>";
                    echo "<td>" . $partido[$i]['e1'] . "</td>";
                    echo "<td>" . $partido[$i]['p1'] . "</td>";
                    echo "<td>" . $partido[$i]['p2'] . "</td>";
                    echo "<td>" . $partido[$i]['e2'] . "</td>";
                    echo "<td>" . $partido[$i]['fecha'] . "</td>";
                    if ($partido[$i]['p1'] == null || $partido[$i]['p2'] == null || $partido[$i]['fecha'] == null) {
                        echo "<td><a href=\"ver_ronda.php?liga=$ligaId&ronda=$rondaId&partido=$i\">Modifica</a></td>";
                    }
                echo "</tr>";
            }
    } else {
        ?>
        <form action="update_ronda.php" method="get">
        <?php
        $partidoId = $_GET['partido'];
        $partId = $partido[$partidoId]['id'];

        $p1 = $partido[$partidoId]['p1'] == null ? null : $partido[$partidoId]['p1'] ;
        $p2 = $partido[$partidoId]['p2'] == null ? null : $partido[$partidoId]['p2'] ;

        $fecha_completa = $partido[$partidoId]['fecha'] == null ? null : $partido[$partidoId]['fecha'] ;
        if ($fecha_completa != null) {
            $fecha = date_format(date_create($fecha_completa), 'Y-m-d');
            $hora = date_format(date_create($fecha_completa), 'H:i:s');
        }

        echo "<tr>";
            echo "<td>" . $partido[$partidoId]['e1'] . "</td>";
            if ($p1 > 0) {
                echo "<td>" . $p2 . "</td>";
                echo "<input type=\"hidden\" name=\"partido[$partId][p1]\" id=\"p1h\ value=\"$p1\"/>";
            } else {
                echo "<td><input type=\"number\" name=\"partido[$partId][p1]\" id=\"p1\" min=\"0\" max=\"100\"></td>";
            }

            if ($p2 > 0) {
                echo "<td>" . $p1 . "</td>";
                echo "<input type=\"hidden\" name=\"partido[$partId][p2]\" id=\"p1h\ value=\"$p2\"/>";
            } else {
                echo "<td><input type=\"number\" name=\"partido[$partId][p2]\" id=\"p2\" min=\"0\" max=\"100\"></td>";
            }
            echo "<td>" . $partido[$partidoId]['e2'] . "</td>";

            if ($fecha_completa == null) {
                echo "<td><input type=\"date\" name=\"partido[$partId][date]\" id=\"date\" value=\"". date('Y-m-d') . "\"></td>";
                echo "<td><input type=\"time\" name=\"partido[$partId][time]\" id=\"time\" value=\"". date("H:i") ."\"></td>";
            } else {
                echo "<td>$fecha_completa</td>";
                echo "<input type=\"hidden\" name=\"partido[$partId][date]\" value=\"$fecha\">";
                echo "<input type=\"hidden\" name=\"partido[$partId][time]\" value=\"$hora\">";
            }

            if($p1 != null && $p2 != null && $fecha == null){
            header("Location: ver_ronda.php?liga=$ligaId&ronda=$rondaId");
        } else {
            echo "<td><input type=\"submit\" value=\"Envia\"></td>";
        }
            
        echo "</tr>";
            ?>
            
        </form>
        </table>
        <?php
    }
}
    ?>
    
    <a href="ver_liga.php?liga=<?=$ligaId?>">Atrás</a>
 </main>   
</body>
</html>