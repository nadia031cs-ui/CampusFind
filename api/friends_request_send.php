<?php
// api/friends_request_send.php
require_once __DIR__ . '/../includes/friends.php';
require_once __DIR__ . '/../includes/notifications.php';
header('Content-Type: application/json');
requireLogin(true);

$myId       = $_SESSION['user_id'];
$receiverId = (int) ($_POST['receiver_id'] ?? 0);

if ($receiverId <= 0 || $receiverId === (int) $myId) {
    echo json_encode(['success' => false, 'message' => 'Invalid user.']);
    exit;
}

$exists = $pdo->prepare('SELECT id FROM users WHERE id = ?');
$exists->execute([$receiverId]);
if (!$exists->fetch()) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

if (areFriends($myId, $receiverId)) {
    echo json_encode(['success' => false, 'message' => 'You are already friends.']);
    exit;
}

// If the other person already sent YOU a request, accept it instead of creating a duplicate
$incoming = $pdo->prepare("SELECT id FROM friend_requests WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'");
$incoming->execute([$receiverId, $myId]);
if ($row = $incoming->fetch()) {
    $pdo->prepare('DELETE FROM friend_requests WHERE id = ?')->execute([$row['id']]);
    addFriendship($myId, $receiverId);
    createNotification($receiverId, 'friend', $_SESSION['username'] . ' accepted your friend request.', 'friends.php');
    echo json_encode(['success' => true, 'message' => 'You are now friends!', 'status' => 'friends']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$myId, $receiverId]);
    createNotification($receiverId, 'friend', $_SESSION['username'] . ' sent you a friend request.', 'Friend_req.php');
    echo json_encode(['success' => true, 'message' => 'Friend request sent.', 'status' => 'pending']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Friend request already sent.']);
}
