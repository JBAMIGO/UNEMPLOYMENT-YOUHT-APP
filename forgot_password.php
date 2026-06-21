<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('db_connect.php');

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        // Generate code
        $code = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // Save to DB
        $update = $conn->prepare("UPDATE users SET reset_code = ?, reset_expiry = ? WHERE email = ?");
        $update->bind_param("sss", $code, $expiry, $email);
        $update->execute();

        $subject = "Your Password Reset Code";
        $message = "Your verification code is: $code\n\nThis code expires in 15 minutes.";

        // PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'YOUR_EMAIL@gmail.com';
            $mail->Password = 'YOUR_APP_PASSWORD';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('YOUR_EMAIL@gmail.com', 'Password Reset');
            $mail->addAddress($email);

            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();

            header("Location: verify_code.php?email=" . urlencode($email));
            exit();

        } catch (Exception $e) {
            $error = "Email could not be sent. Please try again later.";
        }

    } else {
        $error = "This email is not registered.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<style>
body { font-family: Arial; background: #f4f4f4; }
.container {
    max-width: 400px;
    margin: 80px auto;
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
}
input[type=submit] {
    background: #8497d9;
    color: white;
    border: none;
    cursor: pointer;
}
.error {
    color: red;
    text-align: center;
}
</style>
</head>
<body>

<div class="container">
    <h2>Forgot Password</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <input type="submit" value="Send Verification Code">
    </form>
</div>

</body>
</html>