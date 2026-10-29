<?php
$pageTitle = 'Owner Dashboard — ' . $business['name'];
require APP_ROOT . '/views/layout/header.php';
$currency = $business['currency_symbol'] ?? '₹';
?>

<!-- Owner Navbar -->
<nav class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white font-extrabold text-base shadow-md"
                     style="background: linear-gradient(135deg, <?= htmlspecialchars($business['theme_color'] ?? '#6366f1') ?>, <?= htmlspecialchars($business['secondary_color'] ?? '#ec4899') ?>);">
                    <?= strtoupper(substr($business['name'], 0, 1)) ?>
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-900 leading-tight"><?= htmlspecialchars($business['name']) ?></h1>
                    <span class="text-xs text-slate-400">Owner Portal &bull; <?= htmlspecialchars($business['category']) ?></span>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-1">
                <a href="<?= Helper::url('owner/dashboard') ?>" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-indigo-50 text-indigo-700">
                    <i class="fas fa-chart-line mr-1.5"></i> Overview
                </a>
                <a href="<?= Helper::url('staff/terminal') ?>" class="px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition">
                    <i class="fas fa-cash-register mr-1.5 text-indigo-500"></i> Open Terminal
                </a>
                <a href="<?= Helper::url('owner/customers') ?>" class="px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition">
                    <i class="fas fa-users mr-1.5 text-indigo-500"></i> Customers CRM
                </a>
                <a href="<?= Helper::url('owner/card-settings') ?>" class="px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition">
                    <i class="fas fa-id-card mr-1.5 text-indigo-500"></i> Card Builder
                </a>
                <a href="<?= Helper::url('owner/rewards') ?>" class="px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition">
                    <i class="fas fa-gift mr-1.5 text-indigo-500"></i> Rewards
                </a>
                <a href="<?= Helper::url('owner/standee') ?>" class="px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition">
                    <i class="fas fa-qrcode mr-1.5 text-indigo-500"></i> QR Standee
                </a>
                <a href="<?= Helper::url('owner/staff') ?>" class="px-3.5 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition">
                    <i class="fas fa-user-shield mr-1.5 text-indigo-500"></i> Staff
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a href="<?= Helper::url('business.php?slug=' . urlencode($business['slug'])) ?>" target="_blank"
                   class="btn btn-sm btn-secondary text-xs">
                    <i class="fas fa-external-link-alt"></i> Preview Pass
                </a>
                <a href="<?= Helper::url('owner/logout') ?>" class="text-xs text-rose-500 hover:text-rose-700 px-2 py-1 font-semibold">
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Bar -->
<div class="mobile-nav">
    <a href="<?= Helper::url('owner/dashboard') ?>" class="mobile-nav-item active">
        <i class="fas fa-chart-line"></i>
        <span>Overview</span>
    </a>
    <a href="<?= Helper::url('staff/terminal') ?>" class="mobile-nav-item">
        <i class="fas fa-cash-register"></i>
        <span>Terminal</span>
    </a>
    <a href="<?= Helper::url('owner/customers') ?>" class="mobile-nav-item">
        <i class="fas fa-users"></i>
        <span>CRM</span>
    </a>
    <a href="<?= Helper::url('owner/card-settings') ?>" class="mobile-nav-item">
        <i class="fas fa-id-card"></i>
        <span>Card</span>
    </a>
    <a href="<?= Helper::url('owner/standee') ?>" class="mobile-nav-item">
        <i class="fas fa-qrcode"></i>
        <span>Standee</span>
    </a>
</div>

