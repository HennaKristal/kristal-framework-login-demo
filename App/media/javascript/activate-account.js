$(document).ready(function(){startCountDown();updateActivationLink($("#reset-email").val().trim());$("#reset-email").on("input",function(){updateActivationLink($(this).val().trim());});});function updateActivationLink($email){$("#activation-link").attr("href","/activate-account/"+$email);}
function startCountDown(){const feedback=$("#activate-accound-feedback");const text=feedback.text();const match=text.match(/Please wait (\d+) seconds before trying again/);if(!match){return;}
let remaining=parseInt(match[1],10);const interval=setInterval(function(){remaining-=1;if(remaining<=0){clearInterval(interval);feedback.removeClass("failed success warning info");feedback.text("");return;}
feedback.text("Please wait "+remaining+" seconds before trying again");},1000);}
/* Generated: 12.12.2025 20:45:48 */
//# sourceMappingURL=activate-account.js.map
