<?php 
    
    class SQL_Client{

        private $connection;
        
        // SOME SQL QUERIES 
        const CREATE_TABLE_Q= "CREATE TABLE IF NOT EXISTS "
                . "users(user VARCHAR(20) PRIMARY KEY,"
                . "password CHAR(32) NOT NULL, "
                . "presalt CHAR(6) NOT NULL, "
                . "postsalt CHAR(6) NOT NULL);";
        const CREATE_ADMIN_TABLE_Q= "CREATE TABLE IF NOT EXISTS "
                . "admins(user VARCHAR(20) PRIMARY KEY,"
                . "password CHAR(32) NOT NULL, "
                . "presalt CHAR(6) NOT NULL, "
                . "postsalt CHAR(6) NOT NULL);";
        const TEST_USER_Q = "? IN SELECT user FROM users;";
        const TEST_ADMIN_Q = "? IN SELECT user FROM admins;";
        const ADD_USER_Q = "INSERT INTO users(user, password,"
                . "presalt, postsalt) VALUES (?, ?, ?, ?);";
        const ADD_ADMIN_Q = "INSERT INTO admins(user, password,"
                . "presalt, postsalt) VALUES (?, ?, ?, ?);";
        const GET_USER_CRED_Q = "SELECT * FROM users WHERE user = ?;";
        const GET_ADMIN_CRED_Q = "SELECT * FROM admins WHERE user = ?;";
        
        // SOME CONSTANTS
        const HASH_ALGO = "ripemd128";
        const ERROR_MSG = "Could not connected to database.";
        const ERROR_MSG_USER_MIGHT_EXIST = "Cound not determine if the username is "
                . "already taken. \n Try again later.";

        
        function __construct($hn, $un, $pw, $db){
            $this->connection = new mysqli($hn, $un, $pw, $db);
            if($this->connection->connect_error){
                $this->display_error($this->ERROR_MSG);
            }
        }
        
        function __destruct() {
            $this->connection->close();
        }
        
        // Returns true if the user was prorperly inserted into the database
        // Returns false if the user was not added due to bad data 
        // (non secure password, username already taken or non valid)
        public function add_user($username, $password){
            return $this->add($username, $password, false);
        }

        function add($username, $password, $admin){
            // Set up the data
            $username = $this->sanitize($username);
            $pre_salt = bin2hex(random_bytes(3));
            $post_salt = bin2hex(random_bytes(3));
            $password = hash($this->HASH_ALGO, $pre_salt.$this->sanitize($password).$post_salt);
            
            // Make sure the username is not taken
            $stmt = nil;
            if($admin){
                $stmt = $this->connection->prepare($this->CREATE_ADMIN_TABLE_Q);
            }else{
                $stmt = $this->connection->prepare($this->CREATE_TABLE_Q);
            }
            $stmt->bind_param('s', $username);
            if($stmt->execute()){
                $result->bind_result($user_exists);
                if(!$stmt->fetch()){
                    $this->display_error($this->ERROR_MSG_USER_MIGHT_EXIST);
                    die();
                }
                        
            }
            $stmt->close();
            
            // If the user already exists, signify it cannot be added to db
            if($user_exists){
                return false;
            }
            
            // Else add the user to db and return true to signify succes
            if($admin){
                $stmt = $this->connection->prepare($this->INSERT_ADMIN_Q);
            } else {
                $stmt = $this->connection->prepare($this->INSERT_USER_Q);
            }
            $stmt->bind_param('ssss', $username, $password, $pre_salt, $post_salt);
            $result = $stmt->execute();
            $stmt->close();
            return result;
        }
        
        // Returns true if the user was prorperly inserted into the database
        // Returns false if the user was not added due to bad data 
        // (non secure password, username already taken or non valid)
        function add_admin($username, $password){
            return $this->add($username, $password, true);
        }
        
        // Check if the user provides appropriate credentials
        // Returns true if username and password match
        // Returns false otherwise.
        // TODO: check if user is not even in the db.
        private function check_credentials($name, $password, $admin){
            $name = $this->sanitize($name);
            $password = $this->sanitize($password);
            
            // First verify if user exists and retrive the salts
            $stmt = nil;
            if($admin){
                $stmt = $this->connection->prepare($this->GET_ADMIN_CRED_Q);
            }else {
                $stmt = $this->conneciton->prepare($this->GET_USER_CRED_Q);
            }
            $stmt->bind_param('s', $name);
            $result = false;
            if($stmt->execute()){
                $stmt->bind_result($dbUsername, $dbPassord, $pre_salt, $post_salt);
                if($stmt->fetch_result()){
                    if($dbUsername == null || !isset($dbUsername)){
                        $result = false;
                    } else{
                        $password = hash($this->HASH_ALGO, $pre_salt.$password.$post_salt);
                        $result = ($passwod == $dbPassord);
                    }
                }
            }
            $stmt->close();
            return $result;
        }
        
        public function check_user_credentials($name, $password){
            return $this->check_credential($name, $password, false);
        }
                
        
        public function check_admin_credential($name, $password){
            return $this->check_credentials($name, $password, true);
        }
    
        public function sanitize($variable){
            if(get_magic_quotes_gpc()) {
                $variable = stripslashes($variable);
            }
            return $this->connection->real_escape_string($variable);
        }    

        private function display_error($msg){
            echo<<<_END
                    We are sorry, but the requested task could not be completed.
                    <p>$msg</p>
                    Please reload the page. If you are still having problems,
                    <a herf="mailto:isabelle@delmas.us"> email the administrator</a>.
                    Thank you;
_END;
        }
    }