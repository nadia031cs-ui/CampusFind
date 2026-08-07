<?php
// api/profile_password.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

$current = $_POST['currentPassword'] ?? '';
$new     = $_POST['newPassword'] ?? '';
$confirm = $_POST['confirmPassword'] ?? '';

if ($current === '' || $new === '' || $confirm === '') {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if ($new !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
    exit;
}
if (strlen($new) < 6) {
    echo json_encode(['success' => false, 'message' => 'New password must contain at least 6 characters.']);
    exit;
}

$stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!password_verify($current, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

$hashed = password_hash($new, PASSWORD_DEFAULT);
$pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hashed, $_SESSION['user_id']]);

echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
