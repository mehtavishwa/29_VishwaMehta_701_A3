<?php
require_once __DIR__ . '/../config.php';
requireAdmin();

// delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('SELECT image FROM products WHERE id=?'); $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row && $row['image']) {
        @unlink(__DIR__ . '/../public/assets/uploads/' . $row['image']);
    }
    $stmt = $pdo->prepare('DELETE FROM products WHERE id=?'); $stmt->execute([$id]);
    header('Location: products.php'); exit;
}

// handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $category_id = (int)($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);

    $imageName = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $uploadDir = __DIR__ . '/../public/assets/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $target = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // resize to width 400 keeping aspect
        $size = getimagesize($target);
        if ($size) {
            list($w,$h) = $size;
            $nw = 400; $nh = intval($h * ($nw / $w));
            $dst = imagecreatetruecolor($nw, $nh);
            $mime = mime_content_type($target);
            if ($mime === 'image/png') $src = imagecreatefrompng($target);
            else if ($mime === 'image/gif') $src = imagecreatefromgif($target);
            else $src = imagecreatefromjpeg($target);
            imagecopyresampled($dst, $src, 0,0,0,0,$nw,$nh,$w,$h);
            imagejpeg($dst, $target, 85);
            imagedestroy($dst); imagedestroy($src);
        }
    }

    if ($id) {
        if ($imageName) {
            $stmt = $pdo->prepare('UPDATE products SET category_id=?, name=?, price=?, image=? WHERE id=?');
            $stmt->execute([$category_id, $name, $price, $imageName, $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE products SET category_id=?, name=?, price=? WHERE id=?');
            $stmt->execute([$category_id, $name, $price, $id]);
        }
    } else {
        $stmt = $pdo->prepare('INSERT INTO products (category_id,name,price,image) VALUES (?,?,?,?)');
        $stmt->execute([$category_id, $name, $price, $imageName]);
    }
    header('Location: products.php'); exit;
}

$cats = $pdo->query('SELECT * FROM categories')->fetchAll();
$products = $pdo->query('SELECT p.*, c.name as catname FROM products p JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC')->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Products</title></head>
<body>
<h2>Products</h2>
<p><a href="categories.php">Categories</a> | <a href="logout.php">Logout</a></p>

<h3>Add / Edit product</h3>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" id="prod_id">
  <select name="category_id" id="prod_cat" required>
    <option value="">-- Select Category --</option>
    <?php foreach($cats as $c): ?>
      <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
    <?php endforeach; ?>
  </select>
  <input name="name" id="prod_name" placeholder="Product name" required>
  <input name="price" id="prod_price" placeholder="Price" required>
  <input type="file" name="image" accept="image/*">
  <button>Save</button>
</form>

<table border="1" cellpadding="6">
<tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr>
<?php foreach($products as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?php if($p['image']): ?><img src="<?= url('public/assets/uploads/'.$p['image']) ?>" width="80"><?php endif; ?></td>
  <td><?= htmlspecialchars($p['name']) ?></td>
  <td><?= htmlspecialchars($p['catname']) ?></td>
  <td><?= $p['price'] ?></td>
  <td>
    <a href="#" onclick="edit(<?= $p['id'] ?>, <?= $p['category_id'] ?>, '<?= addslashes($p['name']) ?>', <?= $p['price'] ?>);return false;">Edit</a>
    <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</table>

<script>
function edit(id, cat, name, price) {
  document.getElementById('prod_id').value = id;
  document.getElementById('prod_cat').value = cat;
  document.getElementById('prod_name').value = name;
  document.getElementById('prod_price').value = price;
}
</script>
</body>
</html>
