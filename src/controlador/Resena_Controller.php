<?php

use benjamin\plantillaweb\libs\Controlador;
use benjamin\plantillaweb\libs\Conexion;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

class Resena_Controller extends Controlador
{
    public function index()
    {
        $this->cargarVista("resena/index");
    }

    public function dejar_resena()
    {
        $this->cargarVista("resena/dejar_resena");
    }

    public function enviar()
    {
        $base = $GLOBALS['url'] ?? '/';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $base . 'resena');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $calificacion = intval($_POST['calificacion'] ?? 0);
        $mensaje = trim($_POST['mensaje'] ?? '');

        if (empty($nombre) || empty($email) || empty($mensaje)) {
            $_SESSION['error'] = 'Por favor completa todos los campos requeridos.';
            header('Location: ' . $base . 'resena/dejar_resena');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Por favor ingresa un email válido.';
            header('Location: ' . $base . 'resena/dejar_resena');
            exit;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL_SMTP_USUARIO;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->SMTPSecure = EMAIL_SMTP_SECURE;
            $mail->Port = EMAIL_SMTP_PUERTO;

            $mail->setFrom(EMAIL_SMTP_USUARIO, EMPRESA_NOMBRE);
            $mail->addAddress(CONTACTO_EMAIL_CONTACTO);
            $mail->addReplyTo($email, $nombre);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = "Nueva reseña - $nombre ($calificacion estrellas)";

            $calificacion = max(1, min(5, $calificacion));
        $estrellas = str_repeat('★', $calificacion) . str_repeat('☆', 5 - $calificacion);
        $nombreH = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $emailH = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $telefonoH = htmlspecialchars($telefono ?: 'No proporcionado', ENT_QUOTES, 'UTF-8');

            $mail->Body = "
                <h2>Nueva Reseña Recibida</h2>
                <hr>
                <p><strong>Nombre:</strong> $nombreH</p>
                <p><strong>Email:</strong> $emailH</p>
                <p><strong>Teléfono:</strong> $telefonoH</p>
                <p><strong>Calificación:</strong> $estrellas ($calificacion/5)</p>
                <hr>
                <p><strong>Mensaje:</strong></p>
                <p>" . nl2br(htmlspecialchars($mensaje)) . "</p>
                <hr>
                <p><em>Enviado el " . date('d/m/Y H:i') . "</em></p>
            ";

            $mail->AltBody = "Nueva Reseña\n\nNombre: $nombre\nEmail: $email\nTeléfono: " . ($telefono ?: 'No proporcionado') . "\nCalificación: $estrellas\n\nMensaje:\n$mensaje";

            $mail->send();
            $_SESSION['exito'] = 'Gracias por tu mensaje. Nos pondremos en contacto pronto.';
            header('Location: ' . $base . 'resena/gracias');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'No pudimos enviar tu mensaje. Probá de nuevo o escribinos por correo.';
            header('Location: ' . $base . 'resena/dejar_resena');
            exit;
        }
    }

    public function gracias()
    {
        $this->cargarVista("resena/gracias");
    }

    public function google()
    {
        header('Location: ' . RESENA_GOOGLE_PROFILE_URL);
        exit;
    }

    public function privacidad()
    {
        $this->cargarVista("resena/privacidad");
    }
}
