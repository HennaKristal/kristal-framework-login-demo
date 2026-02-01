
$(document).ready(function () {
    $("#new-password, #confirm-password") .on("input change", function () {
        validatePasswordChange();
    });
});


function validatePasswordChange() {
    const new_password = $("#new-password").val();
    const confirm_password = $("#confirm-password").val();
    const feedback = $("#reset-feedback");;
    const button = $("#reset-button");

    feedback.removeClass("failed warning info success");
    feedback.text("");

    // Validate password
    if (new_password.length < 8) {
        feedback.addClass("failed").text("Password must be at least 8 characters long.");
        button.prop("disabled", true);
        return;
    }

    // Match check
    if (new_password !== confirm_password) {
        feedback.addClass("failed").text("Confirmation password does not match.");
        button.prop("disabled", true);
        return;
    }

    if (isWeakPassword(new_password)) {
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
