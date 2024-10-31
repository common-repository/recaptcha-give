(function($) {
  $( document ).on( 'give_gateway_loaded', function() {
    grecaptcha.render( 'give-recaptcha-element', {
      'sitekey': RECAPTCHAGIVE.v2_site_key
    } );
  } );
})(jQuery);
