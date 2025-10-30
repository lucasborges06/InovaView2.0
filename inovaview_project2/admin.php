<?php
session_start();
require 'db_init.php';
$cfg = file_exists(__DIR__ . '/config_local.php') ? include __DIR__ . '/config_local.php' : ['admin_user'=>'admin','admin_hash'=>''];
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
$error = '';
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) $error = 'Token CSRF inválido.';
    else {
        $u = $_POST['user'] ?? '';
        $p = $_POST['pass'] ?? '';
        if ($u === $cfg['admin_user'] && password_verify($p, $cfg['admin_hash'])) {
            $_SESSION['admin'] = true;
            header('Location: admin.php');
            exit;
        } else $error = 'Credenciais inválidas.';
    }
}
if (isset($_GET['logout'])) { session_destroy(); header('Location: admin.php'); exit; }
?>
<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin - InovaView</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<main class="container narrow">
<?php if (empty($_SESSION['admin'])): ?>
  <h1>Login Admin</h1>
  <?php if ($error) echo '<p class="errors">'.htmlspecialchars($error).'</p>'; ?>
  <form method="post">
    <input name="user" placeholder="Usuário" required>
    <input name="pass" type="password" placeholder="Senha" required>
    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
    <input type="hidden" name="action" value="login">
    <button class="btn" type="submit">Entrar</button>
  </form>
<?php else: ?>
  <h1>Painel</h1>
  <p><a href="?logout=1">Sair</a></p>
  <h2>Uploads recentes</h2>
  <?php $rows = $db->query('SELECT * FROM uploads ORDER BY created_at DESC LIMIT 200')->fetchAll(PDO::FETCH_ASSOC); ?>
  <table>
    <thead><tr><th>ID</th><th>Arquivo</th><th>Título</th><th>Data</th></tr></thead>
    <tbody>
    <?php foreach($rows as $r): ?>
      <tr><td><?php echo $r['id']; ?></td><td><a href="uploads/<?php echo htmlspecialchars($r['filename']); ?>">Arquivo</a></td><td><?php echo htmlspecialchars($r['title']); ?></td><td><?php echo htmlspecialchars($r['created_at']); ?></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
</main>
</body>
</html>