<!-- Main Dashboard Container -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Hero Banner with Quick Actions -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="relative z-10 max-w-xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-indigo-300 mb-3 border border-white/10">
                <i class="fas fa-bolt text-amber-400"></i> Loyalty Engine Active
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Boost repeat visits for <?= htmlspecialchars($business['name']) ?></h2>
            <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                Customers join with their mobile number, collect digital stamps, and earn spend-based coins. No app downloads required.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap gap-3">
            <a href="<?= Helper::url('staff/terminal') ?>" class="btn btn-primary btn-lg shadow-xl">
                <i class="fas fa-cash-register"></i> Open Counter Terminal
            </a>
            <a href="<?= Helper::url('owner/standee') ?>" class="btn btn-secondary btn-lg bg-white/10 border-white/20 text-white hover:bg-white/20">
                <i class="fas fa-print"></i> Print Counter Standee
            </a>
        </div>
    </div>

    <!-- 4 Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric 1: Repeat Visit Rate -->
        <div class="card p-6 border-slate-200">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                <span>Repeat Visit Rate</span>
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fas fa-redo"></i>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-900"><?= $stats['repeat_rate'] ?>%</span>
                <span class="text-xs text-emerald-600 font-bold">Loyalty Lift</span>
            </div>
            <p class="text-xs text-slate-500 mt-2">
                Industry average is ~20%. Keep driving regular check-ins!
            </p>
        </div>

        <!-- Metric 2: Total Customers -->
        <div class="card p-6 border-slate-200">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                <span>Enrolled Members</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fas fa-users"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900"><?= $stats['total_customers'] ?></div>
            <p class="text-xs text-slate-500 mt-2">
                Total mobile passes issued across all visits.
            </p>
        </div>

        <!-- Metric 3: Total Recorded Visits -->
        <div class="card p-6 border-slate-200">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                <span>Recorded Visits</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fas fa-stamp"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900"><?= $stats['total_visits'] ?></div>
            <p class="text-xs text-slate-500 mt-2">
                <?= $stats['total_stamps'] ?> total visit stamps awarded.
            </p>
        </div>

        <!-- Metric 4: Active Coins in Circulation -->
        <div class="card p-6 border-slate-200">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                <span>Reward Coins Active</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fas fa-coins"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600">
                <?= number_format($stats['active_coins'], 1) ?>
            </div>
            <p class="text-xs text-slate-500 mt-2">
                Available for discounts at checkout.
            </p>
        </div>
    </div>

    <!-- At-Risk Churn Banner (If Any) -->
    <?php if ($stats['at_risk_count'] > 0): ?>
        <div class="p-6 bg-gradient-to-r from-rose-50 to-orange-50 border border-rose-200 rounded-3xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-lg shadow-rose-500/20">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-rose-950">
                        <?= $stats['at_risk_count'] ?> Customers have not visited in 30+ days!
                    </h4>
                    <p class="text-xs text-rose-700 mt-0.5">
                        These customers are at risk of forgetting your business. Bring them back with an automated WhatsApp offer.
                    </p>
                </div>
            </div>

            <a href="<?= Helper::url('owner/customers?filter=at_risk') ?>" class="btn btn-whatsapp text-xs font-bold whitespace-nowrap shadow-md">
                <i class="fab fa-whatsapp text-sm"></i> Send Win-Back WhatsApp Messages
            </a>
        </div>
    <?php endif; ?>

    <!-- Two-Column Section: 7-Day Trend & Recent Streams -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- 7-Day Trend Chart (lg: 7 cols) -->
        <div class="lg:col-span-7 card p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">7-Day Visit Volume</h3>
                    <p class="text-xs text-slate-500">Customer footfall captured at the counter terminal</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full">
                    Last 7 Days
                </span>
            </div>

            <?php if (!empty($stats['recent_visits'])): ?>
                <div class="space-y-4">
                    <?php 
                        $maxCount = 1;
                        foreach ($stats['recent_visits'] as $v) {
                            if ($v['visit_count'] > $maxCount) $maxCount = $v['visit_count'];
                        }
                    ?>
                    <?php foreach ($stats['recent_visits'] as $rv): 
                        $pct = round(($rv['visit_count'] / $maxCount) * 100);
                    ?>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1">
                                <span class="text-slate-600"><?= date('D, M d', strtotime($rv['visit_date'])) ?></span>
                                <span class="text-indigo-600"><?= $rv['visit_count'] ?> visits</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-pink-500 h-3 rounded-full transition-all" style="width: <?= max(5, $pct) ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 text-slate-400">
                    <i class="fas fa-chart-bar text-4xl mb-3 text-slate-200"></i>
                    <p class="text-xs">No visit activity in the last 7 days yet. Start recording visits on the counter terminal!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Customers & Check-Ins (lg: 5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Recent Signups -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">New Customer Signups</h3>
                    <a href="<?= Helper::url('owner/customers') ?>" class="text-xs text-indigo-600 font-bold hover:underline">View All</a>
                </div>

                <div class="space-y-3">
                    <?php if (!empty($recentCustomers)): ?>
                        <?php foreach ($recentCustomers as $rc): ?>
                            <div class="flex items-center justify-between text-xs p-2.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                        <?= strtoupper(substr($rc['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <strong class="text-slate-900 block"><?= htmlspecialchars($rc['name']) ?></strong>
                                        <span class="text-[11px] text-slate-500 font-mono">+91 <?= htmlspecialchars($rc['mobile']) ?></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[11px] font-bold text-emerald-600 block"><?= number_format($rc['current_coins'], 0) ?> Coins</span>
                                    <span class="text-[10px] text-slate-400"><?= Helper::timeAgo($rc['created_at']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-xs text-slate-400 text-center py-4">No customers registered yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Program Quick Config Overview -->
            <div class="card p-6 bg-slate-900 text-white border-slate-800">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Program Rules Summary</h4>
                <ul class="text-xs space-y-2 text-slate-300">
                    <li class="flex items-center justify-between pb-1.5 border-b border-slate-800">
                        <span>Target Stamp Goal:</span>
                        <strong class="text-amber-400"><?= (int)$business['stamp_target'] ?> Visits</strong>
                    </li>
                    <li class="flex items-center justify-between pb-1.5 border-b border-slate-800">
                        <span>Milestone Stamp Reward:</span>
                        <strong class="text-white"><?= htmlspecialchars($business['stamp_reward_title']) ?></strong>
                    </li>
                    <li class="flex items-center justify-between pb-1.5 border-b border-slate-800">
                        <span>Coin Earning Ratio:</span>
                        <strong class="text-emerald-400">1 Coin per <?= $currency ?><?= number_format($business['coin_rate'], 0) ?></strong>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Welcome Bonus Coins:</span>
                        <strong class="text-pink-400"><?= (int)$business['welcome_bonus_coins'] ?> Coins</strong>
                    </li>
                </ul>

                <a href="<?= Helper::url('owner/card-settings') ?>" class="btn btn-secondary btn-sm w-full mt-4 text-xs font-bold text-slate-900 bg-white hover:bg-slate-100">
                    <i class="fas fa-sliders-h mr-1.5"></i> Edit Loyalty Rules
                </a>
            </div>
        </div>
    </div>
</main>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
