<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>E-Library</title>

    <!-- Set theme before stylesheets to avoid flash -->
    <script src="/library_project/assets/js/theme-init.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="/library_project/assets/css/style.css">
    <link rel="stylesheet" href="/library_project/assets/css/dark-mode.css">

</head>
<body>