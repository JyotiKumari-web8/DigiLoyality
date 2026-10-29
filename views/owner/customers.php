<?php
$pageTitle = 'Customer Directory & Win-Back — ' . $business['name'];
require APP_ROOT . '/views/layout/header.php';
$filter = $_GET['filter'] ?? 'all';
$currency = $business['currency_symbol'] ?? '₹';
?>

<div class="min-h-screen bg-slate-50">
    <!-- Subheader -->
    <header class="bg-white border-b border-slate-200 px-4 py-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <a href="<?= Helper::url('owner/dashboard') ?>" class="text-xs text-indigo-600 font-semibold hover:underline flex items-center gap-1 mb-1">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <h1 class="text-xl font-extrabold text-slate-900">Customer CRM & Retention Engine</h1>
                <p class="text-xs text-slate-500">Track visit habits, reward coin balances, and win back slipping regulars</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Filter Tabs -->
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl text-xs font-bold">
                    <a href="<?= Helper::url('owner/customers?filter=all') ?>" 
                       class="px-3 py-1.5 rounded-xl transition <?= $filter === 'all' ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-900' ?>">
                        All Members
                    </a>
                    <a href="<?= Helper::url('owner/customers?filter=at_risk') ?>" 
                       class="px-3 py-1.5 rounded-xl transition <?= $filter === 'at_risk' ? 'bg-rose-500 text-white shadow' : 'text-rose-600 hover:text-rose-700' ?>">
                        <i class="fas fa-exclamation-circle mr-1"></i> At-Risk (30+ Days)
                    </a>
                    <a href="<?= Helper::url('owner/customers?filter=vip') ?>" 
                       class="px-3 py-1.5 rounded-xl transition <?= $filter === 'vip' ? 'bg-amber-400 text-slate-950 shadow' : 'text-amber-700 hover:text-amber-800' ?>">
                        🥇 VIP Regulars
                    </a>
                </div>

                <button onclick="document.getElementById('enrollCustomerModal').classList.remove('hidden')"
                        class="btn btn-sm btn-primary text-xs font-bold shadow-md shadow-indigo-500/20">
                    <i class="fas fa-user-plus mr-1"></i> Enroll New Customer
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <?php if (!empty($_SESSION['flash'])): 
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
        ?>
            <div class="p-4 rounded-2xl text-xs font-bold <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>
        
        <!-- Customer Table Card -->
        <div class="card p-0 overflow-hidden border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100/75 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">Customer</th>
                            <th class="py-3.5 px-4">Tier Status</th>
                            <th class="py-3.5 px-4">Visits / Stamps</th>
                            <th class="py-3.5 px-4">Coin Balance</th>
                            <th class="py-3.5 px-4">Last Visit</th>
                            <th class="py-3.5 px-4 text-right">Win-Back Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($customersList)): ?>
                            <?php foreach ($customersList as $cust): 
                                $daysAgo = Helper::daysSince($cust['last_visit_at']);
                                $isAtRisk = ($daysAgo >= 30);
                                $tier = Helper::calcTier((int)$cust['total_visits'], (float)$cust['total_spend']);
                                
                                // Pre-filled personalized WhatsApp Win-Back Text
                                $shopUrl = Helper::url("business.php?slug={$business['slug']}");
                                $waText = urlencode("Hi {$cust['name']}! We miss you at {$business['name']}! You have {$cust['current_coins']} reward coins waiting for you. Visit us this week and get bonus perk on your order! View your card: {$shopUrl}");
                            ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4">
                                        <strong class="text-sm font-bold text-slate-900 block"><?= htmlspecialchars($cust['name']) ?></strong>
                                        <span class="text-[11px] text-slate-500 font-mono">+91 <?= htmlspecialchars($cust['mobile']) ?></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="badge-tier <?= $tier['tier'] ?>">
                                            <?= $tier['badge'] ?> <?= $tier['name'] ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-extrabold text-slate-900 text-sm"><?= (int)$cust['total_visits'] ?></span> visits
                                        <span class="text-slate-400 block text-[11px]"><?= (int)$cust['current_stamps'] ?> stamps on active card</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-extrabold text-emerald-600 text-sm"><?= number_format((float)$cust['current_coins'], 1) ?></span>
                                        <span class="text-slate-400 block text-[10px]">
                                            ≈ <?= Helper::formatCurrency((float)$cust['current_coins'] * (float)($business['coin_redeem_value'] ?? 0.50), $currency) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php if ($cust['last_visit_at']): ?>
                                            <span class="<?= $isAtRisk ? 'text-rose-600 font-bold' : 'text-slate-700' ?>">
                                                <?= Helper::timeAgo($cust['last_visit_at']) ?>
                                            </span>
                                            <span class="text-[10px] text-slate-400 block"><?= $daysAgo ?> days ago</span>
                                        <?php else: ?>
                                            <span class="text-slate-400">Never</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="https://wa.me/91<?= preg_replace('/[^0-9]/', '', $cust['mobile']) ?>?text=<?= $waText ?>" target="_blank"
                                           class="btn btn-sm btn-whatsapp font-bold shadow-sm inline-flex items-center gap-1.5 text-xs">
                                            <i class="fab fa-whatsapp text-sm"></i>
                                            <span><?= $isAtRisk ? 'Win Back' : 'WhatsApp' ?></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400">
                                    No customers found matching this filter.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Enroll New Customer Modal (Phase 2 -> Phase 3) -->
<div id="enrollCustomerModal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 border border-slate-100 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div class="flex items-center gap-2.5">
                <span class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                    <i class="fas fa-user-plus"></i>
                </span>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Enroll New Customer</h3>
                    <p class="text-xs text-slate-500">Create customer card account for walk-ins</p>
                </div>
            </div>
            <button onclick="document.getElementById('enrollCustomerModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-lg">
                &times;
            </button>
        </div>

        <form method="POST" action="<?= Helper::url('owner/customers') ?>" class="space-y-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Customer Mobile Number *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 font-semibold">+91</span>
                    <input type="tel" name="mobile" required maxlength="10" placeholder="10-digit mobile"
                           class="w-full pl-12 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-indigo-500">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Customer will use this number to sign in to their loyalty card.</p>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Customer Full Name *</label>
                <input type="text" name="name" required placeholder="e.g. Rahul Sharma"
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Address (Optional)</label>
                <input type="email" name="email" placeholder="customer@example.com"
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Welcome Reward Coins</label>
                <input type="number" name="bonus_coins" value="<?= (int)($business['welcome_bonus_coins'] ?? 20) ?>"
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-indigo-500">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                <button type="button" onclick="document.getElementById('enrollCustomerModal').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary px-5 py-2.5 font-bold shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-check mr-1"></i> Enroll & Create Card
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
