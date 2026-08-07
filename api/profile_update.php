<?php
// api/profile_update.php — shared by Edit Profile.php (full form + photo) and Settings.php (subset of fields)
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
requireLogin(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$myId = $_SESSION['user_id'];

// Only touch fields that were actually submitted, so Settings.php (which
// posts a subset) doesn't wipe out fields Edit Profile.php owns, or vice versa.
$fields = [];
$params = [];

if (isset($_POST['full_name']) && trim($_POST['full_name']) !== '') {
    $fields[] = 'full_name = ?';
    $params[] = trim($_POST['full_name']);
}
if (isset($_POST['student_id'])) {
    $fields[] = 'student_id = ?';
    $params[] = trim($_POST['student_id']) ?: null;
}
if (isset($_POST['department'])) {
    $fields[] = 'department = ?';
    $params[] = trim($_POST['department']) ?: null;
}
if (isset($_POST['semester'])) {
    $fields[] = 'semester = ?';
    $params[] = trim($_POST['semester']) ?: null;
}
if (isset($_POST['batch'])) {
    $fields[] = 'batch = ?';
    $params[] = trim($_POST['batch']) ?: null;
}
if (isset($_POST['phone']) && trim($_POST['phone']) !== '') {
    if (!preg_match('/^[0-9]{11}$/', trim($_POST['phone']))) {
        echo json_encode(['success' => false, 'message' => 'Phone number must contain exactly 11 digits.']);
        exit;
    }
    $fields[] = 'phone = ?';
    $params[] = trim($_POST['phone']);
}
if (isset($_POST['email']) && trim($_POST['email']) !== '') {
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }
    $check = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
    $check->execute([$email, $myId]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'That email is already in use.']);
        exit;
    }
    $fields[] = 'email = ?';
    $params[] = $email;
}

// Optional profile photo upload
if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $tmpPath = $_FILES['photo']['tmp_name'];
    $info = @getimagesize($tmpPath);
    if ($info === false) {
        echo json_encode(['success' => false, 'message' => "Couldn't process that image."]);
        exit;
    }
    [$origW, $origH, $type] = $info;
    $size = 400; // square profile photo
    switch ($type) {
        case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($tmpPath); break;
        case IMAGETYPE_PNG:  $src = imagecreatefrompng($tmpPath);  break;
        case IMAGETYPE_GIF:  $src = imagecreatefromgif($tmpPath);  break;
        case IMAGETYPE_WEBP: $src = imagecreatefromwebp($tmpPath); break;
        default:
            echo json_encode(['success' => false, 'message' => 'Unsupported image type.']);
            exit;
    }

    // center-crop to square, then resize
    $cropSize = min($origW, $origH);
    $srcX = (int) (($origW - $cropSize) / 2);
    $srcY = (int) (($origH - $cropSize) / 2);

    $dst = imagecreatetruecolor($size, $size);
    imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $size, $size, $cropSize, $cropSize);

    $filename = 'profile_' . $myId . '_' . time() . '.jpg';
    if (!is_dir(__DIR__ . '/../uploads/profiles')) {
        mkdir(__DIR__ . '/../uploads/profiles', 0755, true);
    }
    imagejpeg($dst, __DIR__ . '/../uploads/profiles/' . $filename, 80);
    imagedestroy($src);
    imagedestroy($dst);

    $fields[] = 'photo = ?';
    $params[] = 'uploads/profiles/' . $filename;
}

if (empty($fields)) {
    echo json_encode(['success' => false, 'message' => 'Nothing to update.']);
    exit;
}

$params[] = $myId;
$stmt = $pdo->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?');
$stmt->execute($params);

echo json_encode(['success' => true, 'message' => 'Profile updated successfully!', 'user' => currentUser()]);
