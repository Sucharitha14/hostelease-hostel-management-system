<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['roll'])) { header("Location: index.php"); exit(); }

$rooms = $conn->query("SELECT * FROM rooms ORDER BY hostel_name, room_no");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hostels & Rooms — HostelEase</title>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
  <style>
    .room-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(210px,1fr)); gap:1rem; }
    .room-tile { background:var(--bg-card); border:1.5px solid var(--border); border-radius:var(--radius); padding:1.3rem; text-align:center; transition:var(--transition); }
    .room-tile:hover { transform:translateY(-3px); box-shadow:var(--shadow-lg); }
    .room-tile.empty    { border-color:#6ee7b7; }
    .room-tile.occupied { border-color:#fca5a5; opacity:0.7; }
    .filter-bar { display:flex; gap:0.8rem; margin-bottom:1.2rem; flex-wrap:wrap; align-items:center; }
    .filter-bar button { padding:0.4rem 1.1rem; border-radius:100px; border:1.5px solid var(--border); background:#fff; cursor:pointer; font-size:0.85rem; font-family:var(--font-body); transition:var(--transition); }
    .filter-bar button.active, .filter-bar button:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
    .filter-bar input { padding:0.45rem 1rem; border:1.5px solid var(--border); border-radius:100px; font-family:var(--font-body); font-size:0.85rem; outline:none; }
    .filter-bar input:focus { border-color:var(--primary); }
  </style>
</head>
<body>
<div class="he-page">
  <nav class="he-navbar">
    <div class="he-brand"><div class="brand-icon"><i class="fas fa-home"></i></div>Hostel<span>Ease</span></div>
    <ul class="he-nav-links">
      <li><a href="home.php"><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="services.php" class="active"><i class="fas fa-door-open" style="margin-right:5px"></i>Hostels</a></li>
      <li><a href="application_form.php"><i class="fas fa-file-alt" style="margin-right:5px"></i>Apply</a></li>
      <li><a href="contact.php"><i class="fas fa-envelope" style="margin-right:5px"></i>Contact</a></li>
      <li><a href="message_user.php"><i class="fas fa-bell" style="margin-right:5px"></i>Messages</a></li>
    </ul>
    <div class="he-nav-user">
      <div class="he-dropdown">
        <div class="he-avatar"><?php echo strtoupper(substr($_SESSION['roll'],0,2)); ?></div>
        <div class="he-dropdown-menu">
          <a href="profile.php"><i class="fas fa-user" style="margin-right:8px;color:var(--primary)"></i>My Profile</a>
          <hr>
          <a href="includes/logout.inc.php"><i class="fas fa-sign-out-alt" style="margin-right:8px;color:var(--danger)"></i>Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <main class="he-main">
    <p class="he-section-title">Available Hostels & Rooms</p>

    <div class="filter-bar">
      <button class="active" onclick="filterRooms('all',this)">All</button>
      <button onclick="filterRooms('empty',this)">✅ Available</button>
      <button onclick="filterRooms('occupied',this)">🔴 Occupied</button>
      <input type="text" id="searchRooms" placeholder="🔍 Search room or hostel..." oninput="searchRooms()" />
    </div>

    <div class="room-grid" id="roomGrid">
      <?php if($rooms && $rooms->num_rows > 0):
        while($r = $rooms->fetch_assoc()): ?>
      <div class="room-tile <?php echo $r['room_status']; ?>" data-status="<?php echo $r['room_status']; ?>"
           data-name="<?php echo strtolower($r['room_no'].' '.$r['hostel_name'].' '.$r['room_type']); ?>">
        <div style="font-size:2rem;margin-bottom:0.5rem;"><?php echo $r['room_status']==='empty' ? '🟢' : '🔴'; ?></div>
        <div style="font-size:1.3rem;font-weight:700;color:var(--text-dark);"><?php echo htmlspecialchars($r['room_no']); ?></div>
        <div style="font-size:0.82rem;color:var(--text-light);margin:4px 0;"><?php echo htmlspecialchars($r['hostel_name']); ?></div>
        <span class="he-badge <?php echo $r['room_status']==='empty' ? 'he-badge-green' : 'he-badge-red'; ?>" style="margin:6px 0;display:inline-block;">
          <?php echo $r['room_status']==='empty' ? 'Available' : 'Occupied'; ?>
        </span>
        <div style="font-size:0.82rem;color:var(--text-mid);margin-top:4px;">
          <?php echo htmlspecialchars($r['room_type']); ?> &bull; <?php echo $r['capacity']; ?> person(s)
        </div>
        <?php if($r['room_status']==='empty'): ?>
        <a href="application_form.php?room=<?php echo urlencode($r['room_no']); ?>" class="he-btn he-btn-primary he-btn-sm" style="margin-top:0.8rem;width:100%;">
          <i class="fas fa-file-alt"></i> Apply
        </a>
        <?php endif; ?>
      </div>
      <?php endwhile; else: ?>
        <p style="color:var(--text-light);">No rooms found.</p>
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
function searchRooms() {
  const q = document.getElementById('searchRooms').value.toLowerCase();
  document.querySelectorAll('.room-tile').forEach(tile => {
    tile.style.display = tile.dataset.name.includes(q) ? '' : 'none';
  });
}
</script>
</body>
</html>