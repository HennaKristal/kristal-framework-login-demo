$(document).ready(function () {
    $("#username") .on("input change", function () {
        validateUsernameChange();
    });

    $("#new-password, #confirm-password, #current-password") .on("input change", function () {
        validatePasswordChange();
    });

    setupDeleteAccountHandlers();
});


function validateUsernameChange() {
    const username = $("#username").val().trim();
    const feedback = $("#username-feedback");
    const button = $("#update-profile-button");

    feedback.removeClass("failed warning info success");
    feedback.text("");

    if (username.length < 3) {
        feedback.addClass("failed").text("Please enter a username (minimum 3 characters).");
        button.prop("disabled", true);
        return;
    }

    button.prop("disabled", false);
}


function validatePasswordChange() {
    const current_password = $("#current-password").val();
    const new_password = $("#new-password").val();
    const confirm_password = $("#confirm-password").val();
    const feedback = $("#password-feedback");
    const button = $("#change-password-button");

    feedback.removeClass("failed warning info success");
    feedback.text("");

    if (current_password.length < 8) {
        feedback.addClass("failed").text("Current password is too short.");
        button.prop("disabled", true);
        return;
    }

    if (new_password.length < 8) {
        feedback.addClass("failed").text("Password must be at least 8 characters long.");
        button.prop("disabled", true);
        return;
    }

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


// Delete account logic
function setupDeleteAccountHandlers() {
    const deleteAccountForm = $("#delete-account-form");
    const deleteAccountPasswordInput = $("#delete-account-password");
    const deleteAccountButton = $("#delete-account-button");

    if (!deleteAccountForm.length) {
        return;
    }

    if (deleteAccountPasswordInput.length && deleteAccountButton.length) {
        deleteAccountPasswordInput.on("input change", function () {
            const passwordLength = $(this).val().length;
            const isLongEnough = passwordLength >= 8;
            deleteAccountButton.prop("disabled", !isLongEnough);
        });
    }

    deleteAccountForm.on("submit", function (event) {
        if (deleteAccountPasswordInput.length) {
            const passwordValue = deleteAccountPasswordInput.val();
            if (passwordValue.length < 8) {
                event.preventDefault();
                return;
            }
        }

        const confirmed = window.confirm("Are you absolutely sure you want to delete your account?\nThis action is permanent and cannot be undone.");

        if (!confirmed) {
            event.preventDefault();
        }
    });
}


$(document).on("click", "#clear-avatar-button", function (event) {
    event.preventDefault();

    const confirmAvatarDeletion = window.confirm("Are you sure you want to delete your profile picture?");

    if (confirmAvatarDeletion) {
        $("#clear-avatar-form").submit();
    }
});
