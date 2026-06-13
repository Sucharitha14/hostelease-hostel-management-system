<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['manager'])) {
    header("Location: login-hostel_manager.php");
    exit();
}
$manager = $_SESSION['manager'];

// Stats
$total_students = $conn->query("SELECT COUNT(*) as cnt FROM students")->fetch_assoc()['cnt'];
$total_rooms    = $conn->query("SELECT COUNT(*) as cnt FROM rooms")->fetch_assoc()['cnt'];
$empty_rooms    = $conn->query("SELECT COUNT(*) as cnt FROM rooms WHERE room_status='empty'")->fetch_assoc()['cnt'];
$occupied_rooms = $conn->query("SELECT COUNT(*) as cnt FROM rooms WHERE room_status='occupied'")->fetch_assoc()['cnt'];
$messages       = $conn->query("SELECT COUNT(*) as cnt FROM messages WHERE reply IS NULL")->fetch_assoc()['cnt'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Warden Dashboard — HostelEase</title>
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
      <span style="font-size:0.7rem; background:#ede9fe; color:#7c3aed; padding:2px 8px; border-radius:20px; margin-left:6px;">Warden</span>
    </div>
    <ul class="he-nav-links">
      <li><a href="manager_home.php" class="active"><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="allocated_rooms.php"><i class="fas fa-door-closed" style="margin-right:5px"></i>Allocations</a></li>
      <li><a href="empty_rooms.php"><i class="fas fa-door-open" style="margin-right:5px"></i>Empty Rooms</a></li>
      <li><a href="allocate_room.php"><i class="fas fa-plus-circle" style="margin-right:5px"></i>Allocate Room</a></li>
      <li><a href="message_hostel_manager.php"><i class="fas fa-envelope" style="margin-right:5px"></i>Messages
        <?php if($messages > 0): ?>
          <span style="background:var(--primary);color:#fff;border-radius:50%;padding:1px 6px;font-size:0.72rem;margin-left:4px;"><?php echo $messages; ?></span>
        <?php endif; ?>
      </a></li>
    </ul>
    <div class="he-nav-user">
      <div class="he-dropdown">
        <div class="he-avatar"><i class="fas fa-user-shield"></i></div>
        <div class="he-dropdown-menu">
          <a href="#"><i class="fas fa-user" style="margin-right:8px;color:var(--primary)"></i><?php echo htmlspecialchars($manager); ?></a>
          <hr>
          <a href="includes/logout.inc.php"><i class="fas fa-sign-out-alt" style="margin-right:8px;color:var(--danger)"></i>Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <main class="he-main">

    <!-- Banner -->
    <div class="he-banner">
      <div class="he-banner-text">
        <h2>Welcome, <?php echo htmlspecialchars($manager); ?>! 🌸</h2>
        <p>Here's a live overview of HostelEase today.</p>
        <a href="allocate_room.php" class="he-btn he-btn-outline" style="margin-top:1rem;color:#fff;border-color:rgba(255,255,255,0.6);">
          <i class="fas fa-plus-circle"></i> Allocate a Room
        </a>
      </div>
      <div class="he-banner-emoji">🏠</div>
    </div>

    <!-- Stats -->
    <div class="he-stats">
      <div class="he-stat-card">
        <div class="he-stat-icon pink">👩‍🎓</div>
        <div class="he-stat-info">
          <div class="value"><?php echo $total_students; ?></div>
          <div class="label">Total Students</div>
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
        <div class="he-stat-icon green">🛏️</div>
        <div class="he-stat-info">
          <div class="value"><?php echo $occupied_rooms; ?></div>
          <div class="label">Occupied Rooms</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <p class="he-section-title">Quick Actions</p>
    <div class="he-action-grid">
      <a href="allocate_room.php" class="he-action-card">
        <span class="action-icon">🛏️</span>
        <div class="action-label">Allocate Room</div>
        <div class="action-desc">Assign a room to a student</div>
      </a>
      <a href="allocated_rooms.php" class="he-action-card">
        <span class="action-icon">📋</span>
        <div class="action-label">View Allocations</div>
        <div class="action-desc">See all allocated rooms</div>
      </a>
      <a href="empty_rooms.php" class="he-action-card">
        <span class="action-icon">🚪</span>
        <div class="action-label">Empty Rooms</div>
        <div class="action-desc">Browse available rooms</div>
      </a>
      <a href="vacate_room.php" class="he-action-card">
        <span class="action-icon">📤</span>
        <div class="action-label">Vacate Room</div>
        <div class="action-desc">Remove a student from a room</div>
      </a>
      <a href="message_hostel_manager.php" class="he-action-card">
        <span class="action-icon">💬</span>
        <div class="action-label">Messages</div>
        <div class="action-desc"><?php echo $messages; ?> pending repl<?php echo $messages==1?'y':'ies'; ?></div>
      </a>
    </div>

    <!-- Recent Allocations -->
    <p class="he-section-title">Recent Allocations</p>
    <div class="he-card">
      <div class="he-table-wrap">
        <table class="he-table">
          <thead>
            <tr>
              <th>Roll No</th>
              <th>Room No</th>
              <th>Hostel</th>
              <th>Room Type</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $rows = $conn->query("SELECT rd.*, s.student_fname, s.student_lname 
                                  FROM room_details rd 
                                  JOIN students s ON rd.student_roll_no = s.student_roll_no 
                                  ORDER BY rd.allocation_date DESC LIMIT 10");
            if ($rows && $rows->num_rows > 0):
              while($row = $rows->fetch_assoc()):
            ?>
            <tr>
              <td><strong><?php echo htmlspecialchars($row['student_roll_no']); ?></strong><br>
                <small style="color:var(--text-light)"><?php echo htmlspecialchars($row['student_fname'].' '.$row['student_lname']); ?></small>
              </td>
              <td><span class="he-badge he-badge-pink"><?php echo htmlspecialchars($row['room_no']); ?></span></td>
              <td><?php echo htmlspecialchars($row['hostel_name']); ?></td>
              <td><?php echo htmlspecialchars($row['room_type']); ?></td>
              <td><?php echo htmlspecialchars($row['allocation_date']); ?></td>
              <td>
                <a href="vacate_rooms.php?roll=<?php echo urlencode($row['student_roll_no']); ?>" 
                   class="he-btn he-btn-danger he-btn-sm"
                   onclick="return confirm('Vacate this room?')">
                  <i class="fas fa-sign-out-alt"></i> Vacate
                </a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
              <td colspan="6" style="text-align:center; color:var(--text-light); padding:2rem;">
                No allocations yet. <a href="allocate_room.php">Allocate a room →</a>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>

</div>
</body>
</html>