function updateFavicon() {
    const favicon = document.getElementById('favicon');
    const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

    // Set favicon based on theme
    favicon.href = isDarkMode
        ? 'images/logo_white.jpg'  // Dark mode favicon
        : 'images/logo.png'; // Light mode favicon
}

// Run on page load
updateFavicon();

// Listen for changes in theme
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateFavicon);

// Automatically move focus to the next input field when one is filled
function moveFocus(current, next) {
    if (current.value.length === current.maxLength) {
        document.getElementById(next).focus();
    }
}