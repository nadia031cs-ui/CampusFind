<?php
// api/messages_thread.php?with=<user_id>
require_once __DIR__ . '/../includes/friends.php';
header('Content-Type: application/json');
requireLogin(true);

$myId  = $_SESSION['user_id'];
$other = (int) ($_GET['with'] ?? 0);

if ($other <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid conversation.']);
    exit;
}
if (!areFriends($myId, $other)) {
    echo json_encode(['success' => false, 'message' => 'You can only message your friends.']);
    exit;
}

// Mark their messages to me as read
$pdo->prepare('UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0')
    ->execute([$other, $myId]);

$stmt = $pdo->prepare(
    'SELECT id, sender_id, body, image_path, created_at FROM messages
     WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
     ORDER BY created_at ASC'
);
$stmt->execute([$myId, $other, $other, $myId]);
$rows = $stmt->fetchAll();

$messages = array_map(function ($r) use ($myId) {
    return [
        'id'        => (int) $r['id'],
        'fromMe'    => (int) $r['sender_id'] === (int) $myId,
        'body'      => $r['body'] !== null ? h($r['body']) : null,
        'image'     => $r['image_path'] ? h($r['image_path']) : null,
        'createdAt' => $r['created_at'],
    ];
}, $rows);

$userStmt = $pdo->prepare('SELECT full_name, photo FROM users WHERE id = ?');
$userStmt->execute([$other]);
$user = $userStmt->fetch();

echo json_encode([
    'success'  => true,
    'with'     => ['id' => $other, 'name' => h($user['full_name']), 'photo' => h($user['photo'])],
    'messages' => $messages,
]);
