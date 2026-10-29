<?php
$pageTitle = $customer['name'] . ' — ' . $business['name'] . ' Pass';
require APP_ROOT . '/views/layout/header.php';
$themeColor = $business['theme_color'] ?? '#6366f1';
$currency = $business['currency_symbol'] ?? '₹';
$stampTarget = (int) ($business['stamp_target'] ?? 8);
$currentStamps = (int) $customer['current_stamps'];
?>

<div class="min-h-screen bg-slate-100/60 pb-12 pt-4 px-3 sm:px-6">
    <div class="max-w-md mx-auto space-y-4">

        <!-- Top Bar: Shop info & Logout -->
        <div class="flex items-center justify-between px-2">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-md"
                     style="background: linear-gradient(135deg, <?= htmlspecialchars($themeColor) ?>, <?= htmlspecialchars($business['secondary_color'] ?? '#ec4899') ?>);">
                    <?= strtoupper(substr($business['name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 leading-tight"><?= htmlspecialchars($business['name']) ?></h2>
                    <span class="text-[11px] text-slate-500 font-medium">Digital Loyalty Pass</span>
                </div>
            </div>
            
            <a href="<?= Helper::url('business.php?slug=' . urlencode($business['slug']) . '&action=logout') ?>" 
               class="text-xs text-slate-400 hover:text-rose-500 font-medium transition flex items-center gap-1">
                <i class="fas fa-sign-out-alt"></i> Exit
            </a>
        </div>

        <!-- 1. Apple Wallet Style Pass Card -->
        <div class="wallet-pass" style="background: linear-gradient(145deg, #1e1b4b 0%, #312e81 60%, <?= htmlspecialchars($themeColor) ?> 100%);">
            <!-- Pass Card Header -->
            <div class="flex items-start justify-between relative z-10 mb-4">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-white/70 block">Cardholder</span>
                    <h3 class="text-xl font-extrabold text-white leading-tight"><?= htmlspecialchars($customer['name']) ?></h3>
                    <span class="text-xs text-white/80 font-mono">+91 <?= htmlspecialchars($customer['mobile']) ?></span>
                </div>
                <div class="text-right">
                    <span class="badge-tier <?= htmlspecialchars($tier['tier']) ?>">
                        <?= $tier['badge'] ?> <?= htmlspecialchars($tier['name']) ?>
                    </span>
                    <span class="text-[10px] text-white/60 block mt-1"><?= htmlspecialchars($tier['perk']) ?></span>
                </div>
            </div>

            <!-- Digital Stamp Grid -->
            <div class="relative z-10 my-5">
                <div class="flex items-center justify-between text-xs font-semibold mb-2 text-white/90">
                    <span><i class="fas fa-stamp mr-1 text-amber-400"></i> Visit Stamps</span>
                    <span><?= $currentStamps ?> / <?= $stampTarget ?> Completed</span>
                </div>

                <div class="stamp-grid">
                    <?php for ($i = 1; $i <= $stampTarget; $i++): 
                        $isFilled = $i <= $currentStamps;
                        $isRewardSlot = ($i === $stampTarget);
                    ?>
                        <div class="stamp-slot <?= $isFilled ? 'filled' : '' ?> <?= $isRewardSlot ? 'reward-slot' : '' ?>">
                            <?php if ($isFilled): ?>
                                <i class="fas fa-star text-white"></i>
                            <?php elseif ($isRewardSlot): ?>
                                <i class="fas fa-gift text-sm"></i>
                            <?php else: ?>
                                <span><?= $i ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Reward Banner -->
                <div class="mt-4 p-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-trophy text-amber-300 text-sm"></i>
                        <span>Goal: <b><?= htmlspecialchars($business['stamp_reward_title']) ?></b></span>
                    </div>
                    <?php if ($currentStamps >= $stampTarget - 1): ?>
                        <span class="px-2 py-0.5 bg-amber-400 text-slate-900 font-extrabold text-[10px] rounded-full animate-pulse">
                            1 visit away!
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 2. Reward Coins Widget -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="coin-badge">
                    🪙
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Reward Coins</span>
                    <div class="text-2xl font-extrabold text-slate-900 leading-none mt-0.5">
                        <?= number_format((float)$customer['current_coins'], 1) ?>
                        <span class="text-xs font-semibold text-emerald-600 ml-1">
                            (≈ <?= Helper::formatCurrency($coinMoneyValue, $currency) ?>)
                        </span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">
                        Earn 1 coin per <?= $currency ?><?= number_format((float)($business['coin_rate'] ?? 10), 0) ?> spent
                    </span>
                </div>
            </div>

            <button onclick="document.getElementById('rewards-modal').style.display='block'" 
                    class="btn btn-sm btn-primary">
                Redeem <i class="fas fa-chevron-right text-[10px]"></i>
            </button>
        </div>

        <!-- 3. Customer Personal QR Code (For Cashier Scan) -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-3">
                Your Member Check-In QR
            </span>

            <div id="customer-qr-container" class="inline-block p-2 bg-white rounded-2xl border border-slate-100 shadow-inner">
                <!-- QR rendered dynamically by JS -->
            </div>

            <p class="text-xs font-medium text-slate-500 mt-3 flex items-center justify-center gap-1.5">
                <i class="fas fa-qrcode text-indigo-500"></i>
                Show this QR to the cashier at checkout
            </p>
            <div class="font-mono text-xs text-slate-400 mt-1">
                ID: <?= substr($customer['qr_token'], 0, 12) ?>...
            </div>
        </div>

        <!-- 4. Active Claimed Vouchers -->
        <?php if (!empty($vouchers)): ?>
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                    <i class="fas fa-ticket-alt text-amber-500"></i> My Reward Vouchers
                </h4>
                <div class="space-y-2.5">
                    <?php foreach ($vouchers as $v): 
                        $isClaimed = ($v['status'] === 'claimed');
                    ?>
                        <div class="p-3.5 rounded-2xl border <?= $isClaimed ? 'border-amber-200 bg-amber-50/60' : 'border-slate-100 bg-slate-50' ?> flex items-center justify-between">
                            <div>
                                <strong class="text-sm font-bold text-slate-900 block"><?= htmlspecialchars($v['reward_title']) ?></strong>
                                <span class="font-mono text-xs font-bold text-indigo-600 tracking-wider">
                                    Code: <?= htmlspecialchars($v['voucher_code']) ?>
                                </span>
                            </div>
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full <?= $isClaimed ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-600' ?>">
                                <?= $isClaimed ? 'Ready to use' : 'Redeemed' ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 5. 1-Click WhatsApp Referral Share -->
        <div class="p-5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl text-white shadow-lg shadow-emerald-500/20 flex items-center justify-between">
            <div>
                <strong class="text-sm font-bold block">Invite a Friend & Earn Coins!</strong>
                <span class="text-xs text-white/80">Share <?= htmlspecialchars($business['name']) ?> on WhatsApp</span>
            </div>
            <?php 
                $shareUrl = Helper::url("business.php?slug={$business['slug']}");
                $shareText = urlencode("Hey! Join the loyalty program at {$business['name']} and get free bonus coins on signup! Check it out: {$shareUrl}");
            ?>
            <a href="https://wa.me/?text=<?= $shareText ?>" target="_blank"
               class="btn btn-sm bg-white text-emerald-700 hover:bg-emerald-50 shadow-md font-bold">
                <i class="fab fa-whatsapp text-lg"></i> Share
            </a>
        </div>

        <!-- 6. Activity History Drawer -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                <i class="fas fa-history text-indigo-500"></i> Recent Activity
            </h4>
            <?php if (!empty($history)): ?>
                <div class="space-y-3">
                    <?php foreach ($history as $h): ?>
                        <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-50 last:border-none">
                            <div>
                                <span class="font-semibold text-slate-800 block"><?= htmlspecialchars($h['description'] ?? 'Store Visit') ?></span>
                                <span class="text-[10px] text-slate-400"><?= Helper::timeAgo($h['created_at']) ?></span>
                            </div>
                            <div class="text-right">
                                <?php if ($h['stamps_added'] > 0): ?>
                                    <span class="text-amber-600 font-bold block">+<?= $h['stamps_added'] ?> Stamp</span>
                                <?php endif; ?>
                                <?php if ($h['coins_earned'] > 0): ?>
                                    <span class="text-emerald-600 font-bold">+<?= number_format((float)$h['coins_earned'], 1) ?> Coins</span>
                                <?php elseif ($h['coins_earned'] < 0): ?>
                                    <span class="text-rose-600 font-bold"><?= number_format((float)$h['coins_earned'], 1) ?> Coins</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-xs text-slate-400 text-center py-2">No visits recorded yet. Your first visit stamp will appear here!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Rewards Modal -->
<div id="rewards-modal" style="display:none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-3">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Rewards Catalog</h3>
                <p class="text-xs text-slate-500">Redeem your coins at the cashier counter</p>
            </div>
            <button onclick="document.getElementById('rewards-modal').style.display='none'" class="text-slate-400 hover:text-slate-700 text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="space-y-3">
            <?php if (!empty($rewards)): ?>
                <?php foreach ($rewards as $r): 
                    $canAfford = (float)$customer['current_coins'] >= (int)$r['cost'];
                ?>
                    <div class="p-3.5 rounded-2xl border <?= $canAfford ? 'border-indigo-100 bg-indigo-50/40' : 'border-slate-100 bg-slate-50' ?> flex items-center justify-between">
                        <div>
                            <strong class="text-sm font-bold text-slate-900 block"><?= htmlspecialchars($r['title']) ?></strong>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($r['description']) ?></p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-3">
                            <span class="text-xs font-extrabold text-indigo-600 block"><?= (int)$r['cost'] ?> Coins</span>
                            <?php if ($canAfford): ?>
                                <span class="text-[10px] text-emerald-600 font-bold">Available!</span>
                            <?php else: ?>
                                <span class="text-[10px] text-slate-400">Need <?= (int)$r['cost'] - (int)$customer['current_coins'] ?> more</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-xs text-slate-400 text-center py-4">No custom rewards set up yet.</p>
            <?php endif; ?>
        </div>

        <div class="mt-6">
            <button onclick="document.getElementById('rewards-modal').style.display='none'" class="w-full btn btn-secondary">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Render customer QR token
    LoyaltyApp.renderQRCode('customer-qr-container', '<?= $customer['qr_token'] ?>', 170);
});
</script>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
