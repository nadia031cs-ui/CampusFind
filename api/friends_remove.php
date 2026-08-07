<?php
// api/friends_remove.php
require_once __DIR__ . '/../includes/friends.php';
header('Content-Type: application/json');
requireLogin(true);

$friendId = (int) ($_POST['friend_id'] ?? 0);
if ($friendId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user.']);
    exit;
}

removeFriendship($_SESSION['user_id'], $friendId);

echo json_encode(['success' => true, 'message' => 'Friend removed.']);
