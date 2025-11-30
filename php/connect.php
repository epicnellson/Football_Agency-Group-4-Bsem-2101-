<?php
$serverName="localhost";
$userName="root";
$password="";
$conn=mysqli_connect($serverName,$userName,$password);
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

// Create A Database
$dbsql = "CREATE DATABASE football_agent_db";
if (mysqli_query($conn, $dbsql)) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

?>