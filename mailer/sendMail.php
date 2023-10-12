<?php
    //Import PHPMailer classes into the global namespace
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';

function send_email ( string $email,int $verificationCode) {
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
    

    $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPAutoTLS;
            $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
            $mail->Host = 'smtp.gmail.com'; //localhost
            $mail->SMTPAuth = true;
            $mail->Username = 'vicenet99@gmail.com';    // Your Gmail email address
            $mail->Password = 'rzfnhngjepwqvmdw';       // Your Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 25; //587;
        
            $mail->setFrom('noreply@viceNet.com', 'viceNet Account term');  // Sender's email and name 'vicenet99@gmail.com', 'viceNet Account term'
            $mail->addAddress($email, 'Recipient Name');  // Recipient's email and name
            $mail->addReplyTo('noreply@viceNet.com', 'noReply');  // Reply-to email and name
        
            $mail->isHTML(true);
            $mail->Subject = 'Your single-use Verification code';
            $mail->Body = $message;
            $mail->send();
            return true;
        } catch (Exception $e) {
            logFunctionError($e);
            return false;
        }

    // Log the error message to user_signup_log.txt
    function logFunctionError($e) : void 
    {
        $error_message = "Error saving user data: " . $e->getMessage();
        // Log the error message to a file
        $error_log = __DIR__.'/../../var/log/signup.txt';
        file_put_contents($error_log, $error_message . PHP_EOL, FILE_APPEND);
    
        // Redirect to the error.php page
        $error_page = $_SERVER['DOCUMENT_ROOT'].'/viceNet_social_media_website/error.php';
        header("Location: $error_page");
        exit();
    }
}



