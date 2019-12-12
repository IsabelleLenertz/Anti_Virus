<?php
require_once 'sql_util.php';
require_once 'session_management.php';
require_once 'login.php';
    
    // $client manages connection and requests to the database
    $client = new SQL_Client($hn, $un, $pw, $db);
    
    // to create an admin instead of a user uncomment next lin

    //$client->add_admin("<name>", "<pass>");




    
    // Attempts to log the visitor in as a user using the info provided 
    // in the post method from index.html
    if(isset($_POST['returning_username']) && isset($_POST['returning_password'])){
        // Please, notice the sanitization is done by the sql client as it is 
        // using sql method to do so
        // This allows the reste of the code to know nothing about the 
        // database used
        // Therefore in case the database needs to be changed and upadted 
        // only the code in the client needs to change
        if ($client->check_user_credentials($_POST['returning_username'], 
                $_POST['returning_password'])){
            start_user($client->sanitize($_POST['returning_username']));
            echo '<script language="javascript">window.location.'
                . 'href ="virus_checker.php"</script>';
        } 
    }

    // Attempts to log the visitor in as an admin using the info provided 
    // in the post method from index.html
    if(isset($_POST['admin']) && isset($_POST['admin_password'])){
        // Please, notice the sanitization is done by the sql client as it is 
        // using sql method to do so
        // This allows the reste of the code to know nothing about the 
        // database used
        // Therefore in case the database needs to be changed and upadted 
        // only the code in the client needs to change
        if ($client->check_admin_credentials($_POST['admin'], 
                $_POST['admin_password'])){
            start_admin($client->sanitize($_POST['admin']));
            echo '<script language="javascript">window.location.'
                . 'href ="admin.php"</script>';
        }
    }
    
    
    // Attempts create a new account for a user and lods them in
    //  using the info provided in the post method from index.html
    if(isset($_POST['username']) && isset($_POST['password'])){
        // Please, notice the sanitization is done by the sql client as it is 
        // using sql method to do so
        // This allows the reste of the code to know nothing about the 
        // database used
        // Therefore in case the database needs to be changed and upadted 
        // only the code in the client needs to change
        if ($client->add_user($_POST['username'], $_POST['password'])){
            if($client->check_user_credentials($_POST['username'], $_POST['password'])){
                start_user($client->sanitize($_POST['username']));
                echo '<script language="javascript">window.location.'
                . 'href ="virus_checker.php"</script>';
            } else {
                // JS popup to signify error
            echo "<script>window.alert('Your account was created but we could"
                    . "not log you in."
                    . "Please contact an administrator');</script>";
            }
        } else {
            // JS popup to signify error
            echo "<script>window.alert('Could not create a new account. "
                    . "Problems might be:"
                    . "<br> username already taken"
                    . "<br> password does not meet security requirements"
                    . "<br> Problem with backem server. Contact an administrator"
                    . "');</script>";

        }
    }
    
    // If the credentials were not valid or not enough info was provided
    // go back to login.
    echo '<script language="javascript">window.location.'
        . 'href ="index.php"</script>';

 ?>
