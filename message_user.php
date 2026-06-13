<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['roll'])) { header("Location: index.php"); exit(); }
$roll = $_SESSION['roll'];

$stmt = $conn->prepare("SELECT * FROM messages WHERE student_roll_no = ? ORDER BY sent_at DESC");
$stmt->bind_param("s", $roll); $stmt->execute();
$messages = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>My Messages — HostelEase</title>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
  <style>
    .msg-card { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius); padding:1.3rem; margin-bottom:1rem; }
    .msg-bubble { background:#fdf2f8; border-radius:0 var(--radius) var(--radius) var(--radius); padding:0.8rem 1rem; font-size:0.9rem; color:var(--text-mid); margin:0.5rem 0; }
    .reply-bubble { background:#ede9fe; border-radius:var(--radius) 0 var(--radius) var(--radius); padding:0.8rem 1rem; font-size:0.9rem; color:#4c1d95; margin:0.5rem 0; }
  </style>
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
      <li><a href="message_user.php" class="active"><i class="fas fa-bell" style="margin-right:5px"></i>Messages</a></li>
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
    <p class="he-section-title">My Messages</p>

    <?php if($messages && $messages->num_rows > 0):
      while($msg = $messages->fetch_assoc()): ?>
    <div class="msg-card">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
        <span style="font-size:0.82rem;font-weight:700;color:var(--text-mid);">
          <i class="fas fa-user" style="color:var(--primary);margin-right:4px;"></i> You
        </span>
        <small style="color:var(--text-light);"><?php echo date('d M Y, h:i A', strtotime($msg['sent_at'])); ?></small>
      </div>
      <div class="msg-bubble"><?php echo htmlspecialchars($msg['message']); ?></div>

      <?php if($msg['reply']): ?>
        <div style="font-size:0.82rem;font-weight:700;color:var(--secondary);margin-top:0.8rem;margin-bottom:0.3rem;">
          <i class="fas fa-user-shield" style="margin-right:4px;"></i> Warden replied:
        </div>
        <div class="reply-bubble"><?php echo htmlspecialchars($msg['reply']); ?></div>
      <?php else: ?>
        <div class="he-alert he-alert-warn" style="margin-top:0.8rem;padding:0.5rem 1rem;font-size:0.82rem;">
          <i class="fas fa-clock"></i> Awaiting reply from warden...
        </div>
      <?php endif; ?>
    </div>
    <?php endwhile; else: ?>
      <div class="he-alert he-alert-info">
        <i class="fas fa-inbox"></i> No messages yet. <a href="contact.php">Send a message to the warden →</a>
      </div>
    <?php endif; ?>
  </main>

  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>
</div>
</body>
</html>