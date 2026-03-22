<?php
// Pre-compute progress data for this view
$completedSet = array_flip($completedDays ?? []);
$totalCompleted = count($completedDays ?? []);
$planDays = count($days);
$progressPct = $planDays > 0 ? min(100, round(($totalCompleted / $planDays) * 100)) : 0;
$generatedTs = strtotime($blueprint['generated_at'] ?? $blueprint['created_at']);
$todayDayNum = (int) floor((time() - $generatedTs) / 86400) + 1;
$streak = 0;
for ($d = $todayDayNum; $d >= 1; $d--) {
    if (isset($completedSet[$d])) { $streak++; } else { break; }
}
?>
<section class="py-8 md:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="paper rounded-2xl overflow-hidden mb-6 paper-shadow">
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
                    <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0">
                        <?php if ((time() - $generatedTs) <= 7 * 86400 && has_feature('edit_regenerate')): ?>
                        <a href="/customize/<?= $blueprint['id'] ?>?edit=1"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white/10 border border-white/30 text-white rounded-xl font-bold hover:bg-white/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit &amp; Regenerate
                        </a>
                        <?php endif; ?>
                        <a href="/blueprint/download/<?= $blueprint['id'] ?>"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download PDF
                        </a>
                    </div>
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

        <!-- Overall Progress Bar -->
        <div class="paper rounded-xl p-5 md:p-6 mb-6 border border-gold-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                <div class="flex items-center gap-3">
                    <div class="relative w-14 h-14 flex-shrink-0">
                        <?php
                            $circ = 2 * M_PI * 20;
                            $offs = $circ - ($progressPct / 100) * $circ;
                        ?>
                        <svg class="w-14 h-14 -rotate-90" viewBox="0 0 48 48">
                            <circle cx="24" cy="24" r="20" fill="none" stroke="#FEF3C7" stroke-width="5"/>
                            <circle cx="24" cy="24" r="20" fill="none"
                                stroke="#138808"
                                stroke-width="5"
                                stroke-dasharray="<?= round($circ, 2) ?>"
                                stroke-dashoffset="<?= round($offs, 2) ?>"
                                stroke-linecap="round"
                                id="progress-ring-circle"/>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-sm font-black text-india-700" id="progress-ring-pct"><?= $progressPct ?>%</span>
                    </div>
                    <div>
                        <p class="font-bold text-navy-600"><span id="completed-count"><?= $totalCompleted ?></span>/<?= $planDays ?> days completed</p>
                        <?php if ($streak > 0): ?>
                        <p class="text-sm text-orange-600 font-semibold mt-0.5" id="streak-display"><?= $streak ?> din streak!</p>
                        <?php else: ?>
                        <p class="text-sm text-gray-500 mt-0.5" id="streak-display">Streak: 0 din</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex flex-col gap-1 text-right">
                    <span class="text-xs text-gray-500">Exam: <?= date('d M Y', strtotime($blueprint['exam_date'])) ?></span>
                    <?php $daysLeft = (int) ceil((strtotime($blueprint['exam_date']) - time()) / 86400); ?>
                    <?php if ($daysLeft > 0): ?>
                    <span class="text-sm font-bold text-saffron-600"><?= $daysLeft ?> din baaki</span>
                    <?php else: ?>
                    <span class="text-sm font-bold text-purple-600">Exam ho gaya</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="w-full h-2 bg-gold-100 rounded-full overflow-hidden">
                <div class="h-full bg-india-500 rounded-full transition-all duration-700" id="progress-bar" style="width:<?= $progressPct ?>%"></div>
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
                $isCompleted = isset($completedSet[$n]);
                $isToday = ($n === $todayDayNum);
                if ($n <= 10) { $dayColor = 'saffron'; $badgeBg = 'gradient-saffron'; }
                elseif ($n <= 20) { $dayColor = 'navy'; $badgeBg = 'gradient-navy'; }
                else { $dayColor = 'india'; $badgeBg = 'bg-india-500'; }
            ?>
            <details
                class="paper rounded-xl group <?= $isCompleted ? 'opacity-75' : '' ?>"
                id="day-<?= $n ?>"
                <?= ($isToday || $n <= 3) ? 'open' : '' ?>>
                <summary class="flex items-center gap-4 p-4 md:p-5 cursor-pointer list-none select-none">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-white text-xs <?= $badgeBg ?> shadow-sm relative">
                        <?php if ($isCompleted): ?>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <?php else: ?>
                        Day<br><?= $n ?>
                        <?php endif; ?>
                        <?php if ($isToday && !$isCompleted): ?>
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-saffron-500 rounded-full border-2 border-white"></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-navy-600 <?= $isCompleted ? 'line-through text-gray-400' : '' ?> truncate"><?= e($day['title']) ?></h3>
                            <?php if ($isToday): ?>
                            <span class="text-[10px] font-bold bg-saffron-100 text-saffron-700 border border-saffron-200 px-2 py-0.5 rounded flex-shrink-0">Aaj</span>
                            <?php endif; ?>
                            <?php if ($isCompleted): ?>
                            <span class="text-[10px] font-bold bg-india-50 text-india-700 border border-india-200 px-2 py-0.5 rounded flex-shrink-0">Done</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <?php foreach (json_decode($day['subjects_json'], true) as $sub): ?>
                            <span class="text-xs text-gray-500 bg-cream px-2 py-0.5 rounded border border-gold-100"><?= e($sub['subject'] ?? '') ?> (<?= e($sub['hours'] ?? '') ?>h)</span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <!-- Checkbox toggle -->
                        <button
                            class="day-toggle-btn w-8 h-8 rounded-lg border-2 flex items-center justify-center transition
                                <?= $isCompleted
                                    ? 'bg-india-500 border-india-500 text-white'
                                    : 'bg-white border-gray-200 text-transparent hover:border-india-400' ?>"
                            data-bp="<?= $blueprint['id'] ?>"
                            data-day="<?= $n ?>"
                            title="<?= $isCompleted ? 'Uncomplete' : 'Mark complete' ?>"
                            onclick="event.stopPropagation(); toggleDay(this)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </button>
                        <svg class="w-5 h-5 text-gold-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
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

                    <!-- Mark complete button inside day detail -->
                    <div class="mt-4 pt-4 border-t border-gold-100">
                        <button
                            class="day-toggle-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition
                                <?= $isCompleted
                                    ? 'bg-india-50 text-india-700 border border-india-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200'
                                    : 'bg-india-500 text-white hover:bg-india-600' ?>"
                            data-bp="<?= $blueprint['id'] ?>"
                            data-day="<?= $n ?>">
                            <?php if ($isCompleted): ?>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Day <?= $n ?> complete — Undo
                            <?php else: ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Mark Day <?= $n ?> Complete
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </details>
            <?php endforeach; ?>
        </div>

        <!-- Share Your Progress -->
        <div class="mt-10 paper rounded-xl p-6 border border-gold-200 bg-gradient-to-br from-gold-50 to-cream">
            <h3 class="font-bold text-navy-600 mb-1">Apni progress share karo!</h3>
            <p class="text-sm text-gray-500 mb-4">Dosto ko inspire karo — unhe batao tum kitna padhte ho.</p>
            <div class="flex flex-wrap gap-3">
                <button
                    onclick="shareProgress()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-navy-600 text-white rounded-xl font-bold text-sm hover:bg-navy-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    Share Progress
                </button>
                <a href="/blueprint/download/<?= $blueprint['id'] ?>"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-saffron-500 text-white rounded-xl font-bold text-sm hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download PDF
                </a>
                <a href="/dashboard" class="inline-flex items-center px-5 py-2.5 text-gray-500 hover:text-navy-600 font-medium transition text-sm">
                    &larr; Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

