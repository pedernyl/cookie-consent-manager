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

// Enqueue CSS and JavaScript
function local_cookie_consent_enqueue_assets() {
    wp_enqueue_style('local-cookie-consent-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('local-cookie-consent-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'local_cookie_consent_enqueue_assets');

// Show cookie-banner
function local_cookie_consent_banner() {
    $banner_text = get_option('cookie_consent_text', 'Vi bruker cookies for å forbedre opplevelsen din. Vennligst gi ditt samtykke.');

    if (!isset($_COOKIE['cookie_consent'])) {
        echo '
        <div id="cookie-banner">
            <p>' . esc_html($banner_text) . '</p>
            <button id="accept-cookies">Aksepter</button>
            <button id="reject-cookies">Avvis</button>
        </div>
        ';
    }
}
add_action('wp_footer', 'local_cookie_consent_banner');

// Add admin menu for settings
function local_cookie_consent_admin_menu() {
    add_options_page(
        'Cookie-samtykke Inställningar',
        'Cookie-samtykke',
        'manage_options',
        'cookie-consent-settings',
        'local_cookie_consent_settings_page'
    );
}
add_action('admin_menu', 'local_cookie_consent_admin_menu');

// Create settings page
function local_cookie_consent_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if form has been sent
    if (isset($_POST['cookie_consent_text'])) {
        check_admin_referer('save_cookie_consent_settings');
        $new_text = sanitize_text_field($_POST['cookie_consent_text']);
        update_option('cookie_consent_text', $new_text);
        echo '<div class="updated"><p>Inställningarna har sparats.</p></div>';
    }

    // Fetch the saved text
    $saved_text = get_option('cookie_consent_text', 'Vi bruker cookies for å forbedre opplevelsen din. Vennligst gi ditt samtykke.');

    // Display the form
    echo '
    <div class="wrap">
        <h1>Cookie-samtykke Inställningar</h1>
        <form method="post">
            ' . wp_nonce_field('save_cookie_consent_settings') . '
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="cookie_consent_text">Bannertext</label></th>
                    <td>
                        <textarea name="cookie_consent_text" id="cookie_consent_text" rows="4" cols="50">' . esc_textarea($saved_text) . '</textarea>
                        <p class="description">Ange den text som ska visas i cookie-banneret.</p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="Spara ändringar">
            </p>
        </form>
    </div>
    ';
}