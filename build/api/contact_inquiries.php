<?php
/**
 * Contact Inquiries API
 * Handles CRUD operations for contact inquiries
 */

header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();


// Get request method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'stats') {
                // Get status counts
                $stats = [
                    'all' => $pdo->query("SELECT COUNT(*) FROM contact_inquiries")->fetchColumn(),
                    'new' => $pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'new'")->fetchColumn(),
                    'read' => $pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'read'")->fetchColumn(),
                    'replied' => $pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'replied'")->fetchColumn(),
                    'archived' => $pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE status = 'archived'")->fetchColumn(),
                ];
                echo json_encode(['success' => true, 'data' => $stats]);

            } elseif ($action === 'detail' && isset($_GET['id'])) {
                // Get single inquiry
                $id = intval($_GET['id']);
                $stmt = $pdo->prepare("SELECT * FROM contact_inquiries WHERE inquiry_id = ?");
                $stmt->execute([$id]);
                $inquiry = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($inquiry) {
                    // Mark as read if new
                    if ($inquiry['status'] === 'new') {
                        $pdo->prepare("UPDATE contact_inquiries SET status = 'read' WHERE inquiry_id = ?")->execute([$id]);
                        $inquiry['status'] = 'read';
                    }
                    echo json_encode(['success' => true, 'data' => $inquiry]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Inquiry not found']);
                }

            } elseif ($action === 'emailHistory' && isset($_GET['inquiry_id'])) {
                // Get email reply history for an inquiry
                $inquiryId = intval($_GET['inquiry_id']);

                $stmt = $pdo->prepare("
                    SELECT
                        ier.email_id,
                        ier.recipient_email,
                        ier.subject,
                        ier.message_body,
                        ier.sent_at,
                        ier.status,
                        ier.direction,
                        ier.sender_name,
                        ier.sender_email,
                        CONCAT(up.first_name, ' ', up.last_name) as replied_by_name
                    FROM inquiry_email_replies ier
                    LEFT JOIN user_profiles up ON ier.replied_by = up.user_id
                    WHERE ier.inquiry_id = ?
                    ORDER BY ier.sent_at DESC
                ");
                $stmt->execute([$inquiryId]);
                $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode(['success' => true, 'emails' => $emails]);

            } else {
                // List inquiries
                $status = $_GET['status'] ?? 'all';
                $search = $_GET['search'] ?? '';
                $page = max(1, intval($_GET['page'] ?? 1));
                $perPage = 20;
                $offset = ($page - 1) * $perPage;

                // Build query
                $whereClause = '';
                $params = [];

                if ($status !== 'all') {
                    $whereClause = "WHERE status = ?";
                    $params[] = $status;
                }

                if (!empty($search)) {
                    $whereClause .= $whereClause ? ' AND' : 'WHERE';
                    $whereClause .= " (full_name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
                    $searchTerm = "%$search%";
                    $params[] = $searchTerm;
                    $params[] = $searchTerm;
                    $params[] = $searchTerm;
                    $params[] = $searchTerm;
                }

                // Get total count
                $countSql = "SELECT COUNT(*) FROM contact_inquiries $whereClause";
                $countStmt = $pdo->prepare($countSql);
                $countStmt->execute($params);
                $totalCount = $countStmt->fetchColumn();

                // Get inquiries
                $sql = "SELECT * FROM contact_inquiries $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
                $stmt = $pdo->prepare($sql);

                // Bind params
                $paramIndex = 1;
                foreach ($params as $param) {
                    $stmt->bindValue($paramIndex++, $param);
                }
                $stmt->bindValue($paramIndex++, $perPage, PDO::PARAM_INT);
                $stmt->bindValue($paramIndex++, $offset, PDO::PARAM_INT);

                $stmt->execute();
                $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode([
                    'success' => true,
                    'data' => [
                        'inquiries' => $inquiries,
                        'totalCount' => $totalCount,
                        'page' => $page,
                        'perPage' => $perPage,
                        'totalPages' => ceil($totalCount / $perPage)
                    ]
                ]);
            }
            break;

        case 'PUT':
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);

            if ($action === 'updateStatus' && isset($input['id'])) {
                $id = intval($input['id']);
                $status = $input['status'] ?? '';
                $notes = trim($input['notes'] ?? '');

                $validStatuses = ['new', 'read', 'replied', 'archived'];
                if (!in_array($status, $validStatuses)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid status']);
                    break;
                }

                $sql = "UPDATE contact_inquiries SET status = ?, notes = ?";
                $params = [$status, $notes];

                if ($status === 'replied') {
                    $sql .= ", replied_at = NOW(), replied_by = ?";
                    $params[] = $_SESSION['user_id'] ?? null;
                }

                $sql .= " WHERE inquiry_id = ?";
                $params[] = $id;

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                echo json_encode(['success' => true, 'message' => 'Status updated']);

            } elseif ($action === 'delete' && isset($input['id'])) {
                $id = intval($input['id']);
                $stmt = $pdo->prepare("DELETE FROM contact_inquiries WHERE inquiry_id = ?");
                $stmt->execute([$id]);

                echo json_encode(['success' => true, 'message' => 'Inquiry deleted']);

            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (PDOException $e) {
    error_log("Contact inquiries API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
