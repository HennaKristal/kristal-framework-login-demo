let isCountingDown = false;

$(document).ready(function () {
    $("#forgot-password-email") .on("input change", function () {
        validateEmail();
    });

    startCountDown();
});


function validateEmail() {

    if (isCountingDown) {
        return;
    }

    const email = $("#forgot-password-email").val().trim();
    const feedback = $("#forgot-password-feedback");
    const button = $("#send-forgot-password-email-button");
    
    feedback.removeClass("failed warning info success");
    feedback.text("");

    // Validate email
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        feedback.addClass("failed").text("Please enter a valid email address.");
        button.prop("disabled", true);
        return;
    }

    button.prop("disabled", false);
}


function startCountDown() {
    const feedback = $("#forgot-password-feedback");
    const text = feedback.text();
    const match = text.match(/Please wait (\d+) seconds before trying again/);
    
    if (!match) {
        return;
    }

    isCountingDown = true;
    $("#send-forgot-password-email-button").prop("disabled", true);

    let remaining = parseInt(match[1], 10);

    const interval = setInterval(function () {
        remaining -= 1;

        if (remaining <= 0) {
            clearInterval(interval);
            feedback.removeClass("failed success warning info");
            feedback.text("");
            isCountingDown = false;
            validateEmail();
            return;
        }

        feedback.text("Please wait " + remaining + " seconds before trying again");
    }, 1000);
}