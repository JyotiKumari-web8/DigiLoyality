<?php
$pageTitle = 'Business Sign In — DigiLoyalty';
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 sm:p-10 border border-slate-100">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/25">
                <i class="fas fa-id-card text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Business Sign In</h1>
            <p class="text-slate-500 text-xs mt-1">Manage your loyalty program, cards & retention CRM</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-rose-500"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1.5">Owner Email</label>
                <input type="email" name="email" required placeholder="owner@store.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600">Password</label>
                </div>
                <input type="password" name="password" required placeholder="Enter your password"
                       class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition">
            </div>

            <button type="submit" class="w-full btn btn-primary py-3.5 text-base shadow-lg shadow-indigo-500/20">
                Sign In to Dashboard <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between text-xs">
            <a href="<?= Helper::url('staff/login') ?>" class="text-indigo-600 font-bold hover:underline">
                <i class="fas fa-cash-register mr-1"></i> Staff Counter Login
            </a>
            <a href="<?= Helper::url('register') ?>" class="text-slate-500 hover:text-slate-900 font-semibold">
                Create Business
            </a>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
