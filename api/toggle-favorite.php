<?php
session_start();
header('Content-Type: application/json');

require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in first',
        'redirect' => '/library_project/auth/login.php'
    ]);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$book_id = isset($data['book_id']) ? intval($data['book_id']) : 0;
$user_id = $_SESSION['user_id'];

if ($book_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid book ID'
    ]);
    exit();
}

// Check if already favorited
$check_query = "SELECT id FROM favorites WHERE user_id = ? AND book_id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $book_id);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($result) > 0) {
    // Remove from favorites
    $delete_query = "DELETE FROM favorites WHERE user_id = ? AND book_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $book_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        echo json_encode([
            'success' => true,
            'action' => 'removed',
            'message' => 'Removed from favorites'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to remove from favorites'
        ]);
    }
} else {
    // Add to favorites
    $insert_query = "INSERT INTO favorites (user_id, book_id) VALUES (?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "ii", $user_id, $book_id);

    if (mysqli_stmt_execute($insert_stmt)) {
        echo json_encode([
            'success' => true,
            'action' => 'added',
            'message' => 'Added to favorites'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add to favorites'
        ]);
    }
}

mysqli_close($conn);
?>

