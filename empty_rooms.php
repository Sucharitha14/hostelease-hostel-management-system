<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['manager'])) { header("Location: login-hostel_manager.php"); exit(); }
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_status ASC, room_no ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rooms — HostelEase</title>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
  <style>
    .room-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1rem; }
    .room-tile { background:var(--bg-card); border:1.5px solid var(--border); border-radius:var(--radius); padding:1.2rem; text-align:center; transition:var(--transition); }
    .room-tile:hover { transform:translateY(-3px); box-shadow:var(--shadow-lg); }
    .room-tile.occupied { border-color:#fca5a5; background:#fff5f5; }
    .room-tile.empty    { border-color:#6ee7b7; background:#f0fdf4; }
    .room-number { font-size:1.4rem; font-weight:700; color:var(--text-dark); }
    .room-icon   { font-size:2rem; margin-bottom:0.5rem; }
    .filter-bar  { display:flex; gap:0.8rem; margin-bottom:1.2rem; flex-wrap:wrap; }
    .filter-bar button { padding:0.4rem 1.1rem; border-radius:100px; border:1.5px solid var(--border); background:#fff; cursor:pointer; font-size:0.85rem; font-family:var(--font-body); transition:var(--transition); }
    .filter-bar button.active, .filter-bar button:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
  </style>
</head>
<body>
<div class="he-page">
  <nav class="he-navbar">
    <div class="he-brand"><div class="brand-icon"><i class="fas fa-home"></i></div>Hostel<span>Ease</span><span style="font-size:0.7rem;background:#ede9fe;color:#7c3aed;padding:2px 8px;border-radius:20px;margin-left:6px;">Warden</span></div>
    <ul class="he-nav-links">
      <li><a href="manager_home.php"><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="allocated_rooms.php"><i class="fas fa-door-closed" style="margin-right:5px"></i>Allocations</a></li>
      <li><a href="empty_rooms.php" class="active"><i class="fas fa-door-open" style="margin-right:5px"></i>Empty Rooms</a></li>
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
    <p class="he-section-title">Room Status Overview</p>

    <div class="filter-bar">
      <button class="active" onclick="filterRooms('all',this)">All Rooms</button>
      <button onclick="filterRooms('empty',this)">✅ Available</button>
      <button onclick="filterRooms('occupied',this)">🔴 Occupied</button>
    </div>

    <div class="room-grid" id="roomGrid">
      <?php if($rooms && $rooms->num_rows > 0):
        while($r = $rooms->fetch_assoc()): ?>
      <div class="room-tile <?php echo $r['room_status']; ?>" data-status="<?php echo $r['room_status']; ?>">
        <div class="room-icon"><?php echo $r['room_status']==='empty' ? '🟢' : '🔴'; ?></div>
        <div class="room-number"><?php echo htmlspecialchars($r['room_no']); ?></div>
        <div style="font-size:0.82rem;color:var(--text-light);margin:4px 0;"><?php echo htmlspecialchars($r['hostel_name']); ?></div>
        <span class="he-badge <?php echo $r['room_status']==='empty' ? 'he-badge-green' : 'he-badge-red'; ?>" style="margin:6px 0;display:inline-block;">
          <?php echo ucfirst($r['room_status']); ?>
        </span>
        <div style="font-size:0.82rem;color:var(--text-mid);margin-top:4px;">
          <?php echo htmlspecialchars($r['room_type']); ?> &bull; <?php echo $r['capacity']; ?> person(s)
        </div>
        <?php if($r['room_status']==='empty'): ?>
        <a href="allocate_room.php" class="he-btn he-btn-primary he-btn-sm" style="margin-top:0.8rem;width:100%;">
          <i class="fas fa-plus"></i> Allocate
        </a>
        <?php else: ?>
        <a href="vacate_room.php?room=<?php echo urlencode($r['room_no']); ?>" class="he-btn he-btn-danger he-btn-sm" style="margin-top:0.8rem;width:100%;">
          <i class="fas fa-sign-out-alt"></i> Vacate
        </a>
        <?php endif; ?>
      </div>
      <?php endwhile; else: ?>
      <p style="color:var(--text-light);">No rooms found. Add rooms in phpMyAdmin.</p>
      <?php endif; ?>
    </div>
  </main>

  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>
</div>
<script>
function filterRooms(status, btn) {
  document.querySelectorAll('.filter-bar button').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.room-tile').forEach(tile => {
    tile.style.display = (status === 'all' || tile.dataset.status === status) ? '' : 'none';
  });
}
</script>
</body>
</html>