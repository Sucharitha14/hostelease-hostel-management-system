<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create Account — HostelEase</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="web/css/fontawesome-all.css" rel="stylesheet" />
  <link href="web/css/style.css" rel="stylesheet" />
</head>
<body>

<div class="he-auth-wrapper">

  <!-- Left Panel -->
  <div class="he-auth-left">
    <div style="font-size:4rem; margin-bottom:1.2rem;">🌸</div>
    <h1 class="hero-title">Join HostelEase</h1>
    <p class="hero-sub">Register to apply for a hostel room, track your allocation, and stay connected with your warden.</p>
    <div class="he-feature-pills">
      <span class="he-pill">📝 Easy registration</span>
      <span class="he-pill">🛏️ Room apply</span>
      <span class="he-pill">✅ Instant status</span>
      <span class="he-pill">📞 24/7 support</span>
    </div>
  </div>

  <!-- Signup Card -->
  <div class="he-auth-card" style="overflow-y:auto; max-height:100vh;">

    <div style="margin-bottom:1.5rem;">
      <div class="he-brand" style="margin-bottom:0.8rem;">
        <div class="brand-icon"><i class="fas fa-home"></i></div>
        Hostel<span>Ease</span>
      </div>
      <h2>Create your account ✨</h2>
      <p class="subtitle">Fill in your details to get started</p>
    </div>

    <?php if(isset($_GET['error'])): ?>
      <div class="he-alert he-alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php
          $err = $_GET['error'];
          if($err === 'pwdDontMatch')  echo 'Passwords do not match. Please try again.';
          elseif($err === 'userTaken') echo 'This Roll No is already registered.';
          else                         echo 'Something went wrong. Please try again.';
        ?>
      </div>
    <?php endif; ?>

    <form action="includes/signup.inc.php" method="POST">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.8rem;">
        <div class="he-form-group">
          <label>First Name</label>
          <div class="he-input-wrap">
            <i class="fas fa-user"></i>
            <input type="text" name="student_fname" placeholder="First name" required />
          </div>
        </div>
        <div class="he-form-group">
          <label>Last Name</label>
          <div class="he-input-wrap">
            <i class="fas fa-user"></i>
            <input type="text" name="student_lname" placeholder="Last name" required />
          </div>
        </div>
      </div>

      <div class="he-form-group">
        <label>Student Roll No</label>
        <div class="he-input-wrap">
          <i class="fas fa-id-badge"></i>
          <input type="text" name="student_roll_no" placeholder="e.g. P03AC24S126032" required />
        </div>
      </div>

      <div class="he-form-group">
        <label>Mobile Number</label>
        <div class="he-input-wrap">
          <i class="fas fa-phone"></i>
          <input type="tel" name="mobile_no" placeholder="+91 XXXXXXXXXX" required />
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.8rem;">
        <div class="he-form-group">
          <label>Department</label>
          <div class="he-input-wrap">
            <i class="fas fa-graduation-cap"></i>
            <input type="text" name="department" placeholder="e.g. MCA" required />
          </div>
        </div>
        <div class="he-form-group">
          <label>Year of Study</label>
          <div class="he-input-wrap">
            <i class="fas fa-calendar-alt"></i>
            <input type="text" name="year_of_study" placeholder="e.g. III" required />
          </div>
        </div>
      </div>

      <div class="he-form-group">
        <label>Password</label>
        <div class="he-input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="pwd" placeholder="Create a strong password" required />
        </div>
      </div>

      <div class="he-form-group">
        <label>Confirm Password</label>
        <div class="he-input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="confirmpwd" placeholder="Repeat your password" required />
        </div>
      </div>

      <button type="submit" name="signup-submit" class="he-btn he-btn-primary" style="margin-top:0.4rem;">
        <i class="fas fa-user-plus"></i> Create Account
      </button>

    </form>

    <p class="he-register-link" style="margin-top:1rem;">
      Already have an account? <a href="index.php">Sign in here</a>
    </p>

  </div>
</div>

<footer class="he-footer">
  <p>&copy; <?php echo date('Y'); ?> HostelEase &mdash; Administrative Management College, Bangalore.</p>
</footer>

</body>
</html>
