<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['roll'])) { header("Location: index.php"); exit(); }
$roll = $_SESSION['roll'];

$stmt = $conn->prepare("SELECT * FROM students WHERE student_roll_no = ?");
$stmt->bind_param("s", $roll); $stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$stmt2 = $conn->prepare("SELECT * FROM room_details WHERE student_roll_no = ?");
$stmt2->bind_param("s", $roll); $stmt2->execute();
$room = $stmt2->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Profile — HostelEase</title>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
</head>
<body>
<div class="he-page">
  <nav class="he-navbar">
    <div class="he-brand"><div class="brand-icon"><i class="fas fa-home"></i></div>Hostel<span>Ease</span></div>
    <ul class="he-nav-links">
      <li><a href="home.php"><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="services.php"><i class="fas fa-door-open" style="margin-right:5px"></i>Hostels</a></li>
      <li><a href="application_form.php"><i class="fas fa-file-alt" style="margin-right:5px"></i>Apply</a></li>
      <li><a href="contact.php"><i class="fas fa-envelope" style="margin-right:5px"></i>Contact</a></li>
      <li><a href="message_user.php"><i class="fas fa-bell" style="margin-right:5px"></i>Messages</a></li>
    </ul>
    <div class="he-nav-user">
      <div class="he-dropdown">
        <div class="he-avatar"><?php echo strtoupper(substr($student['student_fname'],0,1).substr($student['student_lname'],0,1)); ?></div>
        <div class="he-dropdown-menu">
          <a href="profile.php" class="active"><i class="fas fa-user" style="margin-right:8px;color:var(--primary)"></i>My Profile</a>
          <hr>
          <a href="includes/logout.inc.php"><i class="fas fa-sign-out-alt" style="margin-right:8px;color:var(--danger)"></i>Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <main class="he-main">
    <p class="he-section-title">My Profile</p>

    <div style="max-width:700px;">
      <!-- Profile Header -->
      <div class="he-profile-header">
        <div class="he-profile-avatar">
          <?php echo strtoupper(substr($student['student_fname'],0,1).substr($student['student_lname'],0,1)); ?>
        </div>
        <div>
          <div class="he-profile-name"><?php echo htmlspecialchars($student['student_fname'].' '.$student['student_lname']); ?></div>
          <div class="he-profile-sub"><?php echo htmlspecialchars($student['department']); ?> &bull; Year <?php echo htmlspecialchars($student['year_of_study']); ?></div>
        </div>
      </div>

      <!-- Profile Details -->
      <div class="he-profile-body">
        <p class="he-section-title" style="margin-bottom:1rem;">Personal Details</p>
        <div class="he-info-grid">
          <div class="he-info-item"><label>First Name</label><p><?php echo htmlspecialchars($student['student_fname']); ?></p></div>
          <div class="he-info-item"><label>Last Name</label><p><?php echo htmlspecialchars($student['student_lname']); ?></p></div>
          <div class="he-info-item"><label>Roll No</label><p><?php echo htmlspecialchars($student['student_roll_no']); ?></p></div>
          <div class="he-info-item"><label>Mobile</label><p><?php echo htmlspecialchars($student['mobile_no']); ?></p></div>
          <div class="he-info-item"><label>Department</label><p><?php echo htmlspecialchars($student['department']); ?></p></div>
          <div class="he-info-item"><label>Year of Study</label><p><?php echo htmlspecialchars($student['year_of_study']); ?></p></div>
          <div class="he-info-item"><label>Registered On</label><p><?php echo date('d M Y', strtotime($student['created_at'])); ?></p></div>
        </div>

        <hr style="border:none;border-top:1px solid var(--border);margin:1.5rem 0;">

        <p class="he-section-title" style="margin-bottom:1rem;">Room Allocation</p>
        <?php if($room): ?>
          <div class="he-info-grid">
            <div class="he-info-item"><label>Room No</label><p><?php echo htmlspecialchars($room['room_no']); ?></p></div>
            <div class="he-info-item"><label>Hostel</label><p><?php echo htmlspecialchars($room['hostel_name']); ?></p></div>
            <div class="he-info-item"><label>Room Type</label><p><?php echo htmlspecialchars($room['room_type']); ?></p></div>
            <div class="he-info-item"><label>Allotted On</label><p><?php echo htmlspecialchars($room['allocation_date']); ?></p></div>
          </div>
          <span class="he-badge he-badge-green" style="margin-top:0.8rem;display:inline-block;"><i class="fas fa-check-circle"></i> Room Allocated</span>
        <?php else: ?>
          <div class="he-alert he-alert-warn">
            <i class="fas fa-clock"></i> No room allocated yet.
            <a href="application_form.php" style="font-weight:700;margin-left:8px;">Apply now →</a>
          </div>
        <?php endif; ?>

        <div style="margin-top:1.5rem;">
          <a href="includes/logout.inc.php" class="he-btn he-btn-outline" style="border-color:var(--danger);color:var(--danger);">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </main>

  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>
</div>
</body>
</html>