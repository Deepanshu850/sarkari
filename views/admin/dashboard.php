<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl p-5 border border-gold-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-bl-[40px]"></div>
        <div class="relative">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Revenue</p>
            <p class="text-2xl font-black text-navy-600 mt-2"><?= format_inr($stats['revenue']['total']) ?></p>
            <div class="mt-3 flex items-center gap-3 text-xs">
                <span class="text-gray-500">Today: <strong class="text-navy-600"><?= format_inr($stats['revenue']['today']) ?></strong></span>
                <span class="w-1 h-1 bg-gold-300 rounded-full"></span>
                <span class="text-gray-500">Month: <strong class="text-navy-600"><?= format_inr($stats['revenue']['this_month']) ?></strong></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gold-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-[40px]"></div>
        <div class="relative">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Users</p>
            <p class="text-2xl font-black text-navy-600 mt-2"><?= number_format($stats['users']) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gold-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-purple-50 rounded-bl-[40px]"></div>
        <div class="relative">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Blueprints Ready</p>
            <p class="text-2xl font-black text-navy-600 mt-2"><?= number_format($stats['blueprints_ready']) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gold-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-orange-50 rounded-bl-[40px]"></div>
        <div class="relative">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Payments</p>
            <p class="text-2xl font-black text-navy-600 mt-2"><?= number_format($stats['revenue']['count']) ?></p>
        </div>
    </div>
</div>

<!-- Recent Blueprints -->
<div class="bg-white rounded-xl border border-gold-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gold-100 flex items-center justify-between">
        <h2 class="font-bold text-navy-600">Recent Blueprints</h2>
        <a href="/admin/blueprints" class="text-xs text-saffron-600 font-semibold hover:text-saffron-700">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-cream">
                <tr>
                    <th class="text-left px-6 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider">User</th>
                    <th class="text-left px-6 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider">Exam</th>
                    <th class="text-left px-6 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gold-50">
                <?php foreach ($recentBlueprints as $bp): ?>
                <tr class="hover:bg-cream/50">
                    <td class="px-6 py-3">
                        <div class="font-semibold text-navy-600"><?= e($bp['user_name']) ?></div>
                        <div class="text-gray-400 text-xs"><?= e($bp['user_email']) ?></div>
                    </td>
                    <td class="px-6 py-3 text-gray-600"><?= e($bp['exam_name']) ?></td>
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase
                            <?= match($bp['status']) {
                                'ready' => 'bg-green-50 text-green-700 border border-green-200',
                                'generating' => 'bg-orange-50 text-orange-700 border border-orange-200',
                                'failed' => 'bg-red-50 text-red-700 border border-red-200',
                                default => 'bg-gold-50 text-gold-700 border border-gold-200',
                            } ?>">
                            <?= ucfirst(e($bp['status'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400 text-xs"><?= date('d M Y H:i', strtotime($bp['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recentBlueprints)): ?>
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No blueprints yet</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
