<?php
session_start();
$cfg = file_exists(__DIR__ . '/config_local.php') ? include __DIR__ . '/config_local.php' : [];
$wa = "+5531998101841";
if (!empty($_SESSION['last_message'])) {
    $m = $_SESSION['last_message'];
    $text = urlencode("Contato InovaView\nNome: {$m['name']}\nEmail: {$m['email']}\nMensagem: {$m['message']}");
    // wa.me link
    $link = "https://wa.me/" . preg_replace('/[^0-9]/','', $wa) . "?text={$text}";
    // clear
    unset($_SESSION['last_message']);
    header('Location: ' . $link);
    exit;
} else {
    echo 'Nenhuma mensagem disponível para enviar via WhatsApp.';
}
?>
