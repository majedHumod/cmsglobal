import './bootstrap';
import Alpine from 'alpinejs';

// Performance optimization: defer Alpine initialization
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Alpine only after DOM is ready
    window.Alpine = Alpine;
    Alpine.start();
});

// Optimize scroll performance
let ticking = false;
function optimizeScroll() {
    if (!ticking) {
        requestAnimationFrame(() => {
            // Scroll optimizations can be added here
            ticking = false;
        });
        ticking = true;
    }
}

// Add passive scroll listener for better performance
window.addEventListener('scroll', optimizeScroll, { passive: true });

// Preload critical resources
document.addEventListener('DOMContentLoaded', function() {
    // Preload images that will be needed soon
    const criticalImages = document.querySelectorAll('img[loading="eager"]');
    criticalImages.forEach(img => {
        if (img.dataset.src) {
            img.src = img.dataset.src;
        }
    });
});

