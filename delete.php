<?php
session_start();
// Check Admin Login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit();
}

require_once "config.php";

// Check ID
if(!isset($_GET["id"]) || empty($_GET["id"])){
    header("location:index.php");
    exit();
}
$id = $_GET["id"];

// Delete Student Record

$sql = "DELETE FROM students WHERE id=?";

if($stmt = mysqli_prepare($link,$sql)){

    mysqli_stmt_bind_param($stmt,"i",$id);

    if(mysqli_stmt_execute($stmt)){

        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header("location:index.php?msg=deleted");
        exit();
    }
    else{
        echo "Something went wrong. Please try again.";
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);
?>