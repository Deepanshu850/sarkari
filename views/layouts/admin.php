<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> | <?= e(APP_NAME) ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        saffron: { 50:'#FFF3E8',500:'#FF6B00',600:'#E55A00' },
                        navy: { 50:'#E8EBF0',100:'#C5CCD9',200:'#8B99B3',300:'#52668D',400:'#2A4070',500:'#1A2E56',600:'#0C1B3A' },
                        india: { 50:'#E8F5EE',500:'#046A38' },
                        gold: { 50:'#FBF6EC',100:'#F5ECD8',200:'#EBD9B1',400:'#D4B363',500:'#C7973B',600:'#A67D2F' },
                        cream: '#FBF8F1',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap');
        body { font-family: 'Noto Sans', sans-serif; }
        .tricolor-bar { height: 4px; background: linear-gradient(90deg, #FF6B00 0%, #FF6B00 33.33%, #FFFFFF 33.33%, #FFFFFF 66.66%, #046A38 66.66%, #046A38 100%); }
    </style>
</head>
<body class="bg-cream min-h-screen">
    <div class="tricolor-bar"></div>
    <div class="flex">
        <!-- Sidebar -->
        <aside class="hidden md:flex flex-col w-64 min-h-screen bg-navy-600 text-white">
            <div class="px-6 py-5 border-b border-white/10">
                <a href="/admin" class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full border border-gold-500/50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gold-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L13.09 8.26L20 9L14.14 13.14L15.82 20L12 16.27L8.18 20L9.86 13.14L4 9L10.91 8.26L12 2Z"/></svg>
                    </div>
                    <div>
                        <span class="font-bold tracking-wide">SARKARI</span>
                        <span class="block text-[9px] text-gold-400 tracking-[0.15em] uppercase">Admin Panel</span>
                    </div>
                </a>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <?php
                $navItems = [
                    ['/admin', 'Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['/admin/users', 'Users', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['/admin/blueprints', 'Blueprints', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['/admin/payments', 'Payments', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                ];
                foreach ($navItems as [$href, $label, $icon]):
                    $active = current_path() === $href;
                ?>
                <a href="<?= $href ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?= $active ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' ?> transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/></svg>
                    <?= $label ?>
                </a>
                <?php endforeach; ?>
            </nav>
            <div class="px-4 py-4 border-t border-white/10">
                <a href="/" class="flex items-center gap-2 text-gray-400 hover:text-white transition text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Back to Site
                </a>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 min-h-screen">
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between border-b border-gold-100">
                <h1 class="text-lg font-bold text-navy-600"><?= e($pageTitle ?? 'Admin Dashboard') ?></h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">Namaste, <strong class="text-navy-600"><?= e(auth()['name'] ?? 'Admin') ?></strong></span>
                    <a href="/logout" class="text-sm text-red-500 hover:text-red-700 font-medium">Logout</a>
                </div>
            </header>

            <!-- Mobile nav -->
            <div class="md:hidden bg-white border-b border-gold-100 px-4 py-2 flex gap-3 overflow-x-auto">
                <?php foreach ($navItems as [$href, $label, $icon]):
                    $active = current_path() === $href;
                ?>
                <a href="<?= $href ?>" class="text-sm font-medium whitespace-nowrap px-3 py-1.5 rounded-lg <?= $active ? 'bg-navy-600 text-white' : 'text-gray-500 hover:bg-gray-100' ?>">
                    <?= $label ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Flash Messages -->
            <?php if ($msg = flash('success')): ?>
                <div class="mx-6 mt-4 bg-india-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm font-medium"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($msg = flash('error')): ?>
                <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm font-medium"><?= e($msg) ?></div>
            <?php endif; ?>

            <div class="p-6"><?= $content ?></div>
        </div>
    </div>
</body>
</html>
