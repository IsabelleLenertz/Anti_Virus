<?php
session_start();
require_once 'session_management.php';
require_once 'antivirus.php';

if(!validate_user()){
    // JS popup to signify error
    echo "<script>window.alert('You are not logged in.');</script>"
            . '<script language="javascript">window.location.'
            . 'href ="index.php"</script>';
    
}

require_once 'navbar.php';
    echo <<<_END
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Free Virus Check</title>
                <link rel="stylesheet" type="text/css" media="all" href="style.css">
_END;
    echo $navbar;
    echo <<<_END
    </head>
    <body>
        <h1> Welcome to free virus check </h1> <br>
        <h2> The perfect free tool to make sure your file is indeed a virus! </h2> <br>
        <br>
        <br>
        <form method="post" action="virus_checker.php" enctype='multipart/form-data'>
                Select File: <input type="file" name="filename"> <br>
                <input type="submit" value="Upload">
        </form>
_END;
        
    if($_FILES && $_FILES['filename']['size'] > 0){
        // TODO: check file type? PE only
        $filename = $_FILES['filename']['tmp_name'];
        $microsoftPe = new MicrosftPE($filename);
        if (!$microsoftPe->isPE($filename)){
            echo "<script>window.alert('Improper file format."
            . "We only check .exe files');</script>"
            . "<script language='javascript'>window.location."
            . "href ='virus_checker.php'</script>";
        }
        
        // TODO: some sanity check in the headers
        if (!$microsoftPe->sanity_checks()){
            echo "<script>window.alert('You got a virus!');</script>"
            . "<script language='javascript'>window.location."
            . "href ='virus_checker.php'</script>";
        }
        // TODO: load the array of bytes to check against database
        // TODO: do check against signatures
        
        // Signify non viral file
            echo "<script>window.alert('Your file is safe.');</script>"
            . "<script language='javascript'>window.location."
            . "href ='virus_checker.php'</script>";


    }
    
    echo<<<_END
        <body>
    </html>
_END;        
?>