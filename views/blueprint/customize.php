<section class="py-8 md:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Banner -->
        <div class="bg-india-50 border border-india-200 rounded-xl p-4 mb-8 flex items-center gap-3">
            <div class="w-10 h-10 bg-india-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-india-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <p class="font-bold text-india-700">Payment Successful!</p>
                <p class="text-sm text-india-600">Ab apna blueprint personalize karein - sirf 30 seconds lagenge</p>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Apna Blueprint Customize Karein</h1>
            <p class="text-gray-500 mt-2">
                <span class="font-semibold text-saffron-600"><?= e($blueprint['exam_name']) ?></span> ke liye
            </p>
        </div>

        <form method="POST" action="/customize/<?= $blueprint['id'] ?>">
            <?= csrf_field() ?>

            <!-- Education -->
            <div class="paper rounded-xl p-6 mb-5">
                <h2 class="font-bold text-navy-600 mb-3">Aapki Education</h2>
                <div class="grid grid-cols-2 gap-3">
                    <?php foreach (['10th Pass' => '📕', '12th Pass' => '📗', 'Graduate' => '🎓', 'Post-Graduate' => '🏅'] as $edu => $emoji): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="education" value="<?= e($edu) ?>" class="peer hidden" <?= $edu === 'Graduate' ? 'checked' : '' ?>>
                        <div class="p-3 border-2 border-gold-100 bg-white rounded-xl text-center font-semibold text-navy-600 peer-checked:border-saffron-500 peer-checked:bg-saffron-50 hover:border-gold-300 transition-all">
                            <span class="text-xl block mb-1"><?= $emoji ?></span>
                            <span class="text-sm"><?= e($edu) ?></span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Weak Subjects -->
            <div class="paper rounded-xl p-6 mb-5">
                <h2 class="font-bold text-navy-600 mb-1">Weak Subjects</h2>
                <p class="text-xs text-gray-500 mb-4">Kaun se subjects mein zyada help chahiye?</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <?php foreach ($subjects as $i => $subject): ?>
                    <label class="cursor-pointer flex items-center gap-3 p-3 bg-white border border-gold-100 rounded-lg hover:bg-cream transition has-[:checked]:border-saffron-400 has-[:checked]:bg-saffron-50">
                        <input type="checkbox" name="weak_subjects[]" value="<?= e($subject['name']) ?>"
                            class="w-5 h-5 text-saffron-500 border-gold-300 rounded focus:ring-saffron-500"
                            <?= $i < 2 ? 'checked' : '' ?>>
                        <span class="text-sm font-medium text-navy-600"><?= e($subject['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Study Hours + Exam Date -->
            <div class="paper rounded-xl p-6 mb-5">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="font-bold text-navy-600 mb-2">Daily Study Hours</h2>
                        <input type="range" name="study_hours" id="studyHours" min="1" max="14" step="0.5" value="4"
                            class="w-full h-2.5 rounded-lg cursor-pointer mb-2">
                        <div class="text-center">
                            <span class="text-3xl font-black text-saffron-500" id="hoursDisplay">4</span>
                            <span class="text-gray-500 text-sm ml-1">hours/day</span>
                        </div>
                    </div>
                    <div>
                        <h2 class="font-bold text-navy-600 mb-2">Exam Date</h2>
                        <input type="date" name="exam_date"
                            value="<?= date('Y-m-d', strtotime('+60 days')) ?>"
                            min="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                            class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 text-navy-600 font-semibold">
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full py-4 bg-saffron-500 text-white rounded-xl font-bold text-lg hover:bg-saffron-600 transition shadow-xl shadow-saffron-500/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Generate Mera Blueprint
            </button>
            <p class="text-center text-xs text-gray-400 mt-3">Blueprint 30 seconds mein ready ho jayega</p>
        </form>
    </div>
</section>

<script>
document.getElementById('studyHours').addEventListener('input', function() {
    document.getElementById('hoursDisplay').textContent = this.value;
});
</script>
