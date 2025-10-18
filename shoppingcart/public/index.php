<?php
require_once __DIR__ . '/../config.php';
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Categories</title></head>
<body>
<h2>Categories</h2>
<ul>
<?php foreach($cats as $c): ?>
  <li><a href="category.php?id=<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></a></li>
<?php endforeach; ?>
</ul>
<p><a href="register.php">Register</a> | <a href="login.php">Login</a></p>
</body>
</html>
