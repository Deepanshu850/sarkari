<section class="py-16 md:py-24">
    <div class="max-w-lg mx-auto px-4 text-center">
        <!-- Spinner -->
        <div class="w-20 h-20 mx-auto mb-8 relative">
            <div class="absolute inset-0 rounded-full border-4 border-gold-200"></div>
            <div class="absolute inset-0 rounded-full border-4 border-saffron-500 border-t-transparent animate-spin"></div>
            <div class="absolute inset-3 rounded-full border-4 border-india-500 border-b-transparent animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
        </div>

        <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600 mb-3">
            Aapka Blueprint Ban Raha Hai...
        </h1>

        <p class="text-gray-500 mb-2">
            AI aapke exam, weak subjects, aur study hours ke hisaab se personalized 30-day plan bana raha hai.
        </p>

        <div class="bg-cream border border-gold-200 rounded-xl p-4 mb-6 text-left">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-lg">🎯</span>
                <span class="font-bold text-navy-600 text-sm"><?= e($blueprint['exam_name']) ?></span>
            </div>
            <div class="text-xs text-gray-500 space-y-1">
                <p>Education: <?= e($blueprint['education']) ?></p>
                <p>Study Hours: <?= e($blueprint['study_hours']) ?> hrs/day</p>
                <p>Exam Date: <?= date('d M Y', strtotime($blueprint['exam_date'])) ?></p>
            </div>
        </div>

        <p class="text-sm text-gray-400 mb-6" id="statusText">AI processing... Please wait (30-60 seconds)</p>

        <!-- Progress dots -->
        <div class="flex justify-center gap-2 mb-8">
            <div class="w-3 h-3 bg-saffron-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-3 h-3 bg-saffron-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            <div class="w-3 h-3 bg-saffron-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
        </div>

        <p class="text-xs text-gray-400">Page close mat karo. Blueprint automatically open hoga.</p>
    </div>
</section>

<script>
(function() {
    var bpId = <?= (int) $blueprintId ?>;
    var started = false;
    var pollCount = 0;

    // Step 1: Trigger generation via AJAX
    fetch('/api/blueprint/generate/' + bpId, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: '_token=<?= e(\App\Core\CSRF::generate()) ?>'
    }).catch(function() {});

    // Step 2: Poll for status every 3 seconds
    var poll = setInterval(function() {
        pollCount++;
        var dots = '.'.repeat((pollCount % 3) + 1);
        document.getElementById('statusText').textContent =
            pollCount < 10 ? 'AI generating your plan' + dots + ' (' + (pollCount * 3) + 's)'
            : pollCount < 20 ? 'Almost done' + dots + ' Complex plan being created'
            : 'Finishing up' + dots + ' Just a few more seconds';

        fetch('/api/blueprint/status/' + bpId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'ready' && data.redirect) {
                    clearInterval(poll);
                    document.getElementById('statusText').textContent = 'Blueprint ready! Redirecting...';
                    window.location.href = data.redirect;
                } else if (data.status === 'failed') {
                    clearInterval(poll);
                    document.getElementById('statusText').textContent = 'Generation failed. Redirecting to dashboard...';
                    setTimeout(function() { window.location.href = '/dashboard'; }, 2000);
                }
            })
            .catch(function() {});

        // Timeout after 3 minutes
        if (pollCount > 60) {
            clearInterval(poll);
            document.getElementById('statusText').textContent = 'Taking too long. Check your dashboard.';
            setTimeout(function() { window.location.href = '/dashboard'; }, 3000);
        }
    }, 3000);
})();
</script>
