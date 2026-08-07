<?php
// api/friends_request_accept.php
require_once __DIR__ . '/../includes/friends.php';
require_once __DIR__ . '/../includes/notifications.php';
header('Content-Type: application/json');
requireLogin(true);

$myId      = $_SESSION['user_id'];
$requestId = (int) ($_POST['request_id'] ?? 0);

$stmt = $pdo->prepare("SELECT id, sender_id FROM friend_requests WHERE id = ? AND receiver_id = ? AND status = 'pending'");
$stmt->execute([$requestId, $myId]);
$request = $stmt->fetch();

if (!$request) {
    echo json_encode(['success' => false, 'message' => 'Friend request not found.']);
    exit;
}

$senderId = (int) $request['sender_id'];

$pdo->prepare('DELETE FROM friend_requests WHERE id = ?')->execute([$requestId]);
addFriendship($myId, $senderId);
createNotification($senderId, 'friend', $_SESSION['username'] . ' accepted your friend request.', 'friends.php');

echo json_encode(['success' => true, 'message' => 'Friend request accepted.']);
