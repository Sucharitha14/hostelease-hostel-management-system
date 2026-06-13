<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['manager'])) { header("Location: login-hostel_manager.php"); exit(); }
 
$success = '';
if (isset($_POST['reply-submit'])) {
    $id    = intval($_POST['message_id']);
    $reply = trim($_POST['reply']);
    $stmt  = $conn->prepare("UPDATE messages SET reply = ? WHERE id = ?");
    $stmt->bind_param("si", $reply, $id);
    $stmt->execute();
    $success = "Reply sent successfully!";
}
 
$messages = $conn->query("SELECT m.*, s.student_fname, s.student_lname 
                          FROM messages m 
                          JOIN students s ON m.student_roll_no = s.student_roll_no 
                          ORDER BY m.sent_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Messages — HostelEase</title>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
  <style>
    .msg-card { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius); padding:1.3rem; margin-bottom:1rem; }
    .msg-card.unread { border-left:4px solid var(--primary); }
    .msg-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.6rem; }
    .msg-bubble { background:#fdf2f8; border-radius:0 var(--radius) var(--radius) var(--radius); padding:0.8rem 1rem; font-size:0.9rem; color:var(--text-mid); margin:0.6rem 0; }
    .reply-bubble { background:#ede9fe; border-radius:var(--radius) 0 var(--radius) var(--radius); padding:0.8rem 1rem; font-size:0.9rem; color:#4c1d95; margin:0.6rem 0; }
    .reply-form textarea { width:100%; padding:0.7rem 1rem; border:1.5px solid var(--border); border-radius:var(--radius-sm); font-family:var(--font-body); font-size:0.9rem; resize:vertical; outline:none; margin-top:0.5rem; }
    .reply-form textarea:focus { border-color:var(--primary); }
  </style>
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
      <li><a href="message_hostel_manager.php" class="active"><i class="fas fa-envelope" style="margin-right:5px"></i>Messages</a></li>
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
    <p class="he-section-title">Student Messages</p>
 
    <?php if($success): ?>
      <div class="he-alert he-alert-success"><i class="fas fa-check-circle"></i><?php echo $success; ?></div>
    <?php endif; ?>
 
    <?php if($messages && $messages->num_rows > 0):
      while($msg = $messages->fetch_assoc()): ?>
    <div class="msg-card <?php echo !$msg['reply'] ? 'unread' : ''; ?>">
      <div class="msg-header">
        <div>
          <strong><?php echo htmlspecialchars($msg['student_fname'].' '.$msg['student_lname']); ?></strong>
          <span class="he-badge he-badge-pink" style="margin-left:8px;"><?php echo htmlspecialchars($msg['student_roll_no']); ?></span>
          <?php if(!$msg['reply']): ?>
            <span class="he-badge he-badge-yellow" style="margin-left:6px;">Pending</span>
          <?php else: ?>
            <span class="he-badge he-badge-green" style="margin-left:6px;">Replied</span>
          <?php endif; ?>
        </div>
        <small style="color:var(--text-light);"><?php echo date('d M Y, h:i A', strtotime($msg['sent_at'])); ?></small>
      </div>
 
      <div class="msg-bubble">
        <i class="fas fa-comment" style="color:var(--primary);margin-right:6px;"></i>
        <?php echo htmlspecialchars($msg['message']); ?>
      </div>
 
      <?php if($msg['reply']): ?>
        <div class="reply-bubble">
          <i class="fas fa-reply" style="color:var(--secondary);margin-right:6px;"></i>
          <?php echo htmlspecialchars($msg['reply']); ?>
        </div>
      <?php else: ?>
        <form method="POST" class="reply-form">
          <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>" />
          <textarea name="reply" rows="2" placeholder="Type your reply here..." required></textarea>
          <button type="submit" name="reply-submit" class="he-btn he-btn-primary he-btn-sm" style="margin-top:0.6rem;">
            <i class="fas fa-paper-plane"></i> Send Reply
          </button>
        </form>
      <?php endif; ?>
    </div>
    <?php endwhile; else: ?>
      <div class="he-alert he-alert-info"><i class="fas fa-inbox"></i> No messages from students yet.</div>
    <?php endif; ?>
  </main>
 
  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>
</div>
</body>
</html>