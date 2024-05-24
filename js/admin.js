jQuery(document).ready(function($) {
    var addonIndex = $('#pae_addons_container .pae_addon').length;

    $('#add_new_addon').click(function(e) {
        e.preventDefault();
        $('#pae_addons_container').append('<div class="pae_addon">' +
            '<input type="text" name="pae_addons[' + addonIndex + '][name]" placeholder="Addon Name" />' +
            '<input type="text" name="pae_addons[' + addonIndex + '][price]" placeholder="Addon Price" />' +
            '<button class="remove_addon">Remove</button>' +
        '</div>');
        addonIndex++;
    });

    $('#pae_addons_container').on('click', '.remove_addon', function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });
});
