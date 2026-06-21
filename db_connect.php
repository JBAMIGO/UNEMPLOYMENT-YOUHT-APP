<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$conn = new mysqli("127.0.0.1", "root", "", "youth_platform", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
