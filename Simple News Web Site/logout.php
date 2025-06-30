<?php 
    session_start();
    session_destroy();
    header("Location: startpage.php");
?>