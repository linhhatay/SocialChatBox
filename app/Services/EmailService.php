<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        // Cấu hình SMTP hoặc các cài đặt khác của PHPMailer tại đây (nếu cần).
        $this->settings();
    }

    private function settings()
    {
        $this->mailer->SMTPDebug = 0;                      //Enable verbose debug output
        $this->mailer->isSMTP();                                            //Send using SMTP
        $this->mailer->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $this->mailer->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mailer->Username   = 'linhhatay2404@gmail.com';                     //SMTP username
        $this->mailer->Password   = 'xevlvarlsvcxkgpv';                               //SMTP password
        $this->mailer->SMTPSecure = 'tls';   //Enable implicit TLS encryption
        $this->mailer->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    }

    public function sendEmail($recipientEmail, $recipientName, $subject, $body)
    {
        try {
            $this->mailer->setFrom('linhhatay2404@gmail.com', 'Chat App');
            $this->mailer->addAddress($recipientEmail, $recipientName);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Xử lý lỗi nếu có.
            echo "Message could not be sent. Mailer Error: " . $e->getMessage();
            return false;
        }
    }
}

// // Sử dụng class EmailServices
// $emailService = new EmailServices();

// $recipientEmail = 'recipient@example.com';
// $recipientName = 'Recipient Name';
// $subject = 'Test Email';
// $body = 'This is a test email sent using PHPMailer.';

// if ($emailService->sendEmail($recipientEmail, $recipientName, $subject, $body)) {
//     echo "Email sent successfully!";
// } else {
//     echo "Failed to send email.";
// }
