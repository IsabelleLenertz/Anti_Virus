<?php
require_once 'sql_utils.php';
require_once 'session_management';

// Main method of the file
if(isset(_POST['name']) && isset(_POST['password']) 
                        && isser(_POST['confrim_password'])){
    $client = new SQL_Cient();
    // Sinitizing is done by the client
    $result = $client ->add_user(_POST['name'], _POSE['password']); 
    if($result == false){
        echo "<p> We could not add your credentials to the database. "
                . "The username might already have been taken.\n"
                . "Press on the button bellow to be redirection the main page"
                . "and try again.";
    }
    $session_client = new Session_Client();
    $session_client->start_session();
    hearder('Location: antivirus.php');    
} else {
    echo <<<_END
    <p> you need to enter both valid usernam, password, and confirme
        the password. Click on the button bellow to be redirected to
        the main page and try again.
    </p>
_END;
}

?>