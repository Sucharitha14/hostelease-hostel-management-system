<?php
require 'config.inc.php';

if (isset($_POST['signup-submit'])) {

    $fname  = trim($_POST['student_fname']);
    $lname  = trim($_POST['student_lname']);
    $roll   = trim($_POST['student_roll_no']);
    $mobile = trim($_POST['mobile_no']);
    $dept   = trim($_POST['department']);
    $year   = trim($_POST['year_of_study']);
    $pwd    = trim($_POST['pwd']);
    $cpwd   = trim($_POST['confirmpwd']);

    // Check passwords match
    if ($pwd !== $cpwd) {
        header("Location: ../signup.php?error=pwdDontMatch");
        exit();
    }

    // Check if roll number already exists
    $stmt = $conn->prepare("SELECT id FROM students WHERE student_roll_no = ?");
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: ../signup.php?error=userTaken");
        exit();
    }

    // Hash password and insert
    $hashed = password_hash($pwd, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO students 
        (student_fname, student_lname, student_roll_no, mobile_no, department, year_of_study, pwd)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fname, $lname, $roll, $mobile, $dept, $year, $hashed);

    if ($stmt->execute()) {
        $_SESSION['roll']  = $roll;
        $_SESSION['fname'] = $fname;
        header("Location: ../home.php");
        exit();
    } else {
        header("Location: ../signup.php?error=dbError");
        exit();
    }
}

header("Location: ../signup.php");
exit();
?>