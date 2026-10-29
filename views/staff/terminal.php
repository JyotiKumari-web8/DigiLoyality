<?php
$pageTitle = 'Cashier Speed Terminal — ' . ($business['name'] ?? 'Loyalty');
require APP_ROOT . '/views/layout/header.php';
$staffUser = Auth::currentStaff();
?>

<div class="min-h-screen bg-slate-900 text-white flex flex-col justify-between">
    <!-- Terminal Header Bar -->
    <header class="bg-slate-800/80 backdrop-blur-md border-b border-slate-700/60 px-4 py-3 sticky top-0 z-20">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-sm">
                    <i class="fas fa-cash-register"></i>
                </span>
                <div>
                    <h1 class="text-sm font-extrabold tracking-tight"><?= htmlspecialchars($business['name']) ?></h1>
                    <span class="text-[11px] text-slate-400">Terminal &bull; <?= htmlspecialchars($staffUser['name'] ?? 'Owner Staff') ?></span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="<?= Helper::url('owner/dashboard') ?>" class="text-xs bg-slate-700 hover:bg-slate-600 px-3 py-1.5 rounded-lg text-slate-200 font-semibold transition">
                    <i class="fas fa-chart-line mr-1"></i> Dashboard
                </a>
                <a href="<?= Helper::url('staff/logout') ?>" class="text-xs text-rose-400 hover:text-rose-300 px-2 py-1 transition">
                    <i class="fas fa-power-off"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto w-full p-4 flex-1 grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT: Phone Keypad & Lookup (md: 6 cols) -->
        <div class="md:col-span-6 space-y-4">
            <div class="bg-slate-800/90 rounded-3xl p-5 border border-slate-700 shadow-xl">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Lookup Customer</span>
                    <button onclick="LoyaltyApp.keypadClear()" class="text-xs text-slate-400 hover:text-white transition">
                        Clear
                    </button>
                </div>

                <!-- Input Display -->
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-lg">
                        +91
                    </div>
                    <input type="text" id="terminal-phone-input" readonly
                           placeholder="Enter 10-digit mobile"
                           class="w-full pl-16 pr-4 py-4 bg-slate-900 border border-slate-700 rounded-2xl text-xl font-mono font-bold tracking-widest text-white text-center focus:outline-none">
                </div>

                <!-- Status Text -->
                <div id="terminal-status" class="text-center text-xs font-medium min-h-[20px] mb-3"></div>

                <!-- Numeric Keypad Grid -->
                <div class="keypad-grid">
                    <button onclick="LoyaltyApp.keypadPress('1')" class="keypad-btn">1</button>
                    <button onclick="LoyaltyApp.keypadPress('2')" class="keypad-btn">2</button>
                    <button onclick="LoyaltyApp.keypadPress('3')" class="keypad-btn">3</button>
                    <button onclick="LoyaltyApp.keypadPress('4')" class="keypad-btn">4</button>
                    <button onclick="LoyaltyApp.keypadPress('5')" class="keypad-btn">5</button>
                    <button onclick="LoyaltyApp.keypadPress('6')" class="keypad-btn">6</button>
                    <button onclick="LoyaltyApp.keypadPress('7')" class="keypad-btn">7</button>
                    <button onclick="LoyaltyApp.keypadPress('8')" class="keypad-btn">8</button>
                    <button onclick="LoyaltyApp.keypadPress('9')" class="keypad-btn">9</button>
                    <button onclick="LoyaltyApp.keypadClear()" class="keypad-btn text-xs text-rose-500 font-bold uppercase">CLR</button>
                    <button onclick="LoyaltyApp.keypadPress('0')" class="keypad-btn">0</button>
                    <button onclick="LoyaltyApp.keypadBackspace()" class="keypad-btn text-slate-600"><i class="fas fa-backspace"></i></button>
                </div>

                <!-- QR Scan or Direct Search button -->
                <div class="mt-4 flex gap-2">
                    <button onclick="LoyaltyApp.searchCustomer()" class="w-full btn btn-primary py-3">
                        <i class="fas fa-search mr-1"></i> Search Customer
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT: Customer Action Terminal (md: 6 cols) -->
        <div class="md:col-span-6 space-y-4">
            
            <!-- 1. Customer Found Action Card -->
            <div id="customer-action-card" style="display:none;" class="bg-slate-800 rounded-3xl p-6 border border-slate-700 shadow-xl space-y-5">
                
                <!-- Customer Header Profile -->
                <div class="flex items-start justify-between pb-4 border-b border-slate-700">
                    <div>
                        <span id="c-tier" class="badge-tier bronze mb-1">🥉 Bronze</span>
                        <h3 id="c-name" class="text-xl font-extrabold text-white leading-tight">Customer Name</h3>
                        <span id="c-mobile" class="text-xs text-slate-400 font-mono">+91 0000000000</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Total Visits</span>
                        <span id="c-visits" class="text-xl font-bold text-indigo-400">0</span>
                    </div>
                </div>

                <!-- At-Risk Alert Banner -->
                <div id="c-at-risk-box" style="display:none;" class="p-3 bg-rose-500/15 border border-rose-500/30 rounded-2xl flex items-center gap-2.5 text-xs text-rose-300">
                    <i class="fas fa-exclamation-triangle text-rose-400"></i>
                    <span><b>At-Risk Customer:</b> Hasn't visited in 30+ days! Welcome them warmly!</span>
                </div>

                <!-- Stats summary: Stamps & Coins -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 bg-slate-900/80 rounded-2xl border border-slate-700/60">
                        <span class="text-[11px] font-bold text-slate-400 uppercase block">Stamps Progress</span>
                        <span id="c-stamps" class="text-lg font-black text-amber-400 block mt-0.5">0 / 8</span>
                    </div>
                    <div class="p-3.5 bg-slate-900/80 rounded-2xl border border-slate-700/60">
                        <span class="text-[11px] font-bold text-slate-400 uppercase block">Reward Coins</span>
                        <span id="c-coins" class="text-lg font-black text-emerald-400 block mt-0.5">0.0</span>
                        <span id="c-coin-val" class="text-[10px] text-emerald-300">₹0.00 value</span>
                    </div>
                </div>

                <!-- Stamp Milestone Ready Alert -->
                <div id="stamp-reward-alert" style="display:none;" class="p-3 bg-amber-500/20 border border-amber-500/40 rounded-2xl flex items-center gap-2 text-xs text-amber-200">
                    <i class="fas fa-gift text-amber-400 text-sm"></i>
                    <span>This visit will complete their loyalty card for a <b>Free Reward!</b></span>
                </div>

                <!-- Action 1: Add Stamp & Coins -->
                <div class="p-4 bg-slate-900/90 rounded-2xl border border-indigo-500/20 space-y-3">
                    <span class="text-xs font-bold text-indigo-300 uppercase tracking-wider block">Record Customer Visit</span>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">Enter Bill Amount (Optional - Auto-awards Coins):</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold"><?= htmlspecialchars($business['currency_symbol'] ?? '₹') ?></span>
                            <input type="number" id="terminal-bill-input" placeholder="0.00" step="1"
                                   class="w-full pl-8 pr-3 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white font-bold outline-none focus:border-indigo-500">
                        </div>
                    </div>

                    <button id="btn-add-stamp" onclick="LoyaltyApp.addStampAndCoins()" class="w-full btn btn-primary py-3.5 text-base">
                        <i class="fas fa-stamp mr-1"></i> +1 Visit Stamp & Credit Coins
                    </button>
                </div>

                <!-- Action 2: Burn Coins for Bill Discount -->
                <div class="p-4 bg-slate-900/90 rounded-2xl border border-slate-700 space-y-3">
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider block">Redeem Coins for Bill Discount</span>
                    <div class="flex gap-2">
                        <input type="number" id="terminal-coins-burn-input" placeholder="e.g. 50"
                               class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white font-bold text-sm outline-none">
                        <button onclick="LoyaltyApp.burnCoins()" class="btn btn-success btn-sm flex-shrink-0">
                            Apply Discount
                        </button>
                    </div>
                </div>

                <!-- Action 3: Available Vouchers -->
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Customer Vouchers</span>
                    <div id="c-vouchers-list" class="space-y-2"></div>
                </div>
            </div>

            <!-- 2. Quick Register Card (Shown if customer not found) -->
            <div id="quick-register-card" style="display:none;" class="bg-slate-800 rounded-3xl p-6 border border-amber-500/30 shadow-xl space-y-4">
                <div class="flex items-center gap-2 text-amber-400">
                    <i class="fas fa-user-plus text-lg"></i>
                    <h3 class="text-base font-bold">Fast Customer Enrollment</h3>
                </div>
                <p class="text-xs text-slate-300">
                    This mobile number is not yet in the system. Register them in 2 seconds to award welcome coins!
                </p>

                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Mobile Number</label>
                    <input type="tel" id="quick-reg-mobile" readonly
                           class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono font-bold">
                </div>

                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Customer Name (Optional)</label>
                    <input type="text" id="quick-reg-name" placeholder="Customer Name"
                           class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white font-semibold outline-none focus:border-indigo-500">
                </div>

                <button onclick="LoyaltyApp.quickRegister()" class="w-full btn btn-primary py-3">
                    <i class="fas fa-check mr-1"></i> Register & Give Welcome Bonus
                </button>
            </div>

            <!-- 3. Recent Terminal Visits Today -->
            <div class="bg-slate-800/80 rounded-3xl p-5 border border-slate-700/60 shadow-lg">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                    <i class="fas fa-history text-indigo-400"></i> Today's Check-Ins
                </h4>
                <div class="space-y-2">
                    <?php if (!empty($recentVisits)): ?>
                        <?php foreach ($recentVisits as $rv): ?>
                            <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-900/60 border border-slate-800">
                                <div>
                                    <strong class="text-slate-200"><?= htmlspecialchars($rv['customer_name']) ?></strong>
                                    <span class="text-[10px] text-slate-500 font-mono block">+91 <?= htmlspecialchars($rv['customer_mobile']) ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-amber-400 font-bold">+<?= $rv['stamps_added'] ?> Stamp</span>
                                    <span class="text-[10px] text-slate-500 block"><?= date('H:i', strtotime($rv['created_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-xs text-slate-500 text-center py-2">No visits checked in today yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
