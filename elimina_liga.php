<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
head("Elimina");
if (isSession()) {
    //Si está marcado el checkbox, se eliminará toda la información de la liga
    if (isset($_GET['liga'])) {
        $ligaId = $_GET['liga'];
        if (isset($_GET['si']) == "on") {
            $sql = "delete from partido where liga_id = $ligaId";
            mysqli_query($con, $sql);
            $sql = "delete from equipo where liga_id = $ligaId";
            mysqli_query($con, $sql);
            $sql = "delete from liga where id = $ligaId";
            mysqli_query($con, $sql);
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }

    ?>
<form action="elimina_liga.php" method="get" id="eliminaLiga">
    <input type="hidden" name="liga" value="<?= $ligaId ?>">
    <input type="checkbox" name="si" id="si"><label for="si">¿Está seguro?</label>
    <input type="submit" value="Envia">
</form>
</main>
</body>
</html>

<?php
} else {
header("Location: index.php");
}
?>