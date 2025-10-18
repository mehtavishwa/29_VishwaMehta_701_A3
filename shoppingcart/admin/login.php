<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    // change credentials here as needed
    if ($u === 'admin' && $p === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: products.php'); exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Login</title></head>
<body>
<h2>Admin Login</h2>
<?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
  <label>Username: <input name="username" required></label><br><br>
  <label>Password: <input name="password" type="password" required></label><br><br>
  <button>Login</button>
</form>
</body>
</html>
