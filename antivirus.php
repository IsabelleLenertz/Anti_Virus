<?php
    $s_client = new Session_Client();
    if(!$s_client->validate_session()){
        
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