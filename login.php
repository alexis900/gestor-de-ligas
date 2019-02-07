<?php
require_once('includes/functions.php');
require_once('db.php');
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

head("Log In");
if (isset($_POST['username']) && isset($_POST['passwd'])) {
    $username = $_POST['username'];
    $passwd = md5($_POST['passwd']);
    $sql = "SELECT count(*) FROM administradores where login = '$username' and password = '$passwd'";
    $rst = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($rst);

    if ($row[0] == 1) {
        $_SESSION['username'] = $username;
    }
    header("Location: index.php");
}

?>
<form action="login.php" method="post">
    <input type="text" name="username" id="username"><br>
    <input type="password" name="passwd" id="passwd"><br>
    <input type="submit" value="Inicia sesión">
</form>
</main>
</body>
</html>