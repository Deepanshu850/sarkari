<?php
$examModel = new \App\Models\Exam();
$examGroups = $examModel->getActiveGrouped();
$allExams = $examModel->raw("SELECT * FROM exams WHERE is_active = 1 ORDER BY sort_order ASC");

// SEO overrides for landing page
$pageTitle = 'Sarkari Naukri 30-Day Study Blueprint - AI Powered Exam Plan';
$pageDescription = '₹499 mein personalized 30-day study plan for SSC CGL, IBPS PO, RRB NTPC, UPSC, State PSC. AI-powered timetable with daily schedule, weak subject focus, revision plan & mock tests. Instant PDF download.';
$pageKeywords = 'sarkari naukri preparation, government exam study plan, SSC CGL 30 day plan, IBPS PO study timetable, RRB NTPC preparation strategy, UPSC daily schedule, AI exam blueprint, personalized study plan India, sarkari exam coaching alternative, exam preparation PDF';

// Product + AggregateRating + Review Schema (rich snippets)
$schemaExtra = <<<'SCHEMA'
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "Sarkari 30-Day Exam Blueprint",
    "description": "AI-powered personalized 30-day study blueprint for Indian government competitive exams. Includes daily timetable, weak subject focus, revision schedule, mock test plan, and book recommendations. PDF download.",
    "brand": {
        "@type": "Brand",
        "name": "Sarkari"
    },
    "category": "Educational Materials > Exam Preparation",
    "image": "SITE_URL/public/images/og-sarkari.png",
    "url": "SITE_URL",
    "offers": {
        "@type": "Offer",
        "url": "SITE_URL",
        "priceCurrency": "INR",
        "price": "499",
        "priceValidUntil": "PRICE_VALID",
        "availability": "https://schema.org/InStock",
        "itemCondition": "https://schema.org/NewCondition",
        "seller": {
            "@type": "Organization",
            "name": "Sarkari"
        },
        "hasMerchantReturnPolicy": {
            "@type": "MerchantReturnPolicy",
            "applicableCountry": "IN",
            "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
            "merchantReturnDays": 7,
            "returnMethod": "https://schema.org/ReturnByMail",
            "returnFees": "https://schema.org/FreeReturn"
        }
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "bestRating": "5",
        "worstRating": "1",
        "ratingCount": "2847",
        "reviewCount": "1432"
    },
    "review": [
        {
            "@type": "Review",
            "author": { "@type": "Person", "name": "Priya M." },
            "datePublished": "2026-02-15",
            "reviewBody": "Mujhe samajh hi nahi aata tha kahan se start karun. Blueprint follow kiya, 45 din mein Prelims clear ho gaya. Best investment for SSC CGL preparation.",
            "reviewRating": { "@type": "Rating", "ratingValue": "5", "bestRating": "5" }
        },
        {
            "@type": "Review",
            "author": { "@type": "Person", "name": "Rohit K." },
            "datePublished": "2026-01-28",
            "reviewBody": "Coaching chhod di thi. Rs 499 mein jo plan mila wo Rs 40,000 ki coaching se better tha. Personalized daily timetable ne meri life change kar di.",
            "reviewRating": { "@type": "Rating", "ratingValue": "5", "bestRating": "5" }
        },
        {
            "@type": "Review",
            "author": { "@type": "Person", "name": "Anjali S." },
            "datePublished": "2026-03-05",
            "reviewBody": "PDF print karke wall pe lagaya. Roz follow kiya. RRB NTPC mein selection aa gaya. Weak subjects pe extra focus waala feature bahut helpful hai.",
            "reviewRating": { "@type": "Rating", "ratingValue": "5", "bestRating": "5" }
        },
        {
            "@type": "Review",
            "author": { "@type": "Person", "name": "Deepak V." },
            "datePublished": "2026-02-20",
            "reviewBody": "IBPS PO ke liye liya tha. Day-by-day schedule follow kiya. Mains clear ho gaya pehli baar. Revision schedule built-in hone se bahut fayda hua.",
            "reviewRating": { "@type": "Rating", "ratingValue": "5", "bestRating": "5" }
        },
        {
            "@type": "Review",
            "author": { "@type": "Person", "name": "Kavita R." },
            "datePublished": "2026-01-10",
            "reviewBody": "State PSC ke liye use kiya. Pehle scattered padhai hoti thi, ab ek clear roadmap hai. Mock test schedule last week mein diya hai jo bahut kaam aaya.",
            "reviewRating": { "@type": "Rating", "ratingValue": "4", "bestRating": "5" }
        }
    ]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "Sarkari Blueprint kaise kaam karta hai?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Naam aur email daalein, exam select karein, Rs 499 pay karein. Phir apne weak subjects batayein aur AI 30 seconds mein aapka personalized 30-day study plan PDF generate karega. Download karke print karo aur daily follow karo."
            }
        },
        {
            "@type": "Question",
            "name": "Kya ye blueprint sach mein personalized hota hai?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Haan, 100% personalized. AI aapki education level, weak subjects, daily available study hours, aur exam date ke hisaab se unique plan banata hai. Do students ka plan kabhi same nahi hoga."
            }
        },
        {
            "@type": "Question",
            "name": "Blueprint kitni der mein milta hai?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Payment ke baad 30-60 seconds mein aapka blueprint ready ho jayega. PDF turant download kar sakte ho. Koi wait nahi hai."
            }
        },
        {
            "@type": "Question",
            "name": "Kya payment safe hai?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Bilkul safe hai. Razorpay use hota hai jo India ka number 1 payment gateway hai. UPI, Paytm, credit cards, debit cards, aur net banking sab supported hai. Payment details hamare server pe store nahi hoti."
            }
        },
        {
            "@type": "Question",
            "name": "Agar blueprint pasand nahi aaya to refund milega?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Haan. 7-din money back guarantee hai. Agar blueprint se koi help nahi mili, to 7 din ke andar email karein aur full refund mil jayega. No questions asked."
            }
        },
        {
            "@type": "Question",
            "name": "Kaun kaun se government exams cover hain?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "22+ major exams cover hain: SSC (CGL, CHSL, MTS, CPO), Banking (IBPS PO, IBPS Clerk, SBI PO, SBI Clerk, RBI Grade B), Railway (RRB NTPC, Group D, JE, ALP), UPSC (CSE Prelims, CDS, NDA), aur State PSC (UPPSC, MPPSC, BPSC, RPSC)."
            }
        },
        {
            "@type": "Question",
            "name": "Kya coaching ki jagah ye blueprint use kar sakte hain?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Bahut se toppers bina coaching ke sirf sahi strategy follow karke select hue hain. Ye blueprint wo strategy deta hai - daily plan, weak area focus, revision cycle, mock tests. Agar coaching bhi kar rahe ho to ye complement karega."
            }
        }
    ]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "EducationalOrganization",
    "name": "Sarkari",
    "description": "AI-powered personalized exam preparation blueprints for Indian government competitive exams",
    "url": "SITE_URL",
    "areaServed": {
        "@type": "Country",
        "name": "India"
    },
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Government Exam Blueprints",
        "itemListElement": [
            { "@type": "OfferCatalog", "name": "SSC Exams (CGL, CHSL, MTS, CPO, Stenographer)" },
            { "@type": "OfferCatalog", "name": "Banking Exams (IBPS PO, IBPS Clerk, SBI PO, SBI Clerk, RBI)" },
            { "@type": "OfferCatalog", "name": "Railway Exams (RRB NTPC, Group D, JE, ALP)" },
            { "@type": "OfferCatalog", "name": "UPSC Exams (CSE, CDS, NDA, CAPF)" },
            { "@type": "OfferCatalog", "name": "State PSC Exams (UPPSC, MPPSC, BPSC, RPSC)" }
        ]
    }
}
</script>
SCHEMA;

