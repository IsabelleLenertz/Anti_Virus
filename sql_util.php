<?php 
// CREATE USER 'web_agent'@'localhost' IDENTIFIED BY 'IAimeTheCrepesAuJellyEtSuggar!!VeryBeaucoup';
// GRANT INSERT, SELECT, CREATE ON antivirus.* to 'web_agent'@'localhost';
    
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
        const CREACTE_SECTION_TABLE_Q = "CREATE TABLE IF NOT EXISTS "
                . "sections(name CHAR(8) PRIMARY KEY);";
        const TEST_USER_Q = "SELECT * FROM users WHERE user = ?;";
        const TEST_ADMIN_Q = "SELECT * FROM users WHERE user = ?;";
        const ADD_USER_Q = "INSERT INTO users(user, password,"
                . "presalt, postsalt) VALUES (?, ?, ?, ?);";
        const ADD_ADMIN_Q = "INSERT INTO admins(user, password,"
                . "presalt, postsalt) VALUES (?, ?, ?, ?);";
        const ADD_SECTION_Q = "INSERT INTO sections(name) VALUES(?);";
        const GET_ALL_SECTION_NAMES_Q = "SELECT * FROM sections;";
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
                $this->display_error(self::ERROR_MSG);
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
            $password = hash(self::HASH_ALGO, $pre_salt.$this->sanitize($password).$post_salt);
            
            // Create tables if they do not exists
            $stmt = nil;
            if($admin){
                $stmt = $this->connection->prepare(self::CREATE_ADMIN_TABLE_Q);
            }else{
                $stmt = $this->connection->prepare(self::CREATE_TABLE_Q);
            }
            if(!$stmt->execute() || $stmt->affected_rows !== 0){
                $this->display_error(self::ERROR_MSG);
                return false;
            }
            $stmt->close();
            $stmt = null;
            
            // Check if the username is already taken
            if($admin){
                $stmt = $this->connection->prepare(self::TEST_ADMIN_Q);
            }else{
                $stmt = $this->connection->prepare(self::TEST_USER_Q);
            }
            $stmt->bind_param('s', $username);
            if(!$stmt->execute()){
                $this->display_error(self::ERROR_MSGT);
                return false;
            }
            if($stmt->affected_rows > 0){
                $this->display_error(self::ERROR_MSG_USER_MIGHT_EXIST);
                return false;
            }
            $stmt->close();
            $stmt=nil;
            
            
            // Else add the user to db and return true to signify succes
            if($admin){
                $stmt = $this->connection->prepare(self::ADD_ADMIN_Q);
            } else {
                $stmt = $this->connection->prepare(self::ADD_USER_Q);
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
            
            // Create tables if they do not exists
            $stmt = nil;
            if($admin){
                $stmt = $this->connection->prepare(self::CREATE_ADMIN_TABLE_Q);
            }else{
                $stmt = $this->connection->prepare(self::CREATE_TABLE_Q);
            }
            if(!$stmt->execute() || $stmt->affected_rows !== 0){
                $this->display_error(self::ERROR_MSG);
                return false;
            }
            $stmt->close();
            $stmt = nil;
            
            // First verify if user exists and retrive the salts
            $stmt = nil;
            if($admin){
                $stmt = $this->connection->prepare(self::GET_ADMIN_CRED_Q);
            }else {
                $stmt = $this->connection->prepare(self::GET_USER_CRED_Q);
            }
            $stmt->bind_param('s', $name);
            $result = false;
            if($stmt->execute()){
                $stmt->bind_result($dbUsername, $dbPassword, $pre_salt, $post_salt);
                if($stmt->fetch()){
                    if($dbUsername === null || !isset($dbUsername)){
                        $result = false;
                    } else{
                        $password = hash(self::HASH_ALGO, $pre_salt.$password.$post_salt);
                        $result = ($password === $dbPassword);
                    }
                }
            }
            $stmt->close();
            return $result;
        }
        
        public function check_user_credentials($name, $password){
            return $this->check_credentials($name, $password, false);
        }
                
        
        public function check_admin_credentials($name, $password){
            return $this->check_credentials($name, $password, true);
        }
    
        public function add_section($name){
            $name = $this->sanitize($name);
            $stmt = $this->connection->prepare(self::CREACTE_SECTION_TABLE_Q);
            if(!$stmt->execute() || $stmt->affected_rows !== 0){
                $this->display_error(self::ERROR_MSG);
                return false;
            }
            $stmt->close();
            $stmt = nil;
            
            $stmt = $this->connection->prepare(self::ADD_SECTION_Q);
            $stmt->bind_param('s', $name);
            
            if(!$stmt->execute()){
                $this->display_error(self::ERROR_MSGT);
                return false;
            }
            if($stmt->affected_rows !== 1){
                $this->display_error(self::ERROR_MSG);
                return false;
            }
            $stmt->close();  
            return true;
        }
        
        public function add_sections($arr){
            for($i = 0; $i < sizeof($arr); $i++){
                if (! $this->add_section($arr[$i])){
                    return false;
                }
            }
            return true;
        }
        public function get_whitelisted_sections(){
            $stmt = $this->connection->prepare(self::CREACTE_SECTION_TABLE_Q);
            if(!$stmt->execute() || $stmt->affected_rows !== 0){
                $this->display_error(self::ERROR_MSG);
                return false;
            }
            $stmt->close();
            
            $result =  $this->connection->query(self::GET_ALL_SECTION_NAMES_Q);
            if(!$result){
                $this->display_error(self::ERROR_MSGT);
                return false;
            }
            $arr = array();
            $rows = $result->num_rows;
            for($i = 0; $i < $rows; $i++){
                $result->data_seek($i);
                $row = $result->fetch_assoc();
                array_push($arr, $row['name']);    
            }
            return $arr;
        }
        /**
         * $query = "SELECT * FROM viruses";
        $result = $connection->query($query);
        if (!$result) die(display_error("Could not access virus database"));
        $rows = $result->num_rows;
        for($i = 0; $i < $rows; $i++){
            $result->data_seek($i);
            $row = $result->fetch_assoc();
            $bad = $row['signature'];

         * @param type $variable
         * @return type
         */
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