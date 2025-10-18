<?php
require_once __DIR__ . '/../config.php';
$catId = (int)($_GET['id'] ?? 0);
$page = max(1, (int)($_GET['page'] ?? 1));
$per = max(1, (int)($_GET['per'] ?? 4));
$offset = ($page - 1) * $per;

$stmt = $pdo->prepare('SELECT COUNT(*) as c FROM products WHERE category_id=?');
$stmt->execute([$catId]); $total = (int)$stmt->fetch()['c'];

$stmt = $pdo->prepare('SELECT * FROM products WHERE category_id=? ORDER BY id DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $catId, PDO::PARAM_INT);
$stmt->bindValue(2, $per, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as &$r) {
    if ($r['image']) $r['image'] = url('public/assets/uploads/'.$r['image']);
    else $r['image'] = null;
}
echo json_encode([
    'page' => $page,
    'per' => $per,
    'total' => $total,
    'pages' => max(1, (int)ceil($total / $per)),
    'items' => $rows
]);
