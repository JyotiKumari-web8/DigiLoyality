<?php
$pageTitle = 'Staff Counter Sign In — DigiLoyalty';
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-slate-900 flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md p-8 border border-slate-700">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-500/20 text-indigo-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-indigo-500/30">
                <i class="fas fa-cash-register text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Staff Counter Login</h1>
            <p class="text-slate-400 text-xs mt-1">Sign in to scan customer cards & record visits</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs rounded-2xl flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-rose-400"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="text-xs font-bold uppercase tracking-wider text-slate-300 block mb-1.5">Staff Email</label>
                <input type="email" name="email" required placeholder="staff@store.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-2xl text-sm text-white font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition">
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-wider text-slate-300 block mb-1.5">4-Digit Security PIN</label>
                <input type="password" name="pin" required maxlength="6" placeholder="••••"
                       class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-2xl text-center text-lg font-mono font-bold tracking-widest text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
            </div>

            <button type="submit" class="w-full btn btn-primary py-3.5 text-base shadow-lg shadow-indigo-500/20">
                Open Terminal <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-700 text-center">
            <a href="<?= Helper::url('login') ?>" class="text-xs text-slate-400 hover:text-white font-semibold transition">
                <i class="fas fa-user-shield mr-1"></i> Owner Sign In
            </a>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
