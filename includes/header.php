<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$titulo = $titulo ?? 'Fitlife Studio';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo) ?> | Fitlife Studio</title>
    <link rel="icon" type="image/jpeg" href="/fitlife/assets/logo-fitlife.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/fitlife/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main class="container py-4">
