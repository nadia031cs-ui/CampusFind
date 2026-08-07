<?php
// includes/auth.php — session bootstrap, login guard, output-escape helper

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

/** Escape a string for safe HTML output (fixes the XSS risk from the localStorage version) */
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/** True if a user is currently logged in */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/** Call at the top of any page/API that requires login. Redirects HTML pages, JSON-errors APIs. */
function requireLogin($isApi = false) {
    if (!isLoggedIn()) {
        if ($isApi) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not logged in.']);
        } else {
            header('Location: Login.php');
        }
        exit;
    }
}

/** Fetch the current logged-in user's row, or null */
function currentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    $stmt = $pdo->prepare('SELECT id, full_name, age, gender, phone, dob, email, student_id, department, semester, batch, photo, bio, created_at FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
