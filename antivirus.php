<?php
    require_once 'session_management.php';

    if(!Sessions.validate_user()){
        header('Location: index.html');
    }
    echo <<<_END
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Antivirus</title>
        </head>
        <body>
            <h1> Welcome to free virus check </h1> <br>
            <h2> The perfect free tool to make sure your file is indeed a virus! </h2> <br>
            <br>
            <br>
            <form method="post" action="antivirus.php" enctype='multipart/form-data'>
                Select File: <input type="file" name="filename"> <br>
                <input type="submit" value="Upload">
            </form>
_END;        
    
    if(_FILES){
        // TODO: check file type? exe only?
        // TODO: Load file as bytes
        // TODO: the array of bytes to check against database
        // TODO: do check
    }
    

?>