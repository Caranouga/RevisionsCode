console.log('Content script loaded');

// Listen for messages from the popup script
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === "getScripts") {
        const scripts = document.querySelectorAll('script');
        const scriptContents = [];
        scripts.forEach(script => scriptContents.push(script.textContent));
        sendResponse({ scripts: scriptContents });
    }
});
