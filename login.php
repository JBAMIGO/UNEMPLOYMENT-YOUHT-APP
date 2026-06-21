<?php
session_start();
include('db_connect.php'); 

if(isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if($stmt->num_rows > 0) {
        $stmt->fetch();
        if(password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: home.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with this email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Youth Employment Platform - Login</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: url('picture.jpg') no-repeat center center fixed;
    background-size: cover;
}

.page-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 100vh;
    padding: 50px;
}

.login-info {
    color: white;
    max-width: 400px;
    line-height: 1.6;
}

.login-box {
    background: rgba(255,255,255,0.85);
    padding: 40px;
    border-radius: 10px;
    width: 350px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

.login-box h2 {
    margin-top: 0;
    margin-bottom: 20px;
    text-align: center;
}

.login-box input[type="email"],
.login-box input[type="password"],
.login-box input[type="submit"] {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #999;
}

.login-box input[type="submit"] {
    background-color: #8497d9;
    color: white;
    border: none;
    cursor: pointer;
}

.login-box input[type="submit"]:hover {
    background-color: #333;
}

.signup-link {
    text-align: center;
    margin-top: 10px;
}
.signup-link a {
    color: #444;
    text-decoration: none;
    font-weight: bold;
}
.signup-link a:hover {
    text-decoration: underline;
}

.error {
    color: red;
    text-align: center;
}
</style>
</head>
<body>

<div class="page-container">
    <div class="login-info">
        <h1>Youth Employment Platform</h1>
        <p>Empowering young people to find the right job opportunities.</p>
        <p>Upload your qualifications and get matched to relevant roles.</p>
        <p>Improve your skills and career prospects with tailored guidance.</p>
        <p>Connect directly with employers looking for youth talent.</p>
        <p>Step into your future with confidence and support.</p>
    </div>

    <div class="login-box">
        <h2>Login</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
		<p style="text-align:center;">
    <a href="forgot_password.php">Forgot your password?</a>
</p>

        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
    </div>
</div>

</body>
</html>
