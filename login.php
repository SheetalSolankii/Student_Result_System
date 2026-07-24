<!-- This page is use for admin authentication and he has access of: Dashboard, Add Student, Update Student and Delete Student  -->

<?php
session_start();

// If already logged in, go to dashboard
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

require_once "config.php";

$username = "";
$password = "";
$username_err = "";
$password_err = "";
$login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate
    if(empty($username_err) && empty($password_err)){

        $sql = "SELECT id, username, password FROM admin WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){

                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){

                    $row = mysqli_fetch_assoc($result);

                    // Compare password
                    if($password == $row["password"]){

                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $row["id"];
                        $_SESSION["username"] = $row["username"];

                        header("location: dashboard.php");

                    }else{

                        $login_err = "Invalid username or password.";

                    }

                }else{
                    $login_err = "Invalid username or password.";
                }

            }else{
                echo "Something went wrong.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    text-decoration: none;
    list-style: none;
}

body{
    background:linear-gradient(135deg,#4f46e5,#3b82f6,#60a5fa);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    width:100%;
    max-width:700px;
    padding:30px;
    background:#fff;   
    border-radius:20px;
    box-shadow:0 20px 40px rgba(0,0,0,.15);
    overflow:hidden;
}

form{
    width: 100%;
}

.input-box{
    position:relative;
    margin-bottom:25px;
}

.input-box i{
    position:absolute;
    right:18px;
    top:50%;
    transform:translateY(-50%);
    color:#666;
    font-size:20px;
}

.input-box input{
    height:48px;
    padding-right:45px;
}

.btn-login{
    height:48px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-weight:600;
    transition:.3s;
}

.btn-login:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.forgot-link{
    text-align:right;
    margin-bottom:20px;
}

.forgot-link a{
    color:#2563eb;
    text-decoration:none;
    font-size:14px;
}

.forgot-link a:hover{
    text-decoration:underline;
}
    </style>

</head>

<body>
    <div class="login-box">
        <i class='bx bxs-graduation display-4 text-primary d-block text-center'></i>
        <h3 class="text-center fw-bold"> Student Result Management System </h3>
        <p class="text-center text-muted"> Admin Login Portal</p>
<?php
if(!empty($login_err)){
    echo '<div class="alert alert-danger">'.$login_err.'</div>';
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <div class="input-box">
    <input type="text" name="username" placeholder="Enter Username" class="form-control" required>
    <i class='bx bxs-user'></i>
    </div>
    
    <div class="input-box">
        <input type="password" name="password" placeholder="Enter Your Password" class="form-control" required>
        <i class="bx bxs-lock-alt"></i>
    </div>

    <div class="forgot-link">
        <a href="forgot_password.php">Forgot Password</a>
    </div>
    
    <div class="d-grid">
        <input type="submit" value="Login" class="btn btn-login">
    </div>
    </form>
</div>
</body>
</html>