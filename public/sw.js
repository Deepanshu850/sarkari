// Sarkari Blueprint — Service Worker
// Cache-first for static assets, network-first for HTML pages.

const CACHE_VERSION = 'sarkari-v1';
const STATIC_CACHE  = CACHE_VERSION + '-static';
const PAGE_CACHE    = CACHE_VERSION + '-pages';
const OFFLINE_URL   = '/offline';

// App shell: static assets to pre-cache on install
const APP_SHELL = [
    '/public/css/app.css',
    '/public/js/app.js',
    'https://cdn.tailwindcss.com',
    'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Noto+Sans:wght@400;500;600;700&display=swap',
];

// ── Install: pre-cache the app shell ────────────────────────────────────────
self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(STATIC_CACHE).then(function (cache) {
            return cache.addAll(APP_SHELL).catch(function (err) {
                console.warn('[SW] App shell pre-cache partial failure:', err);
            });
        }).then(function () {
            return self.skipWaiting();
        })
    );
});

// ── Activate: clean up old caches ───────────────────────────────────────────
self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys.filter(function (key) {
                    return key.startsWith('sarkari-') && key !== STATIC_CACHE && key !== PAGE_CACHE;
                }).map(function (key) {
                    return caches.delete(key);
                })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

// ── Fetch: routing strategy ─────────────────────────────────────────────────
self.addEventListener('fetch', function (event) {
    var url = new URL(event.request.url);

    // Only handle GET requests on same origin (or approved CDN)
    if (event.request.method !== 'GET') return;

    // Skip non-http(s) schemes (chrome-extension, etc.)
    if (!url.protocol.startsWith('http')) return;

    // Skip API / admin / auth routes — always go to network
    if (url.pathname.startsWith('/api/') ||
        url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/login') ||
        url.pathname.startsWith('/logout') ||
        url.pathname.startsWith('/register') ||
        url.pathname.startsWith('/checkout') ||
        url.pathname.startsWith('/callback')) {
        return;
    }

    // Static assets (CSS, JS, fonts, images) — Cache First
    if (isStaticAsset(url)) {
        event.respondWith(cacheFirst(event.request, STATIC_CACHE));
        return;
    }

    // Blueprint view pages — cache for offline reading, but prefer fresh
    if (url.pathname.startsWith('/blueprint/') && !url.pathname.includes('/download')) {
        event.respondWith(networkFirstWithPageCache(event.request));
        return;
    }

    // All other HTML — Network First, fall back to cache then offline page
    if (event.request.headers.get('Accept') && event.request.headers.get('Accept').includes('text/html')) {
        event.respondWith(networkFirstWithOfflineFallback(event.request));
        return;
    }
});

// ── Strategy: Cache First ────────────────────────────────────────────────────
function cacheFirst(request, cacheName) {
    return caches.open(cacheName).then(function (cache) {
        return cache.match(request).then(function (cached) {
            if (cached) return cached;
            return fetch(request).then(function (response) {
                if (response && response.status === 200) {
                    cache.put(request, response.clone());
                }
                return response;
            });
        });
    });
}

// ── Strategy: Network First (HTML pages) ────────────────────────────────────
function networkFirstWithOfflineFallback(request) {
    return fetch(request).then(function (response) {
        if (response && response.status === 200) {
            caches.open(PAGE_CACHE).then(function (cache) {
                cache.put(request, response.clone());
            });
        }
        return response;
    }).catch(function () {
        return caches.open(PAGE_CACHE).then(function (cache) {
            return cache.match(request).then(function (cached) {
                return cached || buildOfflinePage();
            });
        });
    });
}

// ── Strategy: Network First + cache blueprint pages ─────────────────────────
function networkFirstWithPageCache(request) {
    return fetch(request).then(function (response) {
        if (response && response.status === 200) {
            caches.open(PAGE_CACHE).then(function (cache) {
                cache.put(request, response.clone());
            });
        }
        return response;
    }).catch(function () {
        return caches.open(PAGE_CACHE).then(function (cache) {
            return cache.match(request).then(function (cached) {
                return cached || buildOfflinePage();
            });
        });
    });
}

// ── Offline Fallback Page ────────────────────────────────────────────────────
function buildOfflinePage() {
    var html = '<!DOCTYPE html><html lang="hi-IN"><head>' +
        '<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">' +
        '<meta name="theme-color" content="#FF6B00">' +
        '<title>Offline — Sarkari Blueprint</title>' +
        '<style>' +
        'body{margin:0;font-family:sans-serif;background:#0C1B3A;color:#fff;display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;padding:1rem;}' +
        '.box{max-width:380px;}' +
        '.icon{font-size:4rem;margin-bottom:1rem;}' +
        'h1{font-size:1.5rem;font-weight:900;color:#FF6B00;margin-bottom:.5rem;}' +
        'p{color:#94a3b8;margin-bottom:1.5rem;line-height:1.6;}' +
        'a{display:inline-block;padding:.75rem 2rem;background:#FF6B00;color:#fff;border-radius:.75rem;font-weight:700;text-decoration:none;}' +
        '</style></head><body>' +
        '<div class="box">' +
        '<div class="icon">📶</div>' +
        '<h1>Internet Nahi Hai</h1>' +
        '<p>Lagta hai aapka internet connection off hai. Blueprint dekhne ke liye online hona zaroori hai.<br><br>Agar pehle blueprint khola tha to wo cached ho sakta hai — try again karein.</p>' +
        '<a href="javascript:location.reload()">Dobara Try Karein</a>' +
        '</div>' +
        '</body></html>';
    return new Response(html, { headers: { 'Content-Type': 'text/html; charset=utf-8' } });
}

// ── Helper: detect static assets ────────────────────────────────────────────
function isStaticAsset(url) {
    var ext = url.pathname.split('.').pop().toLowerCase();
    var staticExts = ['css', 'js', 'woff', 'woff2', 'ttf', 'otf', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'webp'];
    if (staticExts.indexOf(ext) !== -1) return true;
    // CDN resources
    if (url.hostname === 'cdn.tailwindcss.com') return true;
    if (url.hostname === 'fonts.googleapis.com') return true;
    if (url.hostname === 'fonts.gstatic.com') return true;
    return false;
}
