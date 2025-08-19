<?php
/**
 * Plugin Name:       Scroll Top With Progress
 * Plugin URI:        https://wordpress.org/plugins/scroll-top-with-progress/
 * Description:       Scroll top with progress plugin will help you to enable Back to Top button to your WordPress website.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Fazle Rabbi
 * Author URI:        https://devstarit.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/irabbi60
 * Text Domain:       stwp
 */

// Enqueue CSS
function stwp_enqueue_style() {
    wp_enqueue_style('stwp-style', plugins_url('css/stwp-style.css', __FILE__));
}
add_action("wp_enqueue_scripts", "stwp_enqueue_style");

// Enqueue JavaScript
function stwp_enqueue_scripts() {
    wp_enqueue_script('stwp-plugin-script', plugins_url('js/stwp-plugin.js', __FILE__), array('jquery'), '1.0.0', true);
}
add_action("wp_enqueue_scripts", "stwp_enqueue_scripts");

// Add Scroll to Top button HTML
function stwp_add_scroll_button() {
    ?>
    <button id="scrollTopButton" class="stwp-scrolltop scrolltop-hide stwp-shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
    </button>
    <?php
}
add_action('wp_footer', 'stwp_add_scroll_button');


// Plugin Customization Sattings
add_action( "customize_register", "stwp_scroll_to_top");

function stwp_scroll_to_top($wp_customize){

    // Section
    $wp_customize->add_section('stwp_scroll_top_section', array(
        'title'       => __('Scroll To Top', 'stwp'),
        'description' => __('Scroll to top plugin will help you to enable Back to Top button on your WordPress website.', 'stwp'),
        'priority'    => 160,
    ));

    // Primary Color
    $wp_customize->add_setting('stwp_primary_color', array(
        'default'   => '#BDE162',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'stwp_primary_color',
            array(
                'label'   => __('Primary Color', 'stwp'),
                'section' => 'stwp_scroll_top_section',
                'settings'=> 'stwp_primary_color',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting('stwp_secondary_color', array(
        'default'   => '#F5F2F0',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'stwp_secondary_color',
            array(
                'label'   => __('Secondary Color', 'stwp'),
                'section' => 'stwp_scroll_top_section',
                'settings'=> 'stwp_secondary_color',
            )
        )
    );

    // Icon Color
    $wp_customize->add_setting('stwp_icon_color', array(
        'default'   => '#000000',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'stwp_icon_color',
            array(
                'label'   => __('Icon Color', 'stwp'),
                'section' => 'stwp_scroll_top_section',
                'settings'=> 'stwp_icon_color',
            )
        )
    );
}

// Theme CSS Customization
function stwp_theme_color_customize(){
  ?>
  <style>
        .stwp-scrolltop:before {
            background: conic-gradient(
                <?php echo esc_attr( get_theme_mod("stwp_primary_color", "#BDE162") ); ?> var(--stwp-scroll-progress),
                <?php echo esc_attr( get_theme_mod("stwp_secondary_color", "#F5F2F0") ); ?> 0
            );
        }
        .stwp-scrolltop {
            color: <?php echo esc_attr( get_theme_mod("stwp_icon_color", "#000000") ); ?>
        }
    </style>

  <?php
}
add_action('wp_head', 'stwp_theme_color_customize');