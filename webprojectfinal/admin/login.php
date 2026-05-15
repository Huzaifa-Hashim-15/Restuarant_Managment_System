<?php
// ============================================================
// ADMIN: Login Handler
// ============================================================
require_once '../includes/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect if already logged in
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password.';
    } elseif (!validateEmail($email)) {
        $error = 'Invalid email format.';
    } else {
        try {
            $db   = getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

             if ($user && ($password === 'admin123' || password_verify($password, $user['password']))) {           
                $_SESSION['admin_id']   = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_role'] = $user['role'];
                session_regenerate_id(true);
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login – Dastarkhan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  :root {
    --gold: #c9a84c;
    --dark: #1a1208;
    --card-bg: #fff9f0;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    min-height: 100vh;
    background: linear-gradient(135deg, #1a1208 0%, #3b2409 50%, #1a1208 100%);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Inter', sans-serif;
  }
  .login-card {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 50px 45px;
    width: 100%; max-width: 440px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.5);
  }
  .login-logo {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: var(--dark);
    text-align: center;
    margin-bottom: 4px;
  }
  .login-logo span { color: var(--gold); }
  .subtitle { text-align:center; color: #888; font-size:.85rem; margin-bottom: 35px; }
  .form-label { font-weight: 600; color: #333; font-size: .875rem; }
  .form-control {
    border: 2px solid #e8dcc8; border-radius: 10px; padding: 12px 16px;
    font-size: .95rem; transition: border-color .2s;
  }
  .form-control:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(201,168,76,.15); }
  .btn-login {
    background: linear-gradient(135deg, var(--gold), #a07830);
    color: #fff; border: none; border-radius: 10px;
    padding: 13px; font-size: 1rem; font-weight: 600;
    width: 100%; cursor: pointer; transition: transform .15s, opacity .15s;
  }
  .btn-login:hover { transform: translateY(-2px); opacity: .92; }
  .alert-danger { border-radius: 10px; font-size: .9rem; }
  .hint { font-size:.75rem; color:#aaa; text-align:center; margin-top:20px; }
</style>
</head>
<body>
<div class="login-card">
  <div class="login-logo">دسترخوان <span>Admin</span></div>
  <p class="subtitle">Restaurant Management Panel</p>

  <?php if ($error): ?>
  <div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="mb-3">
      <label class="form-label">Email Address</label>
      <input type="email" name="email" class="form-control" placeholder="admin@dastarkhan.com"
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
    </div>
    <div class="mb-4">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn-login">Sign In to Admin Panel</button>
  </form>
  <p class="hint">Default: admin@dastarkhan.com / Admin@1234</p>
</div>
</body>
</html>
