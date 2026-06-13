<!DOCTYPE html>
<html lang="en">
<head>
  <title>Warden Login — HostelEase</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
</head>
<body>

<div class="he-auth-wrapper">

  <div class="he-auth-left">
    <div style="font-size:4rem; margin-bottom:1.2rem;">🔐</div>
    <h1 class="hero-title">Warden Portal</h1>
    <p class="hero-sub">Manage rooms, allocations, student applications, and hostel operations from one place.</p>
    <div class="he-feature-pills">
      <span class="he-pill">🛏️ Allocate Rooms</span>
      <span class="he-pill">📊 Dashboard</span>
      <span class="he-pill">🗒️ Vacate Rooms</span>
      <span class="he-pill">💬 Message Students</span>
    </div>
  </div>

  <div class="he-auth-card">

    <div style="margin-bottom:1.8rem;">
      <div class="he-brand" style="margin-bottom:1rem;">
        <div class="brand-icon"><i class="fas fa-home"></i></div>
        Hostel<span>Ease</span>
      </div>
      <h2>Warden / Admin Login 🔑</h2>
      <p class="subtitle">Enter your credentials to access the dashboard</p>
    </div>

    <?php if(isset($_GET['error'])): ?>
      <div class="he-alert he-alert-error">
        <i class="fas fa-exclamation-circle"></i>
        Invalid username or password.
      </div>
    <?php endif; ?>

    <form action="includes/login-hostel_manager.inc.php" method="POST">

      <div class="he-form-group">
        <label>Username</label>
        <div class="he-input-wrap">
          <i class="fas fa-user-shield"></i>
          <input type="text" name="manager_uname" placeholder="Warden username" required />
        </div>
      </div>

      <div class="he-form-group">
        <label>Password</label>
        <div class="he-input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="pwd" placeholder="Enter password" required />
        </div>
      </div>

      <button type="submit" name="login-submit" class="he-btn he-btn-primary" style="margin-top:0.5rem;">
        <i class="fas fa-sign-in-alt"></i> Login as Warden
      </button>

    </form>

    <p class="he-register-link" style="margin-top:1.2rem;">
      Are you a student? <a href="index.php">Student login here</a>
    </p>

  </div>
</div>

<footer class="he-footer">
  <p>&copy; <?php echo date('Y'); ?> HostelEase &mdash; Administrative Management College, Bangalore.</p>
</footer>

</body>
</html>
