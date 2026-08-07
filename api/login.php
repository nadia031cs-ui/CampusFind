<?php
// api/login.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']) && $_POST['remember'] === 'true';

if ($email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Please enter your email and password.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}
if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least 6 characters.']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, full_name, password, photo FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'No account found. Please Sign Up first.']);
    exit;
}
if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Incorrect Password.']);
    exit;
}

// Regenerate session id on login to prevent session fixation
session_regenerate_id(true);
$_SESSION['user_id']  = $user['id'];
$_SESSION['username'] = $user['full_name'];

if ($remember) {
    // 30-day persistent cookie carrying only the session id, not credentials
    setcookie(session_name(), session_id(), time() + 60 * 60 * 24 * 30, '/');
}

echo json_encode([
    'success'  => true,
    'message'  => 'Login Successful!',
    'username' => $user['full_name'],
    'photo'    => $user['photo'],
]);
