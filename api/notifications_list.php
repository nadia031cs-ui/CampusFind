<?php
// api/notifications_list.php
require_once __DIR__ . '/../includes/notifications.php';
header('Content-Type: application/json');
requireLogin(true);

$stmt = $pdo->prepare('SELECT id, type, text, link, is_read, created_at FROM notifications WHERE recipient_id = ? ORDER BY created_at DESC LIMIT 100');
$stmt->execute([$_SESSION['user_id']]);
$rows = $stmt->fetchAll();

$notifications = array_map(function ($r) {
    return [
        'id'    => (int) $r['id'],
        'type'  => h($r['type']),
        'text'  => h($r['text']),
        'link'  => $r['link'] ? h($r['link']) : null,
        'read'  => (bool) $r['is_read'],
        'time'  => $r['created_at'],
    ];
}, $rows);

echo json_encode([
    'success'      => true,
    'notifications'=> $notifications,
    'unreadCount'  => unreadNotificationCount($_SESSION['user_id']),
]);
