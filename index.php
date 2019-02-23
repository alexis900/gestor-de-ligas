<?php
require_once("includes/functions.php");
require_once("db.php");
session_start();
head("Inicio");
$session = isSession();
$sql = "SELECT * FROM liga";
$rst = mysqli_query($con, $sql);
?>
<table id="index">
    <thead>
        <tr class="accent-color">
            <th>Liga</th>
            <th>Fecha</th>
            <?php 
            if ($session) {
                echo "<th>Elimina una liga</th>";
            }
            
            ?>
        </tr>
    </thead>
    <?php 
        while ($row = mysqli_fetch_row($rst)) {

        //Cambia el Array de las filas por nombres identificativos
        $ligaId = $row[0];
        $ligaNombre = $row[1];
        $ligaFecha = $row[2];
?>
    <tbody>
    <tr>
        <td class="verLiga">
            <a href="ver_liga.php?liga=<?=$ligaId?>"><?=$ligaNombre?></a>
        </td>
        <td>
            <?= transforma_date($ligaFecha)?>
        </td>
        <?php

        //Si tien la sesión iniciada, se muestra la columna para eliminar
            if ($session) {?>
        <td class="elimina">
            <a href="elimina_liga.php?liga=<?=$ligaId?>" class="mdi mdi-trash-can">
                <span>Elimina</span>
            </a>
        </td>
    </tr>
            </tbody>
            
        <?php }
        }
        //Si tiene la sesión iniciada, puede ver el enlace nuevaLiga
        if($session){?>
        <tfoot>
        <tr class="accent-color">
            <td colspan="3" class="nuevaLiga">
                <a href="nueva_liga.php" class="mdi mdi-plus">
                    <span>Nueva liga</span>
                </a>
            </td>
        </tr>
        </tfoot>
        <?php
        }
    ?>
            </table>
        </main>
    </body>
</html>