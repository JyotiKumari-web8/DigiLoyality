<?php
$pageTitle = 'Staff Accounts — ' . $business['name'];
require APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen bg-slate-50 pb-12">
    <header class="bg-white border-b border-slate-200 px-4 py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div>
                <a href="<?= Helper::url('owner/dashboard') ?>" class="text-xs text-indigo-600 font-semibold hover:underline flex items-center gap-1 mb-1">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <h1 class="text-xl font-extrabold text-slate-900">Cashier & Staff Accounts</h1>
                <p class="text-xs text-slate-500">Staff members can log in at the counter to scan customer cards without seeing revenue data</p>
            </div>
            <a href="<?= Helper::url('staff/terminal') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-cash-register mr-1"></i> Open Terminal
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8 space-y-8">
        
        <!-- Add Staff Form -->
        <div class="card p-6 border-slate-200">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-user-plus text-indigo-500"></i> Add New Staff Member
            </h3>

            <form method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <input type="hidden" name="action" value="create">
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Staff Name</label>
                    <input type="text" name="name" required placeholder="e.g. Rahul Cashier"
                           class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Login Email</label>
                    <input type="email" name="email" required placeholder="cashier@store.com"
                           class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">4-Digit PIN</label>
                    <input type="password" name="pin" required maxlength="6" placeholder="1234"
                           class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm font-mono text-center outline-none focus:border-indigo-500">
                </div>

                <div>
                    <button type="submit" class="w-full btn btn-primary py-2 text-sm">
                        <i class="fas fa-plus mr-1"></i> Add Staff
                    </button>
                </div>
            </form>
        </div>

        <!-- Staff List -->
        <div class="card p-0 overflow-hidden border-slate-200">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-sm font-bold text-slate-900">Active Staff Accounts</h3>
                <span class="text-xs text-slate-500"><?= count($staffList) ?> members</span>
            </div>

            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100/60 border-b border-slate-200 text-slate-500 uppercase text-[11px] font-bold">
                        <th class="py-3 px-6">Name</th>
                        <th class="py-3 px-6">Login Email</th>
                        <th class="py-3 px-6">PIN</th>
                        <th class="py-3 px-6">Stamps Logged</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($staffList)): ?>
                        <?php foreach ($staffList as $st): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-6 font-bold text-slate-900"><?= htmlspecialchars($st['name']) ?></td>
                                <td class="py-3.5 px-6 font-mono text-slate-600"><?= htmlspecialchars($st['email']) ?></td>
                                <td class="py-3.5 px-6 font-mono text-slate-400">&bull;&bull;&bull;&bull;</td>
                                <td class="py-3.5 px-6 font-bold text-indigo-600"><?= (int)$st['stamps_given'] ?> visits</td>
                                <td class="py-3.5 px-6">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $st['is_active'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' ?>">
                                        <?= $st['is_active'] ? 'Active' : 'Disabled' ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="staff_id" value="<?= $st['id'] ?>">
                                        <button type="submit" class="text-xs text-slate-500 hover:text-indigo-600 font-semibold">
                                            <?= $st['is_active'] ? 'Deactivate' : 'Activate' ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-8 text-slate-400">No staff members created yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require APP_ROOT . '/views/layout/footer.php'; ?>
