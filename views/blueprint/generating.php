<section class="py-12 md:py-20">
    <div class="max-w-lg mx-auto px-4 text-center">
        <!-- Progress percentage circle -->
        <div class="w-28 h-28 mx-auto mb-6 relative">
            <svg class="w-28 h-28 transform -rotate-90" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="54" fill="none" stroke="#e5ddd0" stroke-width="8"/>
                <circle id="progressCircle" cx="60" cy="60" r="54" fill="none" stroke="#FF6B00" stroke-width="8"
                    stroke-dasharray="339.292" stroke-dashoffset="339.292" stroke-linecap="round"
                    style="transition: stroke-dashoffset 0.5s ease;"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
                <span id="progressPct" class="text-2xl font-black text-navy-600">0%</span>
            </div>
        </div>

        <h1 class="font-display text-xl md:text-2xl font-bold text-navy-600 mb-2">
            Aapka Blueprint Ban Raha Hai
        </h1>

        <p id="statusText" class="text-sm text-gray-500 mb-4">AI se connect ho raha hai...</p>

        <!-- Step indicators -->
        <div class="bg-cream border border-gold-200 rounded-xl p-4 mb-6 text-left space-y-2.5">
            <div id="step1" class="flex items-center gap-3 text-xs text-gray-400">
                <span class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 step-icon">1</span>
                <span>AI se connect ho raha hai...</span>
            </div>
            <div id="step2" class="flex items-center gap-3 text-xs text-gray-400">
                <span class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 step-icon">2</span>
                <span>Aapke weak subjects analyze ho rahe hain</span>
            </div>
            <div id="step3" class="flex items-center gap-3 text-xs text-gray-400">
                <span class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 step-icon">3</span>
                <span>30-day plan generate ho raha hai</span>
            </div>
            <div id="step4" class="flex items-center gap-3 text-xs text-gray-400">
                <span class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 step-icon">4</span>
                <span>Resources aur tips add ho rahe hain</span>
            </div>
            <div id="step5" class="flex items-center gap-3 text-xs text-gray-400">
                <span class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 step-icon">5</span>
                <span>PDF generate ho raha hai</span>
            </div>
        </div>

        <div class="bg-white border border-gold-200 rounded-lg p-3 mb-4 text-left">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-sm font-bold text-navy-600"><?= e($blueprint['exam_name']) ?></span>
            </div>
            <div class="text-[11px] text-gray-400">
                <?= e($blueprint['education']) ?> · <?= e($blueprint['study_hours']) ?> hrs/day · Exam: <?= date('d M Y', strtotime($blueprint['exam_date'])) ?>
            </div>
        </div>

        <p class="text-[11px] text-gray-400">Page close mat karo. Blueprint automatically open hoga.</p>
    </div>
</section>

<style>
.step-done { color: #138808; border-color: #138808; background: #138808; }
.step-done + span { color: #138808; font-weight: 600; }
.step-active { color: #FF6B00; border-color: #FF6B00; animation: pulse 1s infinite; }
.step-active + span { color: #0f172a; font-weight: 600; }
@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.5; } }
</style>

<script>
(function() {
    var bpId = <?= (int) $blueprintId ?>;
    var pollCount = 0;
    var circle = document.getElementById('progressCircle');
    var pctEl = document.getElementById('progressPct');
    var statusEl = document.getElementById('statusText');
    var circumference = 339.292;

    function setProgress(pct) {
        circle.style.strokeDashoffset = circumference - (circumference * pct / 100);
        pctEl.textContent = Math.round(pct) + '%';
    }

    function activateStep(n) {
        for (var i = 1; i <= 5; i++) {
            var el = document.getElementById('step' + i);
            var icon = el.querySelector('.step-icon');
            if (i < n) {
                icon.className = 'w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 step-icon step-done text-white text-[10px]';
                icon.innerHTML = '&#10003;';
                el.className = el.className.replace('text-gray-400', 'text-gray-600');
            } else if (i === n) {
                icon.className = 'w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 step-icon step-active text-[10px]';
                el.className = el.className.replace('text-gray-400', 'text-gray-600');
            }
        }
    }

    // Simulated progress (actual generation is async, we simulate visual progress)
    var fakeProgress = 0;
    var fakeInterval = setInterval(function() {
        if (fakeProgress < 15) { fakeProgress += 3; activateStep(1); statusEl.textContent = 'AI se connect ho raha hai...'; }
        else if (fakeProgress < 35) { fakeProgress += 2; activateStep(2); statusEl.textContent = 'Weak subjects analyze ho rahe hain...'; }
        else if (fakeProgress < 60) { fakeProgress += 1.5; activateStep(3); statusEl.textContent = '30-day plan generate ho raha hai...'; }
        else if (fakeProgress < 80) { fakeProgress += 1; activateStep(4); statusEl.textContent = 'Resources aur tips add ho rahe hain...'; }
        else if (fakeProgress < 90) { fakeProgress += 0.5; activateStep(5); statusEl.textContent = 'PDF generate ho raha hai...'; }
        else { fakeProgress = Math.min(fakeProgress + 0.2, 95); statusEl.textContent = 'Almost done... finishing up'; }
        setProgress(fakeProgress);
    }, 1000);

    // Trigger generation
    fetch('/api/blueprint/generate/' + bpId, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: '_token=<?= e(\App\Core\CSRF::generate()) ?>'
    }).catch(function() {});

    // Poll for actual status
    var poll = setInterval(function() {
        pollCount++;
        fetch('/api/blueprint/status/' + bpId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'ready' && data.redirect) {
                    clearInterval(poll);
                    clearInterval(fakeInterval);
                    setProgress(100);
                    activateStep(6);
                    statusEl.textContent = 'Blueprint ready! Redirecting...';
                    pctEl.textContent = '100%';
                    setTimeout(function() { window.location.href = data.redirect; }, 1000);
                } else if (data.status === 'failed') {
                    clearInterval(poll);
                    clearInterval(fakeInterval);
                    statusEl.textContent = 'Generation failed. Redirecting...';
                    setTimeout(function() { window.location.href = '/dashboard'; }, 2000);
                }
            })
            .catch(function() {});

        if (pollCount > 60) {
            clearInterval(poll);
            clearInterval(fakeInterval);
            statusEl.textContent = 'Taking longer than expected. Check dashboard.';
            setTimeout(function() { window.location.href = '/dashboard'; }, 3000);
        }
    }, 3000);
})();
</script>
