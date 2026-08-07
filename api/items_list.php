<?php
// api/items_list.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$keyword = trim($_GET['q'] ?? '');

$sql = 'SELECT items.id, items.description, items.location, items.item_date, items.item_type,
               items.image_path, items.likes_count, items.created_at,
               users.id AS user_id, users.full_name AS username, users.photo AS profile_image
        FROM items
        JOIN users ON users.id = items.user_id';
$params = [];

if ($keyword !== '') {
    $sql .= ' WHERE items.description LIKE ? OR items.location LIKE ? OR items.item_type LIKE ?';
    $like = "%{$keyword}%";
    $params = [$like, $like, $like];
}

$sql .= ' ORDER BY items.created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$likedStmt = $pdo->prepare('SELECT item_id FROM item_likes WHERE user_id = ?');
$likedStmt->execute([$_SESSION['user_id']]);
$likedIds = array_column($likedStmt->fetchAll(), 'item_id');

$items = array_map(function ($row) use ($likedIds) {
    return [
        'id'          => (int) $row['id'],
        'username'    => h($row['username']),
        'profileImage'=> h($row['profile_image']),
        'ownerId'     => (int) $row['user_id'],
        'description' => h($row['description']),
        'location'    => h($row['location']),
        'date'        => $row['item_date'],
        'itemType'    => h($row['item_type']),
        'image'       => $row['image_path'] ? h($row['image_path']) : null,
        'likes'       => (int) $row['likes_count'],
        'likedByMe'   => in_array((int) $row['id'], $likedIds, true),
        'createdAt'   => $row['created_at'],
    ];
}, $rows);

echo json_encode(['success' => true, 'items' => $items]);
