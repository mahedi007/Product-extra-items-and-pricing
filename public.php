<?php
function pae_enqueue_scripts() {
    wp_enqueue_style('pae_style', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_script('pae_script', plugins_url('/js/frontend.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('pae_script', 'pae_params', array(
        'currency_symbol' => get_woocommerce_currency_symbol()
    ));
}
add_action('wp_enqueue_scripts', 'pae_enqueue_scripts');

function pae_display_add_ons() {
    global $product;
    $addons = get_post_meta($product->get_id(), '_pae_addons', true);
    if ($addons) {
        ?>
        <div class="pae-add-ons">
            <h3><?php _e('Extra Items?', 'products-extra-items-and-pricing'); ?></h3>
            <?php
            foreach ($addons as $index => $addon) {
                ?>
                <p>
                    <label>
                        <input type="checkbox" class="pae-addon-checkbox" name="pae_addons[<?php echo $index; ?>]" data-price="<?php echo esc_attr($addon['price']); ?>" value="<?php echo esc_attr($addon['name']); ?>">
                        <?php echo esc_html($addon['name']); ?> (+<?php echo esc_html($addon['price']); ?>)
                    </label>
                </p>
                <?php
            }
            ?>
        </div>
        <?php
    }
}
add_action('woocommerce_before_add_to_cart_button', 'pae_display_add_ons');

// Add selected add-ons to the cart
function pae_add_ons_to_cart_item($cart_item_data, $product_id) {
    if (!empty($_POST['pae_addons'])) {
        $cart_item_data['pae_addons'] = array();
        foreach ($_POST['pae_addons'] as $index => $addon_name) {
            $addon_price = filter_var($_POST['pae_addons'][$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $cart_item_data['pae_addons'][] = array(
                'name' => sanitize_text_field($addon_name),
                'price' => $addon_price
            );
        }
    }
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'pae_add_ons_to_cart_item', 10, 2);

// Display add-ons in the cart
function pae_display_cart_item_add_ons($item_data, $cart_item) {
    if (isset($cart_item['pae_addons'])) {
        foreach ($cart_item['pae_addons'] as $addon) {
            $item_data[] = array(
                'name' => esc_html($addon['name']),
                'value' => wc_price($addon['price'])
            );
        }
    }
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'pae_display_cart_item_add_ons', 10, 2);

// Calculate the total price including add-ons
function pae_calculate_cart_totals($cart_object) {
    foreach ($cart_object->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['pae_addons'])) {
            $extra_cost = 0;
            foreach ($cart_item['pae_addons'] as $addon) {
                $extra_cost += $addon['price'];
            }
            $cart_item['data']->set_price($cart_item['data']->get_price() + $extra_cost);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'pae_calculate_cart_totals');
