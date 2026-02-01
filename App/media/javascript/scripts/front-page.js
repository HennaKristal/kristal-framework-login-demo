$(document).ready(function () {

    const navbar = $(".navbar");
    const currentPage = navbar.data("page");

    // Only activate transparency on frontpage
    if (currentPage === "frontpage.php") {

        navbar.addClass("transparent-at-top");

        function updateNavbar() {
            if ($(window).scrollTop() > 10) {
                navbar.removeClass("transparent-at-top");
            } else {
                navbar.addClass("transparent-at-top");
            }
        }

        // Run immediately
        updateNavbar();

        // Run on scroll
        $(window).on("scroll", updateNavbar);
    }
});
