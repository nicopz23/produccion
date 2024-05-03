<?php
require 'vendor/autoload.php';
$mail = new PHPMailer\PHPMailer\PHPMailer();

// Configurar el servidor SMTP y las credenciales
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = 'basurista9@gmail.com';
$mail->Password = 'N12345678*';
$mail->SMTPSecure = 'ssl'; // Puede ser 'ssl' también, dependiendo de la configuración del servidor
$mail->Port = 465; // Puerto SMTP

// Configurar el remitente y el destinatario
$mail->setFrom('basurista9@gmail.com', 'Basurita');
$mail->addAddress('samnic1524@gmail.com', 'Nico');

// Configurar el asunto y el cuerpo del correo
$mail->Subject = 'Asunto del Correo';
$mail->Body = 'Este es el contenido del correo electrónico enviado desde PHPMailer.';

// Enviar el correo electrónico
if ($mail->send()) {
    echo 'Correo electrónico enviado correctamente.';
} else {
    echo 'Error al enviar el correo electrónico: ' . $mail->ErrorInfo;
}