<?php
session_start();
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
$roll_no = "";
$name = "";
$class_name = "";
$section = "";
$maths = "";
$science = "";
$hindi = "";
$english = "";
$computer = "";
$total = "";
$percentage = "";
$grade = "";
$success_msg = "";
$error_msg = "";

// Fetch Existing Student

$sql = "SELECT * FROM students WHERE id=?";
if($stmt = mysqli_prepare($link,$sql)){
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result)==1){
        $student = mysqli_fetch_assoc($result);
        $roll_no = $student["roll_no"];
        $name = $student["name"];
        $class_name = $student["class_name"];
        $section = $student["section"];
        $maths = $student["maths"];
        $science = $student["science"];
        $hindi = $student["hindi"];
        $english = $student["english"];
        $computer = $student["computer"];
        $total = $student["total"];
        $percentage = $student["percentage"];
        $grade = $student["grade"];
    }
    else{
        header("location:index.php");
        exit();
    }
        mysqli_stmt_close($stmt);
}
// Update Student Data

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $roll_no = trim($_POST["roll_no"]);
    $name = trim($_POST["name"]);
    $class_name = trim($_POST["class_name"]);
    $section = trim($_POST["section"]);
    $maths = $_POST["maths"];
    $science = $_POST["science"];
    $hindi = $_POST["hindi"];
    $english = $_POST["english"];
    $computer = $_POST["computer"];
    $total = $_POST["total"];
    $percentage = $_POST["percentage"];
    $grade = $_POST["grade"];

    // Validation

    if(empty($roll_no) || empty($name) || empty($class_name) || empty($section)){
        $error_msg = "Please fill all student details.";
    }
    else{
        $sql = "UPDATE students SET roll_no=?, name=?, class_name=?, section=?, maths=?, science=?, hindi=?, english=?,
        computer=?, total=?, percentage=?, grade=? WHERE id=?";
        
        if($stmt = mysqli_prepare($link,$sql)){
            mysqli_stmt_bind_param( $stmt, "ssssiiiiiidsi", $roll_no, $name, $class_name, $section, $maths, $science, $hindi, $english,
                $computer, $total, $percentage, $grade, $id );

            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Student record updated successfully!";
                header("refresh:2;url=index.php");
            }
            else{
                $error_msg = "Something went wrong. Please try again.";
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Student</title>
        
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

.update-card{
    background:white;
    border-radius:20px;
    padding:35px;
    box-shadow:0 15px 35px rgba(0,0,0,.12);
}

.heading{
    color:#2563eb;
    font-weight:600;
}

.form-control{
    height:45px;
}

.btn-update{
    background:#2563eb;
    color:white;
    border-radius:10px;
    height:45px;
}

.btn-update:hover{
    background:#1d4ed8;
    color:white;
}

</style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="update-card">
                    <h2 class="text-center heading mb-4">
                        <i class='bx bx-edit'></i>Update Student Result
                    </h2>
<?php
if(!empty($success_msg)){
echo '<div class="alert alert-success">'.$success_msg.'</div>';
}

if(!empty($error_msg)){
echo '<div class="alert alert-danger">'.$error_msg.'</div>';
}
?>

<form method="post">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Roll Number</label>
            <input type="text" name="roll_no" class="form-control" value="<?php echo $roll_no; ?>">
        </div>
        
        <div class="col-md-6 mb-3">
            <label>Student Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
        </div>
        
        <div class="col-md-6 mb-3">
            <label>Class</label>
            <input type="text" name="class_name" class="form-control" value="<?php echo $class_name; ?>">
        </div>
        
        <div class="col-md-6 mb-3">
            <label>Section</label>
            <input type="text" name="section" class="form-control" value="<?php echo $section; ?>">
        </div>
    </div>
<hr>
<h5 class="mb-3">Subject Marks</h5>
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Maths</label>
        <input type="number" id="maths" name="maths" class="form-control" value="<?php echo $maths; ?>">
    </div>
    
    <div class="col-md-4 mb-3">
        <label>Science</label>
        <input type="number" id="science" name="science" class="form-control" value="<?php echo $science; ?>">
    </div>
    
    <div class="col-md-4 mb-3">
        <label>Hindi</label>
        <input type="number" id="hindi" name="hindi" class="form-control" value="<?php echo $hindi; ?>">
    </div>
    
    <div class="col-md-4 mb-3">
        <label>English</label>
        <input type="number" id="english" name="english" class="form-control" value="<?php echo $english; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label>Computer</label>
        <input type="number" id="computer" name="computer" class="form-control" value="<?php echo $computer; ?>">
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-4">
        <label>Total</label>
        <input type="text" id="total" name="total" class="form-control" value="<?php echo $total; ?>"readonly>
    </div>
    
    <div class="col-md-4">
        <label>Percentage</label>
        <input type="text" id="percentage" name="percentage" class="form-control" value="<?php echo $percentage; ?>" readonly>
    </div>
    
    <div class="col-md-4">
        <label>Grade</label>
        <input type="text" id="grade" name="grade" class="form-control" value="<?php echo $grade; ?>" readonly>
    </div>
</div>
<br>
<div class="text-center">
    <button type="submit" class="btn btn-update px-5">Update Student</button>
    <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
</div>
</form>
</div>
</div>
</div>
</div>

<script>
function calculateResult(){
    let maths = Number(document.getElementById("maths").value) || 0;
    let science = Number(document.getElementById("science").value) || 0;
    let hindi = Number(document.getElementById("hindi").value) || 0;
    let english = Number(document.getElementById("english").value) || 0;
    let computer = Number(document.getElementById("computer").value) || 0;

    // Calculate Total
    let total = maths + science + hindi + english + computer;

    // Calculate Percentage
    // Total marks = 500
    let percentage = (total / 500) * 100;
    // Calculate Grade
    let grade;
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
    // Display Result
    document.getElementById("total").value = total;
    document.getElementById("percentage").value = percentage.toFixed(2)+"%";
    document.getElementById("grade").value = grade;
}
// Run calculation when marks change
document.getElementById("maths").addEventListener("input",calculateResult);
document.getElementById("science").addEventListener("input",calculateResult);
document.getElementById("hindi").addEventListener("input",calculateResult);
document.getElementById("english").addEventListener("input",calculateResult);
document.getElementById("computer").addEventListener("input",calculateResult);

// Calculate automatically when page loads
window.onload = calculateResult;

</script>

</body>
</html>