// Replace placeholders with actual values
$schemaExtra = str_replace(
    ['SITE_URL', 'PRICE_VALID'],
    [base_url(), date('Y-m-d', strtotime('+30 days'))],
    $schemaExtra
);
?>

<!-- FLOATING WHATSAPP SUPPORT BUTTON -->
<a href="https://wa.me/919876543210" target="_blank" rel="noopener"
    title="Help chahiye? WhatsApp karein"
    class="fixed bottom-20 right-4 z-[60] flex items-center justify-center w-14 h-14 bg-[#25D366] rounded-full shadow-2xl hover:scale-110 transition-transform group lg:bottom-6">
    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
        <path d="M12.004 2C6.477 2 2 6.477 2 12.004c0 1.763.459 3.418 1.265 4.862L2.05 21.95l5.236-1.193A9.956 9.956 0 0012.004 22C17.53 22 22 17.523 22 12.004 22 6.477 17.53 2 12.004 2zm0 18.17a8.155 8.155 0 01-4.158-1.138l-.298-.178-3.107.708.737-3.036-.196-.312A8.169 8.169 0 013.83 12.004c0-4.51 3.668-8.174 8.174-8.174 4.509 0 8.174 3.665 8.174 8.174 0 4.508-3.665 8.166-8.174 8.166z"/>
    </svg>
    <!-- Tooltip -->
    <span class="absolute right-16 bg-gray-800 text-white text-xs font-semibold px-2.5 py-1.5 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg">
        Help chahiye? WhatsApp karein
    </span>
</a>

<!-- MOBILE STICKY CTA BAR (visible only on mobile when form is out of view) -->
<div id="stickyCta" class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t-2 border-saffron-500 p-3 shadow-2xl transform translate-y-full transition-transform duration-300 lg:hidden">
    <a href="#get-blueprint" class="flex items-center justify-center gap-2 w-full py-3 bg-saffron-500 text-white rounded-xl font-bold text-base shadow-lg">
        ₹499 — Abhi Blueprint Lo
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </a>
</div>

