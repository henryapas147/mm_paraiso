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
     * Send reservation confirmation email
     *
     * @param array $data Reservation data
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendReservationConfirmation($data)
    {
        try {
            // Clear previous recipients
            $this->mailer->clearAddresses();

            // Add recipient
            $this->mailer->addAddress($data['email'], $data['first_name'] . ' ' . $data['last_name']);

            // Get company info
            $companyInfo = [];
            if ($this->pdo) {
                try {
                    $cs = $this->pdo->query("SELECT company_name, email, phone_number FROM company_information WHERE is_active = 1 LIMIT 1");
                    $companyInfo = $cs->fetch(\PDO::FETCH_ASSOC) ?: [];
                } catch (\Exception $e) {
                    // use defaults
                }
            }

            // Format the confirmation email
            $formattedMessage = $this->formatReservationTemplate($data, $companyInfo);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Reservation Confirmation - ' . $data['reservation_code'];
            $this->mailer->Body = $formattedMessage;
            $this->mailer->AltBody = $this->formatReservationText($data, $companyInfo);

            // Send the email
            $this->mailer->send();

            return [
                'success' => true,
                'message' => 'Confirmation email sent successfully'
            ];

        } catch (Exception $e) {
            error_log('Reservation confirmation email failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send confirmation email: ' . $e->getMessage()
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
          <td style="padding:24px 48px 8px;">
            <p style="margin:0 0 10px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;font-weight:600;">&#128393; Your Original Message' . ($originalDate ? ' &nbsp;&bull;&nbsp; ' . $originalDate : '') . ($originalSubject ? ' &nbsp;&bull;&nbsp; ' . $originalSubject : '') . '</p>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-left:3px solid #d1d5db;border-radius:0 8px 8px 0;padding:16px 20px;">
              <p style="margin:0;font-size:13px;color:#6b7280;line-height:1.7;white-space:pre-wrap;">' . nl2br(htmlspecialchars($originalInquiry['message'])) . '</p>
            </div>
          </td>
        </tr>
        <tr><td style="height:16px;"></td></tr>';
        }

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&amp;M Paraiso Resort</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:\'Segoe UI\',Helvetica,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8;padding:40px 16px;">
  <tr>
    <td align="center">

      <!-- Wrapper -->
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

        <!-- Top Logo Area -->
        <tr>
          <td style="padding-bottom:20px;text-align:center;">
            <span style="font-size:11px;color:#aaaaaa;letter-spacing:1px;text-transform:uppercase;">Official Communication from</span>
          </td>
        </tr>

        <!-- Card -->
        <tr>
          <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 2px 24px rgba(0,0,0,0.08);border:1px solid #e8eaed;">

            <table width="100%" cellpadding="0" cellspacing="0">

              <!-- Header -->
              <tr>
                <td style="background:linear-gradient(150deg,#e84118 0%,#c0392b 60%,#922b21 100%);padding:44px 48px 36px;text-align:center;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="center">
                        <table cellpadding="0" cellspacing="0">
                          <tr>
                            <td style="background:rgba(255,255,255,0.18);border-radius:50%;width:72px;height:72px;text-align:center;vertical-align:middle;">
                              <span style="font-size:32px;line-height:72px;">🏝️</span>
                            </td>
                          </tr>
                        </table>
                        <h1 style="margin:14px 0 4px;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;">' . $companyName . '</h1>
                        <p style="margin:0;color:rgba(255,255,255,0.75);font-size:12px;letter-spacing:1.5px;text-transform:uppercase;">Your Paradise Getaway</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Badge Bar -->
              <tr>
                <td style="background:#fdf3f1;border-bottom:1px solid #fad7d0;padding:12px 48px;">
                  <table cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:#e84118;border-radius:20px;padding:5px 14px;">
                        <span style="color:#ffffff;font-size:11px;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">&#128338;&nbsp; Reply from our Support Team</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Greeting -->
              <tr>
                <td style="padding:36px 48px 0;">
                  <p style="margin:0;font-size:17px;color:#1a1a2e;font-weight:600;line-height:1.4;">' . $greeting . '</p>
                  <p style="margin:10px 0 0;font-size:14px;color:#6b7280;line-height:1.5;">Thank you for reaching out to us. Please see our response below.</p>
                </td>
              </tr>

              <!-- Divider -->
              <tr>
                <td style="padding:20px 48px 0;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="border-top:1px solid #f0f0f0;font-size:0;">&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Message Body -->
              <tr>
                <td style="padding:24px 48px 32px;">
                  <div style="font-size:15px;color:#374151;line-height:1.85;background:#fafbfc;border:1px solid #e5e7eb;border-left:4px solid #e84118;border-radius:0 10px 10px 0;padding:24px 28px;">
                    ' . $formattedMessage . '
                  </div>
                  <p style="margin:20px 0 0;font-size:13px;color:#9ca3af;line-height:1.7;font-style:italic;">
                    If you have further questions, feel free to reply to this email or reach out through the contact details below.
                  </p>
                </td>
              </tr>

              ' . $quotedBlock . '

              <!-- Divider -->
              <tr>
                <td style="padding:0 48px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="border-top:1px solid #f0f0f0;font-size:0;">&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Signature -->
              <tr>
                <td style="padding:28px 48px 36px;">
                  <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td>
                        <p style="margin:0 0 2px;font-size:15px;font-weight:700;color:#1a1a2e;">' . $companyName . ' Support Team</p>
                        <p style="margin:0 0 16px;font-size:13px;color:#9ca3af;">We\'re always happy to help</p>

                        <!-- Email Contact -->
                        <table cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
                          <tr>
                            <td style="vertical-align:middle;padding-right:10px;">
                              <table cellpadding="0" cellspacing="0">
                                <tr>
                                  <td style="background:#fdf3f1;border-radius:6px;width:28px;height:28px;text-align:center;vertical-align:middle;">
                                    <span style="font-size:13px;line-height:28px;">✉️</span>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td>
                              <a href="mailto:' . $companyEmail . '" style="color:#e84118;font-size:13px;text-decoration:none;font-weight:500;">' . $companyEmail . '</a>
                            </td>
                          </tr>
                        </table>

                        <!-- Phone Contact -->
                        <table cellpadding="0" cellspacing="0">
                          <tr>
                            <td style="vertical-align:middle;padding-right:10px;">
                              <table cellpadding="0" cellspacing="0">
                                <tr>
                                  <td style="background:#fdf3f1;border-radius:6px;width:28px;height:28px;text-align:center;vertical-align:middle;">
                                    <span style="font-size:13px;line-height:28px;">📞</span>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td>
                              <span style="color:#374151;font-size:13px;font-weight:500;">' . $companyPhone . '</span>
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
                <td style="background:#1a1a2e;padding:20px 48px;border-radius:0 0 16px 16px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="text-align:center;">
                        <p style="margin:0 0 5px;color:#9ca3af;font-size:12px;">&copy; ' . $year . ' ' . $companyName . '. All rights reserved.</p>
                        <p style="margin:0;color:#6b7280;font-size:11px;">This is a reply to your inquiry &mdash; please do not share this email.</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            </table>
          </td>
        </tr>

        <!-- Bottom spacer -->
        <tr><td style="height:24px;"></td></tr>

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
     * Format reservation confirmation email template
     */
    private function formatReservationTemplate($data, $companyInfo)
    {
        $year = date('Y');
        $companyName = htmlspecialchars($companyInfo['company_name'] ?? 'M&M Paraiso Resort');
        $companyEmail = htmlspecialchars($companyInfo['email'] ?? 'info@mnmparaiso.com');
        $companyPhone = htmlspecialchars($companyInfo['phone_number'] ?? '010-020-0340');

        // Pre-compute variables
        $paymentMethod = htmlspecialchars($data['payment_method'] ?? 'N/A');
        $paymentOption = ucfirst(htmlspecialchars($data['payment_option'] ?? 'N/A'));
        $totalAmount = number_format($data['total_amount'] ?? 0, 2);
        $amountRequired = number_format($data['amount_required'] ?? 0, 2);
        $amountPaid = number_format($data['amount_paid'] ?? 0, 2);
        $paymentStatus = ucfirst(htmlspecialchars($data['payment_status'] ?? 'pending'));
        $txnRef = !empty($data['transaction_reference']) ? "<p><strong>Reference Number:</strong> " . htmlspecialchars($data['transaction_reference']) . "</p>" : '';
        
        // Guest count
        $numAdults = intval($data['num_adults'] ?? 0);
        $numChildren = intval($data['num_children'] ?? 0);
        $numSeniors = intval($data['num_seniors'] ?? 0);
        $guestDisplay = $numAdults . ' Adult' . ($numAdults > 1 ? 's' : '');
        if ($numChildren > 0) {
            $guestDisplay .= ', ' . $numChildren . ' Child' . ($numChildren > 1 ? 'ren' : '');
        }
        if ($numSeniors > 0) {
            $guestDisplay .= ', ' . $numSeniors . ' Senior Citizen' . ($numSeniors > 1 ? 's' : '');
        }

        // Build requirements block for email
        $requirementsBlock = '';
        $requirements = $data['requirements'] ?? [];
        if (!empty($requirements)) {
            $reqRows = '';
            foreach ($requirements as $req) {
                $mandatoryBadge = $req['is_mandatory']
                    ? '<span style="font-size:11px;background:#fee2e2;color:#b91c1c;border-radius:4px;padding:2px 7px;margin-left:8px;font-weight:600;">Required</span>'
                    : '<span style="font-size:11px;background:#f3f4f6;color:#6b7280;border-radius:4px;padding:2px 7px;margin-left:8px;">Optional</span>';
                $desc = !empty($req['description']) ? '<br><span style="font-size:12px;color:#6b7280;">' . htmlspecialchars($req['description']) . '</span>' : '';
                $reqRows .= '
                <tr>
                  <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:14px;color:#374151;">
                    <i style="color:#f59e0b;margin-right:8px;">&#9679;</i>'
                    . htmlspecialchars($req['req_label']) . $mandatoryBadge . $desc . '
                  </td>
                </tr>';
            }
            $requirementsBlock = '
              <!-- Requirements Section -->
              <tr>
                <td style="padding:0 48px 28px;">
                  <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:0.5px;">
                    &#128196; Documents &amp; Requirements Needed Before Check-in
                  </p>
                  <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:16px 20px;">
                    <p style="margin:0 0 10px;font-size:13px;color:#92400e;">Please prepare and submit the following before your visit:</p>
                    <table width="100%" cellpadding="0" cellspacing="0">' . $reqRows . '</table>
                  </div>
                </td>
              </tr>';
        }

        // Format visit date to "Month Day, Year" format
        $visitDateFormatted = '';
        if (!empty($data['visit_date'])) {
            $visitDateObj = date_create($data['visit_date']);
            if ($visitDateObj) {
                $visitDateFormatted = $visitDateObj->format('F j, Y');
            }
        }

        // Format visit time to 12-hour format
        $visitTimeFormatted = '';
        if (!empty($data['visit_time'])) {
            $visitTimeObj = date_create($data['visit_time']);
            if ($visitTimeObj) {
                $visitTimeFormatted = $visitTimeObj->format('g:i A');
            }
        }

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:\'Segoe UI\',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8;padding:40px 16px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

        <!-- Pre-header label -->
        <tr>
          <td style="padding-bottom:20px;text-align:center;">
            <span style="font-size:11px;color:#aaaaaa;letter-spacing:1px;text-transform:uppercase;">Official Communication from</span>
          </td>
        </tr>

        <!-- Card -->
        <tr>
          <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 2px 24px rgba(0,0,0,0.08);border:1px solid #e8eaed;">
            <table width="100%" cellpadding="0" cellspacing="0">

              <!-- Header -->
              <tr>
                <td style="background:linear-gradient(150deg,#e84118 0%,#c0392b 60%,#922b21 100%);padding:44px 48px 36px;text-align:center;">
                  <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td align="center">
                        <table cellpadding="0" cellspacing="0">
                          <tr>
                            <td style="background:rgba(255,255,255,0.18);border-radius:50%;width:72px;height:72px;text-align:center;vertical-align:middle;">
                              <span style="font-size:32px;line-height:72px;">🏝️</span>
                            </td>
                          </tr>
                        </table>
                        <h1 style="margin:14px 0 4px;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;">' . $companyName . '</h1>
                        <p style="margin:0;color:rgba(255,255,255,0.75);font-size:12px;letter-spacing:1.5px;text-transform:uppercase;">Reservation Confirmation</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Confirmation Badge -->
              <tr>
                <td style="background:#f0fdf4;border-bottom:1px solid #bbf7d0;padding:16px 48px;">
                  <table cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:#16a34a;border-radius:20px;padding:5px 16px;">
                        <span style="color:#ffffff;font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;">&#10003;&nbsp; Reservation Received</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Intro -->
              <tr>
                <td style="padding:32px 48px 8px;">
                  <p style="margin:0;font-size:17px;color:#1a1a2e;font-weight:600;">Hello, ' . htmlspecialchars($data['first_name']) . '!</p>
                  <p style="margin:10px 0 0;font-size:14px;color:#6b7280;line-height:1.6;">Thank you for your reservation. Here is a summary of your booking details.</p>
                </td>
              </tr>

              <!-- Reservation Code Banner -->
              <tr>
                <td style="padding:20px 48px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:#fdf3f1;border:1px dashed #f9a78e;border-radius:10px;padding:16px 24px;text-align:center;">
                        <p style="margin:0 0 4px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;">Reservation Code</p>
                        <p style="margin:0;font-size:22px;font-weight:800;color:#e84118;letter-spacing:2px;">' . htmlspecialchars($data['reservation_code']) . '</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Booking Details -->
              <tr>
                <td style="padding:0 48px 24px;">
                  <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
                    <tr>
                      <td colspan="2" style="background:#f9fafb;padding:12px 20px;border-bottom:1px solid #e5e7eb;">
                        <span style="font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.8px;">&#128197; Booking Details</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;width:40%;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Guest Name</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Visit Date</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . htmlspecialchars($visitDateFormatted ?: $data['visit_date']) . '</span>
                        <span style="font-size:13px;color:#6b7280;"> at ' . htmlspecialchars($visitTimeFormatted ?: $data['visit_time']) . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Guests</span>
                      </td>
                      <td style="padding:12px 20px;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . $guestDisplay . '</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Payment Information -->
              <tr>
                <td style="padding:0 48px 32px;">
                  <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
                    <tr>
                      <td colspan="2" style="background:#f0fdf4;padding:12px 20px;border-bottom:1px solid #bbf7d0;">
                        <span style="font-size:12px;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:0.8px;">&#128178; Payment Information</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;width:40%;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Method</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . $paymentMethod . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Option</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . $paymentOption . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Total Amount</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:15px;color:#1a1a2e;font-weight:700;">&#8369;' . $totalAmount . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Amount Required</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">&#8369;' . $amountRequired . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Amount Paid</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">&#8369;' . $amountPaid . '</span>
                      </td>
                    </tr>
                    ' . ($txnRef ? '<tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Reference No.</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . htmlspecialchars($data['transaction_reference'] ?? '') . '</span>
                      </td>
                    </tr>' : '') . '
                    <tr>
                      <td style="padding:12px 20px;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Status</span>
                      </td>
                      <td style="padding:12px 20px;">
                        <table cellpadding="0" cellspacing="0">
                          <tr>
                            <td style="background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:3px 10px;">
                              <span style="font-size:12px;font-weight:700;color:#ea580c;">' . $paymentStatus . ' &mdash; Awaiting Confirmation</span>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              ' . $requirementsBlock . '

              <!-- Contact Note -->
              <tr>
                <td style="padding:0 48px 32px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px 20px;">
                        <p style="margin:0;font-size:13px;color:#1e40af;line-height:1.6;">
                          &#128222;&nbsp; We will reach out to you at <strong>' . htmlspecialchars($data['phone']) . '</strong> to confirm your visit. Please keep your phone accessible.
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Footer -->
              <tr>
                <td style="background:#1a1a2e;padding:20px 48px;border-radius:0 0 16px 16px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="text-align:center;">
                        <p style="margin:0 0 5px;color:#9ca3af;font-size:12px;">&copy; ' . $year . ' ' . $companyName . '. All rights reserved.</p>
                        <p style="margin:0;color:#6b7280;font-size:11px;">' . $companyEmail . ' &nbsp;&bull;&nbsp; ' . $companyPhone . '</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            </table>
          </td>
        </tr>

        <tr><td style="height:24px;"></td></tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>';
    }

    /**
     * Format reservation confirmation as plain text
     */
    private function formatReservationText($data, $companyInfo)
    {
        $companyName = $companyInfo['company_name'] ?? 'M&M Paraiso Resort';
        
        $numAdults = intval($data['num_adults'] ?? 0);
        $numChildren = intval($data['num_children'] ?? 0);
        $numSeniors = intval($data['num_seniors'] ?? 0);
        $guestDisplay = $numAdults . ' Adult' . ($numAdults > 1 ? 's' : '');
        if ($numChildren > 0) {
            $guestDisplay .= ', ' . $numChildren . ' Child' . ($numChildren > 1 ? 'ren' : '');
        }
        if ($numSeniors > 0) {
            $guestDisplay .= ', ' . $numSeniors . ' Senior Citizen' . ($numSeniors > 1 ? 's' : '');
        }

        // Format visit date and time
        $visitDateFormatted = '';
        if (!empty($data['visit_date'])) {
            $visitDateObj = date_create($data['visit_date']);
            if ($visitDateObj) {
                $visitDateFormatted = $visitDateObj->format('F j, Y');
            }
        }
        $visitTimeFormatted = '';
        if (!empty($data['visit_time'])) {
            $visitTimeObj = date_create($data['visit_time']);
            if ($visitTimeObj) {
                $visitTimeFormatted = $visitTimeObj->format('g:i A');
            }
        }

        $text = "Reservation Confirmation - {$data['reservation_code']}\n\n";
        $text .= "Guest: {$data['first_name']} {$data['last_name']}\n";
        $text .= "Visit Date: " . ($visitDateFormatted ?: $data['visit_date']) . " at " . ($visitTimeFormatted ?: $data['visit_time']) . "\n";
        $text .= "Guests: " . $guestDisplay . "\n\n";
        $text .= "PAYMENT INFORMATION:\n";
        $text .= "Method: " . ($data['payment_method'] ?? 'N/A') . "\n";
        $text .= "Option: " . ucfirst($data['payment_option'] ?? 'N/A') . "\n";
        $text .= "Total: ₱" . number_format($data['total_amount'] ?? 0, 2) . "\n";
        $text .= "Required: ₱" . number_format($data['amount_required'] ?? 0, 2) . "\n";
        $text .= "Paid: ₱" . number_format($data['amount_paid'] ?? 0, 2) . "\n";
        if (!empty($data['transaction_reference'])) {
            $text .= "Reference: {$data['transaction_reference']}\n";
        }
        $text .= "Status: " . ucfirst($data['payment_status'] ?? 'pending') . " (Awaiting admin confirmation)\n\n";

        $requirements = $data['requirements'] ?? [];
        if (!empty($requirements)) {
            $text .= "DOCUMENTS & REQUIREMENTS NEEDED BEFORE CHECK-IN:\n";
            foreach ($requirements as $req) {
                $mandatory = $req['is_mandatory'] ? ' [Required]' : ' [Optional]';
                $text .= "  - " . $req['req_label'] . $mandatory . "\n";
                if (!empty($req['description'])) {
                    $text .= "    " . $req['description'] . "\n";
                }
            }
            $text .= "\n";
        }

        $text .= "We will contact you at {$data['phone']} to confirm your visit.\n\n";
        $text .= $companyName;

        return $text;
    }

    /**
     * Send reservation status update email (confirmation/rejection)
     *
     * @param array $data Reservation data including status
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendReservationStatusUpdate($data)
    {
        try {
            // Clear previous recipients
            $this->mailer->clearAddresses();

            // Add recipient
            $this->mailer->addAddress($data['email'], $data['first_name'] . ' ' . $data['last_name']);

            // Get company info
            $companyInfo = [];
            if ($this->pdo) {
                try {
                    $cs = $this->pdo->query("SELECT company_name, email, phone_number FROM company_information WHERE is_active = 1 LIMIT 1");
                    $companyInfo = $cs->fetch(\PDO::FETCH_ASSOC) ?: [];
                } catch (\Exception $e) {
                    // use defaults
                }
            }

            // Format the status update email
            $formattedMessage = $this->formatStatusUpdateTemplate($data, $companyInfo);

            // Content
            $statusLabel = $data['status'] === 'confirmed' ? 'Confirmed' : 'Cancelled';
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Reservation ' . $statusLabel . ' - ' . $data['reservation_code'];
            $this->mailer->Body = $formattedMessage;
            $this->mailer->AltBody = $this->formatStatusUpdateText($data, $companyInfo);

            // Send the email
            $this->mailer->send();

            return [
                'success' => true,
                'message' => 'Status update email sent successfully'
            ];

        } catch (Exception $e) {
            error_log('Status update email failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send status update email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format status update email template
     */
    private function formatStatusUpdateTemplate($data, $companyInfo)
    {
        $year = date('Y');
        $companyName = htmlspecialchars($companyInfo['company_name'] ?? 'M&M Paraiso Resort');
        $companyEmail = htmlspecialchars($companyInfo['email'] ?? 'info@mnmparaiso.com');
        $companyPhone = htmlspecialchars($companyInfo['phone_number'] ?? '010-020-0340');

        $isConfirmed = $data['status'] === 'confirmed';
        $statusLabel = $isConfirmed ? 'Confirmed' : 'Cancelled';
        $statusColor = $isConfirmed ? '#2E7D32' : '#C62828';
        $statusIcon = $isConfirmed ? '✓' : '✕';
        $statusMessage = $isConfirmed 
            ? 'Your reservation has been confirmed! We look forward to welcoming you.'
            : 'Your reservation has been cancelled. If you have any questions, please contact us.';

        // Format check-in date and time
        $checkInDateFormatted = '';
        if (!empty($data['check_in_date'])) {
            $checkInDateObj = date_create($data['check_in_date']);
            if ($checkInDateObj) {
                $checkInDateFormatted = $checkInDateObj->format('F j, Y');
            }
        }
        $checkInTimeFormatted = '';
        if (!empty($data['check_in_time'])) {
            $checkInTimeObj = date_create($data['check_in_time']);
            if ($checkInTimeObj) {
                $checkInTimeFormatted = $checkInTimeObj->format('g:i A');
            }
        }

        $totalAmount = isset($data['total_amount']) ? '₱' . number_format($data['total_amount'], 2) : 'N/A';

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation ' . $statusLabel . '</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:\'Segoe UI\',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8;padding:40px 16px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

        <!-- Pre-header label -->
        <tr>
          <td style="padding-bottom:20px;text-align:center;">
            <span style="font-size:11px;color:#aaaaaa;letter-spacing:1px;text-transform:uppercase;">Official Communication from</span>
          </td>
        </tr>

        <!-- Card -->
        <tr>
          <td style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 2px 24px rgba(0,0,0,0.08);border:1px solid #e8eaed;">
            <table width="100%" cellpadding="0" cellspacing="0">

              <!-- Header -->
              <tr>
                <td style="background:linear-gradient(150deg,#e84118 0%,#c0392b 60%,#922b21 100%);padding:44px 48px 36px;text-align:center;">
                  <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td align="center">
                        <table cellpadding="0" cellspacing="0">
                          <tr>
                            <td style="background:rgba(255,255,255,0.18);border-radius:50%;width:72px;height:72px;text-align:center;vertical-align:middle;">
                              <span style="font-size:32px;line-height:72px;">🏝️</span>
                            </td>
                          </tr>
                        </table>
                        <h1 style="margin:14px 0 4px;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;">' . $companyName . '</h1>
                        <p style="margin:0;color:rgba(255,255,255,0.75);font-size:12px;letter-spacing:1.5px;text-transform:uppercase;">Reservation Update</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Status Banner -->
              <tr>
                <td style="background:' . ($isConfirmed ? '#f0fdf4' : '#fff1f2') . ';border-bottom:1px solid ' . ($isConfirmed ? '#bbf7d0' : '#fecdd3') . ';padding:16px 48px;">
                  <table cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:' . $statusColor . ';border-radius:20px;padding:5px 16px;">
                        <span style="color:#ffffff;font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;">' . $statusIcon . '&nbsp; Reservation ' . $statusLabel . '</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Status Message Block -->
              <tr>
                <td style="padding:32px 48px 24px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:' . ($isConfirmed ? '#f0fdf4' : '#fff1f2') . ';border:1px solid ' . ($isConfirmed ? '#bbf7d0' : '#fecdd3') . ';border-left:4px solid ' . $statusColor . ';border-radius:0 10px 10px 0;padding:20px 24px;">
                        <p style="margin:0 0 6px;font-size:16px;font-weight:700;color:' . $statusColor . ';">' . $statusIcon . ' Reservation ' . $statusLabel . '</p>
                        <p style="margin:0;font-size:14px;color:#374151;line-height:1.6;">' . $statusMessage . '</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Reservation Details -->
              <tr>
                <td style="padding:0 48px 32px;">
                  <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
                    <tr>
                      <td colspan="2" style="background:#f9fafb;padding:12px 20px;border-bottom:1px solid #e5e7eb;">
                        <span style="font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.8px;">&#128197; Reservation Summary</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;width:40%;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Reservation Code</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;font-weight:700;color:#e84118;letter-spacing:1px;">' . htmlspecialchars($data['reservation_code']) . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Guest Name</span>
                      </td>
                      <td style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) . '</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 20px;' . ($isConfirmed ? 'border-bottom:1px solid #f3f4f6;' : '') . '">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Check-in</span>
                      </td>
                      <td style="padding:12px 20px;' . ($isConfirmed ? 'border-bottom:1px solid #f3f4f6;' : '') . '">
                        <span style="font-size:14px;color:#1a1a2e;font-weight:600;">' . htmlspecialchars($checkInDateFormatted ?: $data['check_in_date']) . '</span>
                        <span style="font-size:13px;color:#6b7280;"> at ' . htmlspecialchars($checkInTimeFormatted ?: $data['check_in_time']) . '</span>
                      </td>
                    </tr>
                    ' . ($isConfirmed ? '<tr>
                      <td style="padding:12px 20px;">
                        <span style="font-size:12px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px;">Total Amount</span>
                      </td>
                      <td style="padding:12px 20px;">
                        <span style="font-size:15px;color:#1a1a2e;font-weight:700;">&#8369;' . number_format($data['total_amount'] ?? 0, 2) . '</span>
                      </td>
                    </tr>' : '') . '
                  </table>
                </td>
              </tr>

              <!-- Support Note -->
              <tr>
                <td style="padding:0 48px 32px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px 20px;">
                        <p style="margin:0;font-size:13px;color:#1e40af;line-height:1.6;">
                          Questions? Contact us at <a href="mailto:' . $companyEmail . '" style="color:#1d4ed8;font-weight:600;text-decoration:none;">' . $companyEmail . '</a> or call <strong>' . $companyPhone . '</strong>.
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Footer -->
              <tr>
                <td style="background:#1a1a2e;padding:20px 48px;border-radius:0 0 16px 16px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="text-align:center;">
                        <p style="margin:0 0 5px;color:#9ca3af;font-size:12px;">&copy; ' . $year . ' ' . $companyName . '. All rights reserved.</p>
                        <p style="margin:0;color:#6b7280;font-size:11px;">' . $companyEmail . ' &nbsp;&bull;&nbsp; ' . $companyPhone . '</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            </table>
          </td>
        </tr>

        <tr><td style="height:24px;"></td></tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>';
    }

    /**
     * Format status update email as plain text
     */
    private function formatStatusUpdateText($data, $companyInfo)
    {
        $companyName = $companyInfo['company_name'] ?? 'M&M Paraiso Resort';
        $isConfirmed = $data['status'] === 'confirmed';
        $statusLabel = $isConfirmed ? 'Confirmed' : 'Cancelled';
        $statusMessage = $isConfirmed 
            ? 'Your reservation has been confirmed! We look forward to welcoming you.'
            : 'Your reservation has been cancelled. If you have any questions, please contact us.';

        // Format check-in date and time
        $checkInDateFormatted = '';
        if (!empty($data['check_in_date'])) {
            $checkInDateObj = date_create($data['check_in_date']);
            if ($checkInDateObj) {
                $checkInDateFormatted = $checkInDateObj->format('F j, Y');
            }
        }
        $checkInTimeFormatted = '';
        if (!empty($data['check_in_time'])) {
            $checkInTimeObj = date_create($data['check_in_time']);
            if ($checkInTimeObj) {
                $checkInTimeFormatted = $checkInTimeObj->format('g:i A');
            }
        }

        $totalAmount = isset($data['total_amount']) ? '₱' . number_format($data['total_amount'], 2) : 'N/A';

        $text = "Reservation {$statusLabel} - {$data['reservation_code']}\n\n";
        $text .= "{$statusMessage}\n\n";
        $text .= "Reservation #{$data['reservation_code']}\n";
        $text .= "Guest: {$data['first_name']} {$data['last_name']}\n";
        $text .= "Check-in: " . ($checkInDateFormatted ?: $data['check_in_date']) . " at " . ($checkInTimeFormatted ?: $data['check_in_time']) . "\n";
        if ($isConfirmed) {
            $text .= "Total Amount: {$totalAmount}\n";
        }
        $text .= "\n{$companyName}";

        return $text;
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

    /**
     * Send food order confirmation email
     *
     * @param array $orderData Order data including items, totals, customer info
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendFoodOrderConfirmation($orderData)
    {
        try {
            // Clear previous recipients
            $this->mailer->clearAddresses();

            // Add recipient
            $customerEmail = $orderData['customer_email'] ?? '';
            $customerName = $orderData['customer_name'] ?? 'Guest';
            
            if (empty($customerEmail)) {
                return [
                    'success' => false,
                    'message' => 'No customer email provided'
                ];
            }
            
            $this->mailer->addAddress($customerEmail, $customerName);

            // Get company info
            $companyInfo = [];
            if ($this->pdo) {
                try {
                    $cs = $this->pdo->query("SELECT company_name, email, phone_number FROM company_information WHERE is_active = 1 LIMIT 1");
                    $companyInfo = $cs->fetch(\PDO::FETCH_ASSOC) ?: [];
                } catch (\Exception $e) {
                    // use defaults
                }
            }

            // Format the food order email
            $formattedMessage = $this->formatFoodOrderTemplate($orderData, $companyInfo);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Food Order Confirmation - ' . ($orderData['order_number'] ?? 'Order');
            $this->mailer->Body = $formattedMessage;
            $this->mailer->AltBody = $this->formatFoodOrderText($orderData, $companyInfo);

            // Send the email
            $this->mailer->send();

            return [
                'success' => true,
                'message' => 'Food order confirmation email sent successfully'
            ];

        } catch (Exception $e) {
            error_log('Food order confirmation email failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send food order confirmation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format food order email template
     */
    private function formatFoodOrderTemplate($orderData, $companyInfo = [])
    {
        $year = date('Y');
        $companyName = htmlspecialchars($companyInfo['company_name'] ?? 'M&M Paraiso Resort');
        $companyEmail = htmlspecialchars($companyInfo['email'] ?? 'info@mnmparaiso.com');
        $companyPhone = htmlspecialchars($companyInfo['phone_number'] ?? '010-020-0340');
        
        $orderNumber = htmlspecialchars($orderData['order_number'] ?? 'N/A');
        $customerName = htmlspecialchars($orderData['customer_name'] ?? 'Guest');
        $orderType = ucfirst(str_replace('_', ' ', $orderData['order_type'] ?? 'dine_in'));
        $tableInfo = '';
        $roomInfo = '';
        
        if (!empty($orderData['table_number'])) {
            $tableInfo = '<p><strong>Table:</strong> ' . htmlspecialchars($orderData['table_number']) . '</p>';
        }
        if (!empty($orderData['room_number'])) {
            $roomInfo = '<p><strong>Room:</strong> ' . htmlspecialchars($orderData['room_number']) . '</p>';
        }
        
        $itemsHtml = '';
        if (!empty($orderData['items']) && is_array($orderData['items'])) {
            foreach ($orderData['items'] as $item) {
                $itemsHtml .= '
                <tr>
                    <td style="padding:12px;border-bottom:1px solid #e5e7eb;">' . htmlspecialchars($item['item_name'] ?? 'Item') . '</td>
                    <td style="padding:12px;border-bottom:1px solid #e5e7eb;text-align:center;">' . ($item['quantity'] ?? 1) . '</td>
                    <td style="padding:12px;border-bottom:1px solid #e5e7eb;text-align:right;">₱' . number_format($item['unit_price'] ?? 0, 2) . '</td>
                    <td style="padding:12px;border-bottom:1px solid #e5e7eb;text-align:right;">₱' . number_format($item['total_price'] ?? 0, 2) . '</td>
                </tr>';
            }
        }
        
        $totalAmount = '₱' . number_format($orderData['total_amount'] ?? 0, 2);
        $notes = !empty($orderData['notes']) ? '<p><strong>Special Requests:</strong> ' . htmlspecialchars($orderData['notes']) . '</p>' : '';

        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Order Confirmation</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;">
    <table role="presentation" style="width:100%;border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:48px 16px;">
                <table role="presentation" style="width:100%;max-width:600px;border-collapse:collapse;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#f97316 0%,#ea580c 100%);padding:32px 48px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;">🍽️ Food Order Confirmation</h1>
                            <p style="margin:8px 0 0;color:#fed7aa;font-size:16px;">' . $companyName . '</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:32px 48px;">
                            <p style="margin:0 0 20px;font-size:16px;color:#374151;">Hello ' . $customerName . ',</p>
                            <p style="margin:0 0 24px;font-size:16px;color:#374151;">Thank you for your order! Here are your order details:</p>
                            
                            <!-- Order Info -->
                            <div style="background:#fef3c7;border-radius:8px;padding:20px;margin-bottom:24px;">
                                <p style="margin:0 0 8px;"><strong>Order Number:</strong> ' . $orderNumber . '</p>
                                <p style="margin:0 0 8px;"><strong>Order Type:</strong> ' . $orderType . '</p>
                                ' . $tableInfo . $roomInfo . '
                                ' . $notes . '
                            </div>
                            
                            <!-- Items Table -->
                            <table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
                                <thead>
                                    <tr style="background:#f9fafb;">
                                        <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;font-size:14px;color:#6b7280;">Item</th>
                                        <th style="padding:12px;text-align:center;border-bottom:2px solid #e5e7eb;font-size:14px;color:#6b7280;">Qty</th>
                                        <th style="padding:12px;text-align:right;border-bottom:2px solid #e5e7eb;font-size:14px;color:#6b7280;">Price</th>
                                        <th style="padding:12px;text-align:right;border-bottom:2px solid #e5e7eb;font-size:14px;color:#6b7280;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ' . $itemsHtml . '
                                </tbody>
                                <tfoot>
                                    <tr style="background:#f9fafb;font-weight:600;">
                                        <td colspan="3" style="padding:16px 12px;text-align:right;border-top:2px solid #e5e7eb;">Items Total:</td>
                                        <td style="padding:16px 12px;text-align:right;border-top:2px solid #e5e7eb;color:#ea580c;font-size:18px;">' . $totalAmount . '</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <p style="margin:24px 0 0;font-size:14px;color:#6b7280;">If you have any questions about your order, please contact us at <a href="mailto:' . $companyEmail . '" style="color:#ea580c;text-decoration:none;">' . $companyEmail . '</a> or call ' . $companyPhone . '.</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9fafb;padding:24px 48px;text-align:center;border-top:1px solid #e5e7eb;">
                            <p style="margin:0;font-size:14px;color:#9ca3af;">&copy; ' . $year . ' ' . $companyName . '. All rights reserved.</p>
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
     * Format food order plain text version
     */
    private function formatFoodOrderText($orderData, $companyInfo = [])
    {
        $companyName = $companyInfo['company_name'] ?? 'M&M Paraiso Resort';
        $orderNumber = $orderData['order_number'] ?? 'N/A';
        $customerName = $orderData['customer_name'] ?? 'Guest';
        $orderType = ucfirst(str_replace('_', ' ', $orderData['order_type'] ?? 'dine_in'));
        $totalAmount = '₱' . number_format($orderData['total_amount'] ?? 0, 2);
        
        $text = "Food Order Confirmation - {$orderNumber}\n\n";
        $text .= "Hello {$customerName},\n\n";
        $text .= "Thank you for your order!\n\n";
        $text .= "Order Details:\n";
        $text .= "Order Number: {$orderNumber}\n";
        $text .= "Order Type: {$orderType}\n";
        
        if (!empty($orderData['table_number'])) {
            $text .= "Table: {$orderData['table_number']}\n";
        }
        if (!empty($orderData['room_number'])) {
            $text .= "Room: {$orderData['room_number']}\n";
        }
        
        $text .= "\nItems Ordered:\n";
        if (!empty($orderData['items']) && is_array($orderData['items'])) {
            foreach ($orderData['items'] as $item) {
                $itemName = $item['item_name'] ?? 'Item';
                $qty = $item['quantity'] ?? 1;
                $price = '₱' . number_format($item['total_price'] ?? 0, 2);
                $text .= "- {$itemName} x{$qty} = {$price}\n";
            }
        }
        
        $text .= "\nItems Total: {$totalAmount}\n\n";
        
        if (!empty($orderData['notes'])) {
            $text .= "Special Requests: {$orderData['notes']}\n\n";
        }
        
        $text .= "{$companyName}";
        
        return $text;
    }
}