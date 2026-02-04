// Scroll animations using Intersection Observer
document.addEventListener('DOMContentLoaded', () => {
    // Observer for scroll-reveal elements
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    });

    // Observe all elements with scroll-reveal class
    document.querySelectorAll('.scroll-reveal').forEach(el => {
        scrollObserver.observe(el);
    });

    // Smooth crossfade for sections based on scroll position
    const sections = document.querySelectorAll('[data-section-animate]');

    function updateSectionOpacity() {
        const windowHeight = window.innerHeight;
        const scrollTop = window.scrollY;
        const scrollCenter = scrollTop + (windowHeight / 2);

        sections.forEach((section) => {
            const rect = section.getBoundingClientRect();
            const sectionTop = rect.top + scrollTop;
            const sectionBottom = sectionTop + rect.height;
            const sectionCenter = sectionTop + (rect.height / 2);

            // Calculate distance from viewport center to section center
            const distanceFromCenter = Math.abs(scrollCenter - sectionCenter);
            const fadeDistance = windowHeight * 0.8; // Start fading when 80% of viewport away

            // Calculate opacity (1 when centered, 0 when far away)
            let opacity = 1 - (distanceFromCenter / fadeDistance);
            opacity = Math.max(0, Math.min(1, opacity)); // Clamp between 0 and 1

            // Apply opacity to section
            section.style.opacity = opacity;

            // Slightly scale content based on opacity for subtle effect
            const content = section.querySelectorAll('[data-section-content]');
            content.forEach(el => {
                const scale = 0.95 + (opacity * 0.05); // Scale from 0.95 to 1
                const translateY = (1 - opacity) * 20; // Move up to 20px
                el.style.transform = `scale(${scale}) translateY(${translateY}px)`;
                el.style.opacity = opacity;
            });
        });

        requestAnimationFrame(updateSectionOpacity);
    }

    // Start the animation loop
    updateSectionOpacity();
});
