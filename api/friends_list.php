<?php
// api/friends_list.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$myId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'SELECT u.id, u.full_name, u.student_id, u.department, u.photo, f.created_at
     FROM friends f
     JOIN users u ON u.id = IF(f.user_low = ?, f.user_high, f.user_low)
     WHERE f.user_low = ? OR f.user_high = ?
     ORDER BY u.full_name ASC'
);
$stmt->execute([$myId, $myId, $myId]);
$rows = $stmt->fetchAll();

$friends = array_map(function ($r) {
    return [
        'id'         => (int) $r['id'],
        'name'       => h($r['full_name']),
        'studentId'  => h($r['student_id'] ?? ''),
        'department' => h($r['department'] ?? ''),
        'photo'      => h($r['photo']),
        'connectedAt'=> $r['created_at'],
    ];
}, $rows);

echo json_encode(['success' => true, 'friends' => $friends]);
