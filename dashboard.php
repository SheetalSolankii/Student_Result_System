<!-- It is the main admin home page -->

<?php

// Protect dashboard page
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: login.php");
    exit();
}

require_once "config.php";

$total_students = 0;     // Total students
$sql = "SELECT COUNT(id) AS total FROM students";

if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_assoc($result);
    $total_students = $row['total'];
}

$passed_students = 0;     // Passed students
$sql = "SELECT COUNT(id) AS passed FROM students WHERE grade != 'F'";

if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_assoc($result);
    $passed_students = $row['passed'];
}

$failed_students = 0;     // Failed students
$sql = "SELECT COUNT(id) AS failed FROM students WHERE grade = 'F'";

if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_assoc($result);
    $failed_students = $row['failed'];
}

$average_percentage = 0;  // Average percentage
$sql = "SELECT AVG(percentage) AS avg_percentage FROM students";

if($result = mysqli_query($link, $sql)){
    $row = mysqli_fetch_assoc($result);
    $average_percentage = round($row['avg_percentage'],2);
}

mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Poppins',sans-serif;
}

body{
    background:#f1f5f9;
}

.navbar{
    background:linear-gradient(135deg,#4f46e5,#3b82f6);
}

.navbar-brand{
    color:white !important;
    font-weight:600;
}

.logout{
    color:white;
    text-decoration:none;
}

.container{
    margin-top:40px;
}

.welcome{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}

.card-box{
    border:none;
    border-radius:20px;
    padding:25px;
    color:white;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.card-box i{
    font-size:40px;
}

.total{
    background:#2563eb;
}

.pass{
    background:#16a34a;
}

.fail{
    background:#dc2626;
}

.average{
    background:#9333ea;
}

.action-card{
    background:white;
    border-radius:20px;
    padding:25px;
    text-align:center;
    transition:.3s;
}

.action-card:hover{
    transform:translateY(-5px);
}

.action-card i{
    font-size:45px;
    color:#2563eb;
}

</style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-5">
            <a class="navbar-brand" href="#">
                <i class='bx bxs-graduation'></i> Student Result Management System </a>
                
                <div>
                    <span class="text-white me-3"> Welcome, <?php echo $_SESSION["username"]; ?></span>
                    
                    <a href="logout.php" class="logout">
                        <i class='bx bx-log-out'></i>Logout
                    </a>
                </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="welcome mb-4">
            <h3> Dashboard </h3>
            
            <p class="text-muted mb-0">Manage students, results and reports from here</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card-box total">
                    <i class='bx bxs-user'></i>
                    
                    <h2> <?php echo $total_students; ?> </h2>
                    <p>Total Students</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-box pass">
                    <i class='bx bxs-check-circle'></i>
                    
                    <h2> <?php echo $passed_students; ?> </h2>
                    <p>Passed Students</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-box fail">
                    <i class='bx bxs-x-circle'></i>
                    
                    <h2> <?php echo $failed_students; ?> </h2>
                    <p>Failed Students</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-box average">
                    <i class='bx bxs-bar-chart-alt-2'></i>
                    
                    <h2> <?php echo $average_percentage; ?>% </h2>
                    <p>Average Percentage</p>
                </div>
            </div>
        </div>
        
        <div class="row mt-5 g-4">
            <div class="col-md-4">
                <a href="create.php" class="text-decoration-none">
                    <div class="action-card">
                        <i class='bx bx-user-plus'></i>

                        <h5 class="mt-3"> Add Student </h5>
                    
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="index.php" class="text-decoration-none">
                    <div class="action-card">
                        <i class='bx bx-list-ul'></i>
                        
                        <h5 class="mt-3"> Manage Students </h5>
                    
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="search.php" class="text-decoration-none">
                    <div class="action-card">
                        <i class='bx bx-search'></i>
                        <h5 class="mt-3"> Search Result </h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>