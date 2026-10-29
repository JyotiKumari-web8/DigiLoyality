<?php
$pageTitle = 'Reseller Admin Sign In';
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-slate-950 flex items-center justify-center p-4">
    <div class="bg-slate-900 rounded-3xl shadow-2xl w-full max-w-md p-8 border border-slate-800 text-white">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-amber-500/20 text-amber-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-amber-500/30">
                <i class="fas fa-crown text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight">
                <?= $adminCount === 0 ? 'Create Master Admin' : 'Agency Reseller Portal' ?>
            </h1>
            <p class="text-slate-400 text-xs mt-1">
                <?= $adminCount === 0 ? 'Phase 1: Setup your agency account to onboard & sell to businesses' : 'White-label admin panel for managing local merchants' ?>
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs rounded-2xl flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-rose-400"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($adminCount === 0): ?>
            <!-- Master Admin Initial Setup -->
            <form method="POST" class="space-y-4">
                <input type="hidden" name="is_setup" value="1">
                
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Agency / Company Name</label>
                    <input type="text" name="agency_name" required placeholder="e.g. My Digital Agency"
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-amber-500 outline-none transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Admin Name</label>
                        <input type="text" name="name" required placeholder="Your Name"
                               class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-amber-500 outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Phone / WhatsApp</label>
                        <input type="tel" name="phone" placeholder="9876543210"
                               class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-amber-500 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Admin Email</label>
                    <input type="email" name="email" required placeholder="admin@myagency.com"
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-amber-500 outline-none transition">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="Choose a strong password"
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-amber-500 outline-none transition">
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 text-slate-950 font-black rounded-2xl shadow-lg transition hover:brightness-110">
                    Create Master Admin & Launch <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </button>
            </form>
        <?php else: ?>
            <!-- Standard Admin Sign In -->
            <form method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Admin Email</label>
                    <input type="email" name="email" required placeholder="admin@domain.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-amber-500 outline-none transition">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Password</label>
                    <input type="password" name="password" required placeholder="Enter admin password"
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-amber-500 outline-none transition">
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 text-slate-950 font-black rounded-2xl shadow-lg transition hover:brightness-110">
                    Sign In to Reseller Console <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </button>
            </form>
        <?php endif; ?>

        <div class="mt-6 pt-6 border-t border-slate-800 text-center">
            <a href="<?= Helper::url('login') ?>" class="text-xs text-slate-500 hover:text-slate-300">
                &larr; Return to Business Owner Login
            </a>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
