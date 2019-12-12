<?php
session_start();
require_once 'session_management.php';

    // If the user is not logged in as an admin, return to main page
    if(!validate_admin()){
    // JS popup to signify error
    //echo "<script>window.alert('You are not an admin.');</script>"
    //        . '<script language="javascript">window.location.'
    //        . 'href ="index.php"</script>';
    }
    
    // The user is already logged in as an admin,
    // display the new virus upload interface.
    echo <<<_END
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Antivirus</title>
        </head>
        <body>
            <h1> Welcome to free virus check </h1> <br>
            <h2> In here, you can submit infected files to train your engine</h2> <br>
            <br>
            <br>
            <form method="post" action="admin.php" enctype='multipart/form-data'>
                 Select Viral File: <input type="file" name="filename"> <br>
                 Name the virus: <input type="text" name="virus_name"> <br>
                 Artist: <input type="text" name="author"> <br>
                <input type="submit" value="Upload">
            </form>
_END;        
    
    // Not only is the user logged in as an admin,
    // they also have submitted a file through the previous interface
    if(_FILES){
        if(!isset($_POST['virus_name']) || !isset($_POST['author'])){
            echo "You need to enter a name and a author "
            . "to save a virus' signature\n";
        }
        // Make sur it's an exe file
        // Geat signature from the file
        // Store info in database
        // Clear _FILES
        // Set a global variable (or cookie?) to signify success/failure
        // Reload admin.php
        // Display feedback
    } else if(isset($_POST['virus_name']) || isset($_POST['author'])){
        echo "You need to provide a file to generate its signature.\n";
    }
    
    
    
    // Reste super global
    // Not sure I need that.
    $_FILES = nil;
    
    // Loads the page again
    //header('Location: admin.php');
?>