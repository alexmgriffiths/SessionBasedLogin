<?php

require_once("global.php");
$Users = new Users();
if(!$Users->isLoggedIn())
    header("Location: /login.php");

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>
            Home
        </title>
    </head>
    <body>
        Welcome home!
    </body>
</html>
