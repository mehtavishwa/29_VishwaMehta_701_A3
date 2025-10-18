<?php
require_once __DIR__ . '/../config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $id = $_POST['id'] ?? '';
    if ($name !== '') {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE categories SET name=? WHERE id=?');
            $stmt->execute([$name, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
            $stmt->execute([$name]);
        }
        header('Location: categories.php'); exit;
    } else {
        $error = "Name required";
    }
}

if (isset($_GET['delete'])) {
    $del = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id=?');
    $stmt->execute([$del]);
    header('Location: categories.php'); exit;
}

$cats = $pdo->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Categories</title></head>
<body>
<h2>Manage Categories</h2>
<p><a href="products.php">Manage products</a> | <a href="logout.php">Logout</a></p>

<?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
  <input type="hidden" name="id" id="cat_id">
  <input name="name" id="cat_name" placeholder="Category name" required>
  <button>Add / Update</button>
</form>

<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>Actions</th></tr>
<?php foreach($cats as $c): ?>
<tr>
  <td><?=htmlspecialchars($c['id'])?></td>
  <td><?=htmlspecialchars($c['name'])?></td>
  <td>
    <a href="#" onclick="edit(<?= $c['id'] ?>, '<?= addslashes($c['name']) ?>');return false;">Edit</a>
    <a href="?delete=<?= $c['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</table>

<script>
function edit(id, name) {
  document.getElementById('cat_id').value = id;
  document.getElementById('cat_name').value = name;
}
</script>
</body>
</html>
