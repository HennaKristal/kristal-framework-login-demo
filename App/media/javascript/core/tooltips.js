//Initialize Bootstrap 5 tooltips
document.addEventListener("DOMContentLoaded", function()
{
    const tooltip_elements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltip_elements.forEach (element => new bootstrap.Tooltip(element));
});
