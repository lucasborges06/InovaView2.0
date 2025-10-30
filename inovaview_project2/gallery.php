<?php
require 'db_init.php';
$perPage = 8;
$page = max(1, intval($_GET['page'] ?? 1));
$total = $db->query('SELECT COUNT(*) FROM uploads')->fetchColumn();
$pages = max(1, ceil($total / $perPage));
$offset = ($page - 1) * $perPage;
$stmt = $db->prepare('SELECT * FROM uploads ORDER BY created_at DESC LIMIT :lim OFFSET :off');
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Galeria - InovaView</title><link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="hero small"><div class="container"><h1>Galeria</h1><a class="btn" href="upload.php">Enviar imagem</a></div></header>
  <main class="container">
    <div class="grid">
    <?php foreach($items as $it): ?>
      <figure class="card" data-filename="<?php echo htmlspecialchars($it['filename']); ?>" data-title="<?php echo htmlspecialchars($it['title']); ?>" data-desc="<?php echo htmlspecialchars($it['description']); ?>">
        <img src="uploads/<?php echo htmlspecialchars($it['filename']); ?>" alt="<?php echo htmlspecialchars($it['title']); ?>">
        <figcaption>
          <strong><?php echo htmlspecialchars($it['title']); ?></strong>
          <p><?php echo htmlspecialchars($it['description']); ?></p>
          <time><?php echo htmlspecialchars($it['created_at']); ?></time>
        </figcaption>
      </figure>
    <?php endforeach; ?>
    <?php if (empty($items)): ?><p>Nenhuma imagem enviada ainda.</p><?php endif; ?>
    </div>
    <!-- pagination -->
    <div class="pagination">
    <?php for($p=1;$p<=$pages;$p++): ?>
      <?php if ($p==$page): ?><span class="page current"><?=$p?></span><?php else: ?><a class="page" href="?page=<?=$p?>"><?=$p?></a><?php endif; ?>
    <?php endfor; ?>
    </div>
  </main>

  <!-- modal -->
  <div id="modal" class="modal" aria-hidden="true"><div class="modal-content"><button id="closeModal" class="btn hollow">Fechar</button><img id="modalImg"><h3 id="modalTitle"></h3><p id="modalDesc"></p></div></div>

  <footer class="container footer"><p>&copy; InovaView</p></footer>
  <script src="assets/js/main.js"></script>
</body>
</html>
