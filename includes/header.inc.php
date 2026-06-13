<?php
// includes/header.inc.php
// Drop this at the top of every protected student page:
// require 'includes/header.inc.php';
if(!isset($_SESSION['roll'])) {
    header("Location: index.php");
    exit();
}
$roll = $_SESSION['roll'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo isset($page_title) ? $page_title . ' — HostelEase' : 'HostelEase'; ?></title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
</head>
<body>
<div class="he-page">

  <nav class="he-navbar">
    <div class="he-brand">
      <div class="brand-icon"><i class="fas fa-home"></i></div>
      Hostel<span>Ease</span>
    </div>
    <ul class="he-nav-links">
      <li><a href="home.php" <?php echo basename($_SERVER['PHP_SELF'])=='home.php'?'class="active"':''; ?>><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="services.php" <?php echo basename($_SERVER['PHP_SELF'])=='services.php'?'class="active"':''; ?>><i class="fas fa-door-open" style="margin-right:5px"></i>Hostels</a></li>
      <li><a href="application_form.php" <?php echo basename($_SERVER['PHP_SELF'])=='application_form.php'?'class="active"':''; ?>><i class="fas fa-file-alt" style="margin-right:5px"></i>Apply</a></li>
      <li><a href="contact.php" <?php echo basename($_SERVER['PHP_SELF'])=='contact.php'?'class="active"':''; ?>><i class="fas fa-envelope" style="margin-right:5px"></i>Contact</a></li>
      <li><a href="message_user.php" <?php echo basename($_SERVER['PHP_SELF'])=='message_user.php'?'class="active"':''; ?>><i class="fas fa-bell" style="margin-right:5px"></i>Messages</a></li>
    </ul>
    <div class="he-nav-user">
      <div class="he-dropdown">
        <div class="he-avatar"><?php echo strtoupper(substr($roll,0,2)); ?></div>
        <div class="he-dropdown-menu">
          <a href="profile.php"><i class="fas fa-user" style="margin-right:8px;color:var(--primary)"></i>My Profile</a>
          <hr>
          <a href="includes/logout.inc.php"><i class="fas fa-sign-out-alt" style="margin-right:8px;color:var(--danger)"></i>Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <main class="he-main">