<?php

class Database {

    //These credentials should be stored in environmental variables, or read from a file only on the server
    //Never ever store credentials in the source code.
    //Seriously. Don't do this. I'm doing this for this example.
    private static $host = "localhost";
    private static $username = "db_user";
    private static $password = "DatabasePassword123";
    private static $database = "my_database";
    private static $mysqli;

    public static function mysqli() {

        if(!self::$mysqli) {
            self::$mysqli = new mysqli(self::$host, self::$username, self::$password, self::$database);
            if(self::$mysqli->connect_errno != 0)
                die("Could not connect to database. Error: " . self::$mysqli->connect_error);
        }
        return self::$mysqli;

    }

}