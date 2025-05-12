// config.js
const config = {
    firebase: {
        apiKey: window.FIREBASE_API_KEY,
        authDomain: window.FIREBASE_AUTH_DOMAIN,
        projectId: window.FIREBASE_PROJECT_ID,
        storageBucket: window.FIREBASE_STORAGE_BUCKET,
        messagingSenderId: window.FIREBASE_MESSAGING_SENDER_ID,
        appId: window.FIREBASE_APP_ID
    },
    stripe: {
        publishableKey: window.STRIPE_PUBLISHABLE_KEY
    }
};

export default config; 