<?php

    class Users {

        //You should also do CSFR token checking, but that's getting a bit deep at this point.
        public function login($username, $password) {

            //Make sure username is alpha-numeric, more than 3 characters and less then 25 characters.
            if(!ctype_alnum($username) || (strlen($username) < 4 || strlen($username) > 25))
                return "Please enter a valid username.";

            //Make sure password is greater than 4 characters and less then 256 characters.
            if(strlen($password) < 5 || strlen($password) > 255)
                return "Invalid username or password.";

            //Make sure the username exists in the database.
            if(!$this->userExists())
                return "That user could not be found!";

            //Since this will also return an ID we need to specify that it has to be exactly = FALSE to fail
            $userId = $this->validCredentials($username, $password);
            if($userId === FALSE)
                return "Invalid username and/or password combination.";

            $this->initiateSession($userId);

            header("Location: /home.php");

        }

        public function isLoggedIn() {
            if($this->getUserIdBySession() === false)
                return false;
            return true;
        }

        public function getUserIdBySession() {
            if(!isset($_SESSION["session_token"]))
                return false;
            $token = $_SESSION["session_token"];
            $sql = "SELECT `user_id` FROM `sessions` WHERE `token` = ? DESC LIMIT 1";
            $query = Database::mysqli()->prepare($sql);
            $query->bind_param("s", $token);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows == 0)
                return false;
            $row = $result->fetch_assoc();
            $query->close();
            return $row["user_id"];
        }

        private function initiateSession($userId) {

            //Delete existing sessions, logging everyone else on the account out.
            $this->removeSessions($userId);

            $token = $this->generateToken();

            $sql = "INSERT INTO `sessions` (`user_id`, `token`) VALUES (?, ?)";
            $query = Database::mysqli()->prepare($sql);
            $query->bind_param("is", $userId, $token);
            $query->execute();
            $query->close();

            //In the real world you'd would want to update the last login time along with timestamp, Ip, etc
            $_SESSION["session_token"] = $token;

        }

        private function generateToken($length = 125) {
            $random = bin2hex(random_bytes($length));
            return substr($random, 0, $length);
        }

        private function removeSessions($userId) {
            //In prod you'd want to keep these records and not delete them. You'd just insert a new row and only grab that one with
            //ORDER BY id DESC LIMIT 1 so it only pulls the most recent token and id and doesn't remove previous login logs.
            $sql = "DELETE FROM `sessions` WHERE `user_id` = ?";
            $query = Database::mysqli()->prepare($sql);
            $query->bind_param('i', $userId);
            $query->execute();
            $query->close();
        }

        private function validCredentials($username, $password) {

            //Get the id from the database where the username and password match
            $sql = "SELECT `id` FROM `users` WHERE `username` = ? AND `password` = ? LIMIT 1";
            $query = Database::mysqli()->prepare($sql);
            $query->bind_param('ss', $username, $password);
            $query->execute();

            //Store count and Id as variables so we can close the connection ASAP. I'm super anal about that. Not sure why.
            $result = $query->get_result();
            $count = $result->num_rows;
            $id = $result->fetch_assoc()["id"];
            $query->close();
            if($count == 0)
                return false;
            return $id;

        }

        private function userExists($username) {

            //Don't grab anything from the database, we just need to know if the username is present.
            $sql = "SELECT NULL FROM `users` WHERE `username` = ? LIMIT 1";
            $query = Database::mysqli()->prepare($sql);
            $query->bind_param('s', $username);
            $query->execute();
            $result = $query->get_result();
            $query->close();
            if($result->num_rows == 0)
                return false;
            return true;

        }

    }
