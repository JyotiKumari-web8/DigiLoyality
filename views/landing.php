<?php
$pageTitle = 'DigiLoyalty Pro — Digital Loyalty & Repeat-Visit Engine';
require APP_ROOT . '/views/layout/header.php';
?>

<!-- SaaS Navigation -->
<nav class="fixed top-0 left-0 right-0 bg-white/85 backdrop-blur-md border-b border-slate-200/60 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="<?= Helper::url('') ?>" class="flex items-center space-x-2.5">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-pink-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 text-white font-extrabold text-sm">
                    <i class="fas fa-id-card"></i>
                </div>
                <span class="text-xl font-extrabold text-slate-900 tracking-tight">DigiLoyalty <span class="gradient-text">Pro</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                <a href="#features" class="hover:text-indigo-600 transition">Features</a>
                <a href="#how-it-works" class="hover:text-indigo-600 transition">How It Works</a>
                <a href="#demo" class="hover:text-indigo-600 transition">Live Demo</a>
                <a href="#pricing" class="hover:text-indigo-600 transition">Pricing</a>
            </div>

            <div class="flex items-center space-x-3">
                <a href="<?= Helper::url('login') ?>" class="text-xs sm:text-sm font-bold text-slate-700 hover:text-indigo-600 px-3 py-2">
                    Sign In
                </a>
                <a href="<?= Helper::url('register') ?>" class="btn btn-primary btn-sm text-xs sm:text-sm rounded-full">
                    Launch Free Card <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="pt-32 pb-20 sm:pt-40 sm:pb-28 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(99,102,241,0.2),transparent_50%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_70%,rgba(236,72,153,0.15),transparent_50%)]"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-white text-xs font-bold mb-6 border border-white/10 shadow-lg">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Dual Engine: Visit Stamps + Reward Coins &bull; Zero App Install Required
        </div>

        <h1 class="text-4xl sm:text-6xl font-black text-white mb-6 leading-[1.1] tracking-tight">
            Bring customers back.<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 via-purple-300 to-indigo-300">
                Reward visits & spend with zero friction.
            </span>
        </h1>

        <p class="text-base sm:text-xl text-slate-300 mb-10 leading-relaxed max-w-2xl mx-auto">
            Create an Apple Wallet-style digital loyalty card for your shop, salon, or restaurant. Customers join in 5 seconds with their mobile number. Staff scan & stamp from any phone or counter screen.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= Helper::url('register') ?>" class="w-full sm:w-auto btn btn-primary btn-lg shadow-xl shadow-indigo-500/25">
                <span>Start Your Loyalty Card</span>
                <i class="fas fa-arrow-right ml-1 text-sm"></i>
            </a>
            <a href="#demo" class="w-full sm:w-auto btn btn-secondary btn-lg bg-white/10 border-white/20 text-white hover:bg-white/20">
                <i class="fas fa-play-circle mr-1"></i> Try Live Demo Card
            </a>
        </div>

        <!-- Trust Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-16 max-w-3xl mx-auto pt-10 border-t border-white/10 text-center">
            <div>
                <p class="text-3xl font-black text-white">67%</p>
                <p class="text-xs text-slate-400 mt-1 uppercase font-semibold">Higher Repeat Spend</p>
            </div>
            <div>
                <p class="text-3xl font-black text-emerald-400">5 Sec</p>
                <p class="text-xs text-slate-400 mt-1 uppercase font-semibold">Cashier Check-In Speed</p>
            </div>
            <div>
                <p class="text-3xl font-black text-amber-400">0 MB</p>
                <p class="text-xs text-slate-400 mt-1 uppercase font-semibold">No App Store Download</p>
            </div>
            <div>
                <p class="text-3xl font-black text-pink-400">1-Click</p>
                <p class="text-xs text-slate-400 mt-1 uppercase font-semibold">WhatsApp Win-Back</p>
            </div>
        </div>
    </div>
</section>

