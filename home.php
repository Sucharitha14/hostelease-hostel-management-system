<?php
require 'includes/config.inc.php';
if(!isset($_SESSION['roll'])) {
    header("Location: index.php");
    exit();
}
$roll = $_SESSION['roll'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard — HostelEase</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
</head>
<body>

<div class="he-page">

  <!-- Navbar -->
  <nav class="he-navbar">
    <div class="he-brand">
      <div class="brand-icon"><i class="fas fa-home"></i></div>
      Hostel<span>Ease</span>
    </div>

    <ul class="he-nav-links">
      <li><a href="home.php" class="active"><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="services.php"><i class="fas fa-door-open" style="margin-right:5px"></i>Hostels</a></li>
      <li><a href="application_form.php"><i class="fas fa-file-alt" style="margin-right:5px"></i>Apply</a></li>
      <li><a href="contact.php"><i class="fas fa-envelope" style="margin-right:5px"></i>Contact</a></li>
      <li><a href="message_user.php"><i class="fas fa-bell" style="margin-right:5px"></i>Messages</a></li>
    </ul>

    <div class="he-nav-user">
      <div class="he-dropdown">
        <div class="he-avatar">
          <?php echo strtoupper(substr($roll, 0, 2)); ?>
        </div>
        <div class="he-dropdown-menu">
          <a href="profile.php"><i class="fas fa-user" style="margin-right:8px;color:var(--primary)"></i>My Profile</a>
          <hr>
          <a href="includes/logout.inc.php"><i class="fas fa-sign-out-alt" style="margin-right:8px;color:var(--danger)"></i>Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="he-main">

    <!-- Hero Banner -->
    <div class="he-banner">
      <div class="he-banner-text">
        <h2>Welcome back, <?php echo htmlspecialchars($roll); ?>! 🌸</h2>
        <p>Here's everything happening at your hostel today.</p>
        <a href="application_form.php" class="he-btn he-btn-outline" style="margin-top:1rem; color:#fff; border-color:rgba(255,255,255,0.6);">
          <i class="fas fa-plus-circle"></i> Apply for a Room
        </a>
      </div>
      <div class="he-banner-emoji">🏠</div>
    </div>

    <!-- Stats -->
    <?php
      // Fetch room allocation status for this student
      $stmt = $conn->prepare("SELECT * FROM room_details WHERE student_roll_no = ?");
      $stmt->bind_param("s", $roll);
      $stmt->execute();
      $result = $stmt->get_result();
      $room = $result->fetch_assoc();
      $stmt->close();

      // Count total rooms
      $total_res = $conn->query("SELECT COUNT(*) as cnt FROM rooms");
      $total_rooms = $total_res ? $total_res->fetch_assoc()['cnt'] : '—';

      // Count empty rooms
      $empty_res = $conn->query("SELECT COUNT(*) as cnt FROM rooms WHERE room_status = 'empty'");
      $empty_rooms = $empty_res ? $empty_res->fetch_assoc()['cnt'] : '—';
    ?>

    <div class="he-stats">
      <div class="he-stat-card">
        <div class="he-stat-icon pink">🛏️</div>
        <div class="he-stat-info">
          <div class="value"><?php echo $room ? htmlspecialchars($room['room_no']) : 'N/A'; ?></div>
          <div class="label">Your Room</div>
        </div>
      </div>
      <div class="he-stat-card">
        <div class="he-stat-icon purple">🏠</div>
        <div class="he-stat-info">
          <div class="value"><?php echo $total_rooms; ?></div>
          <div class="label">Total Rooms</div>
        </div>
      </div>
      <div class="he-stat-card">
        <div class="he-stat-icon teal">✅</div>
        <div class="he-stat-info">
          <div class="value"><?php echo $empty_rooms; ?></div>
          <div class="label">Available Rooms</div>
        </div>
      </div>
      <div class="he-stat-card">
        <div class="he-stat-icon green">📋</div>
        <div class="he-stat-info">
          <div class="value"><?php echo $room ? '<span style="color:var(--success)">Active</span>' : '<span style="color:var(--warning)">Pending</span>'; ?></div>
          <div class="label">Allocation Status</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <p class="he-section-title">Quick Actions</p>
    <div class="he-action-grid">
      <a href="application_form.php" class="he-action-card">
        <span class="action-icon">📝</span>
        <div class="action-label">Apply for Room</div>
        <div class="action-desc">Submit a room request</div>
      </a>
      <a href="services.php" class="he-action-card">
        <span class="action-icon">🏨</span>
        <div class="action-label">View Hostels</div>
        <div class="action-desc">Browse available hostels</div>
      </a>
      <a href="profile.php" class="he-action-card">
        <span class="action-icon">👤</span>
        <div class="action-label">My Profile</div>
        <div class="action-desc">View & update your info</div>
      </a>
      <a href="contact.php" class="he-action-card">
        <span class="action-icon">💬</span>
        <div class="action-label">Contact Warden</div>
        <div class="action-desc">Send a message</div>
      </a>
      <a href="message_user.php" class="he-action-card">
        <span class="action-icon">🔔</span>
        <div class="action-label">My Messages</div>
        <div class="action-desc">Replies from warden</div>
      </a>
    </div>

    <!-- Room Status Card -->
    <p class="he-section-title">My Room Status</p>
    <div class="he-card">
      <?php if($room): ?>
        <div class="he-info-grid">
          <div class="he-info-item">
            <label>Room Number</label>
            <p><?php echo htmlspecialchars($room['room_no']); ?></p>
          </div>
          <div class="he-info-item">
            <label>Hostel Name</label>
            <p><?php echo isset($room['hostel_name']) ? htmlspecialchars($room['hostel_name']) : '—'; ?></p>
          </div>
          <div class="he-info-item">
            <label>Room Type</label>
            <p><?php echo isset($room['room_type']) ? htmlspecialchars($room['room_type']) : '—'; ?></p>
          </div>
          <div class="he-info-item">
            <label>Allocation Date</label>
            <p><?php echo isset($room['allocation_date']) ? htmlspecialchars($room['allocation_date']) : '—'; ?></p>
          </div>
        </div>
        <div style="margin-top:1.2rem;">
          <span class="he-badge he-badge-green"><i class="fas fa-check-circle"></i> Room Allocated</span>
        </div>
      <?php else: ?>
        <div class="he-alert he-alert-warn">
          <i class="fas fa-clock"></i>
          You don't have a room allocated yet.
          <a href="application_form.php" style="font-weight:700; margin-left:8px;">Apply now →</a>
        </div>
      <?php endif; ?>
    </div>

  </main>

  <!-- Footer -->
  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase &mdash; Administrative Management College, Bangalore.
    Developed as part of MCA Mini Project (III Semester) &mdash; Bangalore University.</p>
  </footer>

</div>
</body>
</html>