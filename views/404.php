<?php
$pageTitle = '404 — Not Found';
require APP_ROOT . '/views/layout/header.php';
?>
<div class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl font-black">
            404
        </div>
        <h1 class="text-2xl font-black text-slate-900">Card or Page Not Found</h1>
        <p class="text-xs text-slate-500 mt-2 mb-6">
            The loyalty card slug or URL you are looking for does not exist or may have expired.
        </p>
        <a href="<?= Helper::url('') ?>" class="btn btn-primary btn-sm">
            &larr; Return to Homepage
        </a>
    </div>
</div>
<?php require APP_ROOT . '/views/layout/footer.php'; ?>
