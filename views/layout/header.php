<?php
if (!defined('APP_ROOT')) exit;
$pageTitle = $pageTitle ?? 'DigiLoyalty Pro — Customer Loyalty & Repeat-Visit Engine';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <!-- Tailwind CDN for utility layouts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom Design System -->
    <link rel="stylesheet" href="<?= Helper::asset('css/app.css') ?>">
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
    <!-- Flash Notifications -->
    <?php if ($msg = Helper::flash('success')): ?>
        <div class="fixed top-4 right-4 z-50 bg-emerald-600 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="text-sm font-semibold"><?= htmlspecialchars($msg) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($msg = Helper::flash('error')): ?>
        <div class="fixed top-4 right-4 z-50 bg-rose-600 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <span class="text-sm font-semibold"><?= htmlspecialchars($msg) ?></span>
        </div>
    <?php endif; ?>
