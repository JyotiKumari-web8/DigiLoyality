<?php
$pageTitle = $business['name'] . ' — Digital Loyalty Card';
require APP_ROOT . '/views/layout/header.php';
$themeColor = $business['theme_color'] ?? '#6366f1';
?>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-slate-50 to-purple-50 flex flex-col justify-between py-6 px-4">
    <div class="max-w-md w-full mx-auto">
        
        <!-- Header Branding -->
        <header class="text-center mb-6">
            <div class="w-20 h-20 rounded-3xl mx-auto mb-4 flex items-center justify-center text-3xl font-extrabold text-white shadow-xl shadow-indigo-500/20"
                 style="background: linear-gradient(135deg, <?= htmlspecialchars($themeColor) ?>, <?= htmlspecialchars($business['secondary_color'] ?? '#ec4899') ?>);">
                <?= strtoupper(substr($business['name'], 0, 1)) ?>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight"><?= htmlspecialchars($business['name']) ?></h1>
            <p class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($business['tagline']) ?></p>

            <!-- Reward Pitch Badge -->
            <div class="inline-flex items-center gap-2 mt-4 px-4 py-1.5 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-700 text-xs font-bold shadow-sm">
                <i class="fas fa-gift text-pink-500"></i>
                Reward: <?= htmlspecialchars($business['stamp_reward_title']) ?>
            </div>
        </header>

        <!-- Join Form Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-100 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Get Your Loyalty Pass</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Collect visit stamps & earn reward coins</p>
                </div>
                <span class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fas fa-coins"></i>
                </span>
            </div>

            <!-- Bonus Coins Highlight -->
            <?php if (!empty($business['welcome_bonus_coins'])): ?>
                <div class="mb-5 p-3.5 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/60 rounded-2xl flex items-center gap-3">
                    <span class="text-2xl">🎉</span>
                    <div class="text-xs">
                        <strong class="text-amber-900 font-bold block">Instant Sign-Up Perk:</strong>
                        <span class="text-amber-700">Join now and receive <b class="font-bold text-amber-900"><?= (int)$business['welcome_bonus_coins'] ?> Free Reward Coins</b> immediately!</span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= Helper::url('business.php?slug=' . urlencode($business['slug'])) ?>" class="space-y-4">
                <input type="hidden" name="action" value="join">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Mobile Number <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-slate-500 font-semibold text-sm">+91</span>
                        </div>
                        <input type="tel" name="mobile" required inputmode="numeric" maxlength="10"
                               class="w-full pl-14 pr-4 py-3.5 border border-slate-200 rounded-2xl text-base font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                               placeholder="Enter 10-digit mobile" autocomplete="tel" autofocus>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                        <i class="fas fa-lock text-[10px]"></i> Used as your digital card ID. No SMS or password needed.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Your Full Name
                    </label>
                    <input type="text" name="name"
                           class="w-full px-4 py-3.5 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="Enter your name">
                </div>

                <button type="submit"
                        class="w-full py-4 text-white font-bold rounded-2xl shadow-lg transition-all transform active:scale-95 flex items-center justify-center gap-2 text-base"
                        style="background: linear-gradient(135deg, <?= htmlspecialchars($themeColor) ?>, <?= htmlspecialchars($business['secondary_color'] ?? '#ec4899') ?>); box-shadow: 0 10px 25px -5px <?= htmlspecialchars($themeColor) ?>66;">
                    <span>Open My Loyalty Card</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-xs text-slate-400">
                    Already a member? Just enter the same mobile number to open your card.
                </p>
            </div>
        </div>

        <!-- Staff Link -->
        <div class="text-center mt-6">
            <a href="<?= Helper::url('staff/login') ?>" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition flex items-center justify-center gap-1.5">
                <i class="fas fa-user-tie"></i>
                <span>Staff Counter Login</span>
            </a>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
