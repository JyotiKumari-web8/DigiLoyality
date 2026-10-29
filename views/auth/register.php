<?php
$pageTitle = 'Create Business Loyalty Card — DigiLoyalty';
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 flex items-center justify-center p-4 py-12">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 sm:p-10 border border-slate-100">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/25">
                <i class="fas fa-store text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Launch Your Loyalty Card</h1>
            <p class="text-slate-500 text-xs mt-1">Start rewarding customers and driving repeat visits in minutes</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-rose-500"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Business Name *</label>
                    <input type="text" name="business_name" required placeholder="e.g. Cafe Mocha"
                           value="<?= htmlspecialchars($_POST['business_name'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Category</label>
                    <select name="category" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-indigo-500 bg-white">
                        <option value="Restaurant & Cafe">Restaurant & Cafe</option>
                        <option value="Salon & Spa">Salon & Spa</option>
                        <option value="Retail & Boutique">Retail & Boutique</option>
                        <option value="Barbershop">Barbershop</option>
                        <option value="Bakery & Desserts">Bakery & Desserts</option>
                        <option value="Other Local Business">Other Local Business</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Owner Name *</label>
                    <input type="text" name="owner_name" required placeholder="Your Name"
                           value="<?= htmlspecialchars($_POST['owner_name'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Contact Phone</label>
                    <input type="tel" name="phone" placeholder="9876543210"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                           class="w-full px-3.5 py-2.5 border border-slate-200 rounded-2xl text-sm outline-none focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Owner Email *</label>
                <input type="email" name="email" required placeholder="owner@store.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-2xl text-sm font-medium outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-wider text-slate-600 block mb-1">Password *</label>
                <input type="password" name="password" required minlength="6" placeholder="Min 6 characters"
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-2xl text-sm font-medium outline-none focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full btn btn-primary py-3.5 text-base shadow-lg shadow-indigo-500/20 mt-2">
                Create My Loyalty Program <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </button>
        </form>

        <p class="text-center text-xs text-slate-500 mt-6">
            Already have an account?
            <a href="<?= Helper::url('login') ?>" class="text-indigo-600 font-bold hover:underline">Log in</a>
        </p>
    </div>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
