<?php
// api/friends_search.php — search real users (replaces the old hardcoded FindFriends.js list)
require_once __DIR__ . '/../includes/friends.php';
header('Content-Type: application/json');
requireLogin(true);

$myId       = $_SESSION['user_id'];
$keyword    = trim($_GET['q'] ?? '');
$email      = trim($_GET['email'] ?? '');
$department = trim($_GET['department'] ?? '');
$studentId  = trim($_GET['id'] ?? '');
$name       = trim($_GET['name'] ?? '');

$sql = 'SELECT id, full_name, student_id, email, department, photo FROM users WHERE id != ?';
$params = [$myId];

if ($keyword !== '') {
    $sql .= ' AND (full_name LIKE ? OR student_id LIKE ? OR email LIKE ? OR department LIKE ?)';
    $like = "%{$keyword}%";
    array_push($params, $like, $like, $like, $like);
}
if ($email !== '')      { $sql .= ' AND email LIKE ?';      $params[] = "%{$email}%"; }
if ($department !== '') { $sql .= ' AND department = ?';    $params[] = $department; }
if ($studentId !== '')  { $sql .= ' AND student_id LIKE ?'; $params[] = "%{$studentId}%"; }
if ($name !== '')       { $sql .= ' AND full_name LIKE ?';  $params[] = "%{$name}%"; }

$sql .= ' ORDER BY full_name ASC LIMIT 50';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// Existing friends and pending sent requests, so the UI can grey those out
$friendStmt = $pdo->prepare('SELECT user_low, user_high FROM friends WHERE user_low = ? OR user_high = ?');
$friendStmt->execute([$myId, $myId]);
$friendIds = [];
foreach ($friendStmt->fetchAll() as $f) {
    $friendIds[] = (int) $f['user_low'] === (int) $myId ? (int) $f['user_high'] : (int) $f['user_low'];
}

$sentStmt = $pdo->prepare("SELECT receiver_id FROM friend_requests WHERE sender_id = ? AND status = 'pending'");
$sentStmt->execute([$myId]);
$sentIds = array_column($sentStmt->fetchAll(), 'receiver_id');

$users = array_values(array_filter(array_map(function ($row) use ($friendIds, $sentIds) {
    $id = (int) $row['id'];
    if (in_array($id, $friendIds, true)) return null; // already a friend, don't show in search
    return [
        'id'         => $id,
        'name'       => h($row['full_name']),
        'studentId'  => h($row['student_id'] ?? ''),
        'email'      => h($row['email']),
        'department' => h($row['department'] ?? ''),
        'photo'      => h($row['photo']),
        'requestSent'=> in_array($id, $sentIds, true),
    ];
}, $rows)));

echo json_encode(['success' => true, 'users' => array_values($users)]);
