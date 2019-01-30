<?php
require_once("includes/functions.php");
require_once("db.php");
head("Inicio");

$sql = "SELECT * FROM liga";
$result = mysqli_query($con, $sql);
?>
        <table>
            <tr>
                <th>Liga</th>
                <th>Fecha</th>
            </tr>
        
        <?php 
        while ($row = mysqli_fetch_row($result)) {
        echo "<tr>";
            echo "<td><a href=\"ver_liga.php?liga=$row[0]\">"  . $row[1] . "</a></td>";
            echo "<td>" . $row[2] . "</td>";
            echo "<td><a href=\"elimina_liga.php?liga=$row[0]\">Elimina</a></td>";
        echo "</tr>";
        }
        ?>
        <a href="nueva_liga.php">Nueva liga</a>
        </table>
    </main>
</body>
</html>