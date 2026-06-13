<?php
require 'config.inc.php';

if (isset($_POST['login-submit'])) {

    $roll = trim($_POST['student_roll_no']);
    $pwd  = trim($_POST['pwd']);

    // Check if student exists
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_roll_no = ?");
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: ../index.php?error=noUser");
        exit();
    }

    $student = $result->fetch_assoc();

    // Verify password
    if (!password_verify($pwd, $student['pwd'])) {
        header("Location: ../index.php?error=wrongPassword");
        exit();
    }

    // Set session and redirect
    $_SESSION['roll']  = $student['student_roll_no'];
    $_SESSION['fname'] = $student['student_fname'];
    header("Location: ../home.php");
    exit();
}

header("Location: ../index.php");
exit();
?>