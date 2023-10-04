<?php
$mysqli = new mysqli("localhost", "root", "", "soccer manager");
if ($mysqli->connect_errno) {
    printf("Unable to connect to the database:<br /> %s: %s", $mysqli->connect_errno, $mysqli->connect_error);
    exit();
}
?>