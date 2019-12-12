<?php
session_start();
require_once 'session_management.php';
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
_END;
?>
Hi!