<?php
require_once __DIR__ . '/../config.php';
$catId = (int)($_GET['id'] ?? 0);
$cat = null;
if ($catId) $cat = $pdo->prepare('SELECT * FROM categories WHERE id=?'); $cat->execute([$catId]); $cat = $cat->fetch();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Products</title></head>
<body>
<h2>Products in <?= htmlspecialchars($cat['name'] ?? 'Category') ?></h2>
<div id="products"></div>
<div id="pagination"></div>

<script>
const catId = <?= json_encode($catId) ?>;
let page = 1;
const perPage = 4;

function load() {
  fetch('category_ajax.php?id=' + catId + '&page=' + page + '&per=' + perPage)
    .then(r => r.json())
    .then(data => {
      const wrap = document.getElementById('products');
      if (!data.items.length) { wrap.innerHTML = '<p>No products found.</p>'; return; }
      wrap.innerHTML = data.items.map(p => {
        const img = p.image ? `<img src="${p.image}" width="120">` : '';
        return `<div style="display:inline-block; width:200px; margin:10px; border:1px solid #ddd; padding:8px;">${img}<h4>${p.name}</h4><p>₹ ${p.price}</p><p><a href="product_view.php?id=${p.id}">View</a></p></div>`;
      }).join('');
      const pag = document.getElementById('pagination');
      pag.innerHTML = '';
      for (let i=1;i<=data.pages;i++) {
        pag.innerHTML += `<button onclick="page=${i};load()" ${i===data.page? 'disabled':''}>${i}</button>`;
      }
    })
    .catch(err => { console.error(err); });
}

load();
</script>
</body>
</html>
