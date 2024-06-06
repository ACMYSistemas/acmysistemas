<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: contact.html");
    exit;
}

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$asunto = $_POST['titulo'];
$mensaje = $_POST['mensaje'];

if (empty(trim($nombre))) $nombre = 'Anónimo';

$body = <<<HTML
    <h1>Contacto desde la web</h1>
    <p>De: $nombre / $email</p>
    <h2>Mensaje</h2>
    <p>$mensaje</p>
HTML;

$mailer = new PHPMailer(true);

try {
    if (!extension_loaded('openssl')) {
        throw new Exception('La extensión OpenSSL no está habilitada.');
    }

    // Configuración del servidor SMTP
    $mailer->isSMTP();
    $mailer->Host = 'smtp.gmail.com'; // Cambia esto a tu servidor SMTP
    $mailer->SMTPAuth = true;
    $mailer->Username = 'acmysistemas@gmail.com'; // Tu correo SMTP
    $mailer->Password = 'JeAnSaSa1970'; // Tu contraseña SMTP
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port = 587;

    // Configuración del correo
    $mailer->setFrom('acmysistemas@gmail.com', 'ACMYSistemas'); // Cambia esto a tu correo y nombre
    $mailer->addAddress($email, $nombre); // Enviar a la dirección proporcionada en el formulario
    $mailer->Subject = "Mensaje Web: $asunto";
    $mailer->msgHTML($body);
    $mailer->AltBody = strip_tags($body);
    $mailer->CharSet = 'UTF-8';

    // Enviar correo
    $mailer->send();
    header("Location: gracias.html");
    exit;
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error: {$mailer->ErrorInfo}";
    echo "Detalles: " . $e->getMessage();
}
?>
