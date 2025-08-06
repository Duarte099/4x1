self.addEventListener("install", (event) => {
  console.log("Service Worker: Instalado");
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(urlsToCache))
  );
});

self.addEventListener("activate", (event) => {
  console.log("Service Worker: Ativado");
});

self.addEventListener("fetch", (event) => {
  console.log("Service Worker: Intercetar", event.request.url);
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});