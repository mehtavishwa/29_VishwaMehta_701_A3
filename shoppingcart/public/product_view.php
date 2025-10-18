<?php
require_once __DIR__ . '/../config.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Product not found');
$stmt = $pdo->prepare('SELECT p.*, c.name as catname FROM products p JOIN categories c ON p.category_id=c.id WHERE p.id=?');
$stmt->execute([$id]); $p = $stmt->fetch();
if (!$p) die('Product not found');
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?= htmlspecialchars($p['name']) ?></title></head>
<body>
<h2><?= htmlspecialchars($p['name']) ?></h2>
<?php if($p['image']): ?><img src="<?= url('public/assets/uploads/'.$p['image']) ?>" width="300"><?php endif; ?>
<p>Category: <?= htmlspecialchars($p['catname']) ?></p>
<p>Price: ₹ <?= $p['price'] ?></p>
<p><a href="category.php?id=<?= $p['category_id'] ?>">Back to category</a></p>
</body>
</html>
