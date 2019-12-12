<?php
require_once 'session_management.php';
session_start();
$menu = nil;
if(validate_admin()){
    $name = $_SESSION['username'];
    $menu =  "<li> Welcome admin $name.</li>"
            ."<li><a href=''>Logout</a></li>"
            ."<li><a href=''>Virus Checker</a></li>"
            ."<li><a href=''>Update Signatures</a></li>";
} else if (validate_user()){
    $name = $_SESSION['username'];
    $menu =  "<li> Welcome $name.</li>"
            ."<li><a href=''>Logout</a></li>"
            ."<li><a href=''>Virus Checker</a></li>";
} else {
    $menu = "<p> Please create a new account or login "
          . "to use our service.</p>"; 
}

$navbar =
    "<nav id='navigation'>"
      ."<ul>"
        .$menu
      ."</ul>"
    ."</nav>";

?>