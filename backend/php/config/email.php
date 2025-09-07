<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

function enviarEmail($emailDestino, $assunto, $corpo) {
    $mail = new PHPMailer(true);
    $mail->isHTML(true);       // Para que o corpo seja tratado como HTML
    $mail->CharSet = 'UTF-8';  // Para que acentuação e caracteres especiais funcionem

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'kauavic676@gmail.com'; // Seu e-mail
        $mail->Password = 'ifyb hvck kahv tnml'; // Sua senha ou app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remetente
        $mail->setFrom('kauavic676@gmail.com', 'Portal Consulta');

        // Destinatário
        $mail->addAddress($emailDestino);

        // Assunto e Corpo
        $mail->Subject = $assunto;
        $mail->Body    = $corpo;

        // Enviar
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>