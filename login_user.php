<?php
require_once 'sql_util.php';
require_once 'session_management.php';
require_once 'login.php';
     


    
    // $client manages connection and requests to the database
    $client = new SQL_Client($hn, $un, $pw, $db);


    // Attempts to log the visitor in as a user using the info provided 
    // in the post method from index.html
    if(isset($_POST['returning_username']) && isset($_POST['returning_password'])){
        // Please, notice the sanitization is done by the sql client as it is 
        // using sql method to do so
        // This allows the reste of the code to know nothing about the 
        // database used
        // Therefore in case the database needs to be changed and upadted 
        // only the code in the client needs to change
        if ($client->check_admin_credential($_POST['returning_username'], 
                $_POST['returning_password'])){
            Sessions.start_admin(client.sanitize($_POST['returning_username']));
            header('Location: admin.php');
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
        if ($client->check_admin_credential($_POST['admin'], 
                $_POST['admin_password'])){
            Sessions.start_admin(client.sanitize($_POST['admin']));
            header('Location: admin.php');
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
                Sessions.start_user(client.sanitize($_POST['username']));
                header('Location: virus_checker.php');
            } else {
                // JS popup to signify error
            echo "<script>window.alert('Could log you in with your new account."
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
    header('Location: index.html');

 ?>