<?php
$pageTitle = 'Reseller Console — Agency Dashboard';
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-slate-900 text-white pb-12">
    <!-- Reseller Nav -->
    <header class="bg-slate-950 border-b border-slate-800 px-6 py-4 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center font-black text-lg">
                    <i class="fas fa-crown"></i>
                </span>
                <div>
                    <h1 class="text-base font-extrabold tracking-tight"><?= htmlspecialchars($admin['agency_name'] ?? 'Agency Network') ?></h1>
                    <span class="text-xs text-amber-400 font-bold uppercase tracking-wider">Reseller Console</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button onclick="document.getElementById('onboardModal').classList.remove('hidden')" class="btn btn-sm btn-primary text-xs font-bold">
                    <i class="fas fa-plus mr-1"></i> Onboard New Merchant
                </button>
                <a href="<?= Helper::url('admin/logout') ?>" class="text-xs text-rose-400 hover:text-rose-300 font-semibold">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <?php if (!empty($_SESSION['flash'])): 
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
        ?>
            <div class="p-4 rounded-2xl text-xs font-bold <?= $flash['type'] === 'success' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>
        
        <!-- Global Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-slate-800/80 p-5 rounded-3xl border border-slate-700">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Merchants</span>
                <span class="text-3xl font-black text-white mt-1 block"><?= count($businesses) ?></span>
                <span class="text-[11px] text-emerald-400 mt-1 block">Active client stores</span>
            </div>

            <div class="bg-slate-800/80 p-5 rounded-3xl border border-slate-700">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Total Members</span>
                <span class="text-3xl font-black text-white mt-1 block"><?= $totalCustomers ?></span>
                <span class="text-[11px] text-slate-400 mt-1 block">Registered customers</span>
            </div>

            <div class="bg-slate-800/80 p-5 rounded-3xl border border-slate-700">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Total Visits</span>
                <span class="text-3xl font-black text-white mt-1 block"><?= $totalVisits ?></span>
                <span class="text-[11px] text-indigo-400 mt-1 block">Store check-ins</span>
            </div>

            <div class="bg-slate-800/80 p-5 rounded-3xl border border-slate-700">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Coins Issued</span>
                <span class="text-3xl font-black text-amber-400 mt-1 block"><?= number_format($totalCoinsIssued, 0) ?></span>
                <span class="text-[11px] text-slate-400 mt-1 block">Loyalty currency</span>
            </div>
        </div>

        <!-- Business Subscriptions Table -->
        <div class="bg-slate-800 rounded-3xl border border-slate-700 overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Client Businesses & Subscriptions</h3>
                    <p class="text-xs text-slate-400">Phase 2: Manage the business owners you sell loyalty programs to</p>
                </div>
                <button onclick="document.getElementById('onboardModal').classList.remove('hidden')" class="btn btn-sm btn-primary text-xs">
                    <i class="fas fa-plus mr-1"></i> Add Business
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-950/60 border-b border-slate-700 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-6">Business</th>
                            <th class="py-3.5 px-6">Owner Credentials</th>
                            <th class="py-3.5 px-6">Customers</th>
                            <th class="py-3.5 px-6">Plan Expiry</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/60">
                        <?php if (empty($businesses)): ?>
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fas fa-store text-3xl mb-2 text-slate-600 block"></i>
                                    No client businesses yet. Click <b>"Onboard New Merchant"</b> above to add your first client.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($businesses as $b): 
                            $isExpired = $b['plan_expires_at'] && strtotime($b['plan_expires_at']) < time();
                        ?>
                            <tr class="hover:bg-slate-700/30 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-white text-xs"
                                             style="background: <?= htmlspecialchars($b['theme_color'] ?? '#6366f1') ?>">
                                            <?= strtoupper(substr($b['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong class="text-white text-sm block"><?= htmlspecialchars($b['name']) ?></strong>
                                            <a href="<?= Helper::url('business.php?slug=' . urlencode($b['slug'])) ?>" target="_blank"
                                               class="text-[11px] text-indigo-400 hover:underline">
                                                /card/<?= htmlspecialchars($b['slug']) ?> <i class="fas fa-external-link-alt text-[9px]"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-slate-200 font-semibold block"><?= htmlspecialchars($b['owner_name']) ?></span>
                                    <span class="text-slate-400 font-mono text-[11px]"><?= htmlspecialchars($b['owner_email']) ?></span>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-200">
                                    <?= (int)$b['total_customers'] ?> members
                                    <span class="text-slate-500 block text-[10px]"><?= (int)$b['total_visits'] ?> visits</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold <?= $isExpired ? 'text-rose-400' : 'text-slate-200' ?>">
                                        <?= $b['plan_expires_at'] ? date('M d, Y', strtotime($b['plan_expires_at'])) : 'Lifetime' ?>
                                    </span>
                                    <?php if ($isExpired): ?>
                                        <span class="text-[10px] text-rose-500 font-bold block">Expired!</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $b['status'] === 'active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                                        <?= htmlspecialchars($b['status']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Quick Access to Owner Portal -->
                                        <a href="<?= Helper::url('admin/login-as-owner?id=' . $b['id']) ?>" target="_blank"
                                           class="btn btn-sm bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] px-2.5 py-1 rounded-lg">
                                            <i class="fas fa-sign-in-alt mr-1"></i> Owner Portal
                                        </a>

                                        <!-- Extend Plan Form -->
                                        <form method="POST" action="<?= Helper::url('admin/action') ?>" class="inline">
                                            <input type="hidden" name="action" value="extend_plan">
                                            <input type="hidden" name="business_id" value="<?= $b['id'] ?>">
                                            <input type="hidden" name="days" value="30">
                                            <button type="submit" class="btn btn-sm bg-slate-700 hover:bg-slate-600 text-slate-200 text-[11px] px-2 py-1 rounded-lg">
                                                +30 Days
                                            </button>
                                        </form>

                                        <!-- Toggle Status Form -->
                                        <form method="POST" action="<?= Helper::url('admin/action') ?>" class="inline">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="business_id" value="<?= $b['id'] ?>">
                                            <input type="hidden" name="status" value="<?= $b['status'] ?>">
                                            <button type="submit" class="text-xs text-slate-400 hover:text-white px-1.5 py-1">
                                                <?= $b['status'] === 'active' ? 'Suspend' : 'Activate' ?>
                                            </button>
                                        </form>

                                        <!-- Delete Business Form -->
                                        <form method="POST" action="<?= Helper::url('admin/action') ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this business? All visits and customer records will be removed.')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="business_id" value="<?= $b['id'] ?>">
                                            <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 px-1.5 py-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Onboard New Merchant Modal -->
<div id="onboardModal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-lg w-full p-6 sm:p-8 text-white max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-6">
            <div class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-bold">
                    <i class="fas fa-store"></i>
                </span>
                <div>
                    <h3 class="text-base font-extrabold text-white">Onboard New Merchant</h3>
                    <p class="text-xs text-slate-400">Create client business & owner login credentials</p>
                </div>
            </div>
            <button onclick="document.getElementById('onboardModal').classList.add('hidden')" class="text-slate-400 hover:text-white text-lg">
                &times;
            </button>
        </div>

        <form method="POST" action="<?= Helper::url('admin/create-business') ?>" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Business Name *</label>
                    <input type="text" name="business_name" required placeholder="e.g. Urban Cafe"
                           class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                        <option value="Restaurant & Cafe">Restaurant & Cafe</option>
                        <option value="Salon & Spa">Salon & Spa</option>
                        <option value="Retail & Boutique">Retail & Boutique</option>
                        <option value="Barbershop">Barbershop</option>
                        <option value="Bakery & Desserts">Bakery & Desserts</option>
                        <option value="Gym & Fitness">Gym & Fitness</option>
                        <option value="Other Business">Other Business</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Owner Name *</label>
                    <input type="text" name="owner_name" required placeholder="Client's Name"
                           class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Owner Phone</label>
                    <input type="tel" name="owner_phone" placeholder="9876543210"
                           class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Owner Login Email *</label>
                    <input type="email" name="owner_email" required placeholder="client@store.com"
                           class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Owner Login Password *</label>
                    <input type="password" name="owner_password" required minlength="6" value="123456"
                           class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Subscription Plan</label>
                    <select name="plan_days" class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                        <option value="30">1 Month Trial (30 Days)</option>
                        <option value="90">Quarterly (90 Days)</option>
                        <option value="180">Half-Yearly (180 Days)</option>
                        <option value="365" selected>Annual Plan (365 Days)</option>
                        <option value="3650">Lifetime VIP (10 Years)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Stamp Target</label>
                    <input type="number" name="stamp_target" min="3" max="20" value="8"
                           class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Milestone Reward Title</label>
                <input type="text" name="stamp_reward_title" value="Free Item / ₹150 Off"
                       class="w-full px-3 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-white outline-none focus:border-amber-400">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('onboardModal').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-bold rounded-xl shadow-lg hover:brightness-110">
                    Create & Deliver Credentials
                </button>
            </div>
        </form>
    </div>
</div>
    </main>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
