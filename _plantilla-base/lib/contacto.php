<?php
/**
 * Envío del formulario de contacto (POST /contacto/enviar). Solo si config email.formulario = true.
 * Usa PHPMailer por SMTP si hay smtp_host; si no, mail() de PHP.
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

function contacto_enviar(): void
{
    $volver = url('contacto') . '#contacto-form-title';
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !cfg('email.formulario')) {
        header('Location: ' . url('contacto'), true, 303);
        exit;
    }
    $nombre   = trim((string)($_POST['nombre'] ?? ''));
    $email    = trim((string)($_POST['email'] ?? ''));
    $telefono = trim((string)($_POST['telefono'] ?? ''));
    $servicio = trim((string)($_POST['servicio'] ?? ''));
    $mensaje  = trim((string)($_POST['mensaje'] ?? ''));
    $honeypot = trim((string)($_POST['sitio_web'] ?? ''));

    if ($honeypot !== '') { header('Location: ' . url('contacto/gracias'), true, 303); exit; } // bot
    if ($nombre === '' || $mensaje === '' || ($email === '' && $telefono === '')) {
        $_SESSION['error'] = 'Completá nombre, mensaje y un teléfono o email.';
        header('Location: ' . $volver, true, 303); exit;
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'El email ingresado no es válido.';
        header('Location: ' . $volver, true, 303); exit;
    }
    if ($servicio !== '' && !servicio($servicio) && $servicio !== 'otro') $servicio = '';

    $destino = (string)(cfg('email.destino') ?: cfg('contacto.email'));
    $asunto  = 'Nuevo contacto web - ' . $nombre . ($servicio ? " ({$servicio})" : '');
    $cuerpo  = "Nombre: $nombre\nEmail: " . ($email ?: '-') . "\nTeléfono: " . ($telefono ?: '-') . "\nServicio: " . ($servicio ?: '-') . "\n\nMensaje:\n$mensaje\n\nEnviado el " . date('d/m/Y H:i') . " desde " . cfg('sitio.dominio');

    $ok = false;
    try {
        if (cfg('email.smtp_host') && is_file(BASE_DIR . '/vendor/autoload.php')) {
            require_once BASE_DIR . '/vendor/autoload.php';
            $m = new PHPMailer\PHPMailer\PHPMailer(true);
            $m->isSMTP();
            $m->Host = cfg('email.smtp_host'); $m->SMTPAuth = true;
            $m->Username = cfg('email.smtp_usuario'); $m->Password = cfg('email.smtp_password');
            $m->SMTPSecure = cfg('email.smtp_secure'); $m->Port = (int)cfg('email.smtp_puerto');
            $m->CharSet = 'UTF-8';
            $m->setFrom((string)cfg('email.smtp_usuario'), (string)cfg('marca.nombre'));
            $m->addAddress($destino);
            if ($email) $m->addReplyTo($email, $nombre);
            $m->Subject = $asunto; $m->Body = $cuerpo;
            $ok = $m->send();
        } else {
            $headers = 'From: ' . cfg('marca.nombre') . ' <' . $destino . '>' . "\r\n" . ($email ? "Reply-To: $email\r\n" : '') . "Content-Type: text/plain; charset=UTF-8\r\n";
            $ok = @mail($destino, '=?UTF-8?B?' . base64_encode($asunto) . '?=', $cuerpo, $headers);
        }
    } catch (Throwable $e) {
        $ok = false;
    }
    if ($ok) {
        header('Location: ' . url('contacto/gracias'), true, 303);
    } else {
        $_SESSION['error'] = 'No se pudo enviar el mensaje. Escribinos por WhatsApp o por correo.';
        header('Location: ' . $volver, true, 303);
    }
    exit;
}
