<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dastarkhan - Setup Utility</title>
<style>
body{font-family:Arial,sans-serif;background:#1a1208;color:#fff;padding:40px;max-width:700px;margin:0 auto}
h1{color:#c9a84c;margin-bottom:8px}
.card{background:#2d1c0a;border-radius:12px;padding:24px;margin-bottom:20px;border:1px solid rgba(201,168,76,.2)}
.success{color:#4caf50;font-weight:bold}
.error{color:#f44336;font-weight:bold}
.info{color:#2196f3}
pre{background:#111;padding:12px;border-radius:8px;overflow-x:auto;font-size:.85rem;color:#c9a84c}
.btn{background:#c9a84c;color:#1a1208;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;font-weight:700;font-size:1rem;margin-top:8px}
table{width:100%;border-collapse:collapse}
th,td{padding:8px 12px;text-align:left;border-bottom:1px solid rgba(255,255,255,.1)}
th{color:#c9a84c;font-size:.8rem;text-transform:uppercase}
</style>
</head>
<body>
<h1>🍽️ Dastarkhan Setup Utility</h1>
<p style="color:rgba(255,255,255,.5);margin-bottom:24px">Use this page to verify setup and fix common issues.</p>

<?php
require_once 'includes/config.php';

// 1. DB Connection Test
echo '<div class="card"><h3>1. Database Connection</h3>';
try {
    $db = getDB();
    echo '<p class="success">✓ Database connected successfully to "' . DB_NAME . '"</p>';
} catch (Exception $e) {
    echo '<p class="error">✗ Connection failed: ' . $e->getMessage() . '</p>';
    echo '<p>Make sure XAMPP MySQL is running and the database exists. Import database.sql first.</p>';
    echo '</div></body></html>';
    exit;
}
echo '</div>';

// 2. Tables Check
echo '<div class="card"><h3>2. Tables Status</h3><table><tr><th>Table</th><th>Status</th><th>Rows</th></tr>';
$tables = ['users','categories','menu','orders','reservations','contacts','blog_posts'];
foreach ($tables as $t) {
    try {
        $count = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "<tr><td>$t</td><td style='color:#4caf50'>✓ Exists</td><td>$count rows</td></tr>";
    } catch (Exception $e) {
        echo "<tr><td>$t</td><td style='color:#f44336'>✗ Missing</td><td>–</td></tr>";
    }
}
echo '</table></div>';

// 3. Fix Admin Password
if (isset($_POST['fix_password'])) {
    $newHash = password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost'=>12]);
    try {
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@dastarkhan.com'");
        $stmt->execute([$newHash]);
        if ($stmt->rowCount() > 0) {
            echo '<div class="card"><p class="success">✓ Admin password reset to: Admin@1234</p></div>';
        } else {
            // Try inserting
            $stmt2 = $db->prepare("INSERT INTO users (name, email, password, role) VALUES ('Super Admin', 'admin@dastarkhan.com', ?, 'admin')");
            $stmt2->execute([$newHash]);
            echo '<div class="card"><p class="success">✓ Admin account created. Email: admin@dastarkhan.com / Password: Admin@1234</p></div>';
        }
    } catch (Exception $e) {
        echo '<div class="card"><p class="error">Error: ' . $e->getMessage() . '</p></div>';
    }
}

// 4. Admin Account
echo '<div class="card"><h3>3. Admin Account</h3>';
try {
    $admin = $db->query("SELECT id, name, email, role, created_at FROM users LIMIT 1")->fetch();
    if ($admin) {
        echo "<p class='success'>✓ Admin account found:</p>";
        echo "<table><tr><th>Field</th><th>Value</th></tr>";
        foreach ($admin as $k => $v) { if (!is_int($k)) echo "<tr><td>$k</td><td>$v</td></tr>"; }
        echo "</table>";
    } else {
        echo "<p class='error'>✗ No admin account found.</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}
echo '<form method="POST" style="margin-top:16px">
<button name="fix_password" class="btn">🔑 Reset Admin Password to "Admin@1234"</button>
</form></div>';

// 5. API Test
echo '<div class="card"><h3>4. API Endpoints</h3>';
$apis = [
    'api/menu.php' => 'Menu API',
    'api/orders.php' => 'Orders API',
    'api/reservations.php' => 'Reservations API',
    'api/contact.php' => 'Contact API',
    'api/stats.php' => 'Stats API',
];
echo '<table><tr><th>File</th><th>Status</th></tr>';
foreach ($apis as $file => $label) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path) ? '<span style="color:#4caf50">✓ Found</span>' : '<span style="color:#f44336">✗ Missing</span>';
    echo "<tr><td>$label <small style='color:rgba(255,255,255,.4)'>($file)</small></td><td>$exists</td></tr>";
}
echo '</table></div>';

// 6. Uploads folder
echo '<div class="card"><h3>5. Uploads Directory</h3>';
$uploadDir = __DIR__ . '/uploads/menu/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    echo "<p class='success'>✓ Created uploads/menu/ directory</p>";
} else {
    echo "<p class='success'>✓ uploads/menu/ directory exists</p>";
}
if (is_writable($uploadDir)) {
    echo "<p class='success'>✓ Directory is writable</p>";
} else {
    echo "<p class='error'>✗ Directory is not writable. Run: chmod 755 uploads/menu/</p>";
}
echo '</div>';

echo '<div class="card" style="text-align:center"><h3>🚀 Quick Links</h3>
<div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:8px">
<a href="index.php" style="background:#c9a84c;color:#1a1208;padding:10px 20px;border-radius:8px;font-weight:700;text-decoration:none">🏠 Homepage</a>
<a href="menu.php" style="background:#2d1c0a;color:#c9a84c;border:1px solid #c9a84c;padding:10px 20px;border-radius:8px;font-weight:700;text-decoration:none">🍽️ Menu</a>
<a href="admin/login.php" style="background:#2d1c0a;color:#c9a84c;border:1px solid #c9a84c;padding:10px 20px;border-radius:8px;font-weight:700;text-decoration:none">🔐 Admin Login</a>
<a href="admin/dashboard.php" style="background:#2d1c0a;color:#c9a84c;border:1px solid #c9a84c;padding:10px 20px;border-radius:8px;font-weight:700;text-decoration:none">📊 Dashboard</a>
</div>
<p style="color:rgba(255,255,255,.3);font-size:.75rem;margin-top:20px">⚠️ Delete this setup.php file after initial setup for security.</p>
</div>';
?>
</body>
</html>
