<section class="py-8 md:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Mere Blueprints</h1>
                <p class="text-gray-500 mt-1">Namaste, <span class="font-semibold text-navy-600"><?= e(auth()['name']) ?></span>!</p>
            </div>
            <a href="/blueprint/step1"
                class="inline-flex items-center gap-2 px-6 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Naya Blueprint
            </a>
        </div>

        <?php if (empty($blueprints)): ?>
        <!-- Empty State -->
        <div class="paper rounded-2xl p-12 text-center paper-shadow watermark">
            <div class="w-20 h-20 bg-gold-50 border-2 border-gold-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h2 class="font-display text-xl font-bold text-navy-600 mb-2">Abhi tak koi blueprint nahi</h2>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Apna pehla personalized exam blueprint banayein aur success ki raah shuru karein!</p>
            <a href="/blueprint/step1"
                class="inline-flex items-center gap-2 px-8 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">
                Pehla Blueprint Banayein
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
        <?php else: ?>
        <!-- Blueprint Cards -->
        <div class="grid gap-4">
            <?php foreach ($blueprints as $bp): ?>
            <div class="paper rounded-xl p-5 md:p-6 hover:shadow-md transition-all duration-200 border-l-4
                <?= match($bp['status']) {
                    'ready' => 'border-l-india-500',
                    'generating' => 'border-l-saffron-500',
                    'failed' => 'border-l-red-500',
                    default => 'border-l-gold-400',
                } ?>">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                            <?= match($bp['status']) {
                                'ready' => 'bg-india-50 border border-india-200 text-india-600',
                                'generating' => 'bg-saffron-50 border border-saffron-200 text-saffron-600',
                                'failed' => 'bg-red-50 border border-red-200 text-red-600',
                                default => 'bg-gold-50 border border-gold-200 text-gold-600',
                            } ?>">
                            <?php if ($bp['status'] === 'ready'): ?>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php elseif ($bp['status'] === 'generating'): ?>
                                <div class="chakra-spinner" style="border-top-color: #FF6B00;"></div>
                            <?php elseif ($bp['status'] === 'failed'): ?>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php else: ?>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="font-bold text-navy-600"><?= e($bp['exam_name']) ?></h3>
                            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-sm">
                                <span class="text-gray-400 text-xs"><?= e($bp['exam_category']) ?></span>
                                <span class="w-1 h-1 bg-gold-300 rounded-full"></span>
                                <span class="text-gray-400 text-xs"><?= date('d M Y', strtotime($bp['created_at'])) ?></span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold tracking-wide uppercase
                                    <?= match($bp['status']) {
                                        'ready' => 'bg-india-50 text-india-700 border border-india-200',
                                        'generating' => 'bg-saffron-50 text-saffron-700 border border-saffron-200',
                                        'failed' => 'bg-red-50 text-red-700 border border-red-200',
                                        default => 'bg-gold-50 text-gold-700 border border-gold-200',
                                    } ?>">
                                    <?= ucfirst(str_replace('_', ' ', e($bp['status']))) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:flex-shrink-0">
                        <?php if ($bp['status'] === 'ready'): ?>
                            <a href="/blueprint/view/<?= $bp['id'] ?>"
                                class="px-4 py-2 bg-navy-50 text-navy-700 border border-navy-200 rounded-lg font-semibold text-sm hover:bg-navy-100 transition">
                                View
                            </a>
                            <a href="/blueprint/download/<?= $bp['id'] ?>"
                                class="px-4 py-2 bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-lg font-semibold text-sm hover:bg-saffron-100 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                PDF
                            </a>
                        <?php elseif ($bp['status'] === 'failed'): ?>
                            <a href="/blueprint/retry/<?= $bp['id'] ?>"
                                class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg font-semibold text-sm hover:bg-red-100 transition">
                                Retry
                            </a>
                        <?php elseif ($bp['status'] === 'generating'): ?>
                            <span class="px-4 py-2 text-saffron-600 text-sm font-semibold flex items-center gap-2">
                                <div class="chakra-spinner" style="width:16px;height:16px;border-width:2px;border-top-color:#FF6B00"></div>
                                Generating...
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
