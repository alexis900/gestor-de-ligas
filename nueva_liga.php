<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
if (isSession()) {
head("Inserta ligas");
  
    if (isset($_POST['liga']) && !empty($_POST['equipos'])) {
        //Coge el contenido del textarea
        $equipos = explode("\n", trim($_POST['equipos']));
        $liga = trim(strip_tags($_POST['liga']));

        //Comprueba si la liga ya existe en la base de datos
        $lDuplicados = false;
        $sql = "select nombre from liga;";
        $rst = mysqli_query($con,$sql);
        while ($row = mysqli_fetch_row($rst)) {
            if($row[0] == $liga){
                $lDuplicados = true;
                break;
            }
        }
        
        //Comprueba si hay equipos duplicados
        $eDuplicados = false;
        $res = array_diff($equipos, array_diff(array_unique($equipos), array_diff_assoc($equipos, array_unique($equipos))));
        foreach(array_unique($res) as $v) {
             $eDuplicados = true;
        }

        //Comrprueba que hay como mínimo 3 equipos
        $numEq = count($equipos);
        $fEquipos = false;
        if ($numEq <= 2) {
            $fEquipos = true;
        }

        $nLiga = false;
        if (empty($_POST['liga'])) {
            $nLiga = true;
        }

        //Comprueba los mensajes que tendrá que insertar
        if ($nLiga || $lDuplicados || $eDuplicados || $fEquipos) {
            $mensaje = "<div class=\"error\">";
            //Mensajes
            if ($lDuplicados) {
                $mensaje .=  "<span>La liga ya está en la base de datos</span><br>";
            }

            if ($nLiga) {
                $mensaje .= "<span>No hay nombre en la liga</span><br>";
            }

            if ($eDuplicados) {
                $mensaje .= "<span>Los equipos están duplicados</span><br>";
            }

            if ($fEquipos) {
                $mensaje .= "<span>Hay pocos equipos</span><br>";
            }

            $mensaje .= "</div>";
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
            $sql = "insert into equipo (liga_id, nombre) values ($ligaId,'" . utf8_decode(strip_tags($equipo)) . "')";
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

<form action="nueva_liga.php" method="post" id="nuevaLiga">
    <h2>Insertar liga</h2>
    <?= isset($mensaje) ? $mensaje : null ?>
    <!-- Autocompleta las ligas si hay algún error -->
    <span>Nombre de la liga: </span><br><input type="text" name="liga" id="liga" value="<?= isset($_POST['liga']) ? $_POST['liga'] : null ?>"><br />
    <span>Nombre de los equipos:</span><br>
    <textarea name="equipos" id="textbox" cols="30" rows="10"><?php
    //Autorrellena los equipos si hay algún error
                if (!empty($_POST['equipos'])) {
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