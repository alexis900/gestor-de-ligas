<?php
require_once('includes/functions.php');
require_once('db.php');
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

head("Log In");

//Comprueba si existe el usuario y la contraseña
if (isset($_POST['username']) && isset($_POST['passwd'])) {
    $username = $_POST['username'];
    $passwd = md5($_POST['passwd']);
    $sql = "SELECT count(*) FROM administradores where login = '$username' and password = '$passwd'";
    $rst = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($rst);

    if ($row[0] == 1) {
        $_SESSION['username'] = $username;
    }
    header("Location: login.php?error=1");
}

?>
<form action="login.php" method="post" class="login">
    <?php 
    //Si hay algún error en las credenciales, muestra el siguiente mensaje
    if (isset($_GET['error']) == 1) {?>
    <div class="error">
        <p>Usuario o contraseña incorrectos</p>
    </div>
    <?php
    }?>
    <label for="username">Nombre de usuario</label>
    <input type="text" name="username" id="username"><br>
    <label for="passwd">Contraseña</label>
    <input type="password" name="passwd" id="passwd"><br>
    <input type="submit" value="Inicia sesión">
</form>
</main>
</body>

</html>