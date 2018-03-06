<?php 
$servername = "localhost";
$username = "mustafa2_bloodhu";
$password = "m02800280c";
$dbname = "mustafa2_bloodhub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
} 
