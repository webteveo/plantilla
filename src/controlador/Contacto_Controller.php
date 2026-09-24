<?php

use benjamin\plantillaweb\libs\Controlador;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

class Contacto_Controller extends Controlador
{
    public function index()
    {
        $this->cargarVista('contacto/index');
    }

    public function enviar()
    {
        $base = $GLOBALS['url'] ?? '/';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $base . 'contacto');
            exit;
        }

        $nombre   = trim($_POST['nombre']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $servicio = trim($_POST['servicio'] ?? '');
        $mensaje  = trim($_POST['mensaje']  ?? '');

        if (empty($nombre) || empty($email) || empty($mensaje)) {
            $_SESSION['error'] = 'Por favor completá nombre, email y mensaje.';
            header('Location: ' . $base . 'contacto');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El email ingresado no es válido.';
            header('Location: ' . $base . 'contacto');
            exit;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = EMAIL_SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_SMTP_USUARIO;
            $mail->Password   = EMAIL_SMTP_PASSWORD;
            $mail->SMTPSecure = EMAIL_SMTP_SECURE;
            $mail->Port       = EMAIL_SMTP_PUERTO;

            $mail->setFrom(EMAIL_SMTP_USUARIO, EMPRESA_NOMBRE);
            $mail->addAddress(CONTACTO_EMAIL_CONTACTO);
            $mail->addReplyTo($email, $nombre);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = "Nuevo contacto - $nombre" . ($servicio ? " ($servicio)" : '');

            $servicioHtml = $servicio ? "<p><strong>Servicio de interés:</strong> " . htmlspecialchars($servicio) . "</p>" : '';

            $mail->Body = "
                <h2>Nuevo mensaje de contacto</h2>
                <hr>
                <p><strong>Nombre:</strong> " . htmlspecialchars($nombre) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Teléfono:</strong> " . ($telefono ? htmlspecialchars($telefono) : 'No proporcionado') . "</p>
                $servicioHtml
                <hr>
                <p><strong>Mensaje:</strong></p>
                <p>" . nl2br(htmlspecialchars($mensaje)) . "</p>
                <hr>
                <p><em>Enviado el " . date('d/m/Y H:i') . "</em></p>
            ";

            $mail->AltBody = "Nuevo contacto\n\nNombre: $nombre\nEmail: $email\nTeléfono: " . ($telefono ?: 'No proporcionado') . ($servicio ? "\nServicio: $servicio" : '') . "\n\nMensaje:\n$mensaje";

            $mail->send();
            $_SESSION['exito'] = 'Mensaje enviado. Te respondemos en menos de 24 horas.';
            header('Location: ' . $base . 'contacto/gracias');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'No se pudo enviar el mensaje. Escribinos directamente por correo.';
            header('Location: ' . $base . 'contacto');
            exit;
        }
    }

    public function gracias()
    {
        $this->cargarVista('contacto/gracias');
    }
}
