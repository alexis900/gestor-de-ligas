<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
if (!isset($_GET['liga']) || empty($_GET['liga']) || !is_numeric($_GET['liga'])) {
    header("Location: index.php");
}
$ligaId = $_GET['liga'];

// Selecciona el nombre de la liga
$sql = "select nombre from liga where id=$ligaId";
$rst = mysqli_query($con,$sql);
$row = mysqli_fetch_row($rst);
$nombreLiga = $row[0];

head($nombreLiga);
echo "<h2>$nombreLiga</h2>\n";

//Obtenemos los equipos y los guardamos en un array junto con una serie de contadores:
// PJ = Partidos Jugados
// PG = Partidos Ganados
// PP = Partidos Perdidos
// los partidos empatados se pueden calcular : PE = PJ - PG - PP;

$sql = "select id,nombre from equipo where liga_id=$ligaId";
$rst = mysqli_query($con,$sql);
$equipos = null;
while ($row = mysqli_fetch_row($rst)){
    $id = $row[0];
    $nombre = $row[1];
    $equipos[$id]['nombre'] = $nombre;
    $equipos[$id]['PJ'] = 0;
    $equipos[$id]['PG'] = 0;
    $equipos[$id]['PP'] = 0;
    $equipos[$id]['PE'] = 0;
}
//Consultamos los partidos jugados de la liga y actualizamos los contadores
if ($equipos != null) {
$sql = "select equipo1_id,equipo2_id,puntos1,puntos2 from partido".
        " where liga_id=$ligaId and puntos1 is not null";
$rst = mysqli_query($con,$sql);
while($row = mysqli_fetch_row($rst)){
   $e1 = $row[0];
   $e2 = $row[1];
   $g1 = $row[2];
   $g2 = $row[3];
  
   $equipos[$e1]['PJ']++;
   $equipos[$e2]['PJ']++;

   // contar los partidos ganados y perdidos

    if ($g1 > $g2) {
        $equipos[$e1]['PG']++;
        $equipos[$e2]['PP']++;
   } elseif ($g1 < $g2){
        $equipos[$e2]['PG']++;
        $equipos[$e1]['PP']++;
   } elseif ($g1 == $g2) {
        $equipos[$e1]['PE']++;
        $equipos[$e2]['PE']++;
   }
}

//La puntuación de cada equipo será : PG * 3 + PE * 1
    
?>
<table>
<thead>
    <tr class="accent-color">
        <th>Equipo</th>
        <th>Pts</th>
        <th>PJ</th>
        </th>
        <th>PG</th>
        <th>PP</th>
        <th>PE</th>

    </tr>
    </thead>
    <?php
    //Hace un bucle de todos los equipos de la liga y su información
    $count = count($equipos);
    $num = $id-$count;
    for ($i = $id-$count+1; $i <= $count+$num;$i++) {
    $pts = $equipos[$i]['PG'] * 3 + $equipos[$i]['PE'];
?>
<tbody>
    <tr>
        <td><?=utf8_encode($equipos[$i]['nombre'])?></td>
        <td><?=$pts?></td>
        <td><?=$equipos[$i]['PJ']?></td>
        <td><?=$equipos[$i]['PG']?></td>
        <td><?=$equipos[$i]['PP']?></td>
        <td><?=$equipos[$i]['PE']?></td>
    </tr>
    </tbody>
<?php  
    }
    
?>
</table>
<div class="rondas">
<h3>Rondas</h3>
<ul class="ronda">
    <?php
    
    $count = mysqli_query($con, "select distinct ronda_num from partido where liga_id='$ligaId'");
    for ($i=1; $i <= mysqli_num_rows($count); $i++) { ?>
    <li class="numRonda"><a href="ver_ronda.php?liga=<?=$ligaId?>&ronda=<?=$i?>"><?=$i?></a></li>
    <?php
    }
    
    ?>
</ul>
</div>
<?php
} else {
    echo "<p>La liga no existe</p>";
}
?>
</main>
</body>

</html>