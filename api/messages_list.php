<?php
// api/messages_list.php — left-panel conversation list (one row per friend, real friends only)
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$myId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'SELECT u.id, u.full_name, u.photo
     FROM friends f
     JOIN users u ON u.id = IF(f.user_low = ?, f.user_high, f.user_low)
     WHERE f.user_low = ? OR f.user_high = ?
     ORDER BY u.full_name ASC'
);
$stmt->execute([$myId, $myId, $myId]);
$friends = $stmt->fetchAll();

$lastMsgStmt = $pdo->prepare(
    'SELECT body, image_path, sender_id, created_at FROM messages
     WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
     ORDER BY created_at DESC LIMIT 1'
);
$unreadStmt = $pdo->prepare(
    'SELECT COUNT(*) AS c FROM messages WHERE sender_id = ? AND receiver_id = ? AND is_read = 0'
);

$conversations = [];
foreach ($friends as $f) {
    $friendId = (int) $f['id'];
    $lastMsgStmt->execute([$myId, $friendId, $friendId, $myId]);
    $last = $lastMsgStmt->fetch();
    $unreadStmt->execute([$friendId, $myId]);
    $unread = (int) $unreadStmt->fetch()['c'];

    $conversations[] = [
        'id'         => $friendId,
        'name'       => h($f['full_name']),
        'photo'      => h($f['photo']),
        'lastMessage'=> $last ? ($last['body'] !== null ? h($last['body']) : '📷 Image') : null,
        'lastFromMe' => $last ? ((int) $last['sender_id'] === (int) $myId) : null,
        'lastAt'     => $last ? $last['created_at'] : null,
        'unread'     => $unread,
    ];
}

echo json_encode(['success' => true, 'conversations' => $conversations]);
