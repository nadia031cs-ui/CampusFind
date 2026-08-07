<?php
// api/friends_request_decline.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$requestId = (int) ($_POST['request_id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM friend_requests WHERE id = ? AND receiver_id = ?');
$stmt->execute([$requestId, $_SESSION['user_id']]);

echo json_encode(['success' => true, 'message' => 'Friend request removed.']);
