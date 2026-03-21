<section class="py-8 md:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="paper rounded-2xl overflow-hidden mb-8 paper-shadow">
            <div class="gradient-navy p-6 md:p-8 text-white relative">
                <!-- Decorative -->
                <div class="absolute top-3 right-3 opacity-10">
                    <svg class="w-24 h-24 text-gold-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L13.09 8.26L20 9L14.14 13.14L15.82 20L12 16.27L8.18 20L9.86 13.14L4 9L10.91 8.26L12 2Z"/></svg>
                </div>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="badge-official text-[9px]"><?= e($blueprint['exam_category']) ?></span>
                            <span class="badge-official text-[9px]">AI Generated</span>
                        </div>
                        <h1 class="font-display text-2xl md:text-3xl font-bold"><?= e($blueprint['exam_name']) ?> Blueprint</h1>
                        <p class="text-gray-300 mt-1 text-sm">Generated on <?= date('d M Y', strtotime($blueprint['generated_at'])) ?></p>
                    </div>
                    <a href="/blueprint/download/<?= $blueprint['id'] ?>"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF
                    </a>
                </div>
            </div>

            <!-- Summary -->
            <?php if (!empty($blueprint['summary'])): ?>
            <div class="p-6 bg-saffron-50 border-b border-gold-200">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-saffron-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-saffron-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-navy-600 text-sm mb-1">Strategy Overview</h2>
                        <p class="text-gray-600 text-sm leading-relaxed"><?= e($blueprint['summary']) ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-gold-100">
                <div class="p-4 text-center">
                    <p class="text-2xl font-black text-saffron-500"><?= count($days) ?></p>
                    <p class="text-xs text-gray-500 font-medium">Days</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-2xl font-black text-navy-600"><?= e($blueprint['study_hours']) ?>h</p>
                    <p class="text-xs text-gray-500 font-medium">Hours/Day</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-2xl font-black text-india-500"><?= e($blueprint['education']) ?></p>
                    <p class="text-xs text-gray-500 font-medium">Education</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-2xl font-black text-gold-600"><?= date('d M', strtotime($blueprint['exam_date'])) ?></p>
                    <p class="text-xs text-gray-500 font-medium">Exam Date</p>
                </div>
            </div>
        </div>

        <!-- Focus Areas -->
        <div class="mb-6 flex flex-wrap items-center gap-2">
            <span class="text-sm font-bold text-navy-600">Focus Areas:</span>
            <?php foreach (json_decode($blueprint['weak_subjects'], true) as $sub): ?>
            <span class="px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded text-xs font-semibold"><?= e($sub) ?></span>
            <?php endforeach; ?>
        </div>

        <!-- Day-by-Day Plan -->
        <div class="space-y-3">
            <?php foreach ($days as $day):
                $n = $day['day_number'];
                if ($n <= 10) { $dayColor = 'saffron'; $badgeBg = 'gradient-saffron'; }
                elseif ($n <= 20) { $dayColor = 'navy'; $badgeBg = 'gradient-navy'; }
                else { $dayColor = 'india'; $badgeBg = 'bg-india-500'; }
            ?>
            <details class="paper rounded-xl group" <?= $n <= 3 ? 'open' : '' ?>>
                <summary class="flex items-center gap-4 p-4 md:p-5 cursor-pointer list-none select-none">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-white text-xs <?= $badgeBg ?> shadow-sm">
                        Day<br><?= $n ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-navy-600 truncate"><?= e($day['title']) ?></h3>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <?php foreach (json_decode($day['subjects_json'], true) as $sub): ?>
                            <span class="text-xs text-gray-500 bg-cream px-2 py-0.5 rounded border border-gold-100"><?= e($sub['subject'] ?? '') ?> (<?= e($sub['hours'] ?? '') ?>h)</span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gold-400 group-open:rotate-180 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>

                <div class="px-4 md:px-5 pb-5 border-t border-gold-100 pt-4">
                    <!-- Subjects -->
                    <div class="space-y-3 mb-4">
                        <?php foreach (json_decode($day['subjects_json'], true) as $sub): ?>
                        <div class="flex items-start gap-3 p-3 bg-cream rounded-lg border border-gold-100">
                            <div class="w-8 h-8 bg-<?= $dayColor ?>-50 text-<?= $dayColor ?>-600 border border-<?= $dayColor ?>-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-navy-600 text-sm"><?= e($sub['subject'] ?? '') ?></h4>
                                    <span class="text-xs text-saffron-600 font-bold bg-saffron-50 px-2 py-0.5 rounded"><?= e($sub['hours'] ?? '') ?> hrs</span>
                                </div>
                                <?php if (!empty($sub['topics'])): ?>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <?php foreach ($sub['topics'] as $topic): ?>
                                    <span class="px-2 py-0.5 bg-white text-gray-600 rounded text-xs border border-gold-100"><?= e($topic) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Tips -->
                    <?php if (!empty($day['tips'])): ?>
                    <div class="p-3 bg-gold-50 border border-gold-200 rounded-lg mb-4">
                        <div class="flex items-start gap-2">
                            <span class="text-lg">💡</span>
                            <p class="text-sm text-gold-900"><?= e($day['tips']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Resources -->
                    <?php
                    $resources = json_decode($day['resources'] ?? '[]', true);
                    if (!empty($resources)):
                    ?>
                    <div>
                        <h4 class="text-xs font-bold text-navy-600 uppercase tracking-wider mb-2">Recommended Resources</h4>
                        <div class="space-y-1.5">
                            <?php foreach ($resources as $res): ?>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="px-2 py-0.5 bg-navy-50 text-navy-600 border border-navy-200 rounded text-[10px] uppercase font-bold tracking-wider"><?= e($res['type'] ?? 'book') ?></span>
                                <span class="text-gray-600 text-xs"><?= e($res['title'] ?? '') ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>

        <!-- Bottom Actions -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/blueprint/download/<?= $blueprint['id'] ?>"
                class="inline-flex items-center gap-2 px-8 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </a>
            <a href="/dashboard" class="text-gray-500 hover:text-navy-600 font-medium transition">Back to Dashboard</a>
        </div>
    </div>
</section>
