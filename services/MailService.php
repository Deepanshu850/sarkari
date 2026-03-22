<?php

namespace App\Services;

class MailService {

    /**
     * Send blueprint PDF to user's email
     */
    public function sendBlueprintEmail(array $user, array $blueprint, string $pdfPath): bool {
        $to = $user['email'];
        $name = $user['name'];
        $examName = $blueprint['exam_name'] ?? 'Government Exam';
        $subject = "Aapka {$examName} Blueprint Ready Hai! — Sarkari";

        $htmlBody = $this->buildBlueprintEmailHtml($name, $examName, $blueprint);

        // Try SMTP first, fallback to PHP mail()
        if ($this->hasSmtpConfig()) {
            return $this->sendViaSmtp($to, $name, $subject, $htmlBody, $pdfPath);
        }

        return $this->sendViaPhpMail($to, $subject, $htmlBody, $pdfPath);
    }

    private function hasSmtpConfig(): bool {
        return !empty($_ENV['MAIL_USER'] ?? '') && !empty($_ENV['MAIL_PASS'] ?? '');
    }

    private function sendViaSmtp(string $to, string $toName, string $subject, string $htmlBody, string $pdfPath): bool {
        $host = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $port = (int) ($_ENV['MAIL_PORT'] ?? 587);
        $user = $_ENV['MAIL_USER'] ?? '';
        $pass = $_ENV['MAIL_PASS'] ?? '';
        $fromEmail = $_ENV['MAIL_FROM'] ?? 'noreply@sarkaariblueprint.in';
        $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'Sarkari';

        try {
            $boundary = md5(time());

            // Build MIME message
            $headers = "From: {$fromName} <{$fromEmail}>\r\n";
            $headers .= "Reply-To: {$fromEmail}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

            $body = "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
            $body .= $htmlBody . "\r\n\r\n";

            // Attach PDF if exists
            if (file_exists($pdfPath)) {
                $pdfContent = base64_encode(file_get_contents($pdfPath));
                $pdfFilename = basename($pdfPath);
                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: application/pdf; name=\"{$pdfFilename}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$pdfFilename}\"\r\n\r\n";
                $body .= chunk_split($pdfContent) . "\r\n";
            }
            $body .= "--{$boundary}--\r\n";

            // Connect to SMTP
            $socket = fsockopen(($port === 465 ? 'ssl://' : '') . $host, $port, $errno, $errstr, 10);
            if (!$socket) {
                error_log("SMTP connect failed: {$errstr}");
                return false;
            }

            $this->smtpRead($socket);
            $this->smtpCmd($socket, "EHLO sarkaariblueprint.in");

            if ($port === 587) {
                $this->smtpCmd($socket, "STARTTLS");
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $this->smtpCmd($socket, "EHLO sarkaariblueprint.in");
            }

            $this->smtpCmd($socket, "AUTH LOGIN");
            $this->smtpCmd($socket, base64_encode($user));
            $this->smtpCmd($socket, base64_encode($pass));
            $this->smtpCmd($socket, "MAIL FROM:<{$fromEmail}>");
            $this->smtpCmd($socket, "RCPT TO:<{$to}>");
            $this->smtpCmd($socket, "DATA");

            fwrite($socket, "To: {$toName} <{$to}>\r\n");
            fwrite($socket, "Subject: {$subject}\r\n");
            fwrite($socket, $headers);
            fwrite($socket, "\r\n" . $body . "\r\n.\r\n");
            $this->smtpRead($socket);

            $this->smtpCmd($socket, "QUIT");
            fclose($socket);

            return true;
        } catch (\Exception $e) {
            error_log("SMTP send failed: " . $e->getMessage());
            return false;
        }
    }

    private function smtpCmd($socket, string $cmd): string {
        fwrite($socket, $cmd . "\r\n");
        return $this->smtpRead($socket);
    }

    private function smtpRead($socket): string {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        return $response;
    }