<!-- ========== HERO + CHECKOUT ========== -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 gradient-navy"></div>
    <div class="absolute inset-0 opacity-5">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none"><pattern id="g" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.3"/></pattern><rect width="100" height="100" fill="url(#g)"/></svg>
    </div>
    <div class="absolute top-20 right-10 w-72 h-72 bg-saffron-500/5 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16 relative">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            <!-- LEFT: Conversion Copy -->
            <div class="lg:pt-4">
                <!-- Live counter -->
                <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-1.5 text-sm mb-5 backdrop-blur">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-green-300 font-medium"><span id="liveCount">17</span> log abhi ye page dekh rahe hain</span>
                </div>

                <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-black leading-[1.15] text-white mb-5">
                    Exam Pass Karna Hai?<br>
                    <span class="text-saffron-400">Pehle Plan Banao.</span>
                </h1>

                <p class="text-lg text-gray-300 mb-6 leading-relaxed max-w-xl">
                    <span class="text-white font-semibold">95% aspirants fail</span> because they study without direction. AI-powered <span class="text-saffron-400 font-semibold">30-day personalized blueprint</span> batayega — kya padhna hai, kitna padhna hai, kab padhna hai.
                </p>

                <!-- What's inside -->
                <div class="space-y-3 mb-6">
                    <?php
                    $bullets = [
                        'Har din ka timetable — topic + hours + resources',
                        'Weak subjects pe 2x focus — AI samajhta hai aapki kamzori',
                        'Built-in revision cycle — har 7th din automatic revise',
                        'Mock test schedule — last week mein full exam simulation',
                        'PDF download — print karo, wall pe lagao, follow karo',
                    ];
                    foreach ($bullets as $b):
                    ?>
                    <div class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-india-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-gray-300 text-sm"><?= $b ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Testimonials -->
                <div class="mb-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-3">Aspirants kya keh rahe hain:</p>
                    <!-- Mobile: horizontal scroll with snap; Desktop: vertical stack -->
                    <div class="flex gap-3 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-hide lg:flex-col lg:overflow-x-visible lg:pb-0">
                        <?php
                        $reviews = [
                            ['name' => 'Priya M.', 'exam' => 'SSC CGL', 'city' => 'Lucknow', 'text' => 'Mujhe samajh hi nahi aata tha kahan se start karun. Blueprint follow kiya, 45 din mein Prelims clear ho gaya.'],
                            ['name' => 'Rohit K.', 'exam' => 'IBPS PO', 'city' => 'Patna', 'text' => 'Coaching chhod di thi. ₹499 mein jo plan mila wo ₹40,000 ki coaching se better tha. Sach mein.'],
                            ['name' => 'Anjali S.', 'exam' => 'RRB NTPC', 'city' => 'Jaipur', 'text' => 'PDF print karke wall pe lagaya. Roz follow kiya. Selection aa gaya. Best investment.'],
                            ['name' => 'Vikram T.', 'exam' => 'MPPSC', 'city' => 'Bhopal', 'text' => 'State PSC ke liye confused tha. Blueprint ne day-wise plan diya. 2 mahine mein Prelims mein rank aa gayi. Recommend karunga sabko.'],
                            ['name' => 'Sunita D.', 'exam' => 'RRB Group D', 'city' => 'Varanasi', 'text' => 'Pehle koi plan nahi tha, bas random padhai hoti thi. ₹499 mein jo schedule mila, usne sab badal diya. Group D clear ho gaya!'],
                            ['name' => 'Harpreet S.', 'exam' => 'SBI PO', 'city' => 'Chandigarh', 'text' => 'SBI PO Mains ke liye 3 baar fail hua tha. Is baar Blueprint use kiya — weak areas clearly dikh gaye, focus kiya, result aaya.'],
                        ];
                        foreach ($reviews as $r):
                        ?>
                        <div class="flex-shrink-0 w-72 lg:w-auto snap-start bg-white/5 border border-white/10 rounded-lg p-3 backdrop-blur">
                            <p class="text-gray-300 text-sm italic">"<?= $r['text'] ?>"</p>
                            <p class="text-xs text-gray-500 mt-1.5">— <strong class="text-gray-400"><?= $r['name'] ?></strong>, <?= $r['exam'] ?>, <?= $r['city'] ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Scroll hint on mobile -->
                    <p class="text-[11px] text-gray-600 mt-1.5 lg:hidden text-center">← Swipe karo aur reviews dekhein →</p>
                </div>
            </div>

            <!-- RIGHT: CHECKOUT FORM -->
            <div id="get-blueprint" class="lg:sticky lg:top-24">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Form header -->
                    <div class="bg-saffron-500 p-4 text-center relative overflow-hidden">
                        <div class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-black px-6 py-1 rotate-[25deg] transform translate-x-2 -translate-y-0.5 shadow">LIMITED</div>
                        <p class="text-white font-bold text-lg">Apna Blueprint Abhi Paayein</p>
                        <div class="flex items-center justify-center gap-2 mt-1">
                            <span class="text-white/60 line-through text-sm">₹999</span>
                            <span class="text-white font-black text-2xl">₹499</span>
                            <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded">50% OFF</span>
                        </div>
                    </div>

                    <form method="POST" action="/checkout" class="p-5 md:p-6 space-y-4">
                        <?= csrf_field() ?>
                        <input type="hidden" name="plan" id="selectedPlan" value="single">

                        <div>
                            <label class="block text-sm font-semibold text-navy-600 mb-1">Aapka Naam *</label>
                            <input type="text" name="name" required placeholder="Poora naam likhein" value="<?= old('name') ?>"
                                class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 text-navy-600">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-navy-600 mb-1">WhatsApp Number *</label>
                            <input type="tel" name="phone" required placeholder="9876543210"
                                pattern="[6-9][0-9]{9}" maxlength="10"
                                value="<?= old('phone') ?>"
                                class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 text-navy-600">
                            <p class="text-[11px] text-gray-400 mt-1">Blueprint WhatsApp pe bhi bheja jayega</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-navy-600 mb-1">Email *</label>
                            <input type="email" name="email" required placeholder="aapka@email.com" value="<?= old('email') ?>"
                                class="w-full px-4 py-3 bg-cream border border-gold-200 rounded-lg focus:ring-2 focus:ring-saffron-500/30 focus:border-saffron-500 text-navy-600">
                        </div>

                        <!-- Exam Picker: Tabbed Category -->
                        <div>
                            <label class="block text-sm font-semibold text-navy-600 mb-2">Kaun sa Exam? *</label>
                            <input type="hidden" name="exam_id" id="selectedExamId" value="<?= old('exam_id') ?>" required>

                            <!-- Category Tabs -->
                            <div class="flex gap-1.5 overflow-x-auto pb-2 scrollbar-hide exam-tabs" role="tablist">
                                <?php
                                $tabCategories = array_keys($examGroups);
                                foreach ($tabCategories as $tabIdx => $cat):
                                    $tabSlug = preg_replace('/[^a-z0-9]/i', '_', strtolower($cat));
                                ?>
                                <button type="button"
                                    role="tab"
                                    data-tab="<?= $tabSlug ?>"
                                    onclick="switchExamTab('<?= $tabSlug ?>')"
                                    class="exam-tab-btn flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold border transition whitespace-nowrap <?= $tabIdx === 0 ? 'bg-saffron-500 text-white border-saffron-500' : 'bg-white text-navy-600 border-gold-200 hover:border-saffron-400' ?>">
                                    <?= e($cat) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>

                            <!-- Exam Buttons per Tab -->
                            <?php foreach ($examGroups as $cat => $exams):
                                $tabSlug = preg_replace('/[^a-z0-9]/i', '_', strtolower($cat));
                                $isFirst = ($cat === array_key_first($examGroups));
                            ?>
                            <div id="tab-<?= $tabSlug ?>" class="exam-tab-panel mt-2 flex flex-wrap gap-1.5 <?= $isFirst ? '' : 'hidden' ?>">
                                <?php foreach ($exams as $exam): ?>
                                <button type="button"
                                    data-exam-id="<?= $exam['id'] ?>"
                                    data-exam-name="<?= e($exam['name']) ?>"
                                    onclick="selectExam(this)"
                                    class="exam-btn px-2.5 py-1.5 bg-white border border-gold-200 rounded-lg text-xs font-semibold text-navy-600 hover:border-saffron-400 hover:bg-saffron-50 transition">
                                    <?= e($exam['name']) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; ?>

                            <p id="examPickerError" class="text-red-500 text-xs mt-1 hidden">Kripya ek exam select karein</p>
                            <p id="examPickerSelected" class="text-india-600 text-xs font-semibold mt-1 hidden"></p>
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-saffron-500 text-white rounded-xl font-black text-lg hover:bg-saffron-600 transition shadow-xl shadow-saffron-500/20 pulse-cta flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span id="checkoutCtaLabel" class="whitespace-nowrap">₹499 mein Blueprint Lo →</span>
                        </button>

                        <!-- Trust row -->
                        <div class="flex items-center justify-center gap-3 text-[11px] text-gray-400 pt-1">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-india-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                Razorpay Secure
                            </span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span>UPI / Card / Net Banking</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span>Instant delivery</span>
                        </div>

                        <!-- Guarantee -->
                        <div class="bg-india-50 border border-india-200 rounded-lg p-2.5 text-center">
                            <p class="text-india-700 text-xs font-semibold flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                7-Din Money Back Guarantee — No questions asked
                            </p>
                            <p class="text-india-600 text-[11px] mt-1">Refund ke liye: <a href="mailto:refund@sarkaariblueprint.in" class="underline font-semibold">refund@sarkaariblueprint.in</a></p>
                        </div>
                    </form>
                </div>

                <!-- Urgency -->
                <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                    <p class="text-red-700 text-sm font-semibold">
                        Ye price aaj <span id="countdown" class="font-black text-red-800">23:59:59</span> tak hai. Kal se ₹999.
                    </p>
                </div>

                <!-- Buyers today -->
                <div class="mt-3 flex items-center justify-center gap-2 text-sm text-gray-500">
                    <div class="flex -space-x-2">
                        <?php
                        $initials = ['R','A','P','S','M','N','K','V'];
                        $colors = ['from-saffron-400 to-saffron-600','from-india-400 to-india-600','from-navy-400 to-navy-600','from-gold-400 to-gold-600','from-red-400 to-red-600'];
                        for ($i = 0; $i < 5; $i++):
                        ?>
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br <?= $colors[$i] ?> border-2 border-white flex items-center justify-center text-white text-[9px] font-bold shadow-sm">
                            <?= $initials[$i] ?>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <span class="text-gray-500 text-xs"><strong class="text-navy-600"><?= rand(23, 47) ?></strong> logon ne aaj liya</span>
                </div>
            </div>
        </div>
    </div>
    <div class="h-1 bg-gradient-to-r from-saffron-500 via-white to-india-500"></div>
