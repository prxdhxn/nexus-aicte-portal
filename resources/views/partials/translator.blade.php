<div id="google_translate_element" style="position:absolute; top:-10000px; left:-10000px; width:1px; height:1px; overflow:hidden;"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,hi,ta,fr,es'}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
function doGTranslate(lang_pair) {
    if(lang_pair.value) lang_pair = lang_pair.value;
    if(lang_pair == '') return;
    var lang = lang_pair.split('|')[1];
    
    var teCombo = document.querySelector('.goog-te-combo');
    if (!teCombo) {
        // If the widget isn't loaded yet, try again in 500ms
        setTimeout(function() { doGTranslate(lang_pair); }, 500);
        return;
    }
    
    // Hide the custom dropdown menu
    var menu = document.getElementById('translatorMenu');
    if(menu) menu.classList.remove('show');

    // Trigger translation
    teCombo.value = lang;
    if (typeof(document.createEvent) == 'function') {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent('change', true, true);
        teCombo.dispatchEvent(evt);
    } else {
        var evt = document.createEventObject();
        teCombo.fireEvent('onchange', evt);
    }
}
</script>

<style>
/* Hide the Google Translate toolbar and tooltips */
.goog-te-banner-frame.skiptranslate { display: none !important; }
body { top: 0px !important; }
.goog-tooltip { display: none !important; }
.goog-tooltip:hover { display: none !important; }
.goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
</style>

<!-- Custom Translator Dropdown -->
<div class="custom-translator-wrapper" id="customTranslatorWrapper">
  <button class="translator-btn" type="button" id="translatorDropdownBtn" title="Translate Page">
    <i class="fa-solid fa-language"></i>
  </button>
  <ul class="translator-menu" id="translatorMenu">
    <li><a class="t-item" href="#" onclick="doGTranslate('en|en'); return false;">🇬🇧 English</a></li>
    <li><a class="t-item" href="#" onclick="doGTranslate('en|hi'); return false;">🇮🇳 Hindi (हिंदी)</a></li>
    <li><a class="t-item" href="#" onclick="doGTranslate('en|ta'); return false;">🇮🇳 Tamil (தமிழ்)</a></li>
    <li><a class="t-item" href="#" onclick="doGTranslate('en|fr'); return false;">🇫🇷 French (Français)</a></li>
    <li><a class="t-item" href="#" onclick="doGTranslate('en|es'); return false;">🇪🇸 Spanish (Español)</a></li>
  </ul>
</div>

<style>
/* Premium dropdown styling (Vanilla CSS) */
.custom-translator-wrapper {
    position: relative;
    display: inline-block;
}
.translator-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border-radius: 50%;
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    cursor: pointer;
    padding: 0;
}
.translator-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
    color: #ffffff;
}
.translator-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    padding: 8px;
    min-width: 180px;
    list-style: none;
    margin: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.25s ease;
    z-index: 99999;
}
.translator-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.translator-menu li {
    margin-bottom: 4px;
}
.translator-menu li:last-child {
    margin-bottom: 0;
}
.t-item {
    color: #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
    font-weight: 500;
    display: block;
    text-decoration: none;
    transition: all 0.2s ease;
}
.t-item:hover {
    background: rgba(14, 165, 233, 0.2);
    color: #38bdf8;
    text-decoration: none;
}

/* Specific overrides for light mode dashboards */
[data-theme="light"] .translator-btn {
    background: rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.1);
    color: var(--text-main);
}
[data-theme="light"] .translator-btn:hover {
    background: rgba(0, 0, 0, 0.1);
}
[data-theme="light"] .translator-menu {
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid var(--border-color);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
[data-theme="light"] .t-item {
    color: var(--text-main);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('translatorDropdownBtn');
    const menu = document.getElementById('translatorMenu');
    
    if(btn && menu) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if(!document.getElementById('customTranslatorWrapper').contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    }
});
</script>
