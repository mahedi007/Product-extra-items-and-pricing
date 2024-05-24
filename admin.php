<?php
function pae_add_custom_box() {
    add_meta_box(
        'pae_sectionid',
        __('Product Add-ons and Extras', 'product-add-ons-extras'),
        'pae_custom_box_html',
        'product'
    );
}
add_action('add_meta_boxes', 'pae_add_custom_box');

function pae_custom_box_html($post) {
    $addons = get_post_meta($post->ID, '_pae_addons', true) ? get_post_meta($post->ID, '_pae_addons', true) : array();
    wp_nonce_field('pae_save_addons', 'pae_nonce');
    ?>
    <div id="pae_addons_container">
        <?php
        foreach ($addons as $index => $addon) {
            ?>
            <div class="pae_addon">
                <input type="text" name="pae_addons[<?php echo $index; ?>][name]" value="<?php echo esc_attr($addon['name']); ?>" placeholder="<?php _e('Addon Name', 'product-add-ons-extras'); ?>" />
                <input type="number" name="pae_addons[<?php echo $index; ?>][price]" value="<?php echo esc_attr($addon['price']); ?>" placeholder="<?php _e('Addon Price', 'product-add-ons-extras'); ?>" />
                <button class="remove_addon"><?php _e('Remove', 'product-add-ons-extras'); ?></button>
            </div>
            <?php
        }
        ?>
    </div>
    <button id="add_new_addon"><?php _e('Add New Addon', 'product-add-ons-extras'); ?></button>
    <?php
}

function pae_save_postdata($post_id) {
    if (!isset($_POST['pae_nonce']) || !wp_verify_nonce($_POST['pae_nonce'], 'pae_save_addons')) {
        return;
    }
    if (array_key_exists('pae_addons', $_POST)) {
        update_post_meta(
            $post_id,
            '_pae_addons',
            $_POST['pae_addons']
        );
    } else {
        delete_post_meta($post_id, '_pae_addons');
    }
}
add_action('save_post', 'pae_save_postdata');

function pae_enqueue_admin_scripts() {
    wp_enqueue_script('pae_admin_script', plugins_url('/js/admin.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_style('pae_style', plugins_url('/css/style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'pae_enqueue_admin_scripts');
