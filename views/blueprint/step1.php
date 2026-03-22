<section class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-bold text-saffron-600 tracking-wider uppercase">Step 1 of 3</span>
                <span class="text-sm text-gray-500">Exam Selection</span>
            </div>
            <div class="w-full bg-gold-100 rounded-full h-2.5">
                <div class="bg-saffron-500 h-2.5 rounded-full transition-all shadow-sm" style="width: 33%"></div>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-[10px] text-saffron-500 font-bold">EXAM</span>
                <span class="text-[10px] text-gray-400">BACKGROUND</span>
                <span class="text-[10px] text-gray-400">GENERATE</span>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Kaun sa exam de rahe hain?</h1>
            <p class="text-gray-500 mt-2">Select your target government examination</p>
        </div>

        <form method="POST" action="/blueprint/step1" id="step1Form">
            <?= csrf_field() ?>
            <input type="hidden" name="exam_id" id="selectedExam" value="">

            <?php foreach ($examGroups as $category => $exams): ?>
            <div class="mb-8">
                <h2 class="text-base font-bold text-navy-600 mb-4 flex items-center gap-3">
                    <span class="w-8 h-8 bg-gold-50 border border-gold-200 rounded-lg flex items-center justify-center text-xs font-bold text-gold-700">
                        <?= e(substr($category, 0, 2)) ?>
                    </span>
                    <?= e($category) ?>
                    <span class="text-xs text-gray-400 font-normal">(<?= count($exams) ?> exams)</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <?php foreach ($exams as $exam): ?>
                    <label class="exam-card cursor-pointer block" data-exam-id="<?= $exam['id'] ?>">
                        <div class="p-4 bg-white border-2 border-gold-100 rounded-xl hover:border-saffron-300 hover:bg-saffron-50/30 transition-all duration-200 group"
                             id="examCard<?= $exam['id'] ?>">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-cream border border-gold-200 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-saffron-100 group-hover:border-saffron-300 transition">
                                    <svg class="w-5 h-5 text-gold-600 group-hover:text-saffron-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-navy-600 text-sm"><?= e($exam['name']) ?></h3>
                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2"><?= e($exam['description']) ?></p>
                                </div>
                            </div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="flex justify-end mt-6">
                <button type="submit" id="step1Btn" disabled
                    class="px-8 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20 disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none flex items-center gap-2">
                    Aage Badhein
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
document.querySelectorAll('.exam-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.exam-card > div').forEach(d => {
            d.classList.remove('border-saffron-500', 'bg-saffron-50', 'ring-2', 'ring-saffron-500/20');
            d.classList.add('border-gold-100');
        });
        const inner = this.querySelector('div');
        inner.classList.remove('border-gold-100');
        inner.classList.add('border-saffron-500', 'bg-saffron-50', 'ring-2', 'ring-saffron-500/20');
        document.getElementById('selectedExam').value = this.dataset.examId;
        document.getElementById('step1Btn').disabled = false;
    });
});
</script>
