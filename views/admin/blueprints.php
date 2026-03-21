<!-- Filters -->
<div class="mb-6 flex gap-3">
    <a href="/admin/blueprints" class="px-4 py-2 rounded-lg text-sm font-medium <?= !$status ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">All</a>
    <a href="/admin/blueprints?status=ready" class="px-4 py-2 rounded-lg text-sm font-medium <?= $status === 'ready' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">Ready</a>
    <a href="/admin/blueprints?status=generating" class="px-4 py-2 rounded-lg text-sm font-medium <?= $status === 'generating' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">Generating</a>
    <a href="/admin/blueprints?status=failed" class="px-4 py-2 rounded-lg text-sm font-medium <?= $status === 'failed' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">Failed</a>
    <a href="/admin/blueprints?status=pending_payment" class="px-4 py-2 rounded-lg text-sm font-medium <?= $status === 'pending_payment' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">Pending</a>
</div>

<!-- Blueprints Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">ID</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">User</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Exam</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Study Hrs</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Exam Date</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($blueprints as $bp): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-500">#<?= $bp['id'] ?></td>
                    <td class="px-6 py-3">
                        <div class="font-medium text-gray-900"><?= e($bp['user_name']) ?></div>
                        <div class="text-gray-500 text-xs"><?= e($bp['user_email']) ?></div>
                    </td>
                    <td class="px-6 py-3 text-gray-700"><?= e($bp['exam_name']) ?></td>
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            <?= match($bp['status']) {
                                'ready' => 'bg-green-100 text-green-700',
                                'generating' => 'bg-blue-100 text-blue-700',
                                'failed' => 'bg-red-100 text-red-700',
                                default => 'bg-yellow-100 text-yellow-700',
                            } ?>">
                            <?= ucfirst(str_replace('_', ' ', e($bp['status']))) ?>
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500"><?= e($bp['study_hours']) ?>h</td>
                    <td class="px-6 py-3 text-gray-500"><?= date('d M Y', strtotime($bp['exam_date'])) ?></td>
                    <td class="px-6 py-3 text-gray-500"><?= date('d M Y H:i', strtotime($bp['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($blueprints)): ?>
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No blueprints found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php $pages = (int) ceil($total / 25); ?>
    <?php if ($pages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-500">Page <?= $page ?> of <?= $pages ?> (<?= $total ?> total)</span>
        <div class="flex gap-2">
            <?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>&status=<?= urlencode($status) ?>" class="px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">Previous</a><?php endif; ?>
            <?php if ($page < $pages): ?><a href="?page=<?= $page + 1 ?>&status=<?= urlencode($status) ?>" class="px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">Next</a><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
