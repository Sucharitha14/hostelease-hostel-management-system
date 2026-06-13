<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['manager'])) { header("Location: login-hostel_manager.php"); exit(); }

$success = $error = '';

if (isset($_POST['allocate-submit'])) {
    $roll      = trim($_POST['student_roll_no']);
    $room_no   = trim($_POST['room_no']);

    // Check student exists
    $s = $conn->prepare("SELECT * FROM students WHERE student_roll_no = ?");
    $s->bind_param("s", $roll); $s->execute();
    $student = $s->get_result()->fetch_assoc();

    if (!$student) {
        $error = "No student found with Roll No: $roll";
    } else {
        // Check student not already allocated
        $check = $conn->prepare("SELECT id FROM room_details WHERE student_roll_no = ?");
        $check->bind_param("s", $roll); $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "This student already has a room allocated.";
        } else {
            // Get room info
            $r = $conn->prepare("SELECT * FROM rooms WHERE room_no = ? AND room_status = 'empty'");
            $r->bind_param("s", $room_no); $r->execute();
            $room = $r->get_result()->fetch_assoc();

            if (!$room) {
                $error = "Room $room_no is not available or doesn't exist.";
            } else {
                // Allocate
                $ins = $conn->prepare("INSERT INTO room_details (student_roll_no, room_no, hostel_name, room_type, allocation_date) VALUES (?, ?, ?, ?, CURDATE())");
                $ins->bind_param("ssss", $roll, $room_no, $room['hostel_name'], $room['room_type']);
                $ins->execute();

                // Update room status
                $conn->query("UPDATE rooms SET room_status='occupied' WHERE room_no='$room_no'");
                $success = "Room {$room_no} successfully allocated to {$student['student_fname']} {$student['student_lname']}!";
            }
        }
    }
}

// Get empty rooms for dropdown
$empty_rooms = $conn->query("SELECT * FROM rooms WHERE room_status='empty' ORDER BY room_no");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Allocate Room — HostelEase</title>
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
      <li><a href="allocate_room.php" class="active"><i class="fas fa-plus-circle" style="margin-right:5px"></i>Allocate Room</a></li>
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
    <p class="he-section-title">Allocate Room to Student</p>

    <?php if($success): ?>
      <div class="he-alert he-alert-success"><i class="fas fa-check-circle"></i><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
      <div class="he-alert he-alert-error"><i class="fas fa-exclamation-circle"></i><?php echo $error; ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

      <!-- Allocation Form -->
      <div class="he-card">
        <h3 style="margin-bottom:1.2rem;font-size:1rem;">Assign a Room</h3>
        <form method="POST">
          <div class="he-form-group">
            <label>Student Roll No</label>
            <div class="he-input-wrap">
              <i class="fas fa-id-card"></i>
              <input type="text" name="student_roll_no" placeholder="e.g. P03AC24S126032" required />
            </div>
          </div>
          <div class="he-form-group">
            <label>Select Room</label>
            <div class="he-input-wrap">
              <i class="fas fa-door-open"></i>
              <select name="room_no" required style="padding-left:2.6rem;">
                <option value="">-- Select an empty room --</option>
                <?php if($empty_rooms && $empty_rooms->num_rows > 0):
                  while($r = $empty_rooms->fetch_assoc()): ?>
                  <option value="<?php echo $r['room_no']; ?>">
                    <?php echo $r['room_no'].' — '.$r['hostel_name'].' ('.$r['room_type'].')'; ?>
                  </option>
                <?php endwhile; else: ?>
                  <option disabled>No empty rooms available</option>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <button type="submit" name="allocate-submit" class="he-btn he-btn-primary">
            <i class="fas fa-check"></i> Allocate Room
          </button>
        </form>
      </div>

      <!-- Available Rooms Summary -->
      <div class="he-card">
        <h3 style="margin-bottom:1.2rem;font-size:1rem;">Available Rooms Summary</h3>
        <div class="he-table-wrap">
          <table class="he-table">
            <thead><tr><th>Room No</th><th>Hostel</th><th>Type</th><th>Capacity</th></tr></thead>
            <tbody>
              <?php
              $avail = $conn->query("SELECT * FROM rooms WHERE room_status='empty' ORDER BY room_no");
              if($avail && $avail->num_rows > 0):
                while($r = $avail->fetch_assoc()): ?>
                <tr>
                  <td><span class="he-badge he-badge-green"><?php echo htmlspecialchars($r['room_no']); ?></span></td>
                  <td><?php echo htmlspecialchars($r['hostel_name']); ?></td>
                  <td><?php echo htmlspecialchars($r['room_type']); ?></td>
                  <td><?php echo $r['capacity']; ?> person(s)</td>
                </tr>
              <?php endwhile; else: ?>
                <tr><td colspan="4" style="text-align:center;color:var(--text-light);padding:1.5rem;">No empty rooms available</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
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