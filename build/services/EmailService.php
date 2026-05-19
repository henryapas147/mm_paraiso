<?php

namespace App\Services;

// Manual PHPMailer loading (since composer couldn't install it)
require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/SMTP.php';
require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;
    private $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo;
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer()
    {
        // Get Gmail credentials from environment or config
        $gmailUser = getenv('GMAIL_USER') ?: '';
        $gmailPass = getenv('GMAIL_APP_PASSWORD') ?: '';

        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $gmailUser;
        $this->mailer->Password = $gmailPass;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
        $this->mailer->CharSet = 'UTF-8';

        // From address
        $this->mailer->setFrom($gmailUser, 'M&M Paraiso Support');
        $this->mailer->addReplyTo($gmailUser, 'M&M Paraiso Support');
    }

    /**
     * Send a reply to a contact inquiry
     *
     * @param string $to Email address of the recipient
     * @param string $toName Name of the recipient
     * @param string $subject Subject of the email
     * @param string $message Body of the email
     * @param int|null $inquiryId Optional inquiry ID for tracking
     * @param int|null $repliedBy Optional user ID who sent the reply
     * @return array ['success' => bool, 'message' => string, 'email_id' => int|null]
     */
    public function sendInquiryReply($to, $toName, $subject, $message, $inquiryId = null, $repliedBy = null)
    {
        try {
            // Clear previous recipients
            $this->mailer->clearAddresses();

            // Add recipient
            $this->mailer->addAddress($to, $toName);

            // Fetch company info for template
            $companyInfo = [];
            $originalInquiry = [];
            if ($this->pdo) {
                try {
                    $cs = $this->pdo->query("SELECT company_name, email, phone_number FROM company_information WHERE is_active = 1 LIMIT 1");
                    $companyInfo = $cs->fetch(\PDO::FETCH_ASSOC) ?: [];
                } catch (\Exception $e) {
                    // use defaults
                }
                if ($inquiryId) {
                    try {
                        $qs = $this->pdo->prepare("SELECT subject, message, created_at FROM contact_inquiries WHERE inquiry_id = ?");
                        $qs->execute([$inquiryId]);
                        $originalInquiry = $qs->fetch(\PDO::FETCH_ASSOC) ?: [];
                    } catch (\Exception $e) {
                        // non-critical
                    }
                }
            }

            // Format the reply with a proper template
            $formattedMessage = $this->formatReplyTemplate($message, $companyInfo, $toName, $subject, $originalInquiry);

            // Build a trackable Message-ID so inbound replies can be matched
            $gmailUser = getenv('GMAIL_USER') ?: 'noreply';
            $domain = substr(strrchr($gmailUser, '@'), 1) ?: 'mnmparaiso.com';
            $messageId = 'inquiry-' . ($inquiryId ?? '0') . '-' . uniqid() . '@' . $domain;
            $this->mailer->MessageID = '<' . $messageId . '>';

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Re: ' . $subject;
            $this->mailer->Body = $formattedMessage;
            $this->mailer->AltBody = strip_tags($message);

            // Send the email
            $this->mailer->send();

            // Log the email if we have a database connection
            $emailLogId = null;
            if ($this->pdo && $inquiryId) {
                $emailLogId = $this->logEmail($inquiryId, $to, $subject, $message, $repliedBy, $messageId);
            }

            return [
                'success' => true,
                'message' => 'Reply sent successfully',
                'email_id' => $emailLogId
            ];

        } catch (Exception $e) {
            error_log('Email sending failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
                'email_id' => null
            ];
        }
    }

    /**
     * Format the reply with a professional email template
     */
    private function formatReplyTemplate($message, $companyInfo = [], $recipientName = '', $subject = '', $originalInquiry = [])
    {
        $year = date('Y');        
        $formattedMessage = nl2br(htmlspecialchars($message));
        $companyName = htmlspecialchars($companyInfo['company_name'] ?? 'M&M Paraiso Resort');
        $companyEmail = htmlspecialchars($companyInfo['email'] ?? 'info@mnmparaiso.com');
        $companyPhone = htmlspecialchars($companyInfo['phone_number'] ?? '010-020-0340');
        $greeting = $recipientName ? 'Hello, ' . htmlspecialchars($recipientName) . '!' : 'Hello!';
        $originalSubject = htmlspecialchars($originalInquiry['subject'] ?? $subject ?? '');
        $originalMessage = htmlspecialchars($originalInquiry['message'] ?? '');
        $originalDate = !empty($originalInquiry['created_at'])
            ? date('F j, Y \a\t g:i A', strtotime($originalInquiry['created_at']))
            : '';

        $quotedBlock = '';
        if ($originalMessage) {
            $quotedBlock = '
        <!-- Original Inquiry Quote -->
        <tr>
          <td style="padding:0 40px 28px;">
            <div style="background:#f5f5f5;border-left:3px solid #cccccc;padding:14px 18px;border-radius:0 6px 6px 0;">
              <p style="margin:0 0 8px;font-size:11px;color:#999;text-transform:uppercase;letter-spacing:0.6px;">Original inquiry' . ($originalDate ? ' &bull; ' . $originalDate : '') . ($originalSubject ? ' &bull; Re: ' . $originalSubject : '') . '</p>
              <p style="margin:0;font-size:13px;color:#666;line-height:1.6;white-space:pre-wrap;">' . nl2br(htmlspecialchars($originalInquiry['message'])) . '</p>
            </div>
          </td>
        </tr>';
        }

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&amp;M Paraiso Resort</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f2f5;font-family:\'Segoe UI\',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f2f5;padding:30px 0;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.10);">

        <!-- Header Banner -->
        <tr>
          <td style="background:linear-gradient(135deg,#f35525 0%,#c0392b 100%);padding:36px 40px;text-align:center;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center">
                  <div style="display:inline-block;background:rgba(255,255,255,0.15);border-radius:50%;width:64px;height:64px;line-height:64px;text-align:center;margin-bottom:12px;">
                    <span style="font-size:28px;">🏡</span>
                  </div>
                  <h1 style="margin:0;color:#ffffff;font-size:26px;font-weight:700;letter-spacing:1px;">' . $companyName . '</h1>
                  <p style="margin:6px 0 0;color:rgba(255,255,255,0.85);font-size:13px;letter-spacing:0.5px;">Your Paradise Getaway</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Greeting Bar -->
        <tr>
          <td style="background:#fff8f6;padding:16px 40px;border-bottom:1px solid #fde8e4;">
            <p style="margin:0;font-size:13px;color:#f35525;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;">&#128338; Reply from our team</p>
          </td>
        </tr>

        <!-- Message Body -->
        <tr>
          <td style="padding:36px 40px;">
            <p style="margin:0 0 20px;font-size:15px;color:#555;line-height:1.5;">' . $greeting . '</p>
            <div style="font-size:15px;color:#333333;line-height:1.8;background:#fafafa;border-left:4px solid #f35525;padding:20px 24px;border-radius:0 8px 8px 0;margin-bottom:24px;">
              ' . $formattedMessage . '
            </div>
            <p style="margin:0;font-size:14px;color:#888;line-height:1.6;">
              If you have further questions, feel free to reply to this email or reach out through the contact details below.
            </p>
          </td>
        </tr>

        <!-- Divider -->
        <tr>
          <td style="padding:0 40px;">
            <hr style="border:none;border-top:1px solid #eeeeee;margin:0;">
          </td>
        </tr>

        ' . $quotedBlock . '

        <!-- Signature -->
        <tr>
          <td style="padding:28px 40px;">
            <table cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td style="vertical-align:top;">
                  <p style="margin:0 0 4px;font-size:15px;font-weight:700;color:#1e1e1e;">' . $companyName . ' Team</p>
                  <p style="margin:0 0 12px;font-size:13px;color:#888;">We\'re happy to assist you</p>
                  <table cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:4px 0;">
                        <span style="display:inline-block;background:#fff0ec;color:#f35525;border-radius:4px;padding:3px 8px;font-size:12px;font-weight:600;margin-right:6px;">✉</span>
                        <a href="mailto:' . $companyEmail . '" style="color:#f35525;font-size:13px;text-decoration:none;">' . $companyEmail . '</a>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:4px 0;">
                        <span style="display:inline-block;background:#fff0ec;color:#f35525;border-radius:4px;padding:3px 8px;font-size:12px;font-weight:600;margin-right:6px;">📞</span>
                        <span style="color:#555;font-size:13px;">' . $companyPhone . '</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#1e1e1e;padding:20px 40px;text-align:center;border-radius:0 0 12px 12px;">
            <p style="margin:0 0 6px;color:#aaaaaa;font-size:12px;">&copy; ' . $year . ' ' . $companyName . '. All rights reserved.</p>
            <p style="margin:0;color:#666666;font-size:11px;">This is a reply to your inquiry. Please do not share this email.</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>';
    }

    /**
     * Log the sent email to the database
     */
    private function logEmail($inquiryId, $to, $subject, $message, $repliedBy, $messageId = null)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO inquiry_email_replies 
                (message_id, inquiry_id, recipient_email, subject, message_body, replied_by, sent_at, status, direction)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 'sent', 'outbound')
            ");

            $stmt->execute([
                $messageId,
                $inquiryId,
                $to,
                $subject,
                $message,
                $repliedBy
            ]);

            // Update the inquiry status to replied
            $updateStmt = $this->pdo->prepare("
                UPDATE contact_inquiries 
                SET status = 'replied', replied_at = NOW(), replied_by = ?
                WHERE inquiry_id = ?
            ");
            $updateStmt->execute([$repliedBy, $inquiryId]);

            return $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            error_log('Failed to log email: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get email history for an inquiry
     */
    public function getEmailHistory($inquiryId)
    {
        if (!$this->pdo) {
            return [];
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    email_id,
                    recipient_email,
                    subject,
                    message_body,
                    replied_by,
                    CONCAT(up.first_name, ' ', up.last_name) as replied_by_name,
                    sent_at,
                    status
                FROM inquiry_email_replies ier
                LEFT JOIN user_profiles up ON ier.replied_by = up.user_id
                WHERE inquiry_id = ?
                ORDER BY sent_at DESC
            ");
            $stmt->execute([$inquiryId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Failed to get email history: ' . $e->getMessage());
            return [];
        }
    }
}
