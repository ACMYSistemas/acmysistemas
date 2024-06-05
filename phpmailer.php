<?php
if($_SERVER['REQUEST_METHOD'] != 'POST' ){
    header("Location: index.html" );
}

require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$nombre = $_POST['nombre'];
//$apellido = $_POST['apellido'];
$email = $_POST['email'];
$asunto = $_POST['titulo'];
$mensaje = $_POST['mensaje'];

if( empty(trim($nombre)) ) $nombre = 'anonimo';
if( empty(trim($apellido)) ) $apellido = '';

$body = <<<HTML
    <h1>Contacto desde la web</h1>
    <p>De: $nombre / $email</p>
    <h2>Mensaje</h2>
    $mensaje
HTML;

$mailer = new PHPMailer();
$mailer->setFrom( $email, "$nombre" );
$mailer->addAddress('acmysistemas@gmail.com','ACMYSistemas');
$mailer->Subject = "Mensaje Web: $asunto";
$mailer->msgHTML($body);
$mailer->AltBody = strip_tags($body);
$mailer->CharSet = 'UTF-8';



$rta = $mailer->send( );

//var_dump($rta);
header("Location: index.html" );