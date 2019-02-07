<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
head("Inserta ligas");
$duplicados = false;

if (isset($_SESSION['username'])) {
    if (isset($_POST['liga']) && !empty($_POST['equipos'])) {
        //Coge el contenido del textarea
        $liga = $_POST['liga'];
        $equipos = explode("\n", $_POST['equipos']);

        $sql = "select nombre from liga;";
        $rst = mysqli_query($con,$sql);
        while ($row = mysqli_fetch_row($rst)) {
            if($row[0] == $liga){
                $duplicados = true;
                echo "Equipos duplicados";
                break;
            }
        }

        if (!$duplicados) {
            $res = array_diff($equipos, array_diff(array_unique($equipos), array_diff_assoc($equipos, array_unique($equipos))));
            foreach(array_unique($res) as $v) {
                echo "Hay equipos duplicados";
                $duplicados = true;   
            }
        }
        

        $numEq = count($equipos);
        if (!$duplicados && $numEq > 1) {
        //Inserta la liga
        $sql = "insert into liga(nombre, fecha_creacion) values('" . strip_tags($liga) . "', curdate());";
        mysqli_query($con, $sql);

        //Inserta los equipos capturados anteriormente
        $numEquipos = 0;
        $ligaId = mysqli_insert_id($con);
        foreach($equipos as $equipo){
            $sql = "insert into equipo (liga_id, nombre) values ($ligaId,'" . strip_tags($equipo) . "');";
            mysqli_query($con, $sql);
            $ids[] = mysqli_insert_id($con);
        }
        
        //Crea el Array de enfrentamientos
        $e = enfrentamientos(count($equipos));
            for($ronda=1; $ronda<=count($e); $ronda++){    
            for($partido=0; $partido<count($e[0]); $partido++){
                
                $e1 = $e[$ronda-1][$partido][0] - 1;
                $e2 = $e[$ronda-1][$partido][1] - 1;
                        
                $sql = "insert into partido(liga_id,ronda_num,equipo1_id,equipo2_id)".
                        " values ($ligaId,$ronda,$ids[$e1],$ids[$e2])";
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
    <form action="nueva_liga.php" method="post">
        <span>Nombre de la liga: </span><br><input type="text" name="liga" id="liga"><br/>
        <span>Nombre de los equipos:</span><br><textarea name="equipos" id="" cols="30" rows="10"></textarea><br>
        <input type="submit" value="Envia">
    </form>

</main>
</body>
</html>