<script>
const BLUEPRINT_ID  = <?= (int) $blueprint['id'] ?>;
const PLAN_DAYS     = <?= (int) $planDays ?>;
const CIRC          = 2 * Math.PI * 20; // matches r=20 in SVG

// Sync all buttons with the same bp+day pair
function syncDayUI(dayNum, completed, totalCompleted, streak) {
    const buttons = document.querySelectorAll(`.day-toggle-btn[data-day="${dayNum}"]`);
    const detailEl = document.getElementById(`day-${dayNum}`);

    buttons.forEach(btn => {
        if (completed) {
            btn.classList.add('bg-india-500', 'border-india-500', 'text-white');
            btn.classList.remove('bg-white', 'border-gray-200', 'text-transparent', 'hover:border-india-400');
        } else {
            btn.classList.remove('bg-india-500', 'border-india-500', 'text-white');
            btn.classList.add('bg-white', 'border-gray-200', 'text-transparent', 'hover:border-india-400');
        }
    });

    // Toggle strikethrough on title
    if (detailEl) {
        const title = detailEl.querySelector('summary h3');
        if (title) {
            if (completed) {
                title.classList.add('line-through', 'text-gray-400');
                detailEl.classList.add('opacity-75');
            } else {
                title.classList.remove('line-through', 'text-gray-400');
                detailEl.classList.remove('opacity-75');
            }
        }
    }

    // Update overall progress ring + bar
    const pct = Math.round((totalCompleted / PLAN_DAYS) * 100);
    const offset = CIRC - (pct / 100) * CIRC;
    const ring = document.getElementById('progress-ring-circle');
    if (ring) {
        ring.style.strokeDashoffset = offset;
    }
    const pctEl = document.getElementById('progress-ring-pct');
    if (pctEl) pctEl.textContent = pct + '%';

    const bar = document.getElementById('progress-bar');
    if (bar) bar.style.width = pct + '%';

    const countEl = document.getElementById('completed-count');
    if (countEl) countEl.textContent = totalCompleted;

    const streakEl = document.getElementById('streak-display');
    if (streakEl) {
        streakEl.textContent = streak > 0 ? streak + ' din streak!' : 'Streak: 0 din';
        streakEl.className = streak > 0
            ? 'text-sm text-orange-600 font-semibold mt-0.5'
            : 'text-sm text-gray-500 mt-0.5';
    }
}

function toggleDay(btn) {
    const bpId = btn.dataset.bp;
    const day  = parseInt(btn.dataset.day);
    btn.disabled = true;

    const fd = new FormData();
    fd.append('blueprint_id', bpId);
    fd.append('day_number', day);

    fetch('/api/progress/toggle', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            syncDayUI(day, data.completed === 1, data.total_completed, data.streak);
        })
        .catch(console.error)
        .finally(() => { btn.disabled = false; });
}

// Wire all day-toggle buttons
document.querySelectorAll('.day-toggle-btn').forEach(btn => {
    // Only wire if not already wired via onclick attribute
    if (!btn.getAttribute('onclick')) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDay(this);
        });
    }
});

function shareProgress() {
    const completedCount = parseInt(document.getElementById('completed-count').textContent) || 0;
    const text = `Maine ${completedCount}/${PLAN_DAYS} din complete kar liye apne Sarkari exam blueprint mein! Tumhara blueprint banao: ${window.location.origin}`;
    if (navigator.share) {
        navigator.share({ title: 'Meri Sarkari Prep Progress', text: text, url: window.location.origin });
    } else {
        navigator.clipboard.writeText(text).then(() => alert('Progress text copy ho gaya!'));
    }
}
</script>
