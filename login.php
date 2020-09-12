<?php

require_once("global.php");

if(isset($_POST["login"])) {
    $Users = new Users();
    $error = $Users->login($_POST["username"], $_POST["password"]);
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        Login Below!
        <span style="color:#FF0000;font-weight:bold;"><?=$error?></span>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" /><br />
            <input type="passowrd" name="password" placeholder="Password" /><br />
            <input type="submit" name="login" value="Login" />
        </form>
    </body>
</html>
