jQuery(document).ready(function($) {
    var originalPrice = parseFloat($('.woocommerce-Price-amount').first().text().replace(pae_params.currency_symbol, '').replace(',', ''));

    $('.pae-addon-checkbox').change(function() {
        var totalPrice = originalPrice;
        $('.pae-addon-checkbox:checked').each(function() {
            totalPrice += parseFloat($(this).data('price'));
        });
        $('.woocommerce-Price-amount').text(pae_params.currency_symbol + totalPrice.toFixed(2));
    });
});
