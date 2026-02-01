$(document).ready(function() {

    const animatedElements = document.querySelectorAll(".animation-raise, .animation-fade, .animation-scale, .animation-move-left, .animation-move-right, .animation-move-up, .animation-move-down");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    requestAnimationFrame(() => {
                        entry.target.classList.add("animated");
                    });
                }
            });
        },
        { threshold: 0.25 }
    );

    animatedElements.forEach(element => observer.observe(element));
});
