<?php
require_once __DIR__ . '/../config.php';

// simple numeric captcha stored in session
if (!isset($_SESSION['captcha'])) $_SESSION['captcha'] = rand(1000, 9999);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha = $_POST['captcha'] ?? '';

    if ($captcha != $_SESSION['captcha']) {
        $error = 'Invalid CAPTCHA';
    } elseif ($name === '' || $email === '' || $password === '') {
        $error = 'All fields are required';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
        try {
            $stmt->execute([$name, $email, $hash]);
            unset($_SESSION['captcha']);
            $success = 'Registered successfully. Please login.';
        } catch (PDOException $e) {
            $error = 'Email already registered or DB error';
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title></head>
<body>
<h2>Register</h2>
<?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
<form method="post">
  <label>Name: <input name="name" required></label><br><br>
  <label>Email: <input name="email" type="email" required></label><br><br>
  <label>Password: <input name="password" type="password" required></label><br><br>
  <label>Enter CAPTCHA: <strong><?php echo $_SESSION['captcha']; ?></strong>
  <input name="captcha" required></label><br><br>
  <button>Register</button>
</form>
</body>
</html>
