<?php
$qs = $_SERVER['QUERY_STRING'] ?? '';
$target = '/library_project/dashboard/admin/edit-book.php' . ($qs !== '' ? ('?' . $qs) : '');
header('Location: ' . $target);
exit();
