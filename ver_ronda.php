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

//Selecciona la informació que necessitarem
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

    <table>
        <tr>
            <th>Equipo 1</th>
            <th colspan="2">Puntuación</th>
            <th>Equipo 2</th>
            <?php

            $mostrarFecha = false;
            for($i = 0; $i < $par;$i++){
                if ($partido[$i]['fecha'] != null) {
                    $mostrarFecha = true;
                }
            }
            if($mostrarFecha || isset($_GET['modifica'])){
                echo "<th>Fecha del partido</th>";
            }
            ?> 
        </tr>
        <?php
        for($i = 0; $i < $par;$i++){
        echo "<tr>";
            echo "<td>" . $partido[$i]['e1'] . "</td>";
            if (isset($_GET['modifica']) == "yes") {
                if($partiod[$i]['p1'] == null || $partido[$i]['p2'] == null || $partido[$i]['fecha'] == null){
                    header("Location: ver_ronda.php?liga=$ligaId&ronda=$rondaId");
                }
                echo "<form action=\"update_ronda.php\" method=\"get\">
                    <td><input type=\"number\" name=\"partido[$i][e1]\" id=\"e1\" min=\"0\" max=\"100\"value=\"0\"></td>
                    <td><input type=\"number\" name=\"partido[$i][e2]\" id=\"e2\" min=\"0\" max=\"100\" value=\"0\"></td>
                    <td>" . $partido[$i]['e2'] . "</td>" .
                    "<td>
                        <input type=\"date\" name=\"partido[$i][date]\" id=\"date\" value=\"". date('Y-m-d') . "\">
                        <input type=\"time\" name=\"partido[$i][time]\" id=\"time\" value=\"". date("H:i") ."\"\>
                    </td>";
                
            } else {
                echo "<td>" . $partido[$i]['p1'] . "</td>";
                echo "<td>" . $partido[$i]['p2'] . "</td>";
                echo "<td>" . $partido[$i]['e2'] . "</td>";
            }
            
            if($partido[$i]['fecha'] != null){
                echo "<td>" . $partido[$i]['fecha'] . "</td>";
            }
        echo "</tr>";
        }
        ?>
    </table>
    <?php
    } else {
        echo "<p>No existe la ronda</p>";
    }
    if(isset($_GET['modifica'])){
        echo "<input type=\"hidden\" name=\"liga\" value=\"".$partido[$par]['id']."\">";
        echo "<input type=\"submit\" value=\"Envia\">";
        echo "</form>";
    } else {
        echo "<a href=\"ver_ronda.php?liga=$ligaId&ronda=$rondaId&modifica=yes\">Modifica</a>";
    }
    ?>
    
    <a href="ver_liga.php?liga=<?=$ligaId?>">Atrás</a>
 </main>   
</body>
</html>
