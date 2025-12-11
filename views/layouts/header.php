<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Online Course'; ?></title>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom style -->
<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/script.js" defer></script>

</head>
<body>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="main-content">
