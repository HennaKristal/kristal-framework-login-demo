if(window.history&&window.history.replaceState){window.history.replaceState(null,null,window.location.href);}
document.addEventListener("DOMContentLoaded",function(){const tooltip_elements=document.querySelectorAll('[data-bs-toggle="tooltip"]');tooltip_elements.forEach(element=>new bootstrap.Tooltip(element));});
const KRISTAL_LANGUAGE_KEY="Kristal_Language";let kristal_language;let kristal_translation_url;let kristal_translations;$(document).ready(function(){kristal_language=localStorage.getItem(KRISTAL_LANGUAGE_KEY)||getVariable("language")||"en";kristal_translation_url=getVariable("baseURL")+"/App/media/translations/translations.json";$("html").attr("translation","language-"+kristal_language);if(getVariable("production_mode")==="false"){const random=Math.round(Math.random()*(999999-1))+1;kristal_translation_url+="?"+random;}
$.getJSON(kristal_translation_url,(data)=>{kristal_translations=data;kristal_initTranslations();}).fail(()=>{console.error("Failed to find translations file!\n\nTried to look at url:\n"+kristal_translation_url+"\n\nAlternatively, you may have an error in your JSON format.");});});function kristal_initTranslations(){kristal_updateTranslations();$("[switchLanguage]").click(function(event){event.preventDefault();$("#"+kristal_language+"-button").removeClass("active");kristal_language=$(event.target).attr("switchLanguage");localStorage.setItem(KRISTAL_LANGUAGE_KEY,kristal_language);kristal_updateTranslations();});}
function kristal_updateTranslations(){$("#"+kristal_language+"-button").addClass("active");$("html").attr("translation","language-"+kristal_language);$("[translationKey]").each(function(){$(this).translate();});$("[tooltipTranslationKey]").each(function(){$(this).tooltipTranslate();});}
function setLanguage(new_language){kristal_language=new_language;localStorage.setItem(KRISTAL_LANGUAGE_KEY,new_language);kristal_updateTranslations();}
jQuery.fn.translate=function(key){if(!kristal_translations){return;}
key=key||$(this).attr("translationKey");if(kristal_translations.hasOwnProperty(key)){if($(this).is(":input")&&$(this).attr('placeholder')!==undefined){$(this).prop("placeholder",kristal_translations[key][kristal_language]);}
else if($(this).is("img")){$(this).attr("alt",kristal_translations[key][kristal_language]);}
else{$(this).html(kristal_translations[key][kristal_language]);}}
else{console.warn("Translator was not able to translate value: "+key);}}
jQuery.fn.tooltipTranslate=function(key){if(!kristal_translations){return;}
key=key||$(this).attr("tooltipTranslationKey");if(kristal_translations.hasOwnProperty(key)){$(this).attr("data-bs-title",kristal_translations[key][kristal_language]);kristal_reinitializeTooltip(this.get(0));}
else{console.warn("Translator was not able to translate tooltip value: "+key);}}
function kristal_reinitializeTooltip(element){const bsTooltip=bootstrap.Tooltip.getInstance(element);if(bsTooltip){bsTooltip.dispose();}
new bootstrap.Tooltip(element);}
$(document).ready(function(){const animatedElements=document.querySelectorAll(".animation-raise, .animation-fade, .animation-scale, .animation-move-left, .animation-move-right, .animation-move-up, .animation-move-down");const observer=new IntersectionObserver((entries)=>{entries.forEach(entry=>{if(entry.isIntersecting){requestAnimationFrame(()=>{entry.target.classList.add("animated");});}});},{threshold:0.25});animatedElements.forEach(element=>observer.observe(element));});
/* Generated: 13.12.2025 18:41:37 */
//# sourceMappingURL=core.js.map
