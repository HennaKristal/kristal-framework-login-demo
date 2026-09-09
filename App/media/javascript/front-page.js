$(document).ready(function(){const navbar=$(".navbar");const currentPage=navbar.data("page");if(currentPage==="frontpage.php"){navbar.addClass("transparent-at-top");function updateNavbar(){if($(window).scrollTop()>10){navbar.removeClass("transparent-at-top");}else{navbar.addClass("transparent-at-top");}}
updateNavbar();$(window).on("scroll",updateNavbar);}});
/* Generated: 8.9.2026 23:59:56 */
//# sourceMappingURL=front-page.js.map
