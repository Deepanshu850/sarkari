<section class="py-8 md:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Mere Blueprints</h1>
                    <?= plan_badge() ?>
                </div>
                <?php
                    $readyCount = 0;
                    foreach ($blueprints as $bp) { if ($bp['status'] === 'ready') $readyCount++; }
                    $allowed = blueprints_allowed();
                    $remaining = max(0, $allowed - $readyCount);
                ?>
                <p class="text-gray-500 mt-1">Namaste, <span class="font-semibold text-navy-600"><?= e(auth()['name']) ?></span>!
                    <span class="text-xs text-gray-400">— <?= $remaining ?> of <?= $allowed ?> blueprint<?= $allowed > 1 ? 's' : '' ?> remaining</span>
                </p>
            </div>
            <?php if ($remaining > 0): ?>
            <a href="/blueprint/step1"
                class="inline-flex items-center gap-2 px-6 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Naya Blueprint (<?= $remaining ?> left)
            </a>
            <?php else: ?>
            <a href="/upgrade"
                class="inline-flex items-center gap-2 px-6 py-3 bg-navy-600 text-white rounded-xl font-bold hover:bg-navy-700 transition shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                Upgrade Plan
            </a>
            <?php endif; ?>
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
        <div class="grid gap-6">
            <?php foreach ($blueprints as $bp):
                $bpId  = $bp['id'];
                $prog  = $progressData[$bpId] ?? null;
                $isReady = $bp['status'] === 'ready';
            ?>
            <div class="paper rounded-xl overflow-hidden hover:shadow-md transition-all duration-200 border-l-4
                <?= match($bp['status']) {
                    'ready'      => 'border-l-india-500',
                    'generating' => 'border-l-saffron-500',
                    'failed'     => 'border-l-red-500',
                    default      => 'border-l-gold-400',
                } ?>">

                <!-- Main row -->
                <div class="p-5 md:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <!-- Progress ring or status icon -->
                            <?php if ($isReady && $prog): ?>
                            <?php
                                $totalDays  = max(1, count($prog['completed_days']) + 1);
                                // approximate total plan days as 30 if we don't have it here
                                $planDays   = 30;
                                $pct        = min(100, round(($prog['total_completed'] / $planDays) * 100));
                                $circumference = 2 * M_PI * 18; // r=18
                                $dashOffset    = $circumference - ($pct / 100) * $circumference;
                            ?>
                            <div class="relative w-12 h-12 flex-shrink-0">
                                <svg class="w-12 h-12 -rotate-90" viewBox="0 0 44 44">
                                    <circle cx="22" cy="22" r="18" fill="none" stroke="#FEF3C7" stroke-width="4"/>
                                    <circle cx="22" cy="22" r="18" fill="none"
                                        stroke="#138808"
                                        stroke-width="4"
                                        stroke-dasharray="<?= round($circumference, 2) ?>"
                                        stroke-dashoffset="<?= round($dashOffset, 2) ?>"
                                        stroke-linecap="round"/>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center text-[10px] font-black text-india-700"><?= $pct ?>%</span>
                            </div>
                            <?php else: ?>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                                <?= match($bp['status']) {
                                    'ready'      => 'bg-india-50 border border-india-200 text-india-600',
                                    'generating' => 'bg-saffron-50 border border-saffron-200 text-saffron-600',
                                    'failed'     => 'bg-red-50 border border-red-200 text-red-600',
                                    default      => 'bg-gold-50 border border-gold-200 text-gold-600',
                                } ?>">
                                <?php if ($bp['status'] === 'generating'): ?>
                                    <div class="chakra-spinner" style="border-top-color: #FF6B00;"></div>
                                <?php elseif ($bp['status'] === 'failed'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php else: ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-navy-600"><?= e($bp['exam_name']) ?></h3>
                                <div class="flex flex-wrap items-center gap-3 mt-1.5 text-sm">
                                    <span class="text-gray-400 text-xs"><?= e($bp['exam_category']) ?></span>
                                    <span class="w-1 h-1 bg-gold-300 rounded-full"></span>
                                    <span class="text-gray-400 text-xs"><?= date('d M Y', strtotime($bp['created_at'])) ?></span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold tracking-wide uppercase
                                        <?= match($bp['status']) {
                                            'ready'      => 'bg-india-50 text-india-700 border border-india-200',
                                            'generating' => 'bg-saffron-50 text-saffron-700 border border-saffron-200',
                                            'failed'     => 'bg-red-50 text-red-700 border border-red-200',
                                            default      => 'bg-gold-50 text-gold-700 border border-gold-200',
                                        } ?>">
                                        <?= ucfirst(str_replace('_', ' ', e($bp['status']))) ?>
                                    </span>
                                </div>

                                <?php if ($isReady && $prog): ?>
                                <!-- Progress stats row -->
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <!-- Exam countdown -->
                                    <?php if (!$prog['exam_passed']): ?>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-saffron-700 bg-saffron-50 border border-saffron-200 px-2 py-0.5 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <?= $prog['days_until_exam'] ?> din baaki
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded">
                                        Exam ho gaya
                                    </span>
                                    <?php endif; ?>

                                    <!-- Completed days -->
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-india-700 bg-india-50 border border-india-200 px-2 py-0.5 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <?= $prog['total_completed'] ?>/30 din
                                    </span>

                                    <!-- Streak -->
                                    <?php if ($prog['streak'] > 0): ?>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded">
                                        <?= $prog['streak'] ?> din streak
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Progress bar -->
                                <?php
                                    $barPct = min(100, round(($prog['total_completed'] / 30) * 100));
                                ?>
                                <div class="mt-2 w-full max-w-xs h-1.5 bg-gold-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-india-500 rounded-full transition-all duration-500" style="width:<?= $barPct ?>%"></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex items-center gap-2 sm:flex-shrink-0 flex-wrap">
                            <?php if ($isReady && $prog): ?>
                                <!-- Today's plan quick link -->
                                <?php
                                    $todayDay = min($prog['today_day'], 30);
                                    $alreadyDone = in_array($todayDay, $prog['completed_days']);
                                ?>
                                <?php if (!$alreadyDone && !$prog['exam_passed']): ?>
                                <button
                                    class="mark-done-btn px-4 py-2 bg-india-50 text-india-700 border border-india-200 rounded-lg font-semibold text-sm hover:bg-india-100 transition flex items-center gap-1.5"
                                    data-bp="<?= $bpId ?>"
                                    data-day="<?= $todayDay ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Aaj ka din done
                                </button>
                                <?php elseif ($alreadyDone && !$prog['exam_passed']): ?>
                                <span class="px-4 py-2 bg-india-50 text-india-600 border border-india-200 rounded-lg font-semibold text-sm flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Aaj complete!
                                </span>
                                <?php endif; ?>
                                <a href="/blueprint/view/<?= $bpId ?>"
                                    class="px-4 py-2 bg-navy-50 text-navy-700 border border-navy-200 rounded-lg font-semibold text-sm hover:bg-navy-100 transition">
                                    View
                                </a>
                                <a href="/blueprint/download/<?= $bpId ?>"
                                    class="px-4 py-2 bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-lg font-semibold text-sm hover:bg-saffron-100 transition flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    PDF
                                </a>
                            <?php elseif ($bp['status'] === 'failed'): ?>
                                <a href="/blueprint/retry/<?= $bpId ?>"
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

                <?php if ($isReady && $prog): ?>
                <!-- Today's Plan Banner -->
                <?php if (!$prog['exam_passed'] && $prog['today_day'] >= 1 && $prog['today_day'] <= 30): ?>
                <div class="border-t border-gold-100 bg-cream px-5 md:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 bg-saffron-500 text-white rounded text-[10px] font-black flex items-center justify-center flex-shrink-0">D<?= $prog['today_day'] ?></span>
                        <span class="text-sm font-semibold text-navy-600">Aaj ka plan: Day <?= $prog['today_day'] ?></span>
                        <a href="/blueprint/view/<?= $bpId ?>#day-<?= $prog['today_day'] ?>" class="text-xs text-saffron-600 hover:underline font-medium">Dekho &rarr;</a>
                    </div>
                    <?php if ($prog['streak'] > 1): ?>
                    <span class="text-sm font-bold text-orange-600"><?= $prog['streak'] ?> din consistent!</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Exam passed: result submission -->
                <?php if ($prog['exam_passed']): ?>
                <div class="border-t border-purple-100 bg-purple-50 px-5 md:px-6 py-4" id="result-section-<?= $bpId ?>">
                    <p class="text-sm font-bold text-purple-700 mb-2">Exam ho gaya — apna result batao!</p>
                    <form class="result-form flex flex-wrap gap-2" data-bp="<?= $bpId ?>">
                        <select name="result" class="text-sm border border-purple-200 rounded-lg px-3 py-1.5 bg-white text-navy-600 font-semibold focus:outline-none focus:ring-2 focus:ring-purple-300">
                            <option value="selected">Selected!</option>
                            <option value="appeared" selected>Appeared</option>
                            <option value="not_selected">Not Selected</option>
                            <option value="waiting">Waiting for result</option>
                        </select>
                        <input type="text" name="score" placeholder="Score / Marks (optional)" class="text-sm border border-purple-200 rounded-lg px-3 py-1.5 bg-white text-navy-600 focus:outline-none focus:ring-2 focus:ring-purple-300">
                        <button type="submit" class="px-4 py-1.5 bg-purple-600 text-white rounded-lg text-sm font-bold hover:bg-purple-700 transition">Submit</button>
                    </form>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Referral Card -->
        <div class="mt-8 paper rounded-xl p-5 md:p-6 border border-gold-200 bg-gradient-to-br from-gold-50 to-cream">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-navy-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-saffron-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Dost ko bhejo, dono ko faayda!
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Apna referral link share karo aur dost ke sign up karne par <span class="font-bold text-india-600">&#8377;100 off</span> pao.</p>
                </div>
                <div class="flex flex-col sm:items-end gap-2">
                    <div class="flex items-center gap-2 bg-white border border-gold-200 rounded-lg px-3 py-2">
                        <span class="text-sm font-mono font-bold text-navy-600" id="referral-code-display"><?= e(base_url()) ?>/register?ref=<?= e($referralCode) ?></span>
                        <button onclick="copyReferral()" class="text-saffron-500 hover:text-saffron-700 transition flex-shrink-0" title="Copy link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                    <span class="text-[11px] text-gray-400" id="copy-confirm" style="display:none">Link copy ho gaya!</span>
                </div>
            </div>
        </div>

        <?php endif; ?>
    </div>
</section>

<script>
// Mark today complete via AJAX
document.querySelectorAll('.mark-done-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const bpId  = this.dataset.bp;
        const day   = this.dataset.day;
        const btnEl = this;
        btnEl.disabled = true;
        btnEl.classList.add('opacity-60');

        const form = new FormData();
        form.append('blueprint_id', bpId);
        form.append('day_number', day);

        fetch('/api/progress/toggle', { method: 'POST', body: form })
            .then(r => r.json())
            .then(data => {
                if (data.completed) {
                    btnEl.outerHTML = `<span class="px-4 py-2 bg-india-50 text-india-600 border border-india-200 rounded-lg font-semibold text-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Aaj complete!
                    </span>`;
                } else {
                    btnEl.disabled = false;
                    btnEl.classList.remove('opacity-60');
                }
            })
            .catch(() => {
                btnEl.disabled = false;
                btnEl.classList.remove('opacity-60');
            });
    });
});

// Result submission via AJAX
document.querySelectorAll('.result-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const bpId = this.dataset.bp;
        const fd   = new FormData(this);
        fd.append('blueprint_id', bpId);

        fetch('/api/result/submit', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const section = document.getElementById('result-section-' + bpId);
                    if (section) {
                        section.innerHTML = '<p class="text-sm font-bold text-india-700 py-2">Result submit ho gaya! Shukriya.</p>';
                    }
                }
            });
    });
});

// Copy referral link
function copyReferral() {
    const text = document.getElementById('referral-code-display').innerText;
    navigator.clipboard.writeText(text).then(() => {
        const confirm = document.getElementById('copy-confirm');
        confirm.style.display = 'block';
        setTimeout(() => { confirm.style.display = 'none'; }, 2500);
    });
}
</script>
