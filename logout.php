<?php
    require_once 'session_management.php';
    end_session();
    echo '<script language="javascript">window.location.'
        . 'href ="index.php"</script>';
?>