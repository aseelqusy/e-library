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

// Check if book exists and is available
$book_query = "SELECT id, title, type FROM books WHERE id = ?";
$book_stmt = mysqli_prepare($conn, $book_query);
mysqli_stmt_bind_param($book_stmt, "i", $book_id);
mysqli_stmt_execute($book_stmt);
$book_result = mysqli_stmt_get_result($book_stmt);

if (mysqli_num_rows($book_result) == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Book not found'
    ]);
    exit();
}

$book = mysqli_fetch_assoc($book_result);

if ($book['type'] != 'borrow' && $book['type'] != 'both') {
    echo json_encode([
        'success' => false,
        'message' => 'This book is not available for borrowing'
    ]);
    exit();
}

// Check if user already borrowed this book
$check_query = "SELECT id FROM borrowings WHERE user_id = ? AND book_id = ? AND status = 'borrowed'";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $book_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'You have already borrowed this book'
    ]);
    exit();
}

// Calculate return date (14 days from now)
$return_date = date('Y-m-d', strtotime('+14 days'));

// Insert borrowing record
$insert_query = "INSERT INTO borrowings (user_id, book_id, return_date, status) VALUES (?, ?, ?, 'borrowed')";
$insert_stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($insert_stmt, "iis", $user_id, $book_id, $return_date);

if (mysqli_stmt_execute($insert_stmt)) {
    echo json_encode([
        'success' => true,
        'message' => 'Book borrowed successfully! Return by ' . date('M d, Y', strtotime($return_date)),
        'return_date' => $return_date
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to borrow book. Please try again.'
    ]);
}

mysqli_close($conn);
?>

