<?php
require 'includes/config.inc.php';
if (!isset($_SESSION['roll'])) { header("Location: index.php"); exit(); }
$roll = $_SESSION['roll'];

$success = $error = '';
if (isset($_POST['message-submit'])) {
    $msg = trim($_POST['message']);
    if (strlen($msg) < 5) {
        $error = "Message is too short.";
    } else {
        $stmt = $conn->prepare("INSERT INTO messages (student_roll_no, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $roll, $msg);
        $stmt->execute();
        $success = "Your message has been sent to the warden!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contact Warden — HostelEase</title>
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
      <li><a href="application_form.php"><i class="fas fa-file-alt" style="margin-right:5px"></i>Apply</a></li>
      <li><a href="contact.php" class="active"><i class="fas fa-envelope" style="margin-right:5px"></i>Contact</a></li>
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
    <p class="he-section-title">Contact the Warden</p>
    <div style="max-width:600px;">
      <?php if($success): ?>
        <div class="he-alert he-alert-success"><i class="fas fa-check-circle"></i><?php echo $success; ?></div>
      <?php endif; ?>
      <?php if($error): ?>
        <div class="he-alert he-alert-error"><i class="fas fa-exclamation-circle"></i><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="he-card">
        <p style="color:var(--text-mid);font-size:0.9rem;margin-bottom:1.2rem;">
          Have a query, complaint, or request? Send a message to the warden. You'll receive a reply in your <a href="message_user.php">Messages</a> tab.
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
            <label>Message</label>
            <div class="he-input-wrap" style="align-items:flex-start;">
              <i class="fas fa-comment" style="top:14px;position:absolute;left:14px;"></i>
              <textarea name="message" rows="5" placeholder="Type your message here..." required
                style="width:100%;padding:0.7rem 1rem 0.7rem 2.6rem;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-family:var(--font-body);font-size:0.93rem;resize:vertical;outline:none;background:#fafafa;"
                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>
          </div>
          <button type="submit" name="message-submit" class="he-btn he-btn-primary">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>
    </div>
  </main>

  <footer class="he-footer">
    <p>&copy; <?php echo date('Y'); ?> HostelEase — Administrative Management College, Bangalore.</p>
  </footer>
</div>
</body>
</html>