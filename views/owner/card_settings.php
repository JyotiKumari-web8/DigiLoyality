<?php
$pageTitle = 'Card Builder & Loyalty Rules — ' . $business['name'];
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-slate-50 pb-12">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 px-4 py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div>
                <a href="<?= Helper::url('owner/dashboard') ?>" class="text-xs text-indigo-600 font-semibold hover:underline flex items-center gap-1 mb-1">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <h1 class="text-xl font-extrabold text-slate-900">Loyalty Card Visual Builder</h1>
                <p class="text-xs text-slate-500">Customize card rules, stamp targets, reward coins, and brand styling</p>
            </div>
            <a href="<?= Helper::url('business.php?slug=' . urlencode($business['slug'])) ?>" target="_blank" class="btn btn-sm btn-secondary">
                <i class="fas fa-eye mr-1"></i> Preview Live Card
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <form method="POST" class="space-y-6">
            
            <!-- 1. Brand & Business Info -->
            <div class="card p-6 border-slate-200 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-store text-indigo-500"></i> Store Identity & Aesthetics
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($business['name']) ?>" required
                               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Card Tagline / Hook</label>
                        <input type="text" name="tagline" value="<?= htmlspecialchars($business['tagline']) ?>"
                               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Primary Color</label>
                        <input type="color" name="theme_color" value="<?= htmlspecialchars($business['theme_color'] ?? '#6366f1') ?>"
                               class="w-full h-11 p-1 bg-white border border-slate-200 rounded-xl cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Secondary Accent</label>
                        <input type="color" name="secondary_color" value="<?= htmlspecialchars($business['secondary_color'] ?? '#ec4899') ?>"
                               class="w-full h-11 p-1 bg-white border border-slate-200 rounded-xl cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="<?= htmlspecialchars($business['currency_symbol'] ?? '₹') ?>"
                               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-center outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Shop Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($business['address'] ?? '') ?>" placeholder="e.g. Shop 12, Main Market"
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500">
                </div>
            </div>

            <!-- 2. Stamp Card Rules -->
            <div class="card p-6 border-slate-200 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-stamp text-amber-500"></i> Digital Stamp Card Rules
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Target Visits / Stamps (3 to 20)
                        </label>
                        <input type="number" name="stamp_target" min="3" max="20" value="<?= (int)$business['stamp_target'] ?>" required
                               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-indigo-500">
                        <p class="text-[11px] text-slate-400 mt-1">Number of visit stamps customer needs to earn the reward.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Milestone Reward Title
                        </label>
                        <input type="text" name="stamp_reward_title" value="<?= htmlspecialchars($business['stamp_reward_title']) ?>" required
                               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-indigo-500"
                               placeholder="e.g. Free Coffee, 50% Off Haircut">
                        <p class="text-[11px] text-slate-400 mt-1">What the customer receives upon filling the stamp card.</p>
                    </div>
                </div>
            </div>

            <!-- 3. Reward Coins / Spend-based Loyalty -->
            <div class="card p-6 border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                        <i class="fas fa-coins text-emerald-500"></i> Reward Coins & Spend Cashback
                    </h3>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                        <input type="checkbox" name="coin_enabled" value="1" <?= $business['coin_enabled'] ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600 rounded">
                        Enable Reward Coins
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Coin Earn Ratio (Spend <?= htmlspecialchars($business['currency_symbol'] ?? '₹') ?>X for 1 Coin)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold"><?= htmlspecialchars($business['currency_symbol'] ?? '₹') ?></span>
                            <input type="number" step="0.5" name="coin_rate" value="<?= (float)$business['coin_rate'] ?>" required
                                   class="w-full pl-8 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-indigo-500">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">e.g. 10.00 means customer earns 1 coin for every ₹10 spent.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Coin Redemption Value (1 Coin = <?= htmlspecialchars($business['currency_symbol'] ?? '₹') ?>X)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold"><?= htmlspecialchars($business['currency_symbol'] ?? '₹') ?></span>
                            <input type="number" step="0.05" name="coin_redeem_value" value="<?= (float)$business['coin_redeem_value'] ?>" required
                                   class="w-full pl-8 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-indigo-500">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">e.g. 0.50 means 100 coins give ₹50 discount.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Welcome Bonus Coins on Joining
                        </label>
                        <input type="number" name="welcome_bonus_coins" value="<?= (int)$business['welcome_bonus_coins'] ?>"
                               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-indigo-500">
                        <p class="text-[11px] text-slate-400 mt-1">Given to new members immediately on registration to incentivize first visit.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Minimum Coins Required to Redeem
                        </label>
                        <input type="number" name="min_redeem_coins" value="<?= (int)$business['min_redeem_coins'] ?>"
                               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-indigo-500">
                        <p class="text-[11px] text-slate-400 mt-1">Prevents micro-redemptions (e.g. 50 coins).</p>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end gap-3 pt-2">
                <a href="<?= Helper::url('owner/dashboard') ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                    <i class="fas fa-save mr-1.5"></i> Save & Apply Changes
                </button>
            </div>
        </form>
    </main>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
