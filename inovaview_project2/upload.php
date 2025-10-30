<?php
session_start();
$db = require 'db_init.php';
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        $errors[] = 'Token CSRF inválido.';
    } else {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erro no upload.';
        } else {
            $f = $_FILES['image'];
            $allowed = ['image/jpeg','image/png','image/gif'];
            if (!in_array($f['type'], $allowed)) $errors[] = 'Formato não permitido.';
            if ($f['size'] > 8 * 1024 * 1024) $errors[] = 'Arquivo muito grande (max 8MB).';
            if (empty($errors)) {
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $name = bin2hex(random_bytes(8)) . '.' . $ext;
                $dest = __DIR__ . '/uploads/' . $name;
                if (!move_uploaded_file($f['tmp_name'], $dest)) $errors[] = 'Falha ao mover arquivo.';
                else {
                    $title = substr($_POST['title'] ?? '',0,150);
                    $desc = substr($_POST['description'] ?? '',0,500);
                    $db->prepare('INSERT INTO uploads (filename,title,description) VALUES (?,?,?)')
                       ->execute([$name,$title,$desc]);
                    header('Location: gallery.php');
                    exit;
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Enviar - InovaView</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
  <main class="container narrow">
    <h1>Enviar Imagem</h1>
    <?php if ($errors): ?>
      <div class="errors"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <label>Imagem (jpg/png/gif)</label>
      <input type="file" name="image" required>
      <input name="title" placeholder="Título">
      <textarea name="description" placeholder="Descrição"></textarea>
      <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
      <button class="btn" type="submit">Enviar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
  </main>
  <script src="assets/js/main.js"></script>
</body>
</html>
