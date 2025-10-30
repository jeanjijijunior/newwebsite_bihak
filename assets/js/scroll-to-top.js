/**
 * Scroll to Top Button
 * Shows a button to scroll back to the top of the page
 */

document.addEventListener('DOMContentLoaded', function() {
    const scrollButton = document.getElementById('myBtn');

    if (!scrollButton) return;

    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (document.documentElement.scrollTop > 30) {
            scrollButton.style.display = "block";
        } else {
            scrollButton.style.display = "none";
        }
    });

    // Scroll to top when button is clicked
    scrollButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
