<!-- Search -->
<div class="mb-6">
    <form method="GET" action="/admin/users" class="flex gap-3">
        <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search by name or email..."
            class="flex-1 max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800">Search</button>
        <?php if ($search): ?>
            <a href="/admin/users" class="px-4 py-2 text-gray-600 hover:text-gray-900 text-sm font-medium">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">ID</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Name</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Email</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Phone</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Role</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($result['data'] as $user): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-500">#<?= $user['id'] ?></td>
                    <td class="px-6 py-3 font-medium text-gray-900"><?= e($user['name']) ?></td>
                    <td class="px-6 py-3 text-gray-700"><?= e($user['email']) ?></td>
                    <td class="px-6 py-3 text-gray-500"><?= e($user['phone'] ?? '-') ?></td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' ?>">
                            <?= ucfirst(e($user['role'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($result['data'])): ?>
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($result['pages'] > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-500">Showing page <?= $result['current_page'] ?> of <?= $result['pages'] ?> (<?= $result['total'] ?> total)</span>
        <div class="flex gap-2">
            <?php if ($result['current_page'] > 1): ?>
                <a href="?page=<?= $result['current_page'] - 1 ?>&search=<?= urlencode($search) ?>" class="px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">Previous</a>
            <?php endif; ?>
            <?php if ($result['current_page'] < $result['pages']): ?>
                <a href="?page=<?= $result['current_page'] + 1 ?>&search=<?= urlencode($search) ?>" class="px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
