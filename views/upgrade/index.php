<?php
$currentPlan = user_plan();
$currentConfig = PLANS[$currentPlan] ?? PLANS['starter'];
$upgrades = available_upgrades();
?>

<section class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Plan Upgrade Karein</h1>
            <p class="text-gray-500 mt-2">Aapka current plan: <?= plan_badge() ?> — <?= $currentConfig['blueprints'] ?> blueprint<?= $currentConfig['blueprints'] > 1 ? 's' : '' ?></p>
        </div>

        <?php if (empty($upgrades)): ?>
        <!-- Already on highest plan -->
        <div class="paper rounded-2xl p-8 text-center paper-shadow">
            <span class="text-4xl mb-4 block">🎉</span>
            <h2 class="font-display text-xl font-bold text-navy-600 mb-2">Aap Already Ultimate Plan Pe Hain!</h2>
            <p class="text-gray-500 mb-6">Aapke paas sabse best plan hai — 3 blueprints, unlimited regeneration, lifetime access.</p>
            <a href="/dashboard" class="inline-flex items-center gap-2 px-6 py-3 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition">
                Dashboard Pe Jayein
            </a>
        </div>
        <?php else: ?>

        <!-- Current plan card -->
        <div class="paper rounded-xl p-5 mb-6 border-l-4 border-l-gold-400 bg-gold-50/50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-navy-600">Current Plan:</span>
                        <?= plan_badge() ?>
                    </div>
                    <p class="text-xs text-gray-500 mt-1"><?= $currentConfig['blueprints'] ?> blueprint<?= $currentConfig['blueprints'] > 1 ? 's' : '' ?> · ₹<?= $currentConfig['price'] ?> paid</p>
                </div>
                <span class="text-green-600 text-xs font-bold bg-green-50 border border-green-200 px-2 py-1 rounded">ACTIVE</span>
            </div>
        </div>

        <!-- Upgrade options -->
        <div class="grid <?= count($upgrades) > 1 ? 'md:grid-cols-2' : '' ?> gap-5">
            <?php foreach ($upgrades as $key => $upgrade): ?>
            <div class="paper rounded-2xl overflow-hidden paper-shadow border-2 <?= $key === 'pro' ? 'border-saffron-500 ring-4 ring-saffron-100' : 'border-navy-300 ring-4 ring-navy-100' ?> relative">
                <?php if ($key === 'pro'): ?>
                <div class="absolute -top-0 left-1/2 -translate-x-1/2 bg-saffron-500 text-white text-[10px] font-black px-4 py-1 rounded-b-lg tracking-widest">RECOMMENDED</div>
                <?php endif; ?>

                <div class="p-6">
                    <div class="text-center mb-5 <?= $key === 'pro' ? 'mt-3' : '' ?>">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"><?= e($upgrade['label']) ?> Plan</p>
                        <div class="flex items-center justify-center gap-3">
                            <span class="text-gray-400 line-through text-sm">₹<?= $upgrade['full_price'] ?></span>
                            <span class="text-4xl font-black text-navy-600">₹<?= $upgrade['upgrade_price'] ?></span>
                        </div>
                        <p class="text-xs text-india-600 font-semibold mt-1">
                            Sirf ₹<?= $upgrade['upgrade_price'] ?> aur pay karo (₹<?= $currentConfig['price'] ?> already paid)
                        </p>
                        <p class="text-xs text-gray-400 mt-1"><?= $upgrade['blueprints'] ?> Blueprints Total</p>
                    </div>

                    <ul class="space-y-2 mb-6">
                        <?php
                        $upgradeFeatures = [
                            'pro' => [
                                'Starter ke sab features +',
                                '2 AI-personalized blueprints',
                                'Edit & Regenerate free (7 din)',
                                'Priority AI generation',
                                'Referral rewards (₹100 off)',
                            ],
                            'ultimate' => [
                                'Pro ke sab features +',
                                '3 AI-personalized blueprints',
                                'Unlimited regenerations',
                                'Lifetime access',
                                'Future updates free',
                            ],
                        ];
                        foreach ($upgradeFeatures[$key] ?? [] as $f):
                        ?>
                        <li class="flex items-center gap-2 text-xs text-gray-700">
                            <svg class="w-3.5 h-3.5 text-india-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <?= $f ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <form method="POST" action="/upgrade/checkout">
                        <?= csrf_field() ?>
                        <input type="hidden" name="target_plan" value="<?= e($key) ?>">
                        <button type="submit"
                            class="w-full py-3.5 text-center rounded-xl font-bold transition text-sm
                            <?= $key === 'pro' ? 'bg-saffron-500 text-white hover:bg-saffron-600 shadow-lg shadow-saffron-500/20' : 'bg-navy-600 text-white hover:bg-navy-700 shadow-lg' ?>">
                            ₹<?= $upgrade['upgrade_price'] ?> Pay Karo — <?= e($upgrade['label']) ?> Upgrade
                        </button>
                    </form>

                    <p class="text-center text-[10px] text-gray-400 mt-3">Razorpay Secure · Instant upgrade · No data loss</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-6">
            <a href="/dashboard" class="text-sm text-gray-500 hover:text-navy-600 transition">← Dashboard pe vapas jayein</a>
        </div>
        <?php endif; ?>
    </div>
</section>
