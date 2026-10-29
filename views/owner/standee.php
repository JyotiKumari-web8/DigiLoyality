<?php
$pageTitle = 'Print Counter QR Standee — ' . $business['name'];
require APP_ROOT . '/views/layout/header.php';
$customerUrl = Helper::url("business.php?slug={$business['slug']}");
?>

<div class="min-h-screen bg-slate-100 py-8 px-4">
    <div class="max-w-xl mx-auto">
        
        <!-- Controls (Hidden on Print) -->
        <div class="no-print bg-white rounded-2xl p-4 shadow-sm border border-slate-200 mb-6 flex items-center justify-between">
            <a href="<?= Helper::url('owner/dashboard') ?>" class="text-xs text-indigo-600 font-semibold hover:underline flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="fas fa-print mr-1"></i> Print Standee
                </button>
            </div>
        </div>

        <!-- Printable Tent Card / Standee Container -->
        <div class="standee-container bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border-4 border-indigo-600 text-center relative overflow-hidden">
            
            <!-- Top Gradient Wave -->
            <div class="w-full h-3 bg-gradient-to-r from-indigo-600 via-pink-500 to-amber-400 absolute top-0 left-0 right-0"></div>

            <!-- Shop Identity -->
            <div class="mt-4 mb-5">
                <div class="w-16 h-16 rounded-2xl mx-auto mb-3 flex items-center justify-center text-white text-2xl font-black shadow-lg"
                     style="background: linear-gradient(135deg, <?= htmlspecialchars($business['theme_color'] ?? '#6366f1') ?>, <?= htmlspecialchars($business['secondary_color'] ?? '#ec4899') ?>);">
                    <?= strtoupper(substr($business['name'], 0, 1)) ?>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight"><?= htmlspecialchars($business['name']) ?></h1>
                <p class="text-sm font-semibold text-slate-500 mt-1">Digital Loyalty & Rewards Program</p>
            </div>

            <!-- Reward Pitch Box -->
            <div class="p-4 bg-indigo-50 border-2 border-indigo-200 rounded-2xl my-6">
                <span class="text-xs font-black uppercase tracking-wider text-indigo-700 block">Collect Stamps & Earn Rewards</span>
                <strong class="text-xl font-black text-indigo-950 block mt-1">
                    <?= htmlspecialchars($business['stamp_reward_title']) ?>
                </strong>
                <p class="text-xs text-indigo-600 mt-1">
                    Earn 1 visit stamp on every visit + reward coins on your bill!
                </p>
            </div>

            <!-- Big QR Code for Counter -->
            <div class="my-6 inline-block p-4 bg-white rounded-3xl border-2 border-slate-200 shadow-xl">
                <div id="standee-qr-code"></div>
            </div>

            <!-- Scan Instructions -->
            <div class="space-y-2 mt-4 text-slate-700">
                <h3 class="text-lg font-extrabold text-slate-900 flex items-center justify-center gap-2">
                    <i class="fas fa-camera text-indigo-600"></i>
                    Scan with your Phone Camera
                </h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    No app download needed. Enter your mobile number to get your personal loyalty card instantly!
                </p>

                <!-- Bonus perk announcement -->
                <?php if (!empty($business['welcome_bonus_coins'])): ?>
                    <div class="inline-block mt-3 px-4 py-1.5 bg-amber-100 text-amber-900 rounded-full text-xs font-black">
                        🎁 Get <?= (int)$business['welcome_bonus_coins'] ?> Free Bonus Coins Today!
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer URL -->
            <div class="mt-8 pt-4 border-t border-slate-100 text-[11px] font-mono text-slate-400">
                Or visit directly: <?= htmlspecialchars($customerUrl) ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    LoyaltyApp.renderQRCode('standee-qr-code', '<?= $customerUrl ?>', 220);
});
</script>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
