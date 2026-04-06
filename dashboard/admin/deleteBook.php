<?php
$qs = $_SERVER['QUERY_STRING'] ?? '';
$target = '/library_project/dashboard/admin/books.php' . ($qs !== '' ? ('?' . $qs) : '');
header('Location: ' . $target);
exit();
