<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['manager'])) { header("Location: login-hostel_manager.php"); exit(); }

$rows = $conn->query("SELECT rd.*, s.student_fname, s.student_lname, s.mobile_no, s.department 
                      FROM room_details rd 
                      JOIN students s ON rd.student_roll_no = s.student_roll_no 
                      ORDER BY rd.allocation_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Allocated Rooms — HostelEase</title>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
  <style>
    .search-bar { display:flex; gap:1rem; margin-bottom:1.2rem; }
    .search-bar input { flex:1; padding:0.6rem 1rem; border:1.5px solid var(--border); border-radius:var(--radius-sm); font-family:var(--font-body); font-size:0.9rem; outline:none; }
    .search-bar input:focus { border-color:var(--primary); }
  </style>
</head>
<body>
<div class="he-page">
  <nav class="he-navbar">
    <div class="he-brand"><div class="brand-icon"><i class="fas fa-home"></i></div>Hostel<span>Ease</span><span style="font-size:0.7rem;background:#ede9fe;color:#7c3aed;padding:2px 8px;border-radius:20px;margin-left:6px;">Warden</span></div>
    <ul class="he-nav-links">
      <li><a href="manager_home.php"><i class="fas fa-th-large" style="margin-right:5px"></i>Dashboard</a></li>
      <li><a href="allocated_rooms.php" class="active"><i class="fas fa-door-closed" style="margin-right:5px"></i>Allocations</a></li>
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
    <p class="he-section-title">All Allocated Rooms</p>

    <div class="he-card">
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍  Search by name, roll no, room or department..." onkeyup="filterTable()" />
      </div>
      <div class="he-table-wrap">
        <table class="he-table" id="allocTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Student</th>
              <th>Roll No</th>
              <th>Department</th>
              <th>Mobile</th>
              <th>Room</th>
              <th>Hostel</th>
              <th>Type</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if($rows && $rows->num_rows > 0): $i=1;
              while($row = $rows->fetch_assoc()): ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><strong><?php echo htmlspecialchars($row['student_fname'].' '.$row['student_lname']); ?></strong></td>
              <td><span class="he-badge he-badge-pink"><?php echo htmlspecialchars($row['student_roll_no']); ?></span></td>
              <td><?php echo htmlspecialchars($row['department']); ?></td>
              <td><?php echo htmlspecialchars($row['mobile_no']); ?></td>
              <td><span class="he-badge he-badge-purple"><?php echo htmlspecialchars($row['room_no']); ?></span></td>
              <td><?php echo htmlspecialchars($row['hostel_name']); ?></td>
              <td><?php echo htmlspecialchars($row['room_type']); ?></td>
              <td><?php echo htmlspecialchars($row['allocation_date']); ?></td>
              <td>
                <a href="vacate_rooms.php?roll=<?php echo urlencode($row['student_roll_no']); ?>"
                   class="he-btn he-btn-danger he-btn-sm"
                   onclick="return confirm('Vacate room for <?php echo htmlspecialchars($row['student_fname']); ?>?')">
                  <i class="fas fa-sign-out-alt"></i> Vacate
                </a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="10" style="text-align:center;color:var(--text-light);padding:2rem;">No rooms allocated yet. <a href="allocate_room.php">Allocate one →</a></td></tr>
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
<script>
function filterTable() {
  const input = document.getElementById('searchInput').value.toLowerCase();
  const rows  = document.querySelectorAll('#allocTable tbody tr');
  rows.forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(input) ? '' : 'none';
  });
}
</script>
</body>
</html>