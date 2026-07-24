<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit();
}
require_once "config.php";

// Variables
$roll_no = $name = $class_name = $section = "";
$maths = $science = $hindi = $english = $computer = "";
$total = $percentage = $grade = "";

// Error Variables
$roll_err = $name_err = $class_err = $section_err = "";
$maths_err = $science_err = $hindi_err = $english_err = $computer_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){             // Get Form Data
    $roll_no = trim($_POST["roll_no"]);
    $name = trim($_POST["name"]);
    $class_name = trim($_POST["class_name"]);
    $section = trim($_POST["section"]);
    $maths = trim($_POST["maths"]);
    $science = trim($_POST["science"]);
    $hindi = trim($_POST["hindi"]);
    $english = trim($_POST["english"]);
    $computer = trim($_POST["computer"]);
    $total = trim($_POST["total"]);
    $percentage = str_replace("%","",trim($_POST["percentage"]));
    $grade = trim($_POST["grade"]);

    // Validation
    if(empty($roll_no)){
        $roll_err = "Enter Roll Number.";
    }

    if(empty($name)){
        $name_err = "Enter Student Name.";
    }

    if(empty($class_name)){
        $class_err = "Select Class.";
    }

    if(empty($section)){
        $section_err = "Select Section.";
    }

    // Validate Marks
    function validMarks(string $mark): bool {
    return is_numeric($mark) && $mark >= 0 && $mark <= 100;
    }

    if(!validMarks($maths)){
        $maths_err = "Invalid";
    }

    if(!validMarks($science)){
        $science_err = "Invalid";
    }

    if(!validMarks($hindi)){
        $hindi_err = "Invalid";
    }

    if(!validMarks($english)){
        $english_err = "Invalid";
    }

    if(!validMarks($computer)){
        $computer_err = "Invalid";
    }

    if(empty($roll_err)){                                                        // Duplicate Roll Number

        $check_sql = "SELECT id FROM students WHERE roll_no = ?";

        if($stmt = mysqli_prepare($link,$check_sql)){

            mysqli_stmt_bind_param($stmt,"s",$roll_no);

            mysqli_stmt_execute($stmt);

            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) > 0){
                $roll_err = "Roll Number already exists.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Insert Student

    if( empty($roll_err) && empty($name_err) && empty($class_err) && empty($section_err) && empty($maths_err) && 
    empty($science_err) && empty($hindi_err) && empty($english_err) && empty($computer_err))
    {
        $sql = "INSERT INTO students ( roll_no, name, class_name, section, maths, science, hindi, english, computer, total, percentage, grade ) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,? )";

        if($stmt = mysqli_prepare($link,$sql)){
            mysqli_stmt_bind_param( $stmt, "ssssiiiiiids", $roll_no, $name, $class_name, $section, $maths, $science, $hindi, $english, $computer,$total, $percentage, $grade );

            if(mysqli_stmt_execute($stmt)){
                echo "<script>
                    alert('Student Added Successfully');
                    window.location='dashboard.php';
                </script>";
                exit();
            }else{
                echo "Something went wrong.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Poppins',sans-serif;
}

body{
    background:linear-gradient(135deg,#4f46e5,#3b82f6,#60a5fa);
    min-height:100vh;
}

.navbar{
    background:#ffffff;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

.navbar-brand{
    font-weight:600;
    color:#2563eb !important;
}

.card{
    border:none;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
}

.card-header{
    background:#2563eb;
    color:#fff;
    border-radius:20px 20px 0 0 !important;
}

.form-control,
.form-select{
    height:48px;
    border-radius:10px;
}

.form-control:focus,
.form-select:focus{
    border-color:#2563eb;
    box-shadow:none;
}

.btn-save{
    background:#2563eb;
    color:white;
    height:48px;
    border:none;
    border-radius:10px;
}

.btn-save:hover{
    background:#1d4ed8;
}

.readonly{
    background:#f1f5f9;
    font-weight:600;
}

.section-title{
    color:#2563eb;
    font-weight:600;
}

</style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class='bx bxs-graduation'></i>
                Student Result Management System
            </a>
            
            <div>   
                <span class="me-3"> Welcome, <b><?php echo $_SESSION["username"]; ?></b> </span>
                <a href="dashboard.php" class="btn btn-outline-primary btn-sm"> Dashboard </a>
            </div>
        </div>
    </nav>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0"> <i class='bx bx-user-plus'></i> Add New Student </h3>
                    </div>
                    
                    <div class="card-body">
                        <form method="post" action="create.php">
                            
                        <!-- Student Information -->
                         <h5 class="section-title mb-3"> Student Information </h5>
                         
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Roll Number</label>
                                <input type="text" name="roll_no" id="roll_no" class="form-control" placeholder="Enter Roll Number" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label>Student Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Student Name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Class</label>
                                <select name="class_name" id="class_name" class="form-select" required>
                                    <option value="">Select Class</option>
                                    <option>1st</option>
                                    <option>2nd</option>
                                    <option>3rd</option>
                                    <option>4th</option>
                                    <option>5th</option>
                                    <option>6th</option>
                                    <option>7th</option>
                                    <option>8th</option>
                                    <option>9th</option>
                                    <option>10th</option>
                                    <option>11th</option>
                                    <option>12th</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label>Section</label>
                                <select name="section" id="section" class="form-select" required>
                                    <option value="">Select Section</option>
                                    <option>A</option>
                                    <option>B</option>
                                    <option>C</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <h5 class="section-title mb-3"> Subject Marks </h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Maths</label>
                                <input type="number" name="maths" id="maths" class="form-control" min="0" max="100" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Science</label>
                                <input type="number" name="science" id="science" class="form-control" min="0" max="100" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label>Hindi</label>
                                <input type="number" name="hindi" id="hindi" class="form-control" min="0" max="100" required> 
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label>English</label>
                                <input type="number" name="english" id="english" class="form-control" min="0" max="100" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Computer</label>
                                <input type="number" name="computer" id="computer" class="form-control" min="0" max="100" required>
                            </div>
                        </div>
                        <hr>
                        
                        <h5 class="section-title mb-3"> Result Summary </h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Total</label>
                                <input type="text" name="total" id="total" class="form-control readonly" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Percentage</label>
                                <input type="text" name="percentage" id="percentage" class="form-control readonly" readonly>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label>Grade</label>
                                <input type="text" name="grade" id="grade" class="form-control readonly" readonly>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-save">
                                <i class='bx bx-save'></i>Save Student
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary"> Cancel </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

const marks = [ "maths", "science", "hindi", "english", "computer" ];

// Add event listener to every subject input
marks.forEach(function(subject){
    document.getElementById(subject).addEventListener("input", calculateResult);
});

function calculateResult(){
    let total = 0;
    // Read all subject marks
    for(let i = 0; i < marks.length; i++){
        let value = document.getElementById(marks[i]).value;
        // If field is empty, treat as 0
        if(value == ""){
            value = 0;
        }
        value = parseInt(value);
        // Prevent marks below 0
        if(value < 0){
            value = 0;
        }
        // Prevent marks above 100
        if(value > 100){
            value = 100;
        }
        total += value;
    }
    // Percentage
    let percentage = (total / 500) * 100;
    // Grade Calculation
    let grade = "";
    if(percentage >= 90){
        grade = "A+";
    }
    else if(percentage >= 80){
        grade = "A";
    }
    else if(percentage >= 70){
        grade = "B";
    }
    else if(percentage >= 60){
        grade = "C";
    }
    else if(percentage >= 50){
        grade = "D";
    }
    else{
        grade = "F";
    }
    // Display Results
    document.getElementById("total").value = total;
    document.getElementById("percentage").value =
    percentage.toFixed(2) + "%";
    document.getElementById("grade").value = grade;
}
</script>

</body>
</html>