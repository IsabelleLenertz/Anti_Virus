<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1> Welcome to free virus check </h1> <br>
        <h2> The perfect free tool to make sure your file is indeed a virus! </h2> <br>
        <br>
        <br>
        <h3> Sign Up Form </h3>
        <form method="post" action="add_user.php" enctype='multipart/form-data'>
            Username: <input type="text" name="username"> <br>
            Password: <input type="password" name="password"> <br>
            Confirm password: <input type=password" name="confirm_password"> <br>
            <input type="submit" value="Signup">
        </form>
            <!-- TO DO: JavaScript input validation -->
        <h3> Sign In </h3>
        Already a member? Sign in here. <br>
        <form method="post" action="virus_checker.php" encrype='multipart/form-data'>
            Username: <input type="text" name="returning_username"> <br>
            Password: <input type="password" name="returning_password"> <br>
            <input type="submit" value="Signin">
        </form>
        <h3>Admin Page Login</h3>
        <form method='post' action="admin.php"e ncrype='multipart/form-data'>
            Admin: <input type="text" name="admin"> <br>
            Password: <input type="password" name="admin_password"> <br>
            <input type="submit" value="Signin">
        </form>

    </body>
</html>
