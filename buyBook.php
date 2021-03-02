<?php

    session_start();
    
    $id = $_GET['bookid'];

    $_SESSION['bookid'] = $id;

    header("Location: checkout.php");

?>