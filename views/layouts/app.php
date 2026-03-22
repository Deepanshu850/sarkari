<?php
$_seo_title = ($pageTitle ?? 'AI-Powered 30-Day Exam Blueprint') . ' | ' . APP_NAME;
$_seo_desc = $pageDescription ?? 'Get your personalized 30-day study blueprint for Sarkari Naukri exams. AI-powered plan for SSC CGL, IBPS PO, RRB NTPC, UPSC & State PSC. Only ₹99.';
$_seo_url = base_url() . current_path();
$_seo_canonical = rtrim($_seo_url, '/') ?: base_url();
$_seo_image = base_url() . '/public/images/og-sarkari.png';
$_seo_keywords = $pageKeywords ?? 'sarkari naukri, government exam preparation, SSC CGL study plan, IBPS PO blueprint, RRB NTPC timetable, UPSC preparation, exam study schedule, AI study plan, 30 day study plan, sarkari exam strategy';
?>
<!DOCTYPE html>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2DB51VWB3L"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-2DB51VWB3L');
</script>
<!-- Microsoft Clarity -->
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "vzpyrt8jnh");
</script>
<html lang="hi-IN" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Primary SEO -->
    <title><?= e($_seo_title) ?></title>
    <meta name="description" content="<?= e($_seo_desc) ?>">
    <meta name="keywords" content="<?= e($_seo_keywords) ?>">
    <meta name="author" content="<?= e(APP_NAME) ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="<?= e($_seo_canonical) ?>">

    <!-- Language Alternates -->
    <link rel="alternate" hreflang="hi-IN" href="<?= e($_seo_canonical) ?>">
    <link rel="alternate" hreflang="en-IN" href="<?= e($_seo_canonical) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= e(base_url()) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($_seo_url) ?>">
    <meta property="og:title" content="<?= e($_seo_title) ?>">
    <meta property="og:description" content="<?= e($_seo_desc) ?>">
    <meta property="og:image" content="<?= e($_seo_image) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Sarkari - AI-Powered 30-Day Exam Blueprint for Government Exams">
    <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
    <meta property="og:locale" content="hi_IN">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($_seo_title) ?>">
    <meta name="twitter:description" content="<?= e($_seo_desc) ?>">
    <meta name="twitter:image" content="<?= e($_seo_image) ?>">

    <!-- PWA / Mobile -->
    <meta name="theme-color" content="#FF6B00">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Sarkari">
    <meta name="application-name" content="Sarkari Blueprint">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="manifest" href="/public/manifest.json">
    <link rel="apple-touch-icon" href="/public/images/icon-192.png">

    <!-- Geo Targeting (India) -->
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    <meta name="ICBM" content="20.5937, 78.9629">

    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?= e(APP_NAME) ?>",
        "url": "<?= e(base_url()) ?>",
        "logo": "<?= e(base_url()) ?>/public/images/logo.png",
        "description": "AI-powered personalized exam preparation blueprints for Indian government competitive exams",
        "foundingDate": "2026",
        "areaServed": {
            "@type": "Country",
            "name": "India"
        },
        "sameAs": []
    }
    </script>

    <!-- WebSite Schema (for sitelinks search) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "<?= e(APP_NAME) ?>",
        "url": "<?= e(base_url()) ?>",
        "description": "AI-powered 30-day personalized study blueprints for Sarkari Naukri exams",
        "inLanguage": ["hi-IN", "en-IN"],
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?= e(base_url()) ?>/?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <!-- BreadcrumbList Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "<?= e(base_url()) ?>"
            }
            <?php if (current_path() !== '/'): ?>
            ,{
                "@type": "ListItem",
                "position": 2,
                "name": "<?= e($pageTitle ?? 'Page') ?>",
                "item": "<?= e($_seo_url) ?>"
            }
            <?php endif; ?>
        ]
    }
    </script>

    <?= $schemaExtra ?? '' ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        saffron: { 50:'#FFF3E8',100:'#FFE4CC',200:'#FFC999',300:'#FFAD66',400:'#FF8C33',500:'#FF6B00',600:'#E55A00',700:'#CC4A00',800:'#993800',900:'#662500' },
                        navy: { 50:'#E8EBF0',100:'#C5CCD9',200:'#8B99B3',300:'#52668D',400:'#2A4070',500:'#1A2E56',600:'#0C1B3A',700:'#0A1630',800:'#071025',900:'#050B1A' },
                        india: { 50:'#E8F5EE',100:'#C5E8D5',200:'#8BD1AB',300:'#52BA82',400:'#2D9A5E',500:'#046A38',600:'#035C30',700:'#024D28',800:'#023E20',900:'#012F18' },
                        gold: { 50:'#FBF6EC',100:'#F5ECD8',200:'#EBD9B1',300:'#E0C68A',400:'#D4B363',500:'#C7973B',600:'#A67D2F',700:'#856323',800:'#644A1A',900:'#433211' },
                        parchment: '#FEFCF8',
                        cream: '#FBF8F1',
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'Georgia', 'serif'],
                        body: ['Noto Sans', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <?= $headExtra ?? '' ?>
</head>
<body class="bg-cream min-h-screen flex flex-col font-body">
    <!-- Tricolor Top Bar -->
    <div class="tricolor-bar"></div>

    <!-- Navigation -->
    <nav class="bg-navy-600 text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full border-2 border-gold-500 flex items-center justify-center bg-navy-500 group-hover:border-gold-300 transition">
                        <svg class="w-5 h-5 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L13.09 8.26L20 9L14.14 13.14L15.82 20L12 16.27L8.18 20L9.86 13.14L4 9L10.91 8.26L12 2Z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold tracking-wide">SARKARI</span>
                        <span class="hidden sm:block text-[10px] text-gold-400 tracking-[0.2em] uppercase -mt-1">Success Blueprint</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-1">
                    <!-- Language Toggle -->
                    <div class="relative mr-1">
                        <button id="langToggleBtn"
                            onclick="toggleLanguage()"
                            title="Language toggle"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-300 hover:text-white hover:bg-white/10 transition border border-white/10"
                            data-lang="en">
                            <span class="lang-hi">हिंदी</span>
                            <span class="text-white/30 mx-0.5">|</span>
                            <span class="lang-en font-black text-saffron-400">EN</span>
                        </button>
                        <div id="langTooltip"
                            class="absolute top-full right-0 mt-2 bg-navy-500 border border-gold-500/30 text-gold-300 text-xs font-semibold px-3 py-2 rounded-lg whitespace-nowrap shadow-xl opacity-0 pointer-events-none transition-opacity duration-300 z-50">
                            Full Hindi coming soon! 🇮🇳
                        </div>
                    </div>
                    <?php if (current_path() === '/' && !auth()): ?>
                        <!-- Landing page: minimal nav, no escape routes -->
                        <a href="#get-blueprint" class="px-5 py-2 bg-saffron-500 text-white rounded-lg text-sm font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">
                            Get Blueprint — ₹99
                        </a>
                    <?php elseif (auth()): ?>
                        <a href="/dashboard" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition <?= current_path() === '/dashboard' ? 'text-white bg-white/10' : '' ?>">My Blueprints</a>
                        <a href="/blueprint/step1" class="ml-2 px-5 py-2 bg-saffron-500 text-white rounded-lg text-sm font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            New Blueprint
                        </a>
                        <div class="relative group ml-3">
                            <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition">
                                <div class="w-8 h-8 bg-gold-500/20 border border-gold-500/40 rounded-full flex items-center justify-center">
                                    <span class="text-gold-400 font-bold text-sm"><?= e(strtoupper(substr(auth()['name'], 0, 1))) ?></span>
                                </div>
                                <span class="text-sm text-gray-300 hidden lg:inline"><?= e(auth()['name']) ?></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 overflow-hidden">
                                <div class="px-4 py-3 bg-cream border-b">
                                    <p class="text-sm font-semibold text-navy-600"><?= e(auth()['name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= e(auth()['email']) ?></p>
                                </div>
                                <a href="/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    My Blueprints
                                </a>
                                <?php if (is_admin()): ?>
                                    <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Admin Panel
                                    </a>
                                <?php endif; ?>
                                <hr class="my-1">
                                <a href="/logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition">Login</a>
                        <a href="/register" class="ml-2 px-5 py-2 bg-saffron-500 text-white rounded-lg text-sm font-bold hover:bg-saffron-600 transition shadow-lg shadow-saffron-500/20">Register</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-white/10">
            <div class="px-4 py-3 space-y-1">
                <?php if (auth()): ?>
                    <div class="px-4 py-2.5 border-b border-white/10 mb-1">
                        <p class="text-white font-semibold text-sm"><?= e(auth()['name']) ?></p>
                        <p class="text-gray-400 text-xs"><?= e(auth()['email']) ?> <?= plan_badge() ?></p>
                    </div>
                    <a href="/dashboard" class="block px-4 py-2.5 rounded-lg text-gray-300 hover:text-white hover:bg-white/10 font-medium">My Blueprints</a>
                    <a href="/upgrade" class="block px-4 py-2.5 rounded-lg text-gray-300 hover:text-white hover:bg-white/10 font-medium">Upgrade Plan</a>
                    <?php if (is_admin()): ?>
                        <a href="/admin" class="block px-4 py-2.5 rounded-lg text-gray-300 hover:text-white hover:bg-white/10 font-medium">Admin</a>
                    <?php endif; ?>
                    <a href="/logout" class="block px-4 py-2.5 rounded-lg text-red-400 hover:bg-white/10 font-medium">Logout</a>
                <?php elseif (current_path() === '/'): ?>
                    <a href="#get-blueprint" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-4 py-2.5 rounded-lg bg-saffron-500 text-white font-bold text-center">Get Blueprint — ₹99</a>
                <?php else: ?>
                    <a href="/login" class="block px-4 py-2.5 rounded-lg text-gray-300 hover:text-white hover:bg-white/10 font-medium">Login</a>
                <?php endif; ?>
                <!-- Mobile Language Toggle -->
                <div class="relative pt-1 border-t border-white/10 mt-1">
                    <button onclick="toggleLanguage(); document.getElementById('mobileMenu').classList.add('hidden');"
                        class="flex items-center gap-2 px-4 py-2.5 w-full rounded-lg text-gray-300 hover:text-white hover:bg-white/10 font-medium text-sm">
                        <span>🌐</span>
                        <span class="lang-hi">हिंदी</span>
                        <span class="text-white/30">|</span>
                        <span class="lang-en font-bold text-saffron-400">EN</span>
                        <span class="text-xs text-gray-500 ml-1">Toggle Language</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($msg = flash('success')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-india-50 border border-india-200 text-india-600 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-india-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-india-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="font-medium"><?= e($msg) ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-india-400 hover:text-india-600 text-xl leading-none">&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="font-medium"><?= e($msg) ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 text-xl leading-none">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-1">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-navy-600 text-gray-400 mt-auto">
        <div class="tricolor-bar"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full border-2 border-gold-500/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L13.09 8.26L20 9L14.14 13.14L15.82 20L12 16.27L8.18 20L9.86 13.14L4 9L10.91 8.26L12 2Z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-bold text-white tracking-wide">SARKARI</span>
                            <span class="block text-[10px] text-gold-400 tracking-[0.2em] uppercase">Success Blueprint</span>
                        </div>
                    </div>
                    <p class="text-gray-400 max-w-md leading-relaxed">Your AI-powered companion for Sarkari Naukri preparation. Get a personalized 30-day study blueprint tailored to your exam, your strengths, and your schedule.</p>
                    <div class="flex items-center gap-2 mt-4">
                        <span class="badge-official">Trusted by Aspirants</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm tracking-wider uppercase">Quick Links</h3>
                    <ul class="space-y-2.5">
                        <li><a href="/" class="hover:text-saffron-400 transition text-sm">Home</a></li>
                        <li><a href="/#get-blueprint" class="hover:text-saffron-400 transition text-sm">Get Blueprint</a></li>
                        <?php if (auth()): ?>
                            <li><a href="/dashboard" class="hover:text-saffron-400 transition text-sm">My Blueprints</a></li>
                        <?php else: ?>
                            <li><a href="/login" class="hover:text-saffron-400 transition text-sm">Already purchased? Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm tracking-wider uppercase">Exams Covered</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-saffron-500"></span> SSC (CGL, CHSL, MTS)</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-india-500"></span> Banking (IBPS, SBI)</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gold-500"></span> Railway (NTPC, Group D)</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-navy-300"></span> UPSC & State PSC</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
                <p>&copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.</p>
                <p class="flex items-center gap-2">
                    <span>Empowering India's aspirants with AI</span>
                    <span class="text-saffron-500">&#9679;</span>
                    <span>Jai Hind</span>
                </p>
            </div>
        </div>
    </footer>

    <script src="<?= asset('js/app.js') ?>"></script>

    <!-- Service Worker Registration -->
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/public/sw.js', { scope: '/' })
                .catch(function (err) {
                    console.warn('SW registration failed:', err);
                });
        });
    }
    </script>

    <!-- Hindi/English Language Toggle Logic -->
    <script>
    (function () {
        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(?:^|;\\s*)' + name + '=([^;]*)'));
            return match ? decodeURIComponent(match[1]) : null;
        }
        function setCookie(name, value, days) {
            var expires = '';
            if (days) {
                var d = new Date();
                d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
                expires = '; expires=' + d.toUTCString();
            }
            document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/; SameSite=Lax';
        }

        window.toggleLanguage = function () {
            var current = getCookie('lang') || 'en';
            var next = current === 'en' ? 'hi' : 'en';
            setCookie('lang', next, 365);

            var btn = document.getElementById('langToggleBtn');
            if (btn) {
                btn.setAttribute('data-lang', next);
                updateLangBtn(btn, next);
            }

            if (next === 'hi') {
                // Show tooltip "Full Hindi coming soon!"
                var tooltip = document.getElementById('langTooltip');
                if (tooltip) {
                    tooltip.classList.remove('opacity-0', 'pointer-events-none');
                    tooltip.classList.add('opacity-100');
                    setTimeout(function () {
                        tooltip.classList.remove('opacity-100');
                        tooltip.classList.add('opacity-0');
                    }, 2500);
                }
            }
        };

        function updateLangBtn(btn, lang) {
            var hiSpan = btn.querySelector('.lang-hi');
            var enSpan = btn.querySelector('.lang-en');
            if (lang === 'hi') {
                if (hiSpan) hiSpan.classList.add('font-black', 'text-saffron-400');
                if (enSpan) enSpan.classList.remove('font-black', 'text-saffron-400');
            } else {
                if (enSpan) enSpan.classList.add('font-black', 'text-saffron-400');
                if (hiSpan) hiSpan.classList.remove('font-black', 'text-saffron-400');
            }
        }

        // Init on page load
        window.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('langToggleBtn');
            if (btn) {
                var lang = getCookie('lang') || 'en';
                btn.setAttribute('data-lang', lang);
                updateLangBtn(btn, lang);
            }
        });
    })();
    </script>
</body>
</html>
