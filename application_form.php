<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['roll'])) { header("Location: index.php"); exit(); }
$roll = $_SESSION['roll'];

$success = $error = '';

// Check if already allocated
$check = $conn->prepare("SELECT id FROM room_details WHERE student_roll_no = ?");
$check->bind_param("s", $roll); $check->execute();
$already = $check->get_result()->num_rows > 0;

if (isset($_POST['apply-submit']) && !$already) {
    $room_no = trim($_POST['room_no']);
    $reason  = trim($_POST['reason']);

    $r = $conn->prepare("SELECT * FROM rooms WHERE room_no = ? AND room_status = 'empty'");
    $r->bind_param("s", $room_no); $r->execute();
    $room = $r->get_result()->fetch_assoc();

    if (!$room) {
        $error = "Room $room_no is not available. Please choose another.";
    } else {
        $ins = $conn->prepare("INSERT INTO room_details (student_roll_no, room_no, hostel_name, room_type, allocation_date) VALUES (?, ?, ?, ?, CURDATE())");
        $ins->bind_param("ssss", $roll, $room_no, $room['hostel_name'], $room['room_type']);
        $ins->execute();
        $conn->query("UPDATE rooms SET room_status='occupied' WHERE room_no='".mysqli_real_escape_string($conn,$room_no)."'");
        $success = "Room {$room_no} in {$room['hostel_name']} successfully applied and allocated!";
        $already = true;
    }
}

$prefill_room = isset($_GET['room']) ? trim($_GET['room']) : '';
$empty_rooms  = $conn->query("SELECT * FROM rooms WHERE room_status='empty' ORDER BY hostel_name, room_no");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Apply for Room — HostelEase</title>
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
      <li><a href="application_form.php" class="active"><i class="fas fa-file-alt" style="margin-right:5px"></i>Apply</a></li>
      <li><a href="contact.php"><i class="fas fa-envelope" style="margin-right:5px"></i>Contact</a></li>
      <li><a href="message_user.php"><i class="fas fa-bell" style="margin-right:5px"></i>Messages</a></li>
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
    <p class="he-section-title">Apply for a Room</p>
    <div style="max-width:600px;">

      <?php if($success): ?>
        <div class="he-alert he-alert-success"><i class="fas fa-check-circle"></i><?php echo $success; ?> <a href="home.php">Go to dashboard →</a></div>
      <?php endif; ?>
      <?php if($error): ?>
        <div class="he-alert he-alert-error"><i class="fas fa-exclamation-circle"></i><?php echo $error; ?></div>
      <?php endif; ?>

      <?php if($already && !$success): ?>
        <div class="he-alert he-alert-warn">
          <i class="fas fa-info-circle"></i> You already have a room allocated. <a href="profile.php">View your profile →</a>
        </div>
      <?php elseif(!$already): ?>
      <div class="he-card">
        <p style="color:var(--text-mid);font-size:0.9rem;margin-bottom:1.2rem;">
          Select an available room below to apply. Your allocation will be confirmed immediately.
        </p>
        <form method="POST">
          <div class="he-form-group">
            <label>Your Roll No</label>
            <div class="he-input-wrap">
              <i class="fas fa-id-card"></i>
              <input type="text" value="<?php echo htmlspecialchars($roll); ?>" disabled />
            </div>
          </div>
          <div class="he-form-group">
            <label>Select Room</label>
            <div class="he-input-wrap">
              <i class="fas fa-door-open"></i>
              <select name="room_no" required style="padding-left:2.6rem;">
                <option value="">-- Choose a room --</option>
                <?php if($empty_rooms && $empty_rooms->num_rows > 0):
                  while($r = $empty_rooms->fetch_assoc()): ?>
                  <option value="<?php echo $r['room_no']; ?>" <?php echo $prefill_room===$r['room_no']?'selected':''; ?>>
                    <?php echo $r['room_no'].' — '.$r['hostel_name'].' ('.$r['room_type'].', '.$r['capacity'].' person)'; ?>
                  </option>
                <?php endwhile; else: ?>
                  <option disabled>No rooms available currently</option>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <div class="he-form-group">
            <label>Reason for applying (optional)</label>
            <div class="he-input-wrap" style="align-items:flex-start;">
              <i class="fas fa-comment" style="position:absolute;left:14px;top:14px;"></i>
              <textarea name="reason" rows="3" placeholder="Any special request or note..."
                style="width:100%;padding:0.7rem 1rem 0.7rem 2.6rem;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-family:var(--font-body);font-size:0.93rem;resize:vertical;outline:none;background:#fafafa;"
                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>
          </div>
          <button type="submit" name="apply-submit" class="he-btn he-btn-primary">
            <i class="fas fa-check-circle"></i> Submit Application
          </button>
        </form>
      </div>
      <?php endif; ?>
    </div>
  </main>

  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>
</div>
</body>
</html>