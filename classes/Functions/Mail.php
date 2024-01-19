<?php

namespace Functions;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer() {
        $this->mailer->isSMTP();
        $this->mailer->SMTPAutoTLS;
        $this->mailer->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
        $this->mailer->Host = 'smtp.gmail.com'; // Replace with your SMTP host
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'vicenet99@gmail.com'; // Replace with your email address
        $this->mailer->Password = 'rzfnhngjepwqvmdw'; // Replace with your email password
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587; // Replace with your SMTP port
        $this->mailer->setFrom('noreply@viceNet.com', 'viceNet Account term');
    }

    private function send($email, $subject, $message) {
        try {
            $this->mailer->addAddress($email, 'Recipient Name');
            $this->mailer->addReplyTo('noreply@viceNet.com', 'noReply');
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $message;
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            ErrorLogger::logError($e, null, 'mail');
            return false;
        }
    }

    public function sendEmail($email, $subject, $message): bool
    {
        return $this->send($email, $subject, $message);
    }

    public function sendErrorToAdmin($subject, $message): bool
    {
        $adminEmail = 'www.sanjayamadusanka2017@gmail.com';
        return $this->send($adminEmail, $subject, $message);
    }

    public function sendVerificationCode(string $email, int $verificationCode): bool {
        // Generate the HTML email body using the provided verification code and include your website logo
        $message = '<html><body>';
        $message .= '<div style="text-align: center;">';
        $message .= '<img src="https://i.imgur.com/D0PKiOb.png" alt="Logo" style="max-width: 80%; height: auto;">';
        $message .= '</div>';
        $message .= '<h1>Hi, ' . $email . '. Welcome to viceNet!</h1>';
        $message .= '<h4>The superhero of social networking</h4>';
        $message .= '<p>Please use the following verification code to verify your email address:</p>';
        $message .= '<center><p style="font-size: large;"><strong>Verification Code: ' . $verificationCode . '</strong></p></center>';
        $message .= "<p>If you didn't request this code, you can safely ignore this email. Someone else might have typed your email address by mistake.</p>";
        $message .= '<p>Thank you!</p>';
        $message .= '<p>The viceNet account team</p>';
        $message .= '</body></html>';

        return $this->send($email, 'Your single-use Verification code', $message);
    }
}
