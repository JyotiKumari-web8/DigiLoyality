<?php
$pageTitle = 'Reward Catalog — ' . $business['name'];
require APP_ROOT . '/views/layout/header.php';
$currency = $business['currency_symbol'] ?? '₹';
?>

<div class="min-h-screen bg-slate-50 pb-12">
    <header class="bg-white border-b border-slate-200 px-4 py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div>
                <a href="<?= Helper::url('owner/dashboard') ?>" class="text-xs text-indigo-600 font-semibold hover:underline flex items-center gap-1 mb-1">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <h1 class="text-xl font-extrabold text-slate-900">Reward Catalog Manager</h1>
                <p class="text-xs text-slate-500">Configure free items and discount perks customers can unlock using their reward coins</p>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8 space-y-8">
        <!-- Add Reward Card -->
        <div class="card p-6 border-slate-200">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-plus-circle text-pink-500"></i> Add New Redeemable Reward
            </h3>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Reward Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Free Dessert or ₹100 Discount"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Coin Cost *</label>
                        <input type="number" name="cost" required value="50" min="5"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Short Description</label>
                        <input type="text" name="description" placeholder="e.g. Applicable on orders above ₹300"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Retail Value (Optional)</label>
                        <input type="number" step="0.5" name="discount_value" placeholder="0.00"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-gift mr-1"></i> Add to Catalog
                    </button>
                </div>
            </form>
        </div>

        <!-- Rewards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php if (!empty($rewardsList)): ?>
                <?php foreach ($rewardsList as $rw): ?>
                    <div class="card p-5 border-slate-200 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700">
                                    <?= (int)$rw['cost'] ?> Coins
                                </span>
                                <form method="POST" onsubmit="return confirm('Delete this reward?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="reward_id" value="<?= $rw['id'] ?>">
                                    <button type="submit" class="text-xs text-slate-400 hover:text-rose-500 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <h4 class="text-base font-bold text-slate-900"><?= htmlspecialchars($rw['title']) ?></h4>
                            <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars($rw['description']) ?></p>
                        </div>
                        <?php if ($rw['discount_value'] > 0): ?>
                            <div class="mt-4 pt-2 border-t border-slate-100 text-[11px] text-slate-400">
                                Value: <?= Helper::formatCurrency((float)$rw['discount_value'], $currency) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sm:col-span-2 text-center py-8 text-slate-400">
                    No custom coin rewards in your catalog yet.
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
