<?php
// api/register.php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$age      = trim($_POST['age'] ?? '');
$gender   = trim($_POST['gender'] ?? 'Others');
$phone    = trim($_POST['phone'] ?? '');
$dob      = trim($_POST['dob'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// server-side validation mirrors the client-side checks already in signup.php
if ($fullName === '' || $age === '' || $phone === '' || $dob === '' || $email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}
if (!is_numeric($age) || (int)$age < 16) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid age.']);
    exit;
}
if (!preg_match('/^[0-9]{11}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Phone number must contain exactly 11 digits.']);
    exit;
}
if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least 6 characters.']);
    exit;
}
if (!in_array($gender, ['Female', 'Male', 'Others'], true)) {
    $gender = 'Others';
}

$studentId  = trim($_POST['student_id'] ?? '') ?: null;
$department = trim($_POST['department'] ?? '') ?: null;
$semester   = trim($_POST['semester'] ?? '') ?: null;
$batch      = trim($_POST['batch'] ?? '') ?: null;

try {
    $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute([$email]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO users (full_name, age, gender, phone, dob, email, password, student_id, department, semester, batch, photo)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$fullName, $age, $gender, $phone, $dob, $email, $hashed, $studentId, $department, $semester, $batch, 'default-profile.png']);

    echo json_encode(['success' => true, 'message' => 'Registration Successful!']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
}
