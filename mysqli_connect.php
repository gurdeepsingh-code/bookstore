<?php

$mysqli = new mysqli("localhost", "root", "", "bookstorecreator");

if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    unset($mysqli);
} else {
    $mysqli->set_charset('utf8');
}

?>