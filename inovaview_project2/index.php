<?php
session_start();
$db = require 'db_init.php';
$cfg = file_exists(__DIR__ . '/config_local.php') ? include __DIR__ . '/config_local.php' : ['site_name'=>'InovaView'];
// CSRF token
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($cfg['site_name']); ?> — Plataforma criativa</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" href="assets/favicon.ico">
</head>
<body>
  <header class="hero">
    <div class="container">
      <h1><?php echo htmlspecialchars($cfg['site_name']); ?></h1>
      <p class="subtitle">Plataforma criativa para exibir e compartilhar projetos visuais.</p>
      <a class="btn" href="gallery.php">Ver Galeria</a>
    </div>
  </header>
  <main class="container">
    <section class="features">
      <article>
        <h2>Upload</h2>
        <p>Envie imagens e adicione título e descrição.</p>
        <a class="btn hollow" href="upload.php">Enviar</a>
      </article>
      <article>
        <h2>Contato</h2>
        <p>Envie uma mensagem ao administrador (por e‑mail e WhatsApp).</p>
        <form method="post" action="index.php#contact" class="contact-form">
          <input name="name" placeholder="Seu nome" required>
          <input name="email" type="email" placeholder="Seu email" required>
          <textarea name="message" placeholder="Sua mensagem" required></textarea>
          <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
          <button type="submit" class="btn">Enviar</button>
        </form>
      </article>
    </section>
  </main>
  <footer class="container footer">
    <p>&copy; <?php echo date('Y')?> <?php echo htmlspecialchars($cfg['site_name']); ?></p>
  </footer>
<?php
// Handle contact POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        echo "<script>alert('Token CSRF inválido.');</script>";
    } else {
        $name = substr(trim($_POST['name']),0,100);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';
        $message = trim($_POST['message']);
        if ($email) {
            $db->prepare('INSERT INTO messages (name,email,message) VALUES (?,?,?)')
               ->execute([$name,$email,$message]);
            // try send mail
            @mail('admin@example.com', 'Contato InovaView', "From: $name <$email>\n\n".$message);
            // store message in session to build whatsapp redirect
            $_SESSION['last_message'] = ['name'=>$name,'email'=>$email,'message'=>$message];
            // open whatsapp link in new tab via JS
            $wa_link = 'send_to_whatsapp.php';
            echo "<script>window.open('".$wa_link."','_blank');alert('Mensagem enviada por e-mail e WhatsApp (abrindo nova aba).');</script>";
        } else {
            echo "<script>alert('Email inválido.');</script>";
        }
    }
}
?>
<script src="assets/js/main.js"></script>
</body>
</html>
