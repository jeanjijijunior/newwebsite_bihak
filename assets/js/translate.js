/**
 * Google Translate Integration
 * Handles language switching for the Bihak Center website
 */

// Load Google Translate API
function loadGoogleTranslate() {
    let googleTranslateScript = document.createElement('script');
    googleTranslateScript.src = "https://translate.google.com/translate_a/element.js?cb=initGoogleTranslate";
    document.body.appendChild(googleTranslateScript);
}

// Initialize Google Translate
function initGoogleTranslate() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,fr',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}

// Change Language Function
function changeLanguage(lang) {
    if (!window.google || !window.google.translate) {
        loadGoogleTranslate();
    }

    const interval = setInterval(() => {
        const select = document.querySelector(".goog-te-combo");
        if (select) {
            select.value = lang;
            select.dispatchEvent(new Event("change"));
            clearInterval(interval);
        }
    }, 500);
}

// Load Translation on Page Load
window.addEventListener('DOMContentLoaded', loadGoogleTranslate);
