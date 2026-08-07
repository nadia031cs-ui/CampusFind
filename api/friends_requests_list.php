<?php
// api/friends_requests_list.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$stmt = $pdo->prepare(
    "SELECT fr.id, u.id AS user_id, u.full_name, u.student_id, u.department, u.photo, fr.created_at
     FROM friend_requests fr
     JOIN users u ON u.id = fr.sender_id
     WHERE fr.receiver_id = ? AND fr.status = 'pending'
     ORDER BY fr.created_at DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$rows = $stmt->fetchAll();

$requests = array_map(function ($r) {
    return [
        'requestId'  => (int) $r['id'],
        'userId'     => (int) $r['user_id'],
        'name'       => h($r['full_name']),
        'studentId'  => h($r['student_id'] ?? ''),
        'department' => h($r['department'] ?? ''),
        'photo'      => h($r['photo']),
        'createdAt'  => $r['created_at'],
    ];
}, $rows);

echo json_encode(['success' => true, 'requests' => $requests]);
