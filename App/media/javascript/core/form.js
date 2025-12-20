// Prevent forms sending data again if page is refreshed after the form has been submitted
if (window.history && window.history.replaceState)
{
    window.history.replaceState(null, null, window.location.href);
}
