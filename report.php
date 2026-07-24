<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit();
}

require_once "config.php";

// Check ID
if(!isset($_GET["id"]) || empty($_GET["id"])){
    header("location: index.php");
    exit();
}

$id = $_GET["id"];

$student = [];

// Fetch Student
$sql = "SELECT * FROM students WHERE id = ?";

if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1){
        $student = mysqli_fetch_assoc($result);
    }else{
        header("location:index.php?msg=notfound");
        exit();
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Poppins',sans-serif;
}

body{
    background:#f4f7fc;
}

.navbar{
    background:#2563eb;
}

.navbar-brand{
    color:white !important;
    font-weight:600;
}

.card{
    border:none;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,.12);
}

.card-header{
    background:#2563eb;
    color:white;
    border-radius:20px 20px 0 0 !important;
}

.info-box{
    background:#eef4ff;
    border-radius:12px;
    padding:15px;
    text-align:center;
}

.info-box h6{
    color:#666;
}

.info-box h5{
    font-weight:600;
}

.summary-card{
    color:white;
    border-radius:15px;
    padding:20px;
    text-align:center;
}

.total-card{
    background:#2563eb;
}

.percent-card{
    background:#16a34a;
}

.grade-card{
    background:#f59e0b;
}

.table th{
    background:#2563eb;
    color:white;
}
@media print{
    @page {
        margin: 10mm;
    }
    .navbar, .btn {
        display:none !important;
    }
    body{
        background:white;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .card{
        box-shadow:none !important;
        border:none !important;
    }
}


</style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class='bx bxs-graduation'></i> Student Result Management System
            </a>
            
            <div><a href="index.php" class="btn btn-light btn-sm"> Back </a> </div>
        </div>
    </nav>
    
    <div class="container py-5">
        <div class="card">
            <div class="card-header">
                <h3><i class='bx bx-id-card'></i> Student Report Card </h3>
            </div>
            
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">🎓 Shishu Vihar H.S. School</h2>
                    <h5> Student Result Report Card </h5>
                    
                    <p class="text-muted"> Academic Session: 2026 </p>
                    <hr>
                </div>
                
                <!-- Student Information -->
                 <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="info-box">
                            <h6>Roll Number</h6>
                            <h5><?php echo $student["roll_no"]; ?></h5>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box">
                            <h6>Student Name</h6>
                            <h5><?php echo $student["name"]; ?></h5>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box">
                            <h6>Class</h6>
                            <h5><?php echo $student["class_name"]; ?></h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="info-box">
                            <h6>Section</h6>
                            <h5><?php echo $student["section"]; ?></h5>
                        </div>
                    </div>
                </div>
                <hr>
                
                <h4 class="mb-3">Subject Marks</h4>
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td>Maths</td><td><?php echo $student["maths"]; ?></td>
                        </tr>
                        <tr>
                            <td>Science</td><td><?php echo $student["science"]; ?></td>
                        </tr>
                        <tr>
                            <td>Hindi</td><td><?php echo $student["hindi"]; ?></td>
                        </tr>
                        <tr>
                            <td>English</td><td><?php echo $student["english"]; ?></td>
                        </tr>
                        <tr>
                            <td>Computer</td><td><?php echo $student["computer"]; ?></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h4 class="mb-4">Result Summary</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="summary-card total-card">
                            <h5>Total Marks</h5><h2><?php echo $student["total"]; ?></h2>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="summary-card percent-card">
                            <h5>Percentage</h5>
                            <h2><?php echo number_format($student["percentage"],2); ?>%</h2>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="summary-card grade-card">
                            <h5>Grade</h5><h2><?php echo $student["grade"]; ?></h2>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 text-center">
                    <a href="index.php" class="btn btn-secondary">
                        <i class='bx bx-arrow-back'></i> Back
                    </a>
                    
                    <a href="update.php?id=<?php echo $student['id']; ?>" class="btn btn-warning">
                        <i class='bx bx-edit'></i>Edit Student
                    </a>
                    
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class='bx bx-printer'></i> Print 
                    </button>
                
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>