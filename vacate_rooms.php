<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['manager'])) { header("Location: login-hostel_manager.php"); exit(); }
 
$success = $error = '';
$student = $room = null;
 
// Pre-fill from URL param
$prefill_roll = isset($_GET['roll']) ? trim($_GET['roll']) : '';
$prefill_room = isset($_GET['room']) ? trim($_GET['room']) : '';
 
// If room param given, find the student in it
if ($prefill_room && !$prefill_roll) {
    $s = $conn->prepare("SELECT student_roll_no FROM room_details WHERE room_no = ?");
    $s->bind_param("s", $prefill_room); $s->execute();
    $res = $s->get_result()->fetch_assoc();
    if ($res) $prefill_roll = $res['student_roll_no'];
}
 
// Lookup student allocation
if ($prefill_roll) {
    $stmt = $conn->prepare("SELECT rd.*, s.student_fname, s.student_lname, s.department 
                            FROM room_details rd JOIN students s ON rd.student_roll_no = s.student_roll_no 
                            WHERE rd.student_roll_no = ?");
    $stmt->bind_param("s", $prefill_roll); $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
}
 
if (isset($_POST['vacate-submit'])) {
    $roll    = trim($_POST['student_roll_no']);
    $room_no = trim($_POST['room_no']);
 
    $del = $conn->prepare("DELETE FROM room_details WHERE student_roll_no = ?");
    $del->bind_param("s", $roll); $del->execute();
 
    $upd = $conn->prepare("UPDATE rooms SET room_status='empty' WHERE room_no = ?");
    $upd->bind_param("s", $room_no); $upd->execute();
 
    $success = "Room {$room_no} has been vacated successfully.";
    $student = null;
    $prefill_roll = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vacate Room — HostelEase</title>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
</head>
<body>
<div class="he-page">
  <nav class="he-navbar">
    <div class="he-brand"><div class="brand-icon"><i class="fas fa-home"></i></div>Hostel<span>Ease</span><span style="font-size:0.7rem;background:#ede9fe;color:#7c3aed;padding:2px 8px;border-radius:20px;margin-left:6px;">Warden</span></div>
    <ul class="he-nav-links">
      <li><a href="manager_home.php"><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="allocated_rooms.php"><i class="fas fa-door-closed" style="margin-right:5px"></i>Allocations</a></li>
      <li><a href="empty_rooms.php"><i class="fas fa-door-open" style="margin-right:5px"></i>Empty Rooms</a></li>
      <li><a href="allocate_room.php"><i class="fas fa-plus-circle" style="margin-right:5px"></i>Allocate Room</a></li>
      <li><a href="message_hostel_manager.php"><i class="fas fa-envelope" style="margin-right:5px"></i>Messages</a></li>
    </ul>
    <div class="he-nav-user">
      <div class="he-dropdown">
        <div class="he-avatar"><i class="fas fa-user-shield"></i></div>
        <div class="he-dropdown-menu">
          <a href="includes/logout.inc.php"><i class="fas fa-sign-out-alt" style="margin-right:8px;color:var(--danger)"></i>Logout</a>
        </div>
      </div>
    </div>
  </nav>
 
  <main class="he-main">
    <p class="he-section-title">Vacate a Room</p>
 
    <?php if($success): ?>
      <div class="he-alert he-alert-success"><i class="fas fa-check-circle"></i><?php echo $success; ?> <a href="allocated_rooms.php">View all allocations →</a></div>
    <?php endif; ?>
    <?php if($error): ?>
      <div class="he-alert he-alert-error"><i class="fas fa-exclamation-circle"></i><?php echo $error; ?></div>
    <?php endif; ?>
 
    <div style="max-width:600px;">
      <!-- Search -->
      <div class="he-card" style="margin-bottom:1.2rem;">
        <h3 style="font-size:1rem;margin-bottom:1rem;">Find Student by Roll No</h3>
        <form method="GET" style="display:flex;gap:0.8rem;">
          <div class="he-input-wrap" style="flex:1;">
            <i class="fas fa-search"></i>
            <input type="text" name="roll" value="<?php echo htmlspecialchars($prefill_roll); ?>" placeholder="Enter student roll no" />
          </div>
          <button type="submit" class="he-btn he-btn-outline" style="white-space:nowrap;">
            <i class="fas fa-search"></i> Find
          </button>
        </form>
      </div>
 
      <?php if($student): ?>
      <!-- Student card -->
      <div class="he-card" style="border:2px solid #fca5a5;">
        <div class="he-profile-header" style="border-radius:var(--radius);margin:-1.6rem -1.6rem 1.2rem;">
          <div class="he-profile-avatar"><?php echo strtoupper(substr($student['student_fname'],0,1)); ?></div>
          <div>
            <div class="he-profile-name"><?php echo htmlspecialchars($student['student_fname'].' '.$student['student_lname']); ?></div>
            <div class="he-profile-sub"><?php echo htmlspecialchars($student['student_roll_no']); ?> &bull; <?php echo htmlspecialchars($student['department']); ?></div>
          </div>
        </div>
        <div class="he-info-grid" style="margin-bottom:1.2rem;">
          <div class="he-info-item"><label>Room No</label><p><?php echo htmlspecialchars($student['room_no']); ?></p></div>
          <div class="he-info-item"><label>Hostel</label><p><?php echo htmlspecialchars($student['hostel_name']); ?></p></div>
          <div class="he-info-item"><label>Room Type</label><p><?php echo htmlspecialchars($student['room_type']); ?></p></div>
          <div class="he-info-item"><label>Allocated On</label><p><?php echo htmlspecialchars($student['allocation_date']); ?></p></div>
        </div>
        <div class="he-alert he-alert-warn" style="margin-bottom:1rem;">
          <i class="fas fa-exclamation-triangle"></i> This will free up the room and remove the student's allocation.
        </div>
        <form method="POST">
          <input type="hidden" name="student_roll_no" value="<?php echo htmlspecialchars($student['student_roll_no']); ?>" />
          <input type="hidden" name="room_no" value="<?php echo htmlspecialchars($student['room_no']); ?>" />
          <button type="submit" name="vacate-submit" class="he-btn he-btn-danger"
                  onclick="return confirm('Are you sure you want to vacate this room?')">
            <i class="fas fa-sign-out-alt"></i> Confirm Vacate Room
          </button>
        </form>
      </div>
      <?php elseif($prefill_roll && !$success): ?>
      <div class="he-alert he-alert-error"><i class="fas fa-exclamation-circle"></i> No allocation found for this roll number.</div>
      <?php endif; ?>
    </div>
  </main>
 
  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>
</div>
</body>
</html>