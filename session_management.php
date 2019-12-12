<?php
//session.auto_start = 1

    const MONTH_IN_SECONDS = 2592000;
    const HOUR_IN_SECONDS = 3600;
    
    // Starts a user session 
    function start_session($name, $isAdmin = false){
        ini_set('session.gc_maxlifetime', 60*60*24);
        ini_set('session.save_path', '/home/session_info_antivirus');
        session_start();
        session_regenerate_id();
        $_SESSION['username'] = $name;
        $_SESSION['isAdmin'] = $isAdmin;
        $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR']
                .$_SESSION['check']);
    }
    
    function start_user($name){
        return start_session($name, false);
    }
    
    function start_admin($name){
        return start_session($name, true);
    }
    
    // Make sure the user is in their session
    // if returns false the session should be terminated
    function validate_session(){
        // changes the id to prevent hijacking
        session_regenerate_id();
        // make sure a session was started
        if(!isset($_SESSION[$username])){
            return false;
        }
        // check ip address and browser settings
        if ($_SESSION['check'] != $hash('ripemd128', $_SERVER['REMOTE_ADDR']
                .$_SESSION['check'])){
            return false;
        }
    }
    
    function validate_admin(){
        if(!$_SESSION['isAdmin']){
            return false;
        }
        return validate_session();
    }

    function validate_user(){
        return validate_session();
    }

    // Ends a user session and destroys cookies
    function end_session(){
        // session_start(); // BUT WHY???
        $_SESSION = array();
        setcookie(session_name(), '', time() - $MONTH_IN_SECONDS, '/');
        session_destroy();
    }

  
?> 
