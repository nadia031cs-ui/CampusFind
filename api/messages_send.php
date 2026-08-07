<?php
// api/messages_send.php
require_once __DIR__ . '/../includes/friends.php';
require_once __DIR__ . '/../includes/notifications.php';
header('Content-Type: application/json');
requireLogin(true);

$myId       = $_SESSION['user_id'];
$receiverId = (int) ($_POST['receiver_id'] ?? 0);
$body       = trim($_POST['body'] ?? '');

if ($receiverId <= 0 || $receiverId === (int) $myId) {
    echo json_encode(['success' => false, 'message' => 'Invalid recipient.']);
    exit;
}
if (!areFriends($myId, $receiverId)) {
    echo json_encode(['success' => false, 'message' => 'You can only message your friends.']);
    exit;
}

$imagePath = null;
if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpPath = $_FILES['image']['tmp_name'];
    $info = @getimagesize($tmpPath);
    if ($info === false) {
        echo json_encode(['success' => false, 'message' => "Couldn't process that image."]);
        exit;
    }
    [$origW, $origH, $type] = $info;
    $maxWidth = 800;
    $newW = $origW > $maxWidth ? $maxWidth : $origW;
    $newH = $origW > $maxWidth ? (int) round($origH * ($maxWidth / $origW)) : $origH;

    switch ($type) {
        case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($tmpPath); break;
        case IMAGETYPE_PNG:  $src = imagecreatefrompng($tmpPath);  break;
        case IMAGETYPE_GIF:  $src = imagecreatefromgif($tmpPath);  break;
        case IMAGETYPE_WEBP: $src = imagecreatefromwebp($tmpPath); break;
        default:
            echo json_encode(['success' => false, 'message' => 'Unsupported image type.']);
            exit;
    }

    $dst = imagecreatetruecolor($newW, $newH);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

    $filename = 'msg_' . time() . '_' . bin2hex(random_bytes(4)) . '.jpg';
    $destPath = __DIR__ . '/../uploads/messages/' . $filename;
    if (!is_dir(__DIR__ . '/../uploads/messages')) {
        mkdir(__DIR__ . '/../uploads/messages', 0755, true);
    }
    imagejpeg($dst, $destPath, 70);
    imagedestroy($src);
    imagedestroy($dst);

    $imagePath = 'uploads/messages/' . $filename;
}

if ($body === '' && $imagePath === null) {
    echo json_encode(['success' => false, 'message' => 'Please write a message or attach an image.']);
    exit;
}

$stmt = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, body, image_path) VALUES (?, ?, ?, ?)');
$stmt->execute([$myId, $receiverId, $body !== '' ? $body : null, $imagePath]);

$preview = $body !== '' ? $body : 'sent you an image.';
createNotification($receiverId, 'message', $_SESSION['username'] . ' sent you a message: "' . mb_strimwidth($preview, 0, 60, '...') . '"', 'Messages.php?with=' . $myId);

echo json_encode([
    'success' => true,
    'message' => [
        'id'        => (int) $pdo->lastInsertId(),
        'senderId'  => (int) $myId,
        'body'      => $body !== '' ? h($body) : null,
        'image'     => $imagePath,
        'createdAt' => date('c'),
    ],
]);
