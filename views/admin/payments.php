<!-- Revenue Summary -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg p-4 border border-gray-200">
        <p class="text-sm text-gray-500">Total Revenue</p>
        <p class="text-xl font-bold text-gray-900"><?= format_inr($revenue['total']) ?></p>
    </div>
    <div class="bg-white rounded-lg p-4 border border-gray-200">
        <p class="text-sm text-gray-500">Today's Revenue</p>
        <p class="text-xl font-bold text-gray-900"><?= format_inr($revenue['today']) ?></p>
    </div>
    <div class="bg-white rounded-lg p-4 border border-gray-200">
        <p class="text-sm text-gray-500">This Month</p>
        <p class="text-xl font-bold text-gray-900"><?= format_inr($revenue['this_month']) ?></p>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">ID</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">User</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Order ID</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Amount</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($payments as $p): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-500">#<?= $p['id'] ?></td>
                    <td class="px-6 py-3">
                        <div class="font-medium text-gray-900"><?= e($p['user_name']) ?></div>
                        <div class="text-gray-500 text-xs"><?= e($p['user_email']) ?></div>
                    </td>
                    <td class="px-6 py-3 text-gray-500 font-mono text-xs"><?= e($p['razorpay_order_id']) ?></td>
                    <td class="px-6 py-3 font-medium text-gray-900"><?= format_inr($p['amount']) ?></td>
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            <?= match($p['status']) {
                                'captured' => 'bg-green-100 text-green-700',
                                'created' => 'bg-yellow-100 text-yellow-700',
                                'failed' => 'bg-red-100 text-red-700',
                                'refunded' => 'bg-gray-100 text-gray-700',
                                default => 'bg-gray-100 text-gray-700',
                            } ?>">
                            <?= ucfirst(e($p['status'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($payments)): ?>
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No payments yet</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php $pages = (int) ceil($total / 25); ?>
    <?php if ($pages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-500">Page <?= $page ?> of <?= $pages ?></span>
        <div class="flex gap-2">
            <?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>" class="px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">Previous</a><?php endif; ?>
            <?php if ($page < $pages): ?><a href="?page=<?= $page + 1 ?>" class="px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">Next</a><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
