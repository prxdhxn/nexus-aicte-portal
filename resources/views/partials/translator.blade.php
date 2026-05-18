<!-- Custom Translator Dropdown -->
<div class="custom-translator-wrapper" id="customTranslatorWrapper">
  <button class="translator-btn" type="button" id="translatorDropdownBtn" title="Translate Page">
    <i class="fa-solid fa-language"></i>
  </button>
  <ul class="translator-menu" id="translatorMenu">
    <li>
        <form action="{{ route('preferences.language') }}" method="POST">
            @csrf
            <input type="hidden" name="lang" value="en">
            <button type="submit" class="t-item" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">🇬🇧 English</button>
        </form>
    </li>
    <li>
        <form action="{{ route('preferences.language') }}" method="POST">
            @csrf
            <input type="hidden" name="lang" value="hi">
            <button type="submit" class="t-item" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">🇮🇳 Hindi (हिंदी)</button>
        </form>
    </li>
    <li>
        <form action="{{ route('preferences.language') }}" method="POST">
            @csrf
            <input type="hidden" name="lang" value="ta">
            <button type="submit" class="t-item" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">🇮🇳 Tamil (தமிழ்)</button>
        </form>
    </li>
    <li>
        <form action="{{ route('preferences.language') }}" method="POST">
            @csrf
            <input type="hidden" name="lang" value="fr">
            <button type="submit" class="t-item" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">🇫🇷 French (Français)</button>
        </form>
    </li>
    <li>
        <form action="{{ route('preferences.language') }}" method="POST">
            @csrf
            <input type="hidden" name="lang" value="es">
            <button type="submit" class="t-item" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">🇪🇸 Spanish (Español)</button>
        </form>
    </li>
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
    outline: none;
}
.t-item:hover, .t-item:focus {
    background: rgba(14, 165, 233, 0.2) !important;
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
