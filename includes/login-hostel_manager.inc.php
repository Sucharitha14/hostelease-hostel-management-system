<?php
require 'config.inc.php';

if (isset($_POST['login-submit'])) {

    $uname = trim($_POST['manager_uname']);
    $pwd   = trim($_POST['pwd']);

    $stmt = $conn->prepare("SELECT * FROM managers WHERE manager_uname = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: ../login-hostel_manager.php?error=wrongCredentials");
        exit();
    }

    $manager = $result->fetch_assoc();

    if (!password_verify($pwd, $manager['pwd'])) {
        header("Location: ../login-hostel_manager.php?error=wrongCredentials");
        exit();
    }

    $_SESSION['manager'] = $manager['manager_uname'];
    header("Location: ../manager_home.php");
    exit();

} else {
    header("Location: ../login-hostel_manager.php");
    exit();
}
?>