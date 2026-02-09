// Workbox-enabled Service Worker with dynamic precaching and background sync
const CACHE_NAME = 'deped-eval-v2';

// Try to import Workbox from CDN (graceful fallback to manual manifest caching)
try {
    importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');
} catch (e) {
    // ignore
}

async function dynamicPrecache() {
    try {
        // Prefer Workbox precaching when available
        if (self.workbox && typeof fetch === 'function') {
            const manifestResp = await fetch('/sw-manifest.json', { cache: 'no-store' });
            if (manifestResp.ok) {
                const manifest = await manifestResp.json();
                if (Array.isArray(manifest)) {
                    // Workbox knows how to handle precache manifest entries
                    try { self.workbox.precaching.precacheAndRoute(manifest); return; } catch(e) { /* continue fallback */ }
                }
            }
        }
    } catch (e) { /* ignore and fallback */ }

    // Fallback: manual manifest fetch and cache
    try {
        const r = await fetch('/sw-manifest.json', { cache: 'no-store' });
        if (r.ok) {
            const manifest = await r.json();
            if (Array.isArray(manifest)) {
                const urls = manifest.map(m => m.url || m);
                const c = await caches.open(CACHE_NAME);
                await Promise.all(urls.map(u => c.add(u).catch(()=>{})));
            }
        }
    } catch (e) { /* ignore */ }
}

self.addEventListener('install', (event) => {
    event.waitUntil(dynamicPrecache());
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    // Network-first for API calls, cache-first for others
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(fetch(event.request).catch(() => caches.match(event.request)));
    } else {
        event.respondWith(caches.match(event.request).then(resp => resp || fetch(event.request)));
    }
});

// Background sync handler: send drafts saved in IndexedDB when connectivity returns
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-drafts') {
        event.waitUntil(syncDrafts());
    }
});

self.addEventListener('message', (event) => {
    // allow window to request a sync registration
    try {
        if (event.data && event.data.type === 'registerSync') {
            self.registration.sync.register('sync-drafts').catch(()=>{});
        }
    } catch (e) {}
});

async function syncDrafts() {
    try {
        const openReq = indexedDB.open('deped_eval_db', 1);
        const db = await new Promise((resolve, reject) => {
            openReq.onsuccess = (e) => resolve(e.target.result);
            openReq.onerror = (e) => reject(e.target.error);
        });
        const tx = db.transaction('drafts', 'readwrite');
        const store = tx.objectStore('drafts');
        const getAllReq = store.getAll();
        const drafts = await new Promise((resolve, reject) => {
            getAllReq.onsuccess = (e) => resolve(e.target.result);
            getAllReq.onerror = (e) => reject(e.target.error);
        });
        for (const rec of drafts) {
            try {
                const resp = await fetch('/api/save_draft.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(rec.data)
                });
                const j = await resp.json().catch(()=>null);
                if (j && j.success) {
                    try { store.delete(rec.id); } catch(e){}
                }
            } catch (e) {
                // leave draft in IDB for next sync
            }
        }
    } catch (e) {
        // sync failed - will retry later
    }
}

