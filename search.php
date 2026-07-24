<!-- Allows students/admin to search results -->

<?php
require_once "config.php";
$student = null;
$error = "";

if(isset($_GET["roll_no"])){
    $roll_no = trim($_GET["roll_no"]);
    if(!empty($roll_no)){
        $sql = "SELECT * FROM students WHERE roll_no = ?";
        if($stmt = mysqli_prepare($link,$sql)){
            mysqli_stmt_bind_param($stmt,"s",$roll_no);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result)==1){
                $student = mysqli_fetch_assoc($result);
            }else{
                $error = "No Result Found.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Poppins',sans-serif;
}

body{
    background:linear-gradient(135deg,#2563eb,#60a5fa);
    min-height:100vh;
}

.search-card{
    background:white;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
    padding:35px;
}

.search-title{
    color:#2563eb;
    font-weight:700;
}

.btn-search{
    background:#2563eb;
    color:white;
    border:none;
    height:48px;
}

.btn-search:hover{
    background:#1d4ed8;
    color:white;
}

.result-card{
    margin-top:30px;
    border-radius:15px;
    border:none;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}

.table th{
    background:#2563eb;
    color:white;
}

</style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="search-card">

                    <div class="text-center mb-4">
                        <i class='bx bxs-graduation display-4 text-primary'></i>
                        <h2 class="search-title">Student Result Management System</h2>
                        <p class="text-muted">Search Student Result</p>
                    </div>
                    
                    <form method="GET">
                        <div class="input-group">
                            <input type="text" name="roll_no" class="form-control form-control-lg" placeholder="Enter Roll Number" required>
                            <button class="btn btn-search px-4"><i class='bx bx-search'></i>Search</button>
                        </div>
                    </form>

<?php
if(!empty($error)){
echo "<div class='alert alert-danger mt-4'>$error</div>";
}
?>

<?php if($student){ ?>

        <div class="card result-card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class='bx bx-id-card'></i>Student Report Card</h4>
            </div>
            
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">🎓 Shishu Vihar H.S. School</h2>
                    <h5>Academic Result</h5>
                    <p class="text-muted">Academic Session : 2026</p>
                    <hr>
                </div>
                
                <!-- Student Details -->
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Roll Number</small>
                            <h5><?php echo $student["roll_no"]; ?></h5>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Student Name</small>
                            <h5><?php echo $student["name"]; ?></h5>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Class</small>
                            <h5><?php echo $student["class_name"]; ?></h5>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Section</small>
                            <h5><?php echo $student["section"]; ?></h5>
                        </div>
                    </div>
                </div>
                <hr>
                
                <!-- Subject Marks -->
                 <h5 class="mb-3">Subject Marks</h5>
                 <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td>Maths</td>
                            <td><?php echo $student["maths"]; ?></td>
                        </tr>
                        
                        <tr>
                            <td>Science</td>
                            <td><?php echo $student["science"]; ?></td>
                        </tr>
                        
                        <tr>
                            <td>Hindi</td>
                            <td><?php echo $student["hindi"]; ?></td>
                        </tr>

                        <tr>
                            <td>English</td>
                            <td><?php echo $student["english"]; ?></td>
                        </tr>

                        <tr>
                            <td>Computer</td>
                            <td><?php echo $student["computer"]; ?></td>
                        </tr>
                    
                    </tbody>
                </table>
                <hr>

        <!-- Summary -->
        <div class="row text-center">
            <div class="col-md-4">
                <div class="p-3 rounded text-white bg-primary">
                    <h6>Total Marks</h6>
                    <h2><?php echo $student["total"]; ?></h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded text-white bg-success">
                    <h6>Percentage</h6>
                    <h2><?php echo number_format($student["percentage"],2); ?>%</h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded text-white bg-warning">
                    <h6>Grade</h6>
                    <h2><?php echo $student["grade"]; ?></h2>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button
                class="btn btn-primary" onclick="window.print()">
                <i class='bx bx-printer'></i>Print Result
            </button>
        </div>
    </div>
</div>

<?php } ?>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>