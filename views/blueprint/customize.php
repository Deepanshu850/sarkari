<section class="py-8 md:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center gap-0">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-india-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-india-600 hidden sm:inline">Payment Done</span>
                </div>
                <div class="w-8 sm:w-12 h-1 bg-india-300 mx-1 flex-shrink-0"></div>
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-saffron-500 flex items-center justify-center flex-shrink-0 shadow-sm ring-4 ring-saffron-200">
                        <span class="text-white font-black text-sm">2</span>
                    </div>
                    <span class="text-sm font-bold text-saffron-600 hidden sm:inline">Personalize</span>
                </div>
                <div class="w-8 sm:w-12 h-1 bg-gray-200 mx-1 flex-shrink-0"></div>
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-400 font-black text-sm">3</span>
                    </div>
                    <span class="text-sm font-medium text-gray-400 hidden sm:inline">Blueprint Ready!</span>
                </div>
            </div>
        </div>

        <!-- Success Banner -->
        <?php if (empty($isEdit)): ?>
        <div class="bg-india-50 border border-india-200 rounded-xl p-4 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-india-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-india-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <p class="font-bold text-india-700">Payment Successful!</p>
                <p class="text-sm text-india-600">Ab kuch sawaal jawab do — AI aapke liye HYPER-PERSONALIZED plan banayega</p>
            </div>
        </div>
        <?php endif; ?>

        <div class="text-center mb-6">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">
                <?= !empty($isEdit) ? 'Edit &amp; Regenerate Blueprint' : 'Apni Taiyari Ko Samjhao' ?>
            </h1>
            <p class="text-gray-500 mt-2">
                <span class="font-semibold text-saffron-600"><?= e($blueprint['exam_name']) ?></span> — Jitna sahi bataaoge, utna better plan milega
            </p>
            <?php if (!empty($isEdit)): ?>
            <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 inline-block mt-2">
                Preferences update ho jayengi aur blueprint dobara generate hoga.
            </p>
            <?php endif; ?>
        </div>

        <form method="POST" action="/customize/<?= $blueprint['id'] ?>">
            <?= csrf_field() ?>
            <?php if (!empty($isEdit)): ?>
            <input type="hidden" name="is_edit" value="1">
            <?php endif; ?>

            <!-- Q1: Education -->
            <div class="paper rounded-xl p-5 mb-4">
                <h2 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 bg-saffron-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">1</span>
                    Aapki Education
                </h2>
                <p class="text-xs text-gray-500 mb-3 ml-8">Kya padhe hain abhi tak?</p>
                <div class="grid grid-cols-2 gap-2 ml-8">
                    <?php foreach (['10th Pass' => '📕', '12th Pass' => '📗', 'Graduate' => '🎓', 'Post-Graduate' => '🏅'] as $edu => $emoji): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="education" value="<?= e($edu) ?>" class="peer hidden" <?= $edu === 'Graduate' ? 'checked' : '' ?>>
                        <div class="p-2.5 border-2 border-gold-100 bg-white rounded-xl text-center font-semibold text-navy-600 peer-checked:border-saffron-500 peer-checked:bg-saffron-50 hover:border-gold-300 transition-all text-sm">
                            <span class="text-lg block"><?= $emoji ?></span>
                            <?= e($edu) ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Q2: Weak Subjects -->
            <div class="paper rounded-xl p-5 mb-4">
                <h2 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 bg-saffron-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">2</span>
                    Sabse Weak Subjects
                </h2>
                <p class="text-xs text-gray-500 mb-3 ml-8">In subjects mein sabse zyada problem hoti hai (2-3 select karo)</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ml-8">
                    <?php foreach ($subjects as $i => $subject): ?>
                    <label class="cursor-pointer flex items-center gap-2.5 p-2.5 bg-white border border-gold-100 rounded-lg hover:bg-cream transition has-[:checked]:border-saffron-400 has-[:checked]:bg-saffron-50 text-sm">
                        <input type="checkbox" name="weak_subjects[]" value="<?= e($subject['name']) ?>"
                            class="w-4 h-4 text-saffron-500 border-gold-300 rounded focus:ring-saffron-500"
                            <?= $i < 2 ? 'checked' : '' ?>>
                        <span class="font-medium text-navy-600"><?= e($subject['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Q3: Attempt History — key diagnostic -->
            <div class="paper rounded-xl p-5 mb-4">
                <h2 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 bg-saffron-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">3</span>
                    Ye Exam Pehle Diya Hai?
                </h2>
                <p class="text-xs text-gray-500 mb-3 ml-8">Pehle attempt se AI ko pata chalega aap kahan hain</p>
                <div class="space-y-2 ml-8">
                    <?php foreach ([
                        'first_time' => '🆕 Pehli baar — abhi shuru kar raha/rahi hoon',
                        'attempted_once' => '1️⃣ Ek baar diya, qualify nahi hua',
                        'attempted_multiple' => '🔄 2+ baar diya, abhi tak result nahi aaya',
                        'prelims_cleared' => '✅ Prelims clear hai, Mains ki taiyari',
                    ] as $val => $label): ?>
                    <label class="cursor-pointer flex items-center gap-3 p-3 bg-white border border-gold-100 rounded-lg hover:bg-cream transition has-[:checked]:border-saffron-400 has-[:checked]:bg-saffron-50">
                        <input type="radio" name="attempt_history" value="<?= $val ?>" class="w-4 h-4 text-saffron-500 border-gold-300 focus:ring-saffron-500" <?= $val === 'first_time' ? 'checked' : '' ?>>
                        <span class="text-sm text-navy-600"><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Q4: Biggest Challenge -->
            <div class="paper rounded-xl p-5 mb-4">
                <h2 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 bg-saffron-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">4</span>
                    Sabse Badi Problem Kya Hai?
                </h2>
                <p class="text-xs text-gray-500 mb-3 ml-8">Ek ya zyada select karo — AI ispe focus karega</p>
                <div class="space-y-2 ml-8">
                    <?php foreach ([
                        'no_direction' => '🧭 Kahan se shuru karun samajh nahi aata',
                        'consistency' => '📅 Roz padh nahi paata — 2-3 din chhod deta hoon',
                        'speed' => '⏱️ Time management — exam mein time khatam ho jaata hai',
                        'revision' => '🔁 Padha hua yaad nahi rehta — revision nahi hoti',
                        'mocks' => '📝 Mock test mein marks nahi aate — real exam feel nahi aata',
                        'resources' => '📚 Kaunsi book padhun, kaunsa YouTube dekhun — confused',
                    ] as $val => $label): ?>
                    <label class="cursor-pointer flex items-center gap-3 p-2.5 bg-white border border-gold-100 rounded-lg hover:bg-cream transition has-[:checked]:border-red-200 has-[:checked]:bg-red-50">
                        <input type="checkbox" name="challenges[]" value="<?= $val ?>"
                            class="w-4 h-4 text-red-500 border-gold-300 rounded focus:ring-red-400">
                        <span class="text-sm text-navy-600"><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Q5: Study Situation -->
            <div class="paper rounded-xl p-5 mb-4">
                <h2 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 bg-saffron-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">5</span>
                    Aapki Study Situation
                </h2>
                <p class="text-xs text-gray-500 mb-3 ml-8">Aap kaise padh rahe ho abhi?</p>
                <div class="space-y-2 ml-8">
                    <?php foreach ([
                        'full_time' => '📖 Full-time preparation — sirf padhai karta/karti hoon',
                        'working' => '💼 Job/work ke saath preparation — limited time hai',
                        'college' => '🎓 College ke saath — balance karna padta hai',
                        'coaching' => '🏫 Coaching mein enrolled hoon — supplement chahiye',
                    ] as $val => $label): ?>
                    <label class="cursor-pointer flex items-center gap-3 p-3 bg-white border border-gold-100 rounded-lg hover:bg-cream transition has-[:checked]:border-saffron-400 has-[:checked]:bg-saffron-50">
                        <input type="radio" name="study_situation" value="<?= $val ?>" class="w-4 h-4 text-saffron-500 border-gold-300 focus:ring-saffron-500" <?= $val === 'full_time' ? 'checked' : '' ?>>
                        <span class="text-sm text-navy-600"><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Q6: Study Medium -->
            <div class="paper rounded-xl p-5 mb-4">
                <h2 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 bg-saffron-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">6</span>
                    Aap Kaise Padhte Ho?
                </h2>
                <p class="text-xs text-gray-500 mb-3 ml-8">Resources suggest karte waqt AI isko dhyan rakhega</p>
                <div class="grid grid-cols-2 gap-2 ml-8">
                    <?php foreach ([
                        'hindi' => '🇮🇳 Hindi medium',
                        'english' => '🇬🇧 English medium',
                        'video' => '📹 Video lectures pasand hai',
                        'books' => '📚 Books se padhunga',
                    ] as $val => $label): ?>
                    <label class="cursor-pointer flex items-center gap-2 p-2.5 bg-white border border-gold-100 rounded-lg hover:bg-cream transition has-[:checked]:border-saffron-400 has-[:checked]:bg-saffron-50">
                        <input type="checkbox" name="study_style[]" value="<?= $val ?>"
                            class="w-4 h-4 text-saffron-500 border-gold-300 rounded focus:ring-saffron-500"
                            <?= in_array($val, ['hindi', 'video']) ? 'checked' : '' ?>>
                        <span class="text-xs font-medium text-navy-600"><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Study Hours + Exam Date -->
            <div class="paper rounded-xl p-5 mb-5">
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <h2 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                            <span class="w-6 h-6 bg-saffron-500 text-white rounded-full flex items-center justify-center text-xs font-black flex-shrink-0">7</span>
                            Daily Kitne Ghante?
                        </h2>
                        <div class="ml-8">
                            <input type="range" name="study_hours" id="studyHours" min="1" max="14" step="0.5" value="4"
                                class="w-full h-2 rounded-lg cursor-pointer mb-2">
                            <div class="text-center">
                                <span class="text-3xl font-black text-saffron-500" id="hoursDisplay">4</span>
                                <span class="text-gray-500 text-sm ml-1">hours/day</span>
                            </div>
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
                <?= !empty($isEdit) ? 'Save Changes &amp; Regenerate' : 'Mera Personalized Blueprint Banao' ?>
            </button>
            <p class="text-center text-xs text-gray-400 mt-3">AI aapke jawaabon se hyper-personalized 30-day plan banayega</p>
        </form>
    </div>
</section>

<script>
document.getElementById('studyHours').addEventListener('input', function() {
    document.getElementById('hoursDisplay').textContent = this.value;
});
</script>
