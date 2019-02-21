<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
head("Inicio");
$session = isSession();
$sql = "SELECT * FROM liga";
$rst = mysqli_query($con, $sql);
?>
<table>
    <tr class="accent-color">
        <th>Liga</th>
        <th>Fecha</th>
        <?php 
        if ($session) {
            echo "<th>Elimina una liga</th>";
        }
        
        ?>
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
            if ($session) {?>
        <td>
            <a href="elimina_liga.php?liga=<?=$ligaId?>" class="mdi mdi-trash-can">
                <span>Elimina</span>
            </a>
        </td>
    </tr>
        <?php }
        }
        
        if($session){?>
        <tr>
            <td colspan="3">
                <a href="nueva_liga.php" class="mdi mdi-plus">
                    <span>Nueva liga</span>
                </a>
            </td>
        </tr>
        <?php
        }
    ?>
            </table>
        </main>
    </body>
</html>