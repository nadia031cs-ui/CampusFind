<?php
// api/items_like.php
require_once __DIR__ . '/../includes/notifications.php';
header('Content-Type: application/json');
requireLogin(true);

$itemId = (int) ($_POST['id'] ?? 0);
if ($itemId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid post.']);
    exit;
}

$item = $pdo->prepare('SELECT id, user_id FROM items WHERE id = ?');
$item->execute([$itemId]);
$item = $item->fetch();
if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Post not found.']);
    exit;
}

try {
    $pdo->beginTransaction();

    $insert = $pdo->prepare('INSERT INTO item_likes (item_id, user_id) VALUES (?, ?)');
    $insert->execute([$itemId, $_SESSION['user_id']]);

    $pdo->prepare('UPDATE items SET likes_count = likes_count + 1 WHERE id = ?')->execute([$itemId]);

    $pdo->commit();

    if ((int) $item['user_id'] !== (int) $_SESSION['user_id']) {
        createNotification($item['user_id'], 'like', $_SESSION['username'] . ' liked your post.', 'Home_Feed.php');
    }

    $count = $pdo->prepare('SELECT likes_count FROM items WHERE id = ?');
    $count->execute([$itemId]);
    echo json_encode(['success' => true, 'likes' => (int) $count->fetch()['likes_count']]);
} catch (PDOException $e) {
    $pdo->rollBack();
    // Unique constraint violation = already liked
    echo json_encode(['success' => false, 'message' => 'You already liked this post.']);
}
