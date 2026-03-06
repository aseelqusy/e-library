<?php
session_start();
header('Content-Type: application/json');

require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in first'
    ]);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$borrowing_id = isset($data['borrowing_id']) ? intval($data['borrowing_id']) : 0;
$user_id = $_SESSION['user_id'];

if ($borrowing_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid borrowing ID'
    ]);
    exit();
}

// Verify this borrowing belongs to the user
$check_query = "SELECT id FROM borrowings WHERE id = ? AND user_id = ? AND status = 'borrowed'";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $borrowing_id, $user_id);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Borrowing record not found'
    ]);
    exit();
}

// Update borrowing status
$update_query = "UPDATE borrowings SET status = 'returned' WHERE id = ?";
$update_stmt = mysqli_prepare($conn, $update_query);
mysqli_stmt_bind_param($update_stmt, "i", $borrowing_id);

if (mysqli_stmt_execute($update_stmt)) {
    echo json_encode([
        'success' => true,
        'message' => 'Book returned successfully!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to return book'
    ]);
}

mysqli_close($conn);
?>

