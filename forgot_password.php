<?php
require_once "config.php";

$username = "";
$new_password = "";
$confirm_password = "";
$username_err = "";
$new_password_err = "";
$confirm_password_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Validate New Password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter new password.";
    } elseif(strlen(trim($_POST["new_password"])) < 5){
        $new_password_err = "Password must be at least 5 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    // Validate Confirm Password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);

        if($new_password != $confirm_password){
            $confirm_password_err = "Passwords do not match.";
        }
    }
    // Continue only if no errors
    if(empty($username_err) && empty($new_password_err) && empty($confirm_password_err)){
        // Check username
        $sql = "SELECT id FROM admin WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1){
                    mysqli_stmt_close($stmt);
                    // Update Password
                    $update_sql = "UPDATE admin SET password = ? WHERE username = ?";
                    if($update_stmt = mysqli_prepare($link, $update_sql)){
                        mysqli_stmt_bind_param( $update_stmt, "ss", $new_password, $username );
                        if(mysqli_stmt_execute($update_stmt)){
                            header("refresh:2;url=login.php");
                            $success_msg = "Password updated successfully. Redirecting to Login...";
                        }else{
                            echo "Something went wrong.";
                        }
                        mysqli_stmt_close($update_stmt);
                    }
                }else{
                    $username_err = "Username does not exist.";
                }
            }
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>

*{
    font-family:Poppins,sans-serif;
}

body{
    background:linear-gradient(135deg,#4f46e5,#3b82f6,#60a5fa);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.box{
    background:white;
    width:100%;
    max-width:500px;
    padding:35px;
    border-radius:20px;
    box-shadow:0 20px 40px rgba(0,0,0,.15);
}

.input-box{
    position:relative;
    margin-bottom:20px;
}

.input-box input{
    padding-right:40px;
    height:48px;
}

.input-box i{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    color:#777;
}

.btn-update{
    background:#2563eb;
    color:white;
    height:48px;
    border:none;
    border-radius:10px;
    width:100%;
}

.btn-update:hover{
    background:#1d4ed8;
}
        </style>
</head>

<body>
    <div class="box">
        <h3 class="text-center mb-3"> Reset Password </h3>
        
        <p class="text-center text-muted"> Enter your username and create a new password </p>
<?php
if(!empty($success_msg)){
    echo "<div class='alert alert-success'>$success_msg</div>";
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

<div class="input-box">
    <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>">
    <i class='bx bxs-user'></i>
    
    <div class="text-danger"><?php echo $username_err; ?></div>
</div>

<div class="input-box">
    <input type="password" name="new_password" class="form-control" placeholder="New Password">
    <i class='bx bxs-lock-alt'></i>
    
    <div class="text-danger"><?php echo $new_password_err; ?></div>
</div>

<div class="input-box">
    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
    <i class='bx bxs-lock'></i>
    
    <div class="text-danger"><?php echo $confirm_password_err; ?></div>
</div>

<input type="submit" value="Update Password" class="btn-update">
<br><br>

<a href="login.php" class="btn btn-secondary w-100">Back to Login</a>

</form>

</div>

</body>
</html>