    private function sendViaPhpMail(string $to, string $subject, string $htmlBody, string $pdfPath): bool {
        $fromEmail = $_ENV['MAIL_FROM'] ?? 'noreply@sarkaariblueprint.in';
        $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'Sarkari';
        $boundary = md5(time());

        $headers = "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $body .= $htmlBody . "\r\n\r\n";

        if (file_exists($pdfPath)) {
            $pdfContent = base64_encode(file_get_contents($pdfPath));
            $pdfFilename = basename($pdfPath);
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/pdf; name=\"{$pdfFilename}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$pdfFilename}\"\r\n\r\n";
            $body .= chunk_split($pdfContent) . "\r\n";
        }
        $body .= "--{$boundary}--\r\n";

        return @mail($to, $subject, $body, $headers);
    }

    private function buildBlueprintEmailHtml(string $name, string $examName, array $blueprint): string {
        $studyHours = $blueprint['study_hours'] ?? 4;
        $examDate = isset($blueprint['exam_date']) ? date('d M Y', strtotime($blueprint['exam_date'])) : '';
        $dashboardUrl = ($_ENV['APP_URL'] ?? 'https://sarkaariblueprint.in') . '/dashboard';

        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: sans-serif; background: #f8f6f0; padding: 20px; margin: 0;">
    <div style="max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #0f172a, #1e3a5f); padding: 24px; text-align: center;">
            <div style="height: 4px; background: linear-gradient(to right, #FF6B00, white, #138808); border-radius: 2px; margin-bottom: 16px;"></div>
            <h1 style="color: white; font-size: 22px; margin: 0;">SARKARI</h1>
            <p style="color: #94a3b8; font-size: 12px; margin: 4px 0 0;">AI-Powered Exam Blueprint</p>
        </div>

        <!-- Body -->
        <div style="padding: 24px;">
            <h2 style="color: #0f172a; font-size: 18px; margin: 0 0 8px;">Namaste {$name}! 🎯</h2>
            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0 0 16px;">
                Aapka <strong style="color: #FF6B00;">{$examName}</strong> ka 30-day personalized blueprint ready hai!
                PDF attach kiya hai — <strong>print karke wall pe lagao</strong> aur roz follow karo.
            </p>

            <!-- Stats -->
            <div style="background: #f8f6f0; border: 1px solid #e5ddd0; border-radius: 8px; padding: 16px; margin: 16px 0;">
                <table style="width: 100%; font-size: 13px; color: #4b5563;">
                    <tr>
                        <td style="padding: 4px 0;"><strong>Exam:</strong> {$examName}</td>
                        <td style="padding: 4px 0;"><strong>Hours/Day:</strong> {$studyHours}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;"><strong>Duration:</strong> 30 Days</td>
                        <td style="padding: 4px 0;"><strong>Exam Date:</strong> {$examDate}</td>
                    </tr>
                </table>
            </div>

            <!-- CTA -->
            <div style="text-align: center; margin: 24px 0;">
                <a href="{$dashboardUrl}" style="display: inline-block; background: #FF6B00; color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px;">
                    Dashboard Pe Progress Track Karo →
                </a>
            </div>

            <p style="color: #9ca3af; font-size: 12px; line-height: 1.5; margin: 16px 0 0;">
                <strong>Tips:</strong><br>
                📄 PDF print karke study table pe rakho<br>
                ✅ Har din complete karne pe dashboard pe ✓ mark karo<br>
                🔥 Streak banao — consistency hi success ki key hai
            </p>
        </div>

        <!-- Footer -->
        <div style="background: #f8f6f0; padding: 16px; text-align: center; border-top: 1px solid #e5ddd0;">
            <p style="color: #9ca3af; font-size: 11px; margin: 0;">
                Sarkari Blueprint — AI-Powered Exam Preparation<br>
                <a href="https://sarkaariblueprint.in" style="color: #FF6B00; text-decoration: none;">sarkaariblueprint.in</a>
            </p>
            <div style="height: 3px; background: linear-gradient(to right, #FF6B00, white, #138808); border-radius: 2px; margin-top: 12px;"></div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
