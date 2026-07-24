<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit();
}

require_once "config.php";

// Pagination Settings
$records_per_page = 10;

// Current Page
if(isset($_GET['page']) && is_numeric($_GET['page'])){
    $page = $_GET['page'];
}else{
    $page = 1;
}
$start_from = ($page-1) * $records_per_page;


// Total Records
$count_query = "SELECT COUNT(*) AS total FROM students";
$count_result = mysqli_query($link,$count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $records_per_page);

// Total Students
$total_students = 0;
$result = mysqli_query($link, "SELECT COUNT(*) AS total FROM students");
if($row = mysqli_fetch_assoc($result)){
    $total_students = $row['total'];
}
// A+ Grade
$aplus_students = 0;
$result = mysqli_query($link, "SELECT COUNT(*) AS total FROM students WHERE grade='A+'");
if($row = mysqli_fetch_assoc($result)){
    $aplus_students = $row['total'];
}
// A Grade
$a_students = 0;
$result = mysqli_query($link, "SELECT COUNT(*) AS total FROM students WHERE grade='A'");
if($row = mysqli_fetch_assoc($result)){
    $a_students = $row['total'];
}
// Failed Students
$failed_students = 0;
$result = mysqli_query($link, "SELECT COUNT(*) AS total FROM students WHERE grade='F'");
if($row = mysqli_fetch_assoc($result)){
    $failed_students = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Records</title>
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
    background:linear-gradient(135deg,#2563eb,#4f46e5);
    padding:15px 0;
}
.navbar-brand{
    color:white !important;
    font-size:26px;
    font-weight:700;
}

.nav-btn{
    color:white;
    text-decoration:none;
    margin-right:20px;
    font-weight:500;
    transition:.3s;
}
.nav-btn:hover{
    color:#dbeafe;
}

.nav-link{
    color:white !important;
}

.logout-btn{
    border-radius:8px;
}

.stats-card{
    border:none;
    border-radius:15px;
    color:white;
    transition:.3s;
}

.stats-card:hover{
    transform:translateY(-5px);
}

.card1{
    background:#2563eb;
}

.card2{
    background:#16a34a;
}

.card3{
    background:#f59e0b;
}

.card4{
    background:#dc2626;
}

.stats-card i{
    font-size:40px;
}

.table{
    background:white;
}

.table thead{
    background:#2563eb;
    color:white;
}

.table th, .table td{
    vertical-align:middle;
}

.search-box{
    max-width:350px;
}

.action-btn{
    font-size:20px;
    margin-right:8px;
    text-decoration:none;
}

.view{
    color:#2563eb;
}

.edit{
    color:#16a34a;
}

.delete{
    color:#dc2626;
}

.footer-text{
    color:#888;
    font-size:14px;
}

</style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🎓 SHISHU VIHAR H.S. SCHOOL</a>
            
            <div class="d-flex align-items-center">
                <span class="text-white me-4"> Admin: 
                    <b><?php echo $_SESSION["username"]; ?></b>
                </span>
                
                <a href="dashboard.php" class="nav-btn">Dashboard</a> 
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">

<?php
if(isset($_GET["msg"]) && $_GET["msg"] == "deleted"){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Student record deleted successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}
?>
         <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold"> Student Records </h2>
                <p class="text-muted"> Manage, Search, Update & Delete Student Results </p>
            </div>
            
            <a href="create.php" class="btn btn-primary">
                <i class='bx bx-plus'></i> Add Student
            </a>
        </div>
        
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card card1">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h5>Total Students</h5> <h2><?php echo $total_students; ?></h2>
                        </div>
                        <i class='bx bxs-user'></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card card2">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h5>A+ Grade</h5> <h2><?php echo $aplus_students; ?></h2>
                        </div>
                        <i class='bx bxs-medal'></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card card3">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h5>A Grade</h5><h2><?php echo $a_students; ?></h2>
                        </div>
                        <i class='bx bxs-star'></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card card4">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h5>Failed</h5> <h2><?php echo $failed_students; ?></h2>
                        </div>
                        <i class='bx bxs-x-circle'></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search -->
         
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" id="searchInput" class="form-control search-box" placeholder="Search by Roll No, Name or Class">
        </div>
        
        <!-- Table -->
         
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="studentTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Percentage</th>
                        <th>Grade</th>
                        <th width="180">Action</th>
                    </tr>
                </thead> 
                <!-- Database records will come here -->
                <tbody>
<?php
$sql = "SELECT * FROM students ORDER BY id DESC LIMIT $start_from, $records_per_page";
$result = mysqli_query($link,$sql);
if(mysqli_num_rows($result)>0){ $sr = 1;
while($row = mysqli_fetch_assoc($result)){
?>
                
                <tr>
                    <td><?php echo $sr++; ?></td>
                    <td><?php echo $row["roll_no"]; ?></td>
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo $row["class_name"]; ?></td>
                    <td><?php echo $row["section"]; ?></td>
                    <td><?php echo number_format($row["percentage"],2); ?>%</td>
                    
                    <td>
<?php
$grade = $row["grade"];
if($grade=="A+"){
echo "<span class='badge bg-success'>$grade</span>";
}
elseif($grade=="A"){
echo "<span class='badge bg-primary'>$grade</span>";
}
elseif($grade=="B"){
echo "<span class='badge bg-warning text-dark'>$grade</span>";
}
elseif($grade=="C"){
echo "<span class='badge bg-info'>$grade</span>";
}
elseif($grade=="D"){
echo "<span class='badge bg-secondary'>$grade</span>";
}
else{
echo "<span class='badge bg-danger'>$grade</span>";
}
?>

                    </td>
                    
                    <td>
                        <a href="report.php?id=<?php echo $row['id'];?>" class="action-btn view" title="View">
                            <i class='bx bx-show'></i>
                        </a>
                        
                        <a href="update.php?id=<?php echo $row['id'];?>" class="action-btn edit" title="Edit">
                            <i class='bx bx-edit'></i>
                        </a>
                        
                        <a href="delete.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger btn-sm"  
                        onclick="return confirm('Are you sure you want to delete this student?');">
                        <i class='bx bx-trash'></i>
                        </a>

                    </td>
                </tr>
<?php
}
}
else{
?>
                <tr>
                    <td colspan="8" class="text-center">
                        No Student Records Found
                    </td>
                </tr>
<?php
}
?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Placeholder -->
     <nav>
        <ul class="pagination justify-content-center mt-4">
            <?php if($page > 1){ ?>
            <li class="page-item">
                <a class="page-link" href="index.php?page=<?php echo $page-1; ?>">Previous</a>
            </li>

            <?php } ?>
            
            <?php
            for($i=1; $i <= $total_pages; $i++){   
            ?>

            <li class="page-item <?php echo ($i==$page)?'active':''; ?>">
                <a class="page-link" href="index.php?page=<?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            </li>

            <?php } ?>

            <?php if($page < $total_pages){ ?>
            
            <li class="page-item">
                <a class="page-link" href="index.php?page=<?php echo $page+1; ?>"> Next</a>
            </li>

            <?php } ?>
        </ul>
    </nav>
    
    <p class="text-center footer-text"> Student Result Management System © 2026 </p>

</div>

<script>

document.getElementById("searchInput").addEventListener("keyup",function(){
let filter=this.value.toUpperCase();
let table=document.getElementById("studentTable");
let tr=table.getElementsByTagName("tr");
for(let i=1;i<tr.length;i++){

let td=tr[i].getElementsByTagName("td");
let found=false;
for(let j=1;j<=3;j++){

if(td[j]){
    
let txt=td[j].textContent||td[j].innerText;

if(txt.toUpperCase().indexOf(filter)>-1){
    found=true;
}
}
}
tr[i].style.display=found?"":"none";
}
});

</script>
</body>

</html>