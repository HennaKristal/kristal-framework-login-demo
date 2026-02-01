/**
 * 
 * HOW TO USE SWITCH LANGUAGES:
 * 
 * <div id="language-selection">
 *     <label id="fi-button" switchLanguage="fi">FI</label>
 *     <label id="en-button" switchLanguage="en">EN</label>
 * </div>
 * 
 * 
 * 
 * HOW TO USE:
 * 
 * Normal Texts:
 * <p translationKey="TRANSLATION_KEY">default text</p>
 * 
 * Button values:
 * <button type="submit" class="btn btn-primary" translationKey="TRANSLATION_KEY">default text</button>
 * 
 * Input placeholders:
 * <input type="email" translationKey="TRANSLATION_KEY" placeholder="default text">
 * 
 * Tooltips:
 * <a href="#" data-bs-toggle="tooltip" tooltipTranslationKey="TRANSLATION_KEY" data-bs-title="default text" translationKey="TRANSLATION_KEY">default text</a>
 * 
 * Image alts
 * <img src="example.jpg" translationKey="TRANSLATION_KEY" alt="default text">
 */

const KRISTAL_LANGUAGE_KEY = "Kristal_Language";
let kristal_language;
let kristal_translation_url;
let kristal_translations;


// Get translations
$(document).ready(function()
{
    kristal_language = localStorage.getItem(KRISTAL_LANGUAGE_KEY) || getVariable("language") || "en";
    kristal_translation_url = getVariable("baseURL") + "/App/media/translations/translations.json";

    $("html").attr("translation", "language-" + kristal_language);

    // Force browsers to get latest version when not in production
    if (getVariable("production_mode") === "false")
    {
        const random = Math.round(Math.random() * (999999 - 1)) + 1;
        kristal_translation_url += "?" + random;
    }
    
    // Get translation json file
    $.getJSON(kristal_translation_url, (data) => {
        kristal_translations = data;
        kristal_initTranslations();
    }).fail(() => { console.error("Failed to find translations file!\n\nTried to look at url:\n" + kristal_translation_url + "\n\nAlternatively, you may have an error in your JSON format."); });
});


// Activate language switching buttons
function kristal_initTranslations()
{
    kristal_updateTranslations();

    $("[switchLanguage]").click(function(event)
    {
        event.preventDefault();
        $("#" + kristal_language + "-button").removeClass("active");
        kristal_language = $(event.target).attr("switchLanguage");
        localStorage.setItem(KRISTAL_LANGUAGE_KEY, kristal_language);
        kristal_updateTranslations();
    });
}


// Translate everything
function kristal_updateTranslations()
{
    $("#" + kristal_language + "-button").addClass("active");

    $("html").attr("translation", "language-" + kristal_language);

    $("[translationKey]").each(function()
    {
        $(this).translate();
    });

    $("[tooltipTranslationKey]").each(function()
    {
        $(this).tooltipTranslate();
    });
}


// Allow changing language using code
function setLanguage(new_language)
{
    kristal_language = new_language;
    localStorage.setItem(KRISTAL_LANGUAGE_KEY, new_language);
    kristal_updateTranslations();
}


// Translate normal texts, input placeholders, and image alt texts
jQuery.fn.translate = function(key)
{
    if (!kristal_translations) { return; }

    key = key || $(this).attr("translationKey");

    if (kristal_translations.hasOwnProperty(key))
    {
        if ($(this).is(":input") && $(this).attr('placeholder') !== undefined)
        {
            $(this).prop("placeholder", kristal_translations[key][kristal_language]);
        }
        else if ($(this).is("img"))
        {
            $(this).attr("alt", kristal_translations[key][kristal_language]);
        }
        else
        {
            $(this).html(kristal_translations[key][kristal_language]);
        }
    }
    else
    {
        console.warn("Translator was not able to translate value: " + key);
    }
}


// Translate tooltips
jQuery.fn.tooltipTranslate = function(key)
{
    if (!kristal_translations) { return; }

    key = key || $(this).attr("tooltipTranslationKey");
    
    if (kristal_translations.hasOwnProperty(key))
    {
        $(this).attr("data-bs-title", kristal_translations[key][kristal_language]);
        kristal_reinitializeTooltip(this.get(0));
    }
    else
    {
        console.warn("Translator was not able to translate tooltip value: " + key);
    }
}


// Reinitialize tooltips after translation
function kristal_reinitializeTooltip(element)
{
    const bsTooltip = bootstrap.Tooltip.getInstance(element);
    if (bsTooltip) { bsTooltip.dispose(); }
    new bootstrap.Tooltip(element);
}
