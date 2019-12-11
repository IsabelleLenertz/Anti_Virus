<?php
    $s_client = new Session_Client();
    // If the user is not logged in as an admin, return to main page
    if(!$s_client->validate_admin_session()){
        header('Location: index.php');
    }
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
    
    if(_FILES){
        // Make sur it's an exe file
        // Geat signature from the file
        // Store info in database
        // Clear _FILES
        // Set a global variable (or cookie?) to signify success/failure
        // Reload admin.php
        // Display feedback
    }
    
    // Reste super global
    // Not sure I need that.
    $_FILES = nil;
    
    // Loads the page again
    header('Location: admin.php');
?>