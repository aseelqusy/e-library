<?php
$bookId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$target = '/library_project/dashboard/user/book-details.php';
if ($bookId > 0) {
    $target .= '?id=' . $bookId;
}
header('Location: ' . $target);
exit();

