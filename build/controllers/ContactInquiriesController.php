<?php

namespace App\Controllers;

use PDO;
use PDOException;

class ContactInquiriesController extends Controller
{
    private $pdo;

    public function __construct($params = [])
    {
        parent::__construct($params);
        global $pdo;
        $this->pdo = $pdo;
    }

    public function index()
    {
        // View fetches data via AJAX from API
        $this->view('contact-inquiries/index');
    }

    public function detail()
    {
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            header('Location: ./contact-inquiries');
            exit;
        }

        // Get inquiry details with optional customer profile photo
        $stmt = $this->pdo->prepare("
            SELECT ci.*, up.profile_photo_url
            FROM contact_inquiries ci
            LEFT JOIN user_accounts ua ON ua.email = ci.email
            LEFT JOIN user_profiles up ON up.user_id = ua.user_id
            WHERE ci.inquiry_id = ?
        ");
        $stmt->execute([$id]);
        $inquiry = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inquiry) {
            header('Location: ./contact-inquiries');
            exit;
        }

        // Mark as read if it's new
        if ($inquiry['status'] === 'new') {
            $updateStmt = $this->pdo->prepare("UPDATE contact_inquiries SET status = 'read' WHERE inquiry_id = ?");
            $updateStmt->execute([$id]);
            $inquiry['status'] = 'read';
        }

        $this->view('contact-inquiries/view', [
            'inquiry' => $inquiry
        ]);
    }

    public function updateStatus()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!\validateCsrfToken()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token.']);
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $notes = trim($_POST['notes'] ?? '');

        $validStatuses = ['new', 'read', 'replied', 'archived'];

        if (!$id || !in_array($status, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            return;
        }

        try {
            $sql = "UPDATE contact_inquiries SET status = ?, notes = ?";
            $params = [$status, $notes];

            if ($status === 'replied') {
                $sql .= ", replied_at = NOW(), replied_by = ?";
                $params[] = $_SESSION['user_id'] ?? null;
            }

            $sql .= " WHERE inquiry_id = ?";
            $params[] = $id;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            error_log("Update inquiry status error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!\validateCsrfToken()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid or missing CSRF token.']);
            return;
        }

        $id = intval($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM contact_inquiries WHERE inquiry_id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            error_log("Delete inquiry error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
}