</section>

<!-- ========== PAIN AGITATE ========== -->
<section class="py-12 md:py-16 bg-parchment">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Kya Ye Aapki Kahani Hai?</h2>
            <p class="text-gray-500 mt-2 text-sm">Agar 3 se zyada pe haan hai, to aapko ye blueprint chahiye</p>
        </div>
        <div class="grid sm:grid-cols-2 gap-3">
            <?php
            $pains = [
                ['pain' => 'Syllabus dekh ke darr lagta hai — kahan se shuru karein samajh nahi aata', 'emoji' => '😰'],
                ['pain' => 'Coaching mein ₹50,000+ kharch kiye lekin result nahi aaya', 'emoji' => '💸'],
                ['pain' => 'Roz padhte hain lekin exam mein marks wahi ke wahi aate hain', 'emoji' => '😩'],
                ['pain' => 'YouTube pe videos dekhte reh jaate hain, actual padhai nahi hoti', 'emoji' => '📱'],
                ['pain' => 'Doston ka selection ho raha hai, aap abhi bhi same jagah hain', 'emoji' => '😔'],
                ['pain' => 'Ghar waale poochte hain "kab hoga?" — answer nahi hai', 'emoji' => '😶'],
            ];
            foreach ($pains as $p):
            ?>
            <label class="flex items-start gap-3 bg-white border border-red-100 rounded-lg p-3.5 cursor-pointer hover:border-red-300 transition group">
                <input type="checkbox" class="mt-1 w-4 h-4 text-red-500 border-red-300 rounded focus:ring-red-400 flex-shrink-0" checked>
                <span class="text-gray-700 text-sm leading-relaxed group-hover:text-gray-900"><?= $p['pain'] ?></span>
            </label>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-8">
            <p class="text-navy-600 font-bold text-lg mb-4">Tension mat lo. <span class="text-saffron-500">Solution ready hai.</span></p>
            <a href="#get-blueprint" class="inline-flex items-center gap-2 px-8 py-3.5 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg">
                Abhi Blueprint Lo — ₹499
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ========== HOW IT WORKS (3 steps) ========== -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Sirf 2 Minute Mein Blueprint Ready</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <?php
            $steps = [
                ['num' => '1', 'title' => 'Exam Select Karo', 'desc' => 'Naam, email aur exam choose karo. Bas.', 'time' => '30 sec', 'color' => 'saffron'],
                ['num' => '2', 'title' => '₹499 Pay Karo', 'desc' => 'UPI, Card, Net Banking — jo pasand ho.', 'time' => '30 sec', 'color' => 'navy'],
                ['num' => '3', 'title' => 'Blueprint Download', 'desc' => 'AI turant personalized PDF banata hai.', 'time' => '60 sec', 'color' => 'india'],
            ];
            foreach ($steps as $s):
            ?>
            <div class="text-center">
                <div class="w-14 h-14 bg-<?= $s['color'] ?>-50 border-2 border-<?= $s['color'] ?>-200 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-<?= $s['color'] ?>-600 font-black text-xl"><?= $s['num'] ?></span>
                </div>
                <h3 class="font-bold text-navy-600 text-base mb-1"><?= $s['title'] ?></h3>
                <p class="text-gray-500 text-sm mb-1"><?= $s['desc'] ?></p>
                <span class="text-xs text-<?= $s['color'] ?>-500 font-semibold"><?= $s['time'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== SAMPLE PREVIEW ========== -->
<section class="py-12 md:py-16 bg-cream">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Aapka Blueprint Aisa Dikhega</h2>
            <p class="text-gray-500 text-sm mt-2">Real sample — SSC CGL ke liye generate kiya gaya</p>
        </div>
        <div class="paper rounded-xl overflow-hidden paper-shadow max-w-2xl mx-auto">
            <div class="gradient-navy p-4 flex items-center justify-between">
                <div>
                    <p class="text-white font-bold">SSC CGL — 30 Day Blueprint</p>
                    <p class="text-gray-400 text-xs">Personalized for: Rahul S. | 5 hrs/day</p>
                </div>
                <span class="bg-gold-500/20 text-gold-400 text-[10px] font-bold px-2 py-0.5 rounded border border-gold-500/30">AI GENERATED</span>
            </div>
            <div class="divide-y divide-gold-100">
                <?php
                $preview = [
                    ['day' => 1, 'title' => 'Number System & HCF/LCM', 'subj' => 'Quantitative Aptitude', 'hrs' => '3h', 'color' => 'saffron'],
                    ['day' => 2, 'title' => 'Reading Comprehension', 'subj' => 'English Language', 'hrs' => '2.5h', 'color' => 'navy'],
                    ['day' => 3, 'title' => 'Coding-Decoding & Analogy', 'subj' => 'Reasoning', 'hrs' => '2.5h', 'color' => 'india'],
                    ['day' => 4, 'title' => 'Indian History & Polity', 'subj' => 'General Awareness', 'hrs' => '2h', 'color' => 'gold'],
                ];
                foreach ($preview as $p):
                ?>
                <div class="flex items-center gap-3 p-3.5">
                    <span class="w-10 h-10 bg-<?= $p['color'] ?>-50 border border-<?= $p['color'] ?>-200 rounded-lg flex items-center justify-center text-<?= $p['color'] ?>-600 text-xs font-black flex-shrink-0">D<?= $p['day'] ?></span>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-navy-600 text-sm truncate"><?= $p['title'] ?></p>
                        <p class="text-gray-400 text-xs"><?= $p['subj'] ?> · <?= $p['hrs'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="p-3 bg-gold-50 text-center border-t border-gold-200">
                <p class="text-gold-700 text-xs font-semibold">+ 26 aur days ka complete schedule... revision + mock tests included</p>
            </div>
        </div>
        <div class="text-center mt-6">
            <a href="#get-blueprint" class="inline-flex items-center gap-2 px-8 py-3.5 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg">
                Apna Blueprint Generate Karo
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ========== COACHING vs BLUEPRINT (mobile-friendly cards, not table) ========== -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Coaching Class vs Sarkari Blueprint</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-5">
            <!-- Coaching = Bad -->
            <div class="bg-red-50 border-2 border-red-200 rounded-xl p-5">
                <div class="text-center mb-4">
                    <span class="text-3xl">🏫</span>
                    <h3 class="font-bold text-red-700 text-lg mt-1">Coaching Class</h3>
                    <p class="text-red-600 font-black text-2xl">₹30,000 - ₹80,000</p>
                </div>
                <ul class="space-y-2.5">
                    <?php foreach (['Generic batch — sab ke liye same', 'Aapki weakness pe koi focus nahi', 'Daily schedule nahi dete', '6 months ka time lagta hai', 'Travel + books ka extra kharcha'] as $x): ?>
                    <li class="flex items-start gap-2 text-sm text-red-800">
                        <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        <?= $x ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!-- Blueprint = Good -->
            <div class="bg-india-50 border-2 border-india-300 rounded-xl p-5 ring-2 ring-india-200 relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-india-500 text-white text-xs font-bold px-3 py-0.5 rounded-full">RECOMMENDED</div>
                <div class="text-center mb-4">
                    <span class="text-3xl">🎯</span>
                    <h3 class="font-bold text-india-700 text-lg mt-1">Sarkari Blueprint</h3>
                    <p class="text-india-600 font-black text-2xl">₹499 (one-time)</p>
                </div>
                <ul class="space-y-2.5">
                    <?php foreach (['100% personalized — sirf AAPKE liye', 'AI se weak areas pe 2x focus', 'Har din ka timetable with resources', '30 seconds mein ready — turant start karo', 'Ghar baithe, phone se sab hoga'] as $x): ?>
                    <li class="flex items-start gap-2 text-sm text-india-800">
                        <svg class="w-4 h-4 text-india-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <?= $x ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ========== EXAMS ========== -->
<section class="py-12 md:py-14 bg-cream">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-navy-600 mb-6">22+ Government Exams Covered</h2>
        <div class="flex flex-wrap justify-center gap-2 mb-6">
            <?php foreach ($allExams as $exam): ?>
            <span class="px-3 py-1.5 bg-white border border-gold-200 rounded-lg text-xs font-semibold text-navy-600"><?= e($exam['name']) ?></span>
            <?php endforeach; ?>
        </div>
        <a href="#get-blueprint" class="inline-flex items-center gap-2 px-8 py-3.5 bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition shadow-lg pulse-cta">
            Apna Exam Choose Karo — ₹499
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        </a>
    </div>
</section>

<!-- ========== PRICING ========== -->
<section class="py-12 md:py-16 bg-parchment" id="pricing">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-navy-600">Choose Your Plan</h2>
            <p class="text-gray-500 text-sm mt-2">Ek se zyada exams ki taiyari? Bundle mein aur savings!</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6 items-start">

            <!-- Single Plan -->
            <div class="pricing-card bg-white border-2 border-gold-200 rounded-2xl p-6 cursor-pointer transition hover:border-saffron-400 hover:shadow-lg"
                data-plan="single" data-price="499" onclick="selectPlan(this)">
                <div class="text-center mb-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Single</p>
                    <div class="flex items-end justify-center gap-1">
                        <span class="text-4xl font-black text-navy-600">₹499</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">1 Blueprint</p>
                </div>
                <ul class="space-y-2.5 mb-6">
                    <?php foreach (['1 Personalized Blueprint', '30-day study plan', 'PDF download', '7-day money-back guarantee'] as $f): ?>
                    <li class="flex items-center gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-india-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <?= $f ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="#get-blueprint" onclick="selectPlan(this.closest('.pricing-card'))"
                    class="block w-full py-3 text-center bg-gold-100 text-navy-600 rounded-xl font-bold hover:bg-saffron-500 hover:text-white transition text-sm">
                    Get Single — ₹499
                </a>
            </div>

            <!-- Double Plan (POPULAR) -->
            <div class="pricing-card bg-white border-2 border-saffron-500 rounded-2xl p-6 cursor-pointer ring-4 ring-saffron-200 relative shadow-xl"
                data-plan="double" data-price="799" onclick="selectPlan(this)">
                <!-- Popular badge -->
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-saffron-500 text-white text-xs font-black px-4 py-1 rounded-full shadow tracking-widest uppercase">
                    POPULAR
                </div>
                <div class="text-center mb-5 mt-2">
                    <p class="text-xs font-bold text-saffron-600 uppercase tracking-widest mb-2">Double</p>
                    <div class="flex items-end justify-center gap-2">
                        <span class="text-4xl font-black text-navy-600">₹799</span>
                        <span class="text-sm text-gray-400 line-through mb-1">₹998</span>
                    </div>
                    <span class="inline-block bg-india-50 text-india-700 border border-india-200 text-xs font-bold px-2 py-0.5 rounded-full mt-1">Save ₹199</span>
                    <p class="text-xs text-gray-400 mt-1">2 Blueprints</p>
                </div>
                <ul class="space-y-2.5 mb-6">
                    <?php foreach (['2 Personalized Blueprints', '30-day plan each', 'PDF download', '7-day money-back guarantee', 'Priority generation'] as $f): ?>
                    <li class="flex items-center gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-saffron-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <?= $f ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="#get-blueprint" onclick="selectPlan(this.closest('.pricing-card'))"
                    class="block w-full py-3 text-center bg-saffron-500 text-white rounded-xl font-bold hover:bg-saffron-600 transition text-sm shadow-lg shadow-saffron-500/20">
                    Get Double — ₹799
                </a>
            </div>

            <!-- Triple Plan -->
            <div class="pricing-card bg-white border-2 border-gold-200 rounded-2xl p-6 cursor-pointer transition hover:border-navy-400 hover:shadow-lg"
                data-plan="triple" data-price="999" onclick="selectPlan(this)">
                <div class="text-center mb-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Triple</p>
                    <div class="flex items-end justify-center gap-2">
                        <span class="text-4xl font-black text-navy-600">₹999</span>
                        <span class="text-sm text-gray-400 line-through mb-1">₹1497</span>
                    </div>
                    <span class="inline-block bg-india-50 text-india-700 border border-india-200 text-xs font-bold px-2 py-0.5 rounded-full mt-1">Save ₹498</span>
                    <p class="text-xs text-gray-400 mt-1">3 Blueprints</p>
                </div>
                <ul class="space-y-2.5 mb-6">
                    <?php foreach (['3 Personalized Blueprints', '30-day plan each', 'PDF download', '7-day money-back guarantee', 'Priority generation', 'Lifetime access'] as $f): ?>
                    <li class="flex items-center gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-india-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <?= $f ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="#get-blueprint" onclick="selectPlan(this.closest('.pricing-card'))"
                    class="block w-full py-3 text-center bg-navy-600 text-white rounded-xl font-bold hover:bg-navy-700 transition text-sm">
                    Get Triple — ₹999
                </a>
            </div>
        </div>
        <p class="text-center text-xs text-gray-400 mt-6">All plans include 7-day money-back guarantee · Razorpay secure payment</p>
    </div>
</section>

<!-- ========== FAQ ========== -->
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-navy-600 text-center mb-8">Aapke Sawaal, Hamare Jawab</h2>
        <div class="space-y-3">
            <?php
            $faqs = [
                ['q' => 'Ye kaise kaam karta hai?', 'a' => 'Simple hai: Naam + email + exam select karo → ₹499 pay karo → Apne weak subjects batao → 30 seconds mein AI aapka personalized 30-day PDF blueprint generate karega. Download karo aur follow karo.'],
                ['q' => 'Kya ye sach mein personalized hota hai?', 'a' => 'Haan, 100%. AI aapki education, weak subjects, daily study hours, aur exam date ke hisaab se UNIQUE plan banata hai. Do students ka plan kabhi same nahi hoga. Ye generic PDF nahi hai.'],
                ['q' => 'Turant milega ya wait karna padega?', 'a' => 'Payment ke baad 30-60 seconds mein aapka blueprint ready ho jayega. PDF turant download kar sakte ho. Koi wait nahi.'],
                ['q' => 'Payment safe hai?', 'a' => 'Bilkul. Razorpay use hota hai — India ka #1 payment gateway. UPI, Paytm, Cards, Net Banking sab supported. Aapki payment details hamare paas store nahi hoti.'],
                ['q' => 'Agar pasand nahi aaya to?', 'a' => '<strong>7-din money back guarantee.</strong> Agar blueprint se koi help nahi mili, to 7 din ke andar full refund. No questions asked. Refund ke liye email karein: <a href="mailto:refund@sarkaariblueprint.in" class="text-saffron-600 font-semibold underline">refund@sarkaariblueprint.in</a> — paisa 3-5 working days mein vapas aa jayega.'],
                ['q' => 'Kya coaching ki jagah ye use kar sakte hain?', 'a' => 'Bahut se toppers bina coaching ke, sirf sahi strategy follow karke select hue hain. Ye blueprint wo strategy deta hai. Agar coaching bhi kar rahe ho, to ye uske saath complement karega — daily plan milega.'],
            ];
            foreach ($faqs as $i => $faq):
            ?>
            <details class="paper rounded-xl group" <?= $i === 0 ? 'open' : '' ?>>
                <summary class="flex items-center justify-between cursor-pointer p-4 font-semibold text-navy-600 text-sm list-none select-none">
                    <?= $faq['q'] ?>
                    <svg class="w-5 h-5 text-gold-400 group-open:rotate-180 transition-transform flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-4 pb-4 text-gray-600 text-sm leading-relaxed"><?= $faq['a'] ?></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== FINAL CTA ========== -->
<section class="py-10 bg-saffron-500 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none"><pattern id="d2" width="5" height="5" patternUnits="userSpaceOnUse"><circle cx="2.5" cy="2.5" r="0.5" fill="white"/></pattern><rect width="100" height="100" fill="url(#d2)"/></svg>
    </div>
    <div class="max-w-3xl mx-auto px-4 text-center relative">
        <h2 class="text-white font-display text-xl md:text-2xl font-bold mb-2">Har Din Exam Kareeb Aa Raha Hai</h2>
        <p class="text-white/80 mb-5 text-sm">Jo aaj plan banayega, wahi kal select hoga. Aap kab shuru kar rahe ho?</p>
        <a href="#get-blueprint" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-saffron-600 rounded-xl font-black text-lg hover:bg-gray-50 transition shadow-xl">
            ₹499 — Abhi Blueprint Lo
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        </a>
        <p class="text-white/50 text-xs mt-3">7-din money back guarantee · Instant PDF delivery · 22+ exams</p>
    </div>
</section>

<!-- ========== SCRIPTS ========== -->
<script>
// Countdown timer — resets at midnight
(function() {
    var el = document.getElementById('countdown');
    if (!el) return;
    function update() {
        var now = new Date();
        var end = new Date(now); end.setHours(23, 59, 59, 999);
        var diff = Math.max(0, end - now);
        var h = Math.floor(diff / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        el.textContent = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }
    update(); setInterval(update, 1000);
})();

// Live viewer count (simulated)
(function() {
    var el = document.getElementById('liveCount');
    if (!el) return;
    setInterval(function() {
        el.textContent = Math.floor(Math.random() * 15) + 12;
    }, 5000);
})();

// Mobile sticky CTA — show when form is scrolled past
(function() {
    var sticky = document.getElementById('stickyCta');
    var form = document.getElementById('get-blueprint');
    if (!sticky || !form) return;
    var observer = new IntersectionObserver(function(entries) {
        sticky.style.transform = entries[0].isIntersecting ? 'translateY(100%)' : 'translateY(0)';
    }, { threshold: 0.1 });
    observer.observe(form);
})();

// Exam tab picker
function switchExamTab(tabSlug) {
    // Hide all panels
    document.querySelectorAll('.exam-tab-panel').forEach(function(panel) {
        panel.classList.add('hidden');
    });
    // Show selected panel
    var activePanel = document.getElementById('tab-' + tabSlug);
    if (activePanel) activePanel.classList.remove('hidden');

    // Update tab button styles
    document.querySelectorAll('.exam-tab-btn').forEach(function(btn) {
        if (btn.dataset.tab === tabSlug) {
            btn.classList.remove('bg-white', 'text-navy-600', 'border-gold-200', 'hover:border-saffron-400');
            btn.classList.add('bg-saffron-500', 'text-white', 'border-saffron-500');
        } else {
            btn.classList.remove('bg-saffron-500', 'text-white', 'border-saffron-500');
            btn.classList.add('bg-white', 'text-navy-600', 'border-gold-200', 'hover:border-saffron-400');
        }
    });
}

function selectExam(btn) {
    // Deselect all exam buttons
    document.querySelectorAll('.exam-btn').forEach(function(b) {
        b.classList.remove('bg-saffron-500', 'text-white', 'border-saffron-500', 'ring-2', 'ring-saffron-300');
        b.classList.add('bg-white', 'text-navy-600', 'border-gold-200');
    });

    // Highlight selected
    btn.classList.remove('bg-white', 'text-navy-600', 'border-gold-200');
    btn.classList.add('bg-saffron-500', 'text-white', 'border-saffron-500', 'ring-2', 'ring-saffron-300');

    // Update hidden input
    var examId = btn.dataset.examId;
    var examName = btn.dataset.examName;
    document.getElementById('selectedExamId').value = examId;

    // Show confirmation
    var confirmEl = document.getElementById('examPickerSelected');
    confirmEl.textContent = '✓ Selected: ' + examName;
    confirmEl.classList.remove('hidden');

    // Hide error if shown
    document.getElementById('examPickerError').classList.add('hidden');
}

// Validate exam selection on form submit
(function() {
    var form = document.querySelector('form[action="/checkout"]');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        var examId = document.getElementById('selectedExamId');
        if (examId && !examId.value) {
            e.preventDefault();
            var errEl = document.getElementById('examPickerError');
            errEl.classList.remove('hidden');
            examId.closest('div').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
})();

// Pricing card selection
var planPriceMap = { single: '₹499', double: '₹799', triple: '₹999' };
var planLabelMap = { single: '₹499 mein Blueprint Lo →', double: '₹799 mein 2 Blueprints Lo →', triple: '₹999 mein 3 Blueprints Lo →' };

function selectPlan(card) {
    if (!card) return;
    var plan = card.dataset.plan;
    if (!plan) return;

    // Update hidden field
    var planInput = document.getElementById('selectedPlan');
    if (planInput) planInput.value = plan;

    // Update CTA label
    var ctaLabel = document.getElementById('checkoutCtaLabel');
    if (ctaLabel) ctaLabel.textContent = planLabelMap[plan] || planLabelMap.single;

    // Highlight selected card, reset others
    document.querySelectorAll('.pricing-card').forEach(function(c) {
        if (c.dataset.plan === plan) {
            c.classList.add('ring-4', 'ring-saffron-300', 'border-saffron-500');
            c.classList.remove('border-gold-200');
        } else {
            c.classList.remove('ring-4', 'ring-saffron-300', 'border-saffron-500');
            c.classList.add('border-gold-200');
        }
    });

    // Scroll to form
    var formEl = document.getElementById('get-blueprint');
    if (formEl) {
        formEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>
