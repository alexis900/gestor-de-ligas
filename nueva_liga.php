<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
if (isset($_SESSION['username'])) {
head("Inserta ligas");
  
    if (isset($_POST['liga']) && !empty($_POST['equipos'])) {
        //Coge el contenido del textarea
        $equipos = explode("\n", trim($_POST['equipos']));
        $liga = trim(strip_tags($_POST['liga']));

        //Comprueba si la liga ya existe en la base de datos
        $eDuplicados = false;
        $sql = "select nombre from liga;";
        $rst = mysqli_query($con,$sql);
        while ($row = mysqli_fetch_row($rst)) {
            if($row[0] == $liga){
                $eDuplicados = true;
                break;
            }
        }
        
        //Comprueba si hay equipos duplicados
        $lDuplicados = false;
        $res = array_diff($equipos, array_diff(array_unique($equipos), array_diff_assoc($equipos, array_unique($equipos))));
        foreach(array_unique($res) as $v) {
             $lDuplicados = true;
        }

        //Comrpueba que hay como mínimo 3 equipos
        $numEq = count($equipos);
        $fEquipos = false;
        if ($numEq <= 2) {
            $fEquipos = true;
        }

        $nLiga = false;
        if (empty($_POST['liga'])) {
            $nLiga = true;
        }

        //Mensajes
        if ($nLiga) {
            echo "<p>No hay nombre en la liga</p>";
        }

        if ($lDuplicados) {
            echo "<p>La liga ya está en la base de datos</p>";
        }

        if ($eDuplicados) {
            echo "<p>Los equipos están duplicados</p>";
        }

        if ($fEquipos) {
            echo "<p>Hay pocos equipos. Como mínimo hay que poner 3 equpos.</p>";
        }


        //Si no hay duplicados y hay suficientes equipos se insertará en la base de datos
        if (!$eDuplicados && !$lDuplicados && !$fEquipos && !$nLiga) {
        //Inserta la liga
        $sql = "insert into liga(nombre, fecha_creacion) values('$liga', curdate());";
        mysqli_query($con, $sql);

        //Inserta los equipos capturados anteriormente
        $numEquipos = 0;
        $ligaId = mysqli_insert_id($con);
        foreach($equipos as $equipo){
            $sql = "insert into equipo (liga_id, nombre) values ($ligaId,'" . strip_tags($equipo) . "')";
            mysqli_query($con, $sql);
            $ids[] = mysqli_insert_id($con);
        }
        
        //Crea el Array de enfrentamientos
        $e = enfrentamientos(count($equipos));
            for($ronda=1; $ronda<=count($e); $ronda++){    
            for($partido=0; $partido<count($e[0]); $partido++){
                
                $e1 = $e[$ronda-1][$partido][0] - 1;
                $e2 = $e[$ronda-1][$partido][1] - 1;
                        
                $sql = "insert into partido(liga_id,ronda_num,equipo1_id,equipo2_id) values ($ligaId,$ronda,$ids[$e1],$ids[$e2])";
                mysqli_query($con,$sql);
            }
        }

        header("Location: index.php");
        }
    }
} else {
    header("Location: index.php");
}
?>
<!--
Formulario para insertar las ligas. 
Si hay algún error, no tendremos que volver a escribir los nombres, estos se guardan.
    -->
<form action="nueva_liga.php" method="post">
    <span>Nombre de la liga: </span><br><input type="text" name="liga" id="liga" value="<?= isset($_POST['liga']) ? $_POST['liga'] : null ?>"><br />
    <span>Nombre de los equipos:</span><br>
    <textarea name="equipos" id="textbox" cols="30" rows="10"><?php
                if (isset($_POST['equipos'])) {
                    foreach ($equipos as $key => $value) {
                        echo $value;
                    }        
                } 
            ?></textarea><br>
    <input type="submit" value="Envia">
</form>

</main>
</body>

</html>