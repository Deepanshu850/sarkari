<section class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-bold text-saffron-600 tracking-wider uppercase">Step 2 of 4</span>
                <span class="text-sm text-gray-500">Your Background</span>
            </div>
            <div class="w-full bg-gold-100 rounded-full h-2.5">
                <div class="bg-saffron-500 h-2.5 rounded-full transition-all shadow-sm" style="width: 50%"></div>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-[10px] text-india-500 font-bold">EXAM &#10003;</span>
                <span class="text-[10px] text-saffron-500 font-bold">BACKGROUND</span>
                <span class="text-[10px] text-gray-400">SCHEDULE</span>
                <span class="text-[10px] text-gray-400">REVIEW</span>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Apne baare mein batayen</h1>
            <p class="text-gray-500 mt-2">Preparing for <span class="font-bold text-saffron-600"><?= e($draft['exam_name']) ?></span></p>
        </div>

        <form method="POST" action="/blueprint/step2" class="max-w-2xl mx-auto">
            <?= csrf_field() ?>

            <!-- Education Level -->
            <div class="paper rounded-xl p-6 mb-6">
                <h2 class="font-bold text-navy-600 mb-1">Education Level</h2>
                <p class="text-xs text-gray-500 mb-4">Aapki highest qualification</p>
                <div class="grid grid-cols-2 gap-3">
                    <?php
                    $educations = [
                        ['val' => '10th Pass', 'emoji' => '📕'],
                        ['val' => '12th Pass', 'emoji' => '📗'],
                        ['val' => 'Graduate', 'emoji' => '🎓'],
                        ['val' => 'Post-Graduate', 'emoji' => '🏅'],
                    ];
                    foreach ($educations as $edu):
                    ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="education" value="<?= e($edu['val']) ?>" class="peer hidden"
                            <?= ($draft['education'] ?? '') === $edu['val'] ? 'checked' : '' ?>>
                        <div class="p-3.5 border-2 border-gold-100 bg-white rounded-xl text-center font-semibold text-navy-600 peer-checked:border-saffron-500 peer-checked:bg-saffron-50 peer-checked:ring-2 peer-checked:ring-saffron-500/20 hover:border-gold-300 transition-all duration-200">
                            <span class="text-xl block mb-1"><?= $edu['emoji'] ?></span>
                            <span class="text-sm"><?= e($edu['val']) ?></span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Weak Subjects -->
            <div class="paper rounded-xl p-6 mb-6">
                <h2 class="font-bold text-navy-600 mb-1">Weak Subjects</h2>
                <p class="text-xs text-gray-500 mb-4">Kaun se subjects mein zyada practice chahiye? (Select all that apply)</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <?php foreach ($subjects as $subject): ?>
                    <label class="cursor-pointer flex items-center gap-3 p-3 bg-white border border-gold-100 rounded-lg hover:bg-cream transition-all duration-200 has-[:checked]:border-saffron-400 has-[:checked]:bg-saffron-50 has-[:checked]:ring-1 has-[:checked]:ring-saffron-400/30">
                        <input type="checkbox" name="weak_subjects[]" value="<?= e($subject['name']) ?>"
                            class="w-5 h-5 text-saffron-500 border-gold-300 rounded focus:ring-saffron-500"
                            <?php if (in_array($subject['name'], $draft['weak_subjects'] ?? [])) echo 'checked'; ?>>
                        <span class="text-sm font-medium text-navy-600"><?= e($subject['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="/blueprint/step1" class="px-5 py-2.5 text-gray-500 hover:text-navy-600 font-medium flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Back
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20 flex items-center gap-2">
                    Aage Badhein
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>
        </form>
    </div>
</section>
