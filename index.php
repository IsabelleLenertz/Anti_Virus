<?php
session_start();
require_once 'session_management.php';
require_once 'navbar.php';

echo <<<_END
<html>
    <head>
        <meta charset="UTF-8">
        <title>Free Virus Check</title>
        <link rel="stylesheet" type="text/css" media="all" href="style.css">
        <script src="input_validation.js"> </script>
_END;
    echo $navbar;
    echo <<<_END
    </head>
    <body>
        <h1> Welcome to free virus check </h1> <br>
        <h2> The perfect free tool to make sure your file is indeed a virus! </h2> <br>
        <br>
        <br>
        <h3> Sign Up Form </h3>
        <form method="post" action="login_user.php" onsubmit="return validate_signup(this);" enctype='multipart/form-data'>
            Username: <input type="text" name="username"> <br>
            Password: <input type="password" id="pass1" name="password"> <br>
            Confirm password: <input type="password" name = "confirm_passsord" oninput="real_time_identical_passwords(this.value);"> <br>
            <p id ="pass_error"></p>
            <input type="submit" value="Signup">
        </form>
            <!-- TO DO: JavaScript input validation -->
        <h3> Sign In </h3>
        Already a member? Sign in here. <br>
        <form method="post" action="login_user.php" enctype='multipart/form-data'>
            Username: <input type="text" name="returning_username"> <br>
            Password: <input type="password" name="returning_password"> <br>
            <input type="submit" value="Signin">
        </form>
        <h3>Admin Page Login</h3>
        <form method='post' action="login_user.php" enctype='multipart/form-data'>
            Admin: <input type="text" name="admin"> <br>
            Password: <input type="password" name="admin_password"> <br>
            <input type="submit" value="Signin">
        </form>

    </body>
</html>
_END;
?>