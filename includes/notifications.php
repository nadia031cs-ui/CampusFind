<?php
// includes/notifications.php
require_once __DIR__ . '/auth.php';

function createNotification($recipientId, $type, $text, $link = null) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO notifications (recipient_id, type, text, link) VALUES (?, ?, ?, ?)');
    $stmt->execute([$recipientId, $type, $text, $link]);
}

function unreadNotificationCount($userId) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM notifications WHERE recipient_id = ? AND is_read = 0');
    $stmt->execute([$userId]);
    return (int) $stmt->fetch()['c'];
}
