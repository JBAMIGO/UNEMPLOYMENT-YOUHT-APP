<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>Premium Membership</title>
<style>
body { font-family: Arial; background:#C0C0C0; margin:0; padding:0; }
.container { max-width:800px; margin:50px auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 0 10px #999; }
button { padding:10px 15px; background:#0096FF; color:#fff; border:none; border-radius:5px; cursor:pointer; margin-top:10px; }
</style>
</head>
<body>
<div class="container">
  <h2>Premium Membership</h2>
  <p>Become a premium member to:</p>
  <ul>
    <li>Remove all ads</li>
    <li>Get tailored job recommendations</li>
    <li>Access exclusive interview tips</li>
    <li>Improve your skills with partner platforms</li>
  </ul>
  
  <button onclick="location.href='upgrade.html'">Subscribe Now</button>
  <button onclick="location.href='home.php'">Back to Home</button>
</div>
</body>
</html>