<!-- Live Demo Showcase -->
<section id="demo" class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
                Platform Architecture
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Designed for Seamless Everyday Operations
            </h2>
            <p class="text-slate-500 text-sm mt-2 max-w-xl mx-auto">
                Everything required to turn first-time visitors into loyal repeat customers across every touchpoint.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            
            <!-- Module 1: Customer Pass -->
            <div class="card p-6 border-slate-200 hover:-translate-y-1 transition duration-200">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-xl mb-4 shadow-md shadow-indigo-500/20">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">1. Customer Mobile Pass</h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">Apple Wallet style digital loyalty card. Customers join in 5 seconds with mobile number with 0 app downloads.</p>
                <div class="space-y-2">
                    <a href="<?= Helper::url('register') ?>" class="w-full btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Create Your Card
                    </a>
                </div>
            </div>

            <!-- Module 2: Cashier Terminal -->
            <div class="card p-6 border-slate-200 hover:-translate-y-1 transition duration-200">
                <div class="w-12 h-12 rounded-2xl bg-pink-500 text-white flex items-center justify-center font-bold text-xl mb-4 shadow-md shadow-pink-500/20">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">2. Cashier Speed Terminal</h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">Fast 3-second counter lookup. Stamp visit cards, credit coin cashback, and validate voucher redemptions.</p>
                <div class="space-y-2">
                    <a href="<?= Helper::url('staff/login') ?>" class="w-full btn btn-secondary btn-sm">
                        <i class="fas fa-key mr-1"></i> Staff Terminal Login
                    </a>
                </div>
            </div>

            <!-- Module 3: Owner Retention CRM -->
            <div class="card p-6 border-slate-200 hover:-translate-y-1 transition duration-200 bg-slate-900 text-white">
                <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center font-bold text-xl mb-4 shadow-md shadow-amber-400/20">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-lg font-bold text-white">3. Owner Retention CRM</h3>
                <p class="text-xs text-slate-400 mt-1 mb-4">Track repeat visit rate %, identify at-risk customers, and send 1-click WhatsApp win-back messages.</p>
                <div class="space-y-2">
                    <a href="<?= Helper::url('login') ?>" class="w-full btn btn-sm bg-amber-400 text-slate-950 hover:bg-amber-300 font-bold">
                        <i class="fas fa-sign-in-alt mr-1"></i> Owner Portal Login
                    </a>
                    <a href="<?= Helper::url('install') ?>" class="w-full btn btn-secondary btn-sm bg-slate-800 text-slate-300 border-slate-700">
                        <i class="fas fa-database mr-1"></i> Database Setup
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
                Complete Loyalty Stack
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Everything local businesses need to <span class="gradient-text">multiply repeat visits</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card p-7">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Apple Wallet Style Pass</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Zero app download friction. Customers enter their mobile number at the counter and instantly get a sleek animated card in their browser.
                </p>
            </div>

            <div class="card p-7">
                <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-coins"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Visits + Reward Coins</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Combines habit-building visit stamps with spend-based cashback coins so customers who spend more earn higher rewards.
                </p>
            </div>

            <div class="card p-7">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">5-Second Counter Terminal</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Speed numeric phone keypad and instant camera QR scanner. Staff can check-in a customer and award points in under 3 seconds.
                </p>
            </div>

            <div class="card p-7">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-5">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">1-Click WhatsApp Win-Back</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Automatically detects customers absent for 30+ days. Generates one-click WhatsApp re-engagement offers to win them back.
                </p>
            </div>

            <div class="card p-7">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Auto Counter Tent Standee</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Generates a ready-to-print A5/A4 counter QR standee with your business logo and welcome perk to place right by the cash register.
                </p>
            </div>

            <div class="card p-7">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-5">
                    <i class="fas fa-crown"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Reseller & White-Label</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Full agency reseller portal. Onboard cafes, salons, and retail shops under your own agency brand and subscription plans.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-slate-950 text-white py-12 border-t border-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-pink-500 rounded-xl flex items-center justify-center font-black text-xs">
                <i class="fas fa-id-card"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tight">DigiLoyalty Pro</span>
        </div>

        <div class="flex items-center gap-6 text-xs text-slate-400">
            <a href="<?= Helper::url('register') ?>" class="hover:text-white transition">Launch Card</a>
            <a href="<?= Helper::url('staff/login') ?>" class="hover:text-white transition">Staff Terminal</a>
            <a href="<?= Helper::url('login') ?>" class="hover:text-white transition">Owner Login</a>
            <a href="<?= Helper::url('admin/login') ?>" class="hover:text-white transition">Reseller Admin</a>
            <a href="<?= Helper::url('install') ?>" class="text-indigo-400 hover:text-indigo-300 font-semibold">DB Setup</a>
        </div>

        <p class="text-xs text-slate-500">
            &copy; <?= date('Y') ?> DigiLoyalty Pro. All rights reserved.
        </p>
    </div>
</footer>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
