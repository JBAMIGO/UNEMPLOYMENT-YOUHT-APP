<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $job_id = $_POST['job_id'];
    $apply_type = $_POST['apply_type'];

    if ($apply_type == 'upload') {
        // Handle CV upload
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            $filename = $_FILES['cv']['name'];
            $tmpname = $_FILES['cv']['tmp_name'];
            $destination = 'uploads/' . time() . '_' . $filename; // unique filename
            move_uploaded_file($tmpname, $destination);

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO applications (job_id, cv_path) VALUES (?, ?)");
            $stmt->bind_param("is", $job_id, $destination);
            $stmt->execute();

            echo "CV uploaded successfully!";
        } else {
            echo "Error uploading CV.";
        }

    } elseif ($apply_type == 'manual') {
        // Handle manual input
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $cover_letter = $_POST['cover_letter'] ?? '';

        // Simple validation
        if (empty($name) || empty($email) || empty($cover_letter)) {
            echo "Please fill in all fields.";
            exit;
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO applications (job_id, name, email, cover_letter) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $job_id, $name, $email, $cover_letter);
        $stmt->execute();

        echo "Application submitted successfully!";
    }
}
?>
