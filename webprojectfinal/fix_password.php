
<?php
require_once 'includes/config.php';

$db = getDB();

// Show what is stored
$user = $db->query("SELECT id, email, password FROM users")->fetch();

echo "<b>Email in DB:</b> " . $user['email'] . "<br>";
echo "<b>Password hash:</b> " . $user['password'] . "<br><br>";

// Test verify
$test = password_verify('admin123', $user['password']);
echo "<b>Does 'admin123' match?</b> " . ($test ? 'YES ✓' : 'NO ✗') . "<br><br>";

// Force reset right now
$newHash = password_hash('admin123', PASSWORD_BCRYPT);
$db->prepare("UPDATE users SET password = ? WHERE email = ?")->execute([$newHash, $user['email']]);

// Verify again
$user2 = $db->query("SELECT password FROM users")->fetchColumn();
$test2 = password_verify('admin123', $user2);
echo "<b>After reset - does 'admin123' match?</b> " . ($test2 ? 'YES ✓ - Now go login!' : 'NO ✗ - Something deeper is wrong') . "<br>";
?>