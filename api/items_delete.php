<?php
// api/items_delete.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$itemId = (int) ($_POST['id'] ?? 0);
if ($itemId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid post.']);
    exit;
}

$stmt = $pdo->prepare('SELECT user_id, image_path FROM items WHERE id = ?');
$stmt->execute([$itemId]);
$item = $stmt->fetch();

if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Post not found.']);
    exit;
}
if ((int) $item['user_id'] !== (int) $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'You can only delete your own posts.']);
    exit;
}

$pdo->prepare('DELETE FROM items WHERE id = ?')->execute([$itemId]);

if ($item['image_path']) {
    $fullPath = __DIR__ . '/../' . $item['image_path'];
    if (is_file($fullPath)) @unlink($fullPath);
}

echo json_encode(['success' => true, 'message' => 'Post deleted successfully.']);
