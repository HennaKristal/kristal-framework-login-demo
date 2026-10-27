let isCountingDown = false;

$(document).ready(function () {
    $("#tfa-code").on("input change", validateTfaCode);
    startCountDown();
});


function startCountDown() {
    const feedback = $("#tfa-feedback");
    const text = feedback.text();
    const match = text.match(/Please wait (\d+) seconds before trying again/);
    
    if (!match) {
        return;
    }

    isCountingDown = true;
    $("#tfa-button").prop("disabled", true);

    let remaining = parseInt(match[1], 10);

    const interval = setInterval(function () {
        remaining -= 1;

        if (remaining <= 0) {
            clearInterval(interval);
            feedback.removeClass("failed success warning info");
            feedback.text("");
            isCountingDown = false;
            validateTfaCode();
            return;
        }

        feedback.text("Please wait " + remaining + " seconds before trying again");
    }, 1000);
}


function validateTfaCode() {

    if (isCountingDown) {
        return;
    }

    const code = $("#tfa-code").val().trim();
    const feedback = $("#tfa-feedback");
    const button = $("#tfa-button");

    feedback.removeClass("failed success warning info");
    feedback.text("");

    if (!/^[A-Z0-9]{8}$/.test(code)) {
        feedback
            .addClass("failed")
            .text("Code must be 8 characters using capital letters and numbers.");
        button.prop("disabled", true);
        return;
    }

    button.prop("disabled", false);
}
