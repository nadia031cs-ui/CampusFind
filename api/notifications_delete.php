<?php
// api/notifications_delete.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$id = isset($_POST['id']) ? (int) $_POST['id'] : null;

if ($id) {
    $pdo->prepare('DELETE FROM notifications WHERE id = ? AND recipient_id = ?')
        ->execute([$id, $_SESSION['user_id']]);
} else {
    // no id supplied = clear all
    $pdo->prepare('DELETE FROM notifications WHERE recipient_id = ?')
        ->execute([$_SESSION['user_id']]);
}

echo json_encode(['success' => true]);
