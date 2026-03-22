<section class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-bold text-saffron-600 tracking-wider uppercase">Step 3 of 3</span>
                <span class="text-sm text-gray-500">Study Schedule</span>
            </div>
            <div class="w-full bg-gold-100 rounded-full h-2.5">
                <div class="bg-saffron-500 h-2.5 rounded-full transition-all shadow-sm" style="width: 100%"></div>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-[10px] text-india-500 font-bold">EXAM &#10003;</span>
                <span class="text-[10px] text-india-500 font-bold">BACKGROUND &#10003;</span>
                <span class="text-[10px] text-saffron-500 font-bold">SCHEDULE</span>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Study Schedule</h1>
            <p class="text-gray-500 mt-2">Kitna time de sakte hain daily?</p>
        </div>

        <form method="POST" action="/blueprint/step3" class="max-w-2xl mx-auto">
            <?= csrf_field() ?>

            <!-- Study Hours -->
            <div class="paper rounded-xl p-6 mb-6">
                <h2 class="font-bold text-navy-600 mb-1">Daily Study Hours</h2>
                <p class="text-xs text-gray-500 mb-6">Ek din mein kitne ghante padh sakte hain?</p>

                <div class="space-y-4">
                    <input type="range" name="study_hours" id="studyHours" min="1" max="14" step="0.5"
                        value="<?= e($draft['study_hours'] ?? '4') ?>"
                        class="w-full h-2.5 rounded-lg cursor-pointer">
                    <div class="flex justify-between text-xs text-gray-400 font-medium">
                        <span>1 hr</span>
                        <span>4 hrs</span>
                        <span>8 hrs</span>
                        <span>12 hrs</span>
                        <span>14 hrs</span>
                    </div>
                    <div class="text-center py-4 bg-saffron-50 border border-saffron-200 rounded-xl">
                        <span class="text-5xl font-black text-saffron-500" id="hoursDisplay"><?= e($draft['study_hours'] ?? '4') ?></span>
                        <span class="text-gray-500 ml-1 text-lg">hours/day</span>
                    </div>
                </div>
            </div>

            <!-- Exam Date -->
            <div class="paper rounded-xl p-6 mb-6">
                <h2 class="font-bold text-navy-600 mb-1">Exam Date</h2>
                <p class="text-xs text-gray-500 mb-4">Exam kab hai? (Minimum 7 days se zyada hona chahiye)</p>

                <input type="date" name="exam_date" id="examDate"
                    value="<?= e($draft['exam_date'] ?? '') ?>"
                    min="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                    max="<?= date('Y-m-d', strtotime('+1 year')) ?>"
                    required
                    class="w-full px-4 py-3.5 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 transition text-lg text-navy-600 font-semibold">

                <div class="mt-3 p-3 bg-navy-50 rounded-lg text-center" id="daysBox" style="display:none;">
                    <span class="text-2xl font-black text-navy-600" id="daysCount">-</span>
                    <span class="text-sm text-gray-500 ml-1">days remaining</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="/blueprint/step2" class="px-5 py-2.5 text-gray-500 hover:text-navy-600 font-medium flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Back
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20 flex items-center gap-2">
                    Blueprint Generate Karo
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
const slider = document.getElementById('studyHours');
const display = document.getElementById('hoursDisplay');
slider.addEventListener('input', () => display.textContent = slider.value);

const dateInput = document.getElementById('examDate');
const daysBox = document.getElementById('daysBox');
const daysCount = document.getElementById('daysCount');
function updateDays() {
    if (dateInput.value) {
        const diff = Math.ceil((new Date(dateInput.value) - new Date()) / 86400000);
        daysCount.textContent = diff;
        daysBox.style.display = '';
    }
}
dateInput.addEventListener('change', updateDays);
updateDays();
</script>
