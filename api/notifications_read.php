<?php
// api/notifications_read.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$id = isset($_POST['id']) ? (int) $_POST['id'] : null;

if ($id) {
    $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND recipient_id = ?');
    $stmt->execute([$id, $_SESSION['user_id']]);
} else {
    // no id supplied = mark all as read
    $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE recipient_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}

echo json_encode(['success' => true]);
