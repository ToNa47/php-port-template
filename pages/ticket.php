<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/auth.php'; // just for session_start()

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = false;
$error = null;
$adminEmail = "awaslupaya21@gmail.com"; // fixed destination

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {

        $error = "Sesi kadaluarsa, silakan muat ulang halaman dan coba lagi.";

    } else {

        $subject = trim($_POST["subject"] ?? "");
        $message = trim($_POST["message"] ?? "");

        if ($subject === "" || $message === "") {
            $error = "Semua field wajib diisi";
        } elseif (mb_strlen($subject) > 150 || mb_strlen($message) > 5000) {
            $error = "Subject atau pesan terlalu panjang";
        } else {
            $success = kirimTiket($adminEmail, $subject, $message);
            if (!$success) {
                $error = "Gagal mengirim tiket, coba lagi";
            }
        }
    }
}

function kirimTiket(string $to, string $subject, string $message): bool
{
    $logLine = date("Y-m-d H:i:s") . " | To: $to | Subject: $subject" . PHP_EOL;
    @file_put_contents(__DIR__ . '/../tickets.log', $logLine, FILE_APPEND | LOCK_EX);

    $mail = new PHPMailer(true);

    try {
        // Credentials come from environment variables — see .env.example.
        // The previous version had a real Gmail app password committed
        // directly in this file, which is a serious leak: anyone with the
        // source could log in and send mail as that account. Rotate that
        // app password immediately if this code was ever pushed anywhere.
        $smtpUser = getenv('SMTP_USER');
        $smtpPass = getenv('SMTP_PASS');

        if (!$smtpUser || !$smtpPass) {
            error_log('SMTP_USER / SMTP_PASS environment variables are not set.');
            return false;
        }

        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->Port       = (int) (getenv('SMTP_PORT') ?: 587);
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom($smtpUser, 'Sistem Tiket');
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body    = strip_tags($message);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
<div class="ticket-box">
    <h2>Ticket ke Admin</h2>

    <?php if ($error) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success) : ?>
        <p class="success">Tiket berhasil dikirim ke admin!</p>
    <?php endif; ?>

    <form method="post" class="ticket-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <input
            type="text"
            name="subject"
            placeholder="Subject"
            maxlength="150"
            value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
            required
        >

        <textarea
            name="message"
            placeholder="Message"
            rows="5"
            maxlength="5000"
            required
        ><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button type="submit">Send</button>
    </form>
</div>
