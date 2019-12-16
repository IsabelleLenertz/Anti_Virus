<?php
session_start();
require_once 'session_management.php';
require_once 'sql_util.php';
require_once 'antivirus.php';
require_once 'login.php';

    // If the user is not logged in as an admin, return to main page
    if(!validate_admin()){
    // JS popup to signify error
    //echo "<script>window.alert('You are not an admin.');</script>"
    //        . '<script language="javascript">window.location.'
    //        . 'href ="index.php"</script>';
    }
    $client = new SQL_Client($hn, $un, $pw, $db);

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
            <h2> In here, you can submit clean files to train your engine</h2> <br>
            <br>
            <br>
            <form method="post" action="admin.php" enctype='multipart/form-data'>
                 Select CLEAN PE File: <input type="file" name="filename"> <br>
                <input type="submit" value="Upload">
            </form>
            <form method="post" action=admin.php enctype='multipart/form-data'>
                 Add a section name to white list <input type="text" name="name"> <br>
                <input type="submit" value="Upload">
            </form>
            <p> section names already whitelisted: <br>
_END;        
    $arr = $client->get_whitelisted_sections();
    for($i = 0; $i < sizeof($arr); $i++){
        echo $arr[$i]."<br>";
    }
    // Not only is the user logged in as an admin,
    // they also have submitted a file through the previous interface
    if($_FILES){
        $microsoftPe = new MicrosftPE($_FILES['filename']['tmp_name']);
        if (!$microsoftPe->isPE($filename)){
            echo "<script>window.alert('Improper file format."
            . "We only check .exe files');</script>"
            . "<script language='javascript'>window.location."
            . "href ='admin.php'</script>";
        } else {
            $names = $microsoftPe->get_sections_names(); // return an array with all the section names
            if($client->add_sections($names)){
                echo "<script>window.alert('Database successfuly updated.');</script>"
                . "<script language='javascript'>window.location."
                . "href ='admin.php'</script>";

            } else {
                echo "<script>window.alert('Something went wrong. \nCould add"
                . " all sections to databse. Some might have been added.\n')"
                . ";</script> <script language='javascript'>window.location."
                . "href ='admin.php'</script>";
            } 
        }
    }
    if(isset($_POST['name'])){
        // will be sinitized, as 'normal' section names have only 
        // ASCII char and a '.' which sould not be affected by sanitization.
        // anthing else should not be whiltlisted
        if ($client->add_section($_POST['name'])){
            echo "<script>window.alert('Database successfuly updated.');</script>"
            . "<script language='javascript'>window.location."
            . "href ='admin.php'</script>";

        }else {
            echo "<script>window.alert('Could not update database.');</script>"
            . "<script language='javascript'>window.location."
            . "href ='admin.php'</script>";
        }
    }
?>