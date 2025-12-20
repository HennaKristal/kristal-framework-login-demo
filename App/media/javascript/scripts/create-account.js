$(document).ready(function () {

    // Body background request
    $("body").addClass("signup-gradient");

    // Terms of service popup
    $("#terms-of-service-link").on("click", function () {
        $("#terms-of-service-popup").fadeIn(150).css("display", "flex");
    });

    $("#tos-agree-button").on("click", function () {
        $("#terms-of-service-checkbox").prop("checked", true);
        $("#terms-of-service-popup").fadeOut(150);
        validateSignupForm();
    });

    $("#tos-disagree-button").on("click", function () {
        $("#terms-of-service-checkbox").prop("checked", false);
        $("#terms-of-service-popup").fadeOut(150);
        validateSignupForm();
    });

    // Input detection for validation
    $("#registration-username, #registration-email, #registration-password, #registration-confirmation-password, #terms-of-service-checkbox") .on("input change", function () {
        validateSignupForm();
    });

    if ($("#registration-feedback").text() === "") {
        validateSignupForm();
    }
});


function validateSignupForm() {
    const username = $("#registration-username").val().trim();
    const email = $("#registration-email").val().trim().toLowerCase();
    const password = $("#registration-password").val();
    const confirmPassword = $("#registration-confirmation-password").val();
    const termsChecked = $("#terms-of-service-checkbox").is(":checked");
    const feedback = $("#registration-feedback");
    const button = $("#registration-button");

    feedback.removeClass("failed warning info success");
    feedback.text("");

    // Username min length
    if (username.length < 3) {
        feedback.addClass("failed").text("Please enter a username (minimum 3 characters).");
        button.prop("disabled", true);
        return;
    }

    // Username max length
    if (username.length > 50) {
        feedback.addClass("failed").text("Username has to be shorter than 50 characters.");
        button.prop("disabled", true);
        return;
    }

    // Username format
    if (!/^[A-Za-z0-9_-]+$/.test(username)) {
        feedback.addClass("failed").text("Username may only contain letters, numbers, underscores, and hyphens.");
        button.prop("disabled", true);
        return;
    }

    // Email format
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        feedback.addClass("failed").text("Please enter a valid email address.");
        button.prop("disabled", true);
        return;
    }

    // Password length
    if (password.length < 8) {
        feedback.addClass("failed").text("Password must be at least 8 characters long.");
        button.prop("disabled", true);
        return;
    }

    // Password match check
    if (password !== confirmPassword) {
        feedback.addClass("failed").text("Confirmation password does not match.");
        button.prop("disabled", true);
        return;
    }

    // Terms of service
    if (!termsChecked) {
        feedback.addClass("failed").text("Please accept the Terms of Service.");
        button.prop("disabled", true);
        return;
    }

    // Weak password warning (non-blocking)
    if (isWeakPassword(password)) {
        feedback.addClass("warning").text("Your password is quite weak. We strongly recommend choosing a stronger one.");
    }

    button.prop("disabled", false);
}



function isWeakPassword(password) {
    let score = 0;

    // Length scoring
    if (password.length >= 8) score++;
    if (password.length >= 12) score++;
    if (password.length >= 16) score++;

    // Variety scoring
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/\d/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    // Unique characters scoring
    const uniqueCount = new Set(password).size;
    if (uniqueCount >= 6) score++;
    if (uniqueCount >= 10) score++;

    return score < 5;
}
