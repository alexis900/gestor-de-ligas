<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
head("Inicio");

$sql = "SELECT * FROM liga";
$rst = mysqli_query($con, $sql);
?>
        <table>
            <tr>
                <th>Liga</th>
                <th>Fecha</th>
            </tr>
        
        <?php 
        while ($row = mysqli_fetch_row($rst)) {

        //Cambia el Array de las filas por nombres identificativos
        $ligaId = $row[0];
        $ligaNombre = $row[1];
        $ligaFecha = $row[2];
?>
        <tr>
            <td>
                <a href="ver_liga.php?liga=<?=$ligaId?>"><?=$ligaNombre?></a>
            </td>
            <td>
                <?= transforma_date($ligaFecha)?>
            </td>
            <?php
            if (isset($_SESSION['username'])) {?>
                <td>
                    <a href="elimina_liga.php?liga=<?=$ligaId?>" class="mdi mdi-trash-can">
                    <span>Elimina</span>
                </a>
            </td>
           <?php } 
        echo "</tr>";
        }
        echo "</table>";
        if(isset($_SESSION['username'])){?>
        <a href="nueva_liga.php">Nueva liga</a>
        <?php
        }
    ?>
    </main>
</body>
</html>