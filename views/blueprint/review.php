<section class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-bold text-india-500 tracking-wider uppercase">Step 4 of 4</span>
                <span class="text-sm text-gray-500">Review & Pay</span>
            </div>
            <div class="w-full bg-gold-100 rounded-full h-2.5">
                <div class="bg-india-500 h-2.5 rounded-full transition-all shadow-sm" style="width: 100%"></div>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-[10px] text-india-500 font-bold">EXAM &#10003;</span>
                <span class="text-[10px] text-india-500 font-bold">BACKGROUND &#10003;</span>
                <span class="text-[10px] text-india-500 font-bold">SCHEDULE &#10003;</span>
                <span class="text-[10px] text-india-500 font-bold">REVIEW &#10003;</span>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Sab Sahi Hai?</h1>
            <p class="text-gray-500 mt-2">Review your details and proceed to payment</p>
        </div>

        <div class="max-w-2xl mx-auto">
            <!-- Summary Card -->
            <div class="paper rounded-xl overflow-hidden paper-shadow mb-6">
                <div class="gradient-navy p-5 text-white relative">
                    <div class="absolute top-3 right-3 opacity-20">
                        <svg class="w-16 h-16 text-gold-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L13.09 8.26L20 9L14.14 13.14L15.82 20L12 16.27L8.18 20L9.86 13.14L4 9L10.91 8.26L12 2Z"/></svg>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 border border-gold-400/40 rounded-full flex items-center justify-center bg-white/5">
                            <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold"><?= e($draft['exam_name']) ?></h2>
                            <p class="text-gray-300 text-sm">30-Day Success Blueprint</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gold-100">
                        <div class="flex justify-between items-center py-3.5">
                            <span class="text-gray-500 text-sm flex items-center gap-2">
                                <span class="w-5 h-5 bg-gold-50 rounded flex items-center justify-center text-[10px]">🎓</span>
                                Education Level
                            </span>
                            <span class="font-bold text-navy-600"><?= e($draft['education']) ?></span>
                        </div>
                        <div class="py-3.5">
                            <span class="text-gray-500 text-sm flex items-center gap-2 mb-2">
                                <span class="w-5 h-5 bg-gold-50 rounded flex items-center justify-center text-[10px]">📍</span>
                                Weak Subjects
                            </span>
                            <div class="flex flex-wrap gap-2 ml-7">
                                <?php foreach ($draft['weak_subjects'] as $sub): ?>
                                <span class="px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded text-xs font-semibold"><?= e($sub) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-3.5">
                            <span class="text-gray-500 text-sm flex items-center gap-2">
                                <span class="w-5 h-5 bg-gold-50 rounded flex items-center justify-center text-[10px]">⏰</span>
                                Study Hours/Day
                            </span>
                            <span class="font-bold text-navy-600"><?= e($draft['study_hours']) ?> hours</span>
                        </div>
                        <div class="flex justify-between items-center py-3.5">
                            <span class="text-gray-500 text-sm flex items-center gap-2">
                                <span class="w-5 h-5 bg-gold-50 rounded flex items-center justify-center text-[10px]">📅</span>
                                Exam Date
                            </span>
                            <span class="font-bold text-navy-600"><?= date('d M Y', strtotime($draft['exam_date'])) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3.5">
                            <span class="text-gray-500 text-sm flex items-center gap-2">
                                <span class="w-5 h-5 bg-gold-50 rounded flex items-center justify-center text-[10px]">🎯</span>
                                Days Until Exam
                            </span>
                            <span class="font-bold text-saffron-600">
                                <?= max(0, (int) ((strtotime($draft['exam_date']) - time()) / 86400)) ?> days
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Card -->
            <div class="bg-saffron-50 border-2 border-saffron-200 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-navy-600 text-lg">Blueprint Generation</h3>
                        <p class="text-sm text-gray-500">One-time payment - AI powered plan</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-400 line-through">₹999</div>
                        <span class="text-3xl font-black text-navy-600">₹499</span>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 text-india-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    Secure payment via Razorpay (UPI, Cards, Net Banking)
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="/blueprint/step3" class="px-5 py-2.5 text-gray-500 hover:text-navy-600 font-medium flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Back
                </a>
                <button type="button" id="payBtn" onclick="initiatePayment()"
                    class="px-8 py-3.5 bg-saffron-500 text-white rounded-xl font-bold text-lg hover:bg-saffron-600 transition shadow-xl shadow-saffron-500/20 pulse-cta flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Pay ₹499 & Generate
                </button>
            </div>
        </div>
    </div>
</section>

<script>
function initiatePayment() {
    var btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<div class="chakra-spinner mr-2"></div> Redirecting to payment...';

    fetch('/payment/initiate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '_token=<?= e(\App\Core\CSRF::generate()) ?>'
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.error) {
            alert(data.error);
            resetBtn();
            return;
        }
        window.location.href = data.redirect_url;
    })
    .catch(function() {
        alert('Something went wrong. Please try again.');
        resetBtn();
    });
}

function resetBtn() {
    var btn = document.getElementById('payBtn');
    btn.disabled = false;
    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg> Pay ₹499 & Generate';
}
</script>
