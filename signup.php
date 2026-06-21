<?php
session_start();
include('db_connect.php'); 

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $location = $_POST['location'];
    $highest_qualification = $_POST['highest_qualification'];
    $skills = $_POST['skills'];
    $additional_skill = $_POST['additional_skill'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $qualification = $_POST['qualification'];

    // Check if terms are accepted
    if (!isset($_POST['terms'])) {
        echo "<script>alert('You must accept the Terms of Service before signing up.'); window.history.back();</script>";
        exit();
    }

    // Check if the email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "An account with this email already exists. Please log in instead.";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users 
            (name, surname, email, password, location, highest_qualification, skills, additional_skill, address, age, qualification)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssssis", 
            $name, $surname, $email, $password, $location, $highest_qualification, 
            $skills, $additional_skill, $address, $age, $qualification
        );

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            echo "<script>
                alert('Sign up successful! Welcome to the Youth Employment Platform.');
                window.location.href = 'home.php';
            </script>";
            exit();
        } else {
            $error = "Error creating account. Please try again.";
        }

        $stmt->close();
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Youth Employment Platform - Sign Up</title>
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
            width: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .login-box h2 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-box input[type="text"],
        .login-box input[type="email"],
        .login-box input[type="password"],
        .login-box input[type="number"],
        .login-box select,
        .login-box input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 6px 0;
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
            margin-bottom: 10px;
            font-weight: bold;
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
        <h2>Sign Up</h2>
        <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <input type="text" name="name" placeholder="First Name" required>
            <input type="text" name="surname" placeholder="Surname" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="location" placeholder="Location">
            <input type="text" name="highest_qualification" placeholder="Highest Qualification">
            <input type="text" name="skills" placeholder="Skills">
            <input type="text" name="additional_skill" placeholder="Additional Skill">
            <input type="text" name="address" placeholder="Address">
            <input type="number" name="age" placeholder="Age">
            <select name="qualification" required>
                <option value="">Select Qualification</option>
                <option value="Grade 10-11">Grade 10-11</option>
                <option value="Matric">Matric</option>
                <option value="Tertiary">Tertiary</option>
            </select>

            <div class="form-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="terms.html" target="_blank">Terms of Service</a></label>
            </div>

            <input type="submit" value="Sign Up">
        </form>

        <div class="signup-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
</div>

</body>
</html>
