<?php
    require_once 'login.php';

    class SQL_Cient{
        
        private $connection;
        
        // SOME constants 
        const CREATE_TABLE_Q= "CREATE TABLE IF NOT EXISTS "
                . "users(username VARCHAR(20) PRIMARY KEY,"
                . "password CHAR(32) NOT NULL, "
                . "presalt CHAR(6) NOT NULL, "
                . "postsalt CHAR(6) NOT NULL);";
        const TEST_USER_Q = "? IN SELECT user FROM users";
        const ADD_USER_Q = "INSERT INTO users(username, password,"
                . "presalt, postsalt) VALUES (?, ?, ?, ?);";
        const GET_USER_CRED_Q = "SELECT * FROM users WHERE username = ?;";

        const ERROR_MSG = "Could not connected to database.";
        const ERROR_MSG_USER_MIGHT_EXIST = "Cound not determine if the username is "
                . "already taken. \n Try again later.";
        
        function __construct(){
            $this->connection = new mysql($hn, $un, $pm, $db);
            if($this->connection->connect_error){
                display_error($this->ERROR_MSG);
            }
        }
        
        function __destruct() {
            $this->connection->close();
        }
        
        // Returns true if the user was prorperly inserted into the database
        // Returns false if the user was not added due to bad data 
        // (non secure password, username already taken or non valid)
        function add_user($username, $password){
            // Set up the data
            $username = sanitize($username);
            $pre_salt = bin2hex(random_bytes(3));
            $post_salt = bin2hex(random_bytes(3));
            $password = hash('ripemd128', $pre_salt.$password.$post_salt);
            
            // Make sure the username is not taken
            $stmt = $this->connection->prepare($this->CREATE_TABLE_Q);
            $stmt->bind_param('s', $username);
            if($stmt->execute()){
                $result->bind_result($user_exists);
                if( !$stmt->fetch()){
                    display_error($this->ERROR_MSG_USER_MIGHT_EXIST);
                    die();
                }
                        
            }
            $stmt->close();
            
            // If the user already exists, signify it cannot be added to db
            if($user_exists){
                return false;
            }
            
            // Else add the user to db and return true to signify succes
            $stmt = $this->connection->prepare($this->INSERT_USER_Q);
            $stmt->bind_param('ssss', $username, $password, $pre_salt, $post_salt);
            $result = $stmt->execute();
            $stmt->close();
            return result;
        }
        
        public function check_credentials($name, $password){
            // First verify if user exists and retrive the salts
            
        }
    
        private function sanitize($variable){
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