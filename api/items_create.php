<?php
// api/items_create.php
require_once __DIR__ . '/../includes/notifications.php';
header('Content-Type: application/json');
requireLogin(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$description = trim($_POST['description'] ?? '');
$location    = trim($_POST['location'] ?? '');
$date        = trim($_POST['date'] ?? '');
$itemType    = trim($_POST['itemType'] ?? '');
$validTypes  = ['Electronics', 'Documents', 'Accessories', 'Books and Study Materials', 'Personal Items', 'Others'];

if ($description === '') { echo json_encode(['success' => false, 'message' => 'Please enter a description.']); exit; }
if ($location === '')    { echo json_encode(['success' => false, 'message' => 'Please enter the location.']); exit; }
if ($date === '')        { echo json_encode(['success' => false, 'message' => 'Please select a date.']); exit; }
if ($date > date('Y-m-d')) { echo json_encode(['success' => false, 'message' => "The date can't be in the future."]); exit; }
if (!in_array($itemType, $validTypes, true)) { echo json_encode(['success' => false, 'message' => 'Please select a valid item type.']); exit; }

$imagePath = null;

if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpPath = $_FILES['image']['tmp_name'];
    $info = @getimagesize($tmpPath);
    if ($info === false) {
        echo json_encode(['success' => false, 'message' => "Couldn't process that image. Please try a different photo."]);
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

    $filename = 'item_' . time() . '_' . bin2hex(random_bytes(4)) . '.jpg';
    $destPath = __DIR__ . '/../uploads/items/' . $filename;
    imagejpeg($dst, $destPath, 70); // 70% quality, same as the old client-side canvas compression

    imagedestroy($src);
    imagedestroy($dst);

    $imagePath = 'uploads/items/' . $filename;
}

$stmt = $pdo->prepare(
    'INSERT INTO items (user_id, description, location, item_date, item_type, image_path)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->execute([$_SESSION['user_id'], $description, $location, $date, $itemType, $imagePath]);

createNotification($_SESSION['user_id'], 'post', 'Your post has been posted successfully.', 'Home_Feed.php');

echo json_encode(['success' => true, 'message' => 'Post Created Successfully!']);
