<?php
$pageTitle = 'Setup & Install Database — DigiLoyalty';
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 flex items-center justify-center p-4 py-12">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-8 sm:p-10 border border-slate-100">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/25">
                <i class="fas fa-database text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Database & System Configuration</h1>
            <p class="text-slate-500 text-xs mt-1">Connect your local MySQL database to initialize the system cleanly</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-rose-500 text-base mt-0.5"></i>
                <div>
                    <strong class="font-bold block">Connection Notice:</strong>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-2xl flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                <div>
                    <strong class="font-bold block">Success!</strong>
                    <span><?= htmlspecialchars($success) ?> Redirecting in 2 seconds...</span>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">MySQL Host</label>
                    <input type="text" name="db_host" value="<?= htmlspecialchars($dbHost) ?>" required
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-mono font-semibold outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Port</label>
                    <input type="number" name="db_port" value="<?= (int)$dbPort ?>" required
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-mono font-semibold outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Database Name</label>
                    <input type="text" name="db_name" value="<?= htmlspecialchars($dbName) ?>" required
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-mono font-semibold outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Username</label>
                    <input type="text" name="db_user" value="<?= htmlspecialchars($dbUser) ?>" required
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-mono font-semibold outline-none focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">
                    MySQL Password <span class="text-slate-400 font-normal">(Leave blank if no password)</span>
                </label>
                <input type="password" name="db_pass" value="<?= htmlspecialchars($dbPass) ?>" placeholder="Enter your MySQL password"
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-mono outline-none focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full btn btn-primary py-3.5 text-base shadow-xl mt-2 font-bold">
                <i class="fas fa-check-circle mr-1"></i> Connect & Initialize Clean Database
            </button>
        </form>

        <div class="mt-8 p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-600 flex items-center gap-3">
            <i class="fas fa-shield-alt text-indigo-500 text-lg"></i>
            <div>
                <strong class="font-bold text-slate-800 block">Clean Production Installation</strong>
                <span>No fake or dummy data will be created. You will register and manage your own real business.</span>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
