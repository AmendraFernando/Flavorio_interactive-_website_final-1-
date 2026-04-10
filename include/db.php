<?php

$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "flavorio_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("<div style='font-family:sans-serif;padding:40px;color:red;'>
            <h2>❌ Database Connection Failed</h2>
            <p>" . mysqli_connect_error() . "</p>
            <p>Make sure: <br>
            1. WAMP/XAMPP is running <br>
            2. You imported <strong>database.sql</strong> in phpMyAdmin <br>
            3. The database name is <strong>flavorio_db</strong>
            </p>
         </div>");
}
?>
