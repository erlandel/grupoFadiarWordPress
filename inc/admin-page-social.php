<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_add_social_menu_page() {
    add_menu_page(
        'Redes Sociales',
        'Redes Sociales',
        'manage_options',
        'grupofadiar_social',
        'grupofadiar_render_social_landing',
         'dashicons-share',
        9.6
    );
}
add_action('admin_menu', 'grupofadiar_add_social_menu_page', 0);

function grupofadiar_render_social_landing() {
    $networks = array(
        array(
            'key'   => 'phone',
            'icon'  => '<svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        ),
        array(
            'key'   => 'instagram',
            'icon'  => get_icon('instagram', 'w-7 h-7'),
        ),
        array(
            'key'   => 'facebook',
            'icon'  => get_icon('facebook', 'w-7 h-7'),
        ),
        array(
            'key'   => 'email',
            'icon'  => '<svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
        ),
    );

    // Guardar cada formulario individual
    foreach ($networks as $net) {
        $key = $net['key'];
        $nonce_field = "grupofadiar_social_{$key}_nonce";
        $nonce_action = "grupofadiar_save_social_{$key}";

        if (isset($_POST[$nonce_field]) && wp_verify_nonce($_POST[$nonce_field], $nonce_action)) {
            if (!current_user_can('manage_options')) {
                wp_die('No tienes permisos suficientes.');
            }
            $option_name = "social_url_{$key}";

            if ($key === 'phone') {
                $raw = isset($_POST[$option_name]) ? sanitize_text_field($_POST[$option_name]) : '';
                $raw = preg_replace('/[^0-9]/', '', $raw);

                if (empty($raw)) {
                    $value = '#';
                    update_option($option_name, $value);
                    echo '<div class="updated"><p>' . esc_html(gf_e('social.saved')) . '</p></div>';
                } elseif (grupofadiar_validate_phone('+' . $raw)) {
                    $value = '+' . $raw;
                    update_option($option_name, $value);
                    echo '<div class="updated"><p>' . esc_html(gf_e('social.saved')) . '</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>' . esc_html(gf_e('social.phone_invalid')) . '</p></div>';
                }
            } elseif ($key === 'email') {
                $raw = isset($_POST[$option_name]) ? sanitize_email($_POST[$option_name]) : '';

                if (empty($raw)) {
                    $value = '#';
                    update_option($option_name, $value);
                    echo '<div class="updated"><p>' . esc_html(gf_e('social.saved')) . '</p></div>';
                } elseif (is_email($raw)) {
                    $value = $raw;
                    update_option($option_name, $value);
                    echo '<div class="updated"><p>' . esc_html(gf_e('social.saved')) . '</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>' . esc_html(gf_e('social.email_invalid')) . '</p></div>';
                }
            } else {
                $value = isset($_POST[$option_name]) ? esc_url_raw($_POST[$option_name]) : '#';
                if (empty($value)) {
                    $value = '#';
                }
                update_option($option_name, $value);
                echo '<div class="updated"><p>' . esc_html(gf_e('social.saved')) . '</p></div>';
            }
        }
    }

    ?>
    <style>
        .social-card input:not([type="submit"]) { width:100%; max-width:100%; box-sizing:border-box; }
        .social-card .phone-wrapper { display:flex; align-items:stretch; gap:0; }
        .social-card .phone-prefix {
            display:flex; align-items:center; justify-content:center;
            background:#f0f0f1; border:1px solid #8c8f94; border-right:0; border-radius:4px 0 0 4px;
            padding:0 10px; font-size:14px; font-weight:700; color:#3c434a; line-height:1; min-width:32px;
        }
        .social-card .phone-wrapper input { border-radius:0 4px 4px 0; }
    </style>
    <div class="wrap">
        <h1><?php echo esc_html(gf_e('social.page_title')); ?></h1>
        <p><?php echo esc_html(gf_e('social.page_description')); ?></p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">
            <?php foreach ($networks as $net):
                $key = $net['key'];
                $option_name = "social_url_{$key}";
                $value = get_option($option_name, '#');
                $is_phone = ($key === 'phone');
                $is_email = ($key === 'email');
                if ($is_phone) {
                    $input_value = ltrim($value, '+');
                } elseif ($is_email) {
                    $input_value = str_replace('mailto:', '', $value);
                } else {
                    $input_value = $value;
                }
            ?>
                <div class="social-card" style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;overflow:hidden;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                        <?php echo $net['icon']; ?>
                        <h2 style="margin:0;font-size:18px;"><?php echo esc_html(gf_e("social.network.{$key}")); ?></h2>
                    </div>
                    <form method="post" action="">
                        <?php wp_nonce_field("grupofadiar_save_social_{$key}", "grupofadiar_social_{$key}_nonce"); ?>
                        <label for="<?php echo esc_attr($option_name); ?>" style="display:block;margin-bottom:6px;font-weight:600;">
                            <?php
                            if ($is_phone) {
                                echo esc_html(gf_e('social.phone_label'));
                            } elseif ($is_email) {
                                echo 'Email';
                            } else {
                                echo 'URL';
                            }
                            ?>
                        </label>
                        <?php if ($is_phone): ?>
                            <div class="phone-wrapper">
                                <span class="phone-prefix">+</span>
                                <input type="tel" name="<?php echo esc_attr($option_name); ?>"
                                    id="<?php echo esc_attr($option_name); ?>"
                                    value="<?php echo esc_attr($input_value); ?>"
                                    placeholder="34 612345678" />
                            </div>
                            <p style="color:#787c82;font-size:12px;margin:4px 0 0;font-style:italic;">
                                <?php echo esc_html(gf_e('social.phone_hint')); ?>
                            </p>
                        <?php elseif ($is_email): ?>
                            <div style="display:flex;align-items:stretch;">
                                <span style="display:flex;align-items:center;justify-content:center;background:#f0f0f1;border:1px solid #8c8f94;border-right:0;border-radius:4px 0 0 4px;padding:0 10px;font-size:13px;color:#3c434a;white-space:nowrap;">mailto:</span>
                                <input type="email" name="<?php echo esc_attr($option_name); ?>"
                                    id="<?php echo esc_attr($option_name); ?>"
                                    value="<?php echo esc_attr($input_value); ?>"
                                    placeholder="correo@ejemplo.com"
                                    style="border-radius:0 4px 4px 0;" />
                            </div>
                        <?php else: ?>
                            <input type="url" name="<?php echo esc_attr($option_name); ?>"
                                id="<?php echo esc_attr($option_name); ?>"
                                value="<?php echo esc_url($input_value); ?>"
                                placeholder="https://…" />
                        <?php endif; ?>
                        <?php submit_button(gf_e('social.save_button'), 'primary', 'submit', false, array('style' => 'margin:12px 0 0;align-self:flex-start;')); ?>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

function grupofadiar_validate_phone($number) {
    if (empty($number) || $number[0] !== '+') {
        return false;
    }

    // Formatear con espacio para el regex del proyecto: +XX 12345678
    $digits = substr($number, 1);
    $formatted = '';
    for ($i = 1; $i <= 4 && $i < strlen($digits); $i++) {
        $cc = substr($digits, 0, $i);
        $sub = substr($digits, $i);
        if (strlen($sub) >= 6 && strlen($sub) <= 15) {
            $formatted = '+' . $cc . ' ' . $sub;
            break;
        }
    }

    if (empty($formatted)) {
        return false;
    }

    if (!preg_match('/^\+\d{1,4}\s\d{6,15}$/', $formatted)) {
        return false;
    }

    if (class_exists('\libphonenumber\PhoneNumberUtil')) {
        try {
            $util = \libphonenumber\PhoneNumberUtil::getInstance();
            $proto = $util->parse($number, null);
            return $util->isValidNumber($proto);
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    return true;
}

function grupofadiar_initialize_social_options() {
    $keys = array('phone', 'instagram', 'facebook', 'email');
    foreach ($keys as $key) {
        $option_name = "social_url_{$key}";
        if (get_option($option_name) === false) {
            update_option($option_name, '#');
        }
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_social_options');
add_action('admin_init', 'grupofadiar_initialize_social_options');
