<?php
// includes/friends.php
require_once __DIR__ . '/auth.php';

/** True if the two users are friends */
function areFriends($userA, $userB) {
    global $pdo;
    $lo = min($userA, $userB);
    $hi = max($userA, $userB);
    $stmt = $pdo->prepare('SELECT id FROM friends WHERE user_low = ? AND user_high = ?');
    $stmt->execute([$lo, $hi]);
    return (bool) $stmt->fetch();
}

/** Insert a friendship (order-independent, safe to call once request is accepted) */
function addFriendship($userA, $userB) {
    global $pdo;
    $lo = min($userA, $userB);
    $hi = max($userA, $userB);
    $stmt = $pdo->prepare('INSERT IGNORE INTO friends (user_low, user_high) VALUES (?, ?)');
    $stmt->execute([$lo, $hi]);
}

/** Remove a friendship in either direction */
function removeFriendship($userA, $userB) {
    global $pdo;
    $lo = min($userA, $userB);
    $hi = max($userA, $userB);
    $pdo->prepare('DELETE FROM friends WHERE user_low = ? AND user_high = ?')->execute([$lo, $hi]);
}
