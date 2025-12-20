$(document).ready(function ()
{
    const emailInput = $("#login-email");
    const passwordInput = $("#login-password");
    const rememberCheckbox = $("#login-remember-email");
    const savedEmail = localStorage.getItem("rememberedEmail");
    const savedRemember = localStorage.getItem("rememberRememberMe");
    let emailWasRestored = false;

    // Restore checkbox + email
    if (savedRemember === "true") {
        rememberCheckbox.prop("checked", true);

        if (savedEmail) {
            emailInput.val(savedEmail);
            emailWasRestored = true;
        }
    }

    // Autofocus logic
    if (emailWasRestored) {
        passwordInput.trigger("focus");
    } else {
        emailInput.trigger("focus");
    }

    // Update localStorage when checkbox toggles
    rememberCheckbox.on("change", function () {
        if ($(this).is(":checked")) {
            localStorage.setItem("rememberRememberMe", "true");
            localStorage.setItem("rememberedEmail", emailInput.val().trim());
        } else {
            localStorage.setItem("rememberRememberMe", "false");
            localStorage.removeItem("rememberedEmail");
        }
    });

    // Update saved email while typing
    emailInput.on("input", function () {
        if (rememberCheckbox.is(":checked")) {
            localStorage.setItem("rememberedEmail", $(this).val().trim());
        }
    });
});
