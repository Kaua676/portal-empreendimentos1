<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/env.php';

function enviarEmail($emailDestino, $assunto, $corpo)
{
    $mail = new PHPMailer(true);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($emailDestino);

        $mail->Subject = $assunto;
        $mail->Body = $corpo;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}