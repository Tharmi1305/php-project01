<?php
$conn = mysqli_connect("localhost", "root", "", "php-project");

if (!$conn) {
    // Try without password
    $conn = mysqli_connect("localhost", "root", "", "php-project");
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
?>