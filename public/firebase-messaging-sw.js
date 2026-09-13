// Firebase Messaging Service Worker for ThreadAX
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDZAYp4_s3itqdvuyWSZCXOZtG4HAe0ihU",
    authDomain: "threadax.firebaseapp.com",
    projectId: "threadax",
    storageBucket: "threadax.firebasestorage.app",
    messagingSenderId: "719254291980",
    appId: "1:719254291980:web:e3622d75bc599da91a5029"
};

try {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Background push notification handler for FCM messages
    messaging.onBackgroundMessage(function(payload) {
        console.log('[firebase-messaging-sw.js] Received background FCM message:', payload);

        const notificationTitle = payload.notification?.title || payload.data?.title || 'ThreadAX Streetwear';
        const notificationOptions = {
            body: payload.notification?.body || payload.data?.body || 'New exclusive release & updates!',
            icon: payload.notification?.icon || payload.data?.icon || '/android-chrome-192x192.png',
            image: payload.notification?.image || payload.data?.image || null,
            badge: '/favicon-48x48.png',
            vibrate: [200, 100, 200],
            tag: 'threadax-drop-' + Date.now(),
            renotify: true,
            requireInteraction: true,
            data: {
                click_action: payload.notification?.click_action || payload.data?.click_action || payload.data?.link || '/'
            },
            actions: [
                {
                    action: 'open_url',
                    title: 'View Drop ➔'
                }
            ]
        };

        return self.registration.showNotification(notificationTitle, notificationOptions);
    });
} catch (e) {
    console.warn('[firebase-messaging-sw.js] Firebase init error:', e);
}

// Fallback listener for standard Web Push events
self.addEventListener('push', function(event) {
    if (!event.data) return;

    try {
        const payload = event.data.json();
        // If FCM onBackgroundMessage already handles this, let it proceed; otherwise ensure notification shows
        const title = payload.notification?.title || payload.data?.title || payload.title || 'ThreadAX Streetwear';
        const body = payload.notification?.body || payload.data?.body || payload.body || 'New updates from ThreadAX';
        const icon = payload.notification?.icon || payload.data?.icon || '/android-chrome-192x192.png';
        const badge = '/favicon-48x48.png';
        const link = payload.notification?.click_action || payload.data?.click_action || payload.data?.link || payload.link || '/';

        event.waitUntil(
            self.registration.showNotification(title, {
                body: body,
                icon: icon,
                badge: badge,
                vibrate: [200, 100, 200],
                tag: 'threadax-alert',
                renotify: true,
                requireInteraction: true,
                data: { click_action: link },
                actions: [{ action: 'open_url', title: 'Open ➔' }]
            })
        );
    } catch (err) {
        // Plain text push payload fallback
        const text = event.data.text();
        if (text) {
            event.waitUntil(
                self.registration.showNotification('ThreadAX Streetwear', {
                    body: text,
                    icon: '/android-chrome-192x192.png',
                    badge: '/favicon-48x48.png',
                    data: { click_action: '/' }
                })
            );
        }
    }
});

// Notification click listener
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    const urlToOpen = event.notification.data?.click_action || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(windowClients) {
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});
