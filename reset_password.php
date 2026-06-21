<?php
include('db_connect.php');

$email = $_GET['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL, reset_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $password, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Password reset successfully! You can now log in.'); window.location='login.php';</script>";
    } else {
        $error = "Error resetting password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<style>
body { font-family: Arial; background-color: #f4f4f4; }
.container { max-width: 400px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
input[type=submit] { background: #8497d9; color: #fff; border: none; cursor: pointer; }
.error { color: red; text-align: center; }
</style>
</head>
<body>

<div class="container">
    <h2>Reset Your Password</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="password" name="password" placeholder="Enter new password" required>
        <input type="submit" value="Reset Password">
    </form>
</div>

</body>
</html>
