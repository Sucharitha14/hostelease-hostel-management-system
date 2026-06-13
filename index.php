<!DOCTYPE html>
<html lang="en">
<head>
  <title>HostelEase — Girls Hostel Management System</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="HostelEase — Comfortable Living, Seamless Management" />
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- FontAwesome -->
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <!-- HostelEase Theme -->
  <link href="web/css/style.css" rel="stylesheet" />
</head>
<body>

<div class="he-auth-wrapper">

  <!-- Left Decorative Panel -->
  <div class="he-auth-left">
    <div style="margin-bottom:1.5rem; font-size:4rem;">🏠</div>
    <h1 class="hero-title">HostelEase</h1>
    <p class="hero-sub">Your comfortable home away from home.<br>Seamless room management for the modern student.</p>
    <div class="he-feature-pills">
      <span class="he-pill">🛏️ Room Allocation</span>
      <span class="he-pill">📋 Apply Online</span>
      <span class="he-pill">💬 Contact Warden</span>
      <span class="he-pill">👤 Student Profile</span>
      <span class="he-pill">📊 Room Status</span>
    </div>
    <p style="margin-top:2.5rem; font-size:0.82rem; color:#9ca3af;">
      Administrative Management College, Bangalore<br>
      Girls Hostel Management System &mdash; 2025&ndash;26
    </p>
  </div>

  <!-- Login Card -->
  <div class="he-auth-card">

    <div style="margin-bottom:1.8rem;">
      <div class="he-brand" style="margin-bottom:1rem;">
        <div class="brand-icon"><i class="fas fa-home"></i></div>
        Hostel<span>Ease</span>
      </div>
      <h2>Welcome back! 👋</h2>
      <p class="subtitle">Sign in to your student account</p>
    </div>

    <?php if(isset($_GET['error'])): ?>
      <div class="he-alert he-alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php
          $err = $_GET['error'];
          if($err === 'wrongPassword') echo 'Incorrect password. Please try again.';
          elseif($err === 'noUser')    echo 'No account found with that Roll No.';
          else                        echo 'Something went wrong. Please try again.';
        ?>
      </div>
    <?php endif; ?>

    <form action="includes/login.inc.php" method="POST">

      <div class="he-form-group">
        <label>Student Roll No</label>
        <div class="he-input-wrap">
          <i class="fas fa-id-card"></i>
          <input type="text" name="student_roll_no" placeholder="e.g. P03AC24S126032" required />
        </div>
      </div>

      <div class="he-form-group">
        <label>Password</label>
        <div class="he-input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="pwd" placeholder="Enter your password" required />
        </div>
      </div>

      <button type="submit" name="login-submit" class="he-btn he-btn-primary" style="margin-top:0.5rem;">
        <i class="fas fa-sign-in-alt"></i> Sign In
      </button>

    </form>

    <div class="he-divider">or</div>

    <p class="he-register-link">
      Don't have an account? <a href="signup.php">Create one here</a>
    </p>

    <p class="he-register-link" style="margin-top:0.6rem;">
      Are you the Warden / Admin? <a href="login-hostel_manager.php">Login here</a>
    </p>

  </div>
</div>

<!-- Footer -->
<footer class="he-footer">
  <p>&copy; <?php echo date('Y'); ?> HostelEase &mdash; Administrative Management College, Bangalore.
  Developed as part of MCA Mini Project (III Semester) &mdash; Bangalore University.</p>
</footer>

</body>
</html>
