<?php
/*
Plugin Name: Cookie Consent Manager
Plugin URI: https://peder.website/
Description: Frågar användare om de tillåter cookies och hanterar deras val.
Version: 1.0
Author: Peder Nylander
Author URI: https://peder.website/
License: GPL2
*/

// Enqueue CSS och JavaScript
function cookie_consent_enqueue_assets() {
    wp_enqueue_style('cookie-consent-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('cookie-consent-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'cookie_consent_enqueue_assets');

// Visa cookie-bannern
function cookie_consent_banner() {
    if (!isset($_COOKIE['cookie_consent'])) {
        echo '
        <div id="cookie-banner">
            <p>Vi använder cookies för att förbättra din upplevelse. Godkänner du detta?</p>
            <button id="accept-cookies">Acceptera</button>
            <button id="reject-cookies">Avvisa</button>
        </div>
        ';
    }
}
add_action('wp_footer', 'cookie_consent_banner');

// Blockera cookies om användaren har avvisat dem
function cookie_consent_filter_cookies() {
    if (isset($_COOKIE['cookie_consent']) && $_COOKIE['cookie_consent'] === 'reject') {
        // Rensa cookies
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, '', time() - 3600, '/');
        }
        // Blockera WordPress-standardskript
        add_filter('script_loader_tag', '__return_empty_string', 9999);
    }
}
add_action('init', 'cookie_consent_filter_cookies');

