<!-- Database Connection -->

<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'student_result_db');
 
// Connect to MySQL database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check database connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>