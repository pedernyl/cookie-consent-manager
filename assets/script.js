jQuery(document).ready(function ($) {
    $('#accept-cookies').click(function () {
        document.cookie = "cookie_consent=accept; path=/; max-age=" + 60 * 60 * 24 * 30; // 30 dagar
        $('#cookie-banner').fadeOut();
    });

    $('#reject-cookies').click(function () {
        document.cookie = "cookie_consent=reject; path=/; max-age=" + 60 * 60 * 24 * 30; // 30 dagar
        $('#cookie-banner').fadeOut();
    });
});
