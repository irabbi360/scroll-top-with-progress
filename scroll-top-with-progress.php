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
 * Text Domain:       scroll-top-with-progress
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'STWP_Scroll_Top' ) ) :

class STWP_Scroll_Top {

    private $defaults = array(
        'stwp-primary-color'   => '#BDE162',
        'stwp-secondary-color' => '#F5F2F0',
        'stwp-icon-color'      => '#000000',
        'stwp-scroll-position' => 'right',
    );

    public function __construct() {
        // Admin menu
        add_action( 'admin_menu', array( $this, 'add_theme_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_theme_css' ) );

        // Frontend
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
        add_action( 'wp_footer', array( $this, 'add_scroll_button' ) );
        add_action( 'wp_head', array( $this, 'theme_color_customize' ) );
        add_action( 'wp_head', array( $this, 'scrolltop_custom_css' ) );

        // Customizer
        add_action( 'customize_register', array( $this, 'scroll_to_top_customizer' ) );
    }

    // Admin menu page
    public function add_theme_page() {
        add_menu_page(
            __( 'Scroll To Top Option for Admin', 'scroll-top-with-progress' ),
            __( 'Scroll To Top', 'scroll-top-with-progress' ),
            'manage_options',
            'stwp-plugin-option',
            array( $this, 'create_page' ),
            'dashicons-arrow-up-alt',
            101
        );
    }

    // Admin styles
    public function add_theme_css() {
        wp_enqueue_style( 'stwp-admin-style', plugins_url( 'css/stwp-admin-style.css', __FILE__ ), false, '1.0.0' );
    }

    // Admin page
    public function create_page() {
        // Handle reset
        if ( isset($_POST['stwp_reset_colors']) && check_admin_referer('stwp_reset_action','stwp_reset_nonce') ) {
            foreach( $this->defaults as $key => $val ) {
                update_option($key, $val);
            }
            echo '<div class="updated"><p>' . esc_html__( 'Colors have been reset to default.', 'scroll-top-with-progress' ) . '</p></div>';
        }
        ?>
        <div class="stwp_main_area">
            <div class="stwp_body_area stwp_common">
                <h3 id="title"><?php echo esc_html__( '🎨 Scroll Top Customizer', 'scroll-top-with-progress' ); ?></h3>
                <form action="options.php" method="post">
                    <?php wp_nonce_field('update-options'); ?>

                    <!-- Primary Color -->
                    <label for="stwp-primary-color"><?php echo esc_html__( 'Primary Color', 'scroll-top-with-progress' ); ?></label>
                    <small><?php echo esc_html__( 'Add your Primary Color', 'scroll-top-with-progress' ); ?></small>
                    <input type="color" name="stwp-primary-color" 
                           value="<?php echo esc_attr( get_option('stwp-primary-color', $this->defaults['stwp-primary-color']) ); ?>">

                    <!-- Secondary Color -->
                    <label for="stwp-secondary-color"><?php echo esc_html__( 'Secondary Color', 'scroll-top-with-progress' ); ?></label>
                    <small><?php echo esc_html__( 'Add your Secondary Color', 'scroll-top-with-progress' ); ?></small>
                    <input type="color" name="stwp-secondary-color" 
                           value="<?php echo esc_attr( get_option('stwp-secondary-color', $this->defaults['stwp-secondary-color']) ); ?>">

                    <!-- Icon Color -->
                    <label for="stwp-icon-color"><?php echo esc_html__( 'Icon Color', 'scroll-top-with-progress' ); ?></label>
                    <small><?php echo esc_html__( 'Add your Icon Color', 'scroll-top-with-progress' ); ?></small>
                    <input type="color" name="stwp-icon-color" 
                           value="<?php echo esc_attr( get_option('stwp-icon-color', $this->defaults['stwp-icon-color']) ); ?>">

                    <!-- Button Position -->
                    <label for="stwp-scroll-position"><?php echo esc_html__( 'Button Position', 'scroll-top-with-progress' ); ?></label>
                    <small><?php echo esc_html__( 'Where do you want to show your button position?', 'scroll-top-with-progress' ); ?></small>
                    <select name="stwp-scroll-position" id="stwp-scroll-position">
                        <option value="left" <?php selected(get_option('stwp-scroll-position'), 'left'); ?>><?php echo esc_html__('Left', 'scroll-top-with-progress'); ?></option>
                        <option value="right" <?php selected(get_option('stwp-scroll-position'), 'right'); ?>><?php echo esc_html__('Right', 'scroll-top-with-progress'); ?></option>
                    </select>

                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="page_options" value="stwp-primary-color, stwp-secondary-color, stwp-icon-color, stwp-scroll-position">

                    <p>
                        <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'scroll-top-with-progress') ?>">
                    </p>
                </form>

                <!-- Reset Form -->
                <form method="post">
                    <?php wp_nonce_field('stwp_reset_action','stwp_reset_nonce'); ?>
                    <p>
                        <input type="submit" name="stwp_reset_colors" class="button button-secondary" value="<?php esc_attr_e('Reset to Default Colors', 'scroll-top-with-progress') ?>">
                    </p>
                </form>
            </div>

            <div class="stwp_sidebar_area stwp_common">
                <h3 id="title"><?php echo esc_html__( '👩‍💻 About Author', 'scroll-top-with-progress' ); ?></h3>
                <p><img src="<?php echo esc_url( plugin_dir_url(__FILE__) . 'img/author.jpeg' ); ?>" class="img-author" alt=""></p>
                <p><?php echo esc_html__( "I'm Fazle Rabbi, almost 5 years experience in front end & back-end development. Confident, driven, trustworthy, hard-working individual.", 'scroll-top-with-progress' ); ?></p>
                <h5 id="title"><?php echo esc_html__( 'Watch Help Video', 'scroll-top-with-progress' ); ?></h5>
                <p><a href="https://www.youtube.com/@CodingXpress" target="_blank" class="btn"><?php echo esc_html__('Watch On YouTube', 'scroll-top-with-progress'); ?></a></p>
            </div>
        </div>
        <?php
    }

    // Frontend styles and scripts
    public function enqueue_styles_scripts() {
        wp_enqueue_style('stwp-style', plugins_url('css/stwp-style.css', __FILE__));
        wp_enqueue_script('stwp-plugin-script', plugins_url('js/stwp-plugin.js', __FILE__), array('jquery'), '1.0.0', true);
    }

    // Add scroll button
    public function add_scroll_button() {
        ?>
        <button id="scrollTopButton" class="stwp-scrolltop scrolltop-hide stwp-shadow-lg">
            <img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/back-to-top.svg'; ?>" alt="Back to top" />
        </button>
        <?php
    }

    // Output theme colors
    public function theme_color_customize() {
        $primary   = get_option('stwp-primary-color', $this->defaults['stwp-primary-color']);
        $secondary = get_option('stwp-secondary-color', $this->defaults['stwp-secondary-color']);
        $icon      = get_option('stwp-icon-color', $this->defaults['stwp-icon-color']);
        ?>
        <style>
            .stwp-scrolltop:before {
                background: conic-gradient(
                    <?php echo esc_attr($primary); ?> var(--stwp-scroll-progress),
                    <?php echo esc_attr($secondary); ?> 0
                );
            }
            .stwp-scrolltop {
                color: <?php echo esc_attr($icon); ?>;
            }
        </style>
        <?php
    }

    // Scroll button position CSS
    public function scrolltop_custom_css() {
        $position = get_option('stwp-scroll-position', 'right');
        ?>
        <style>
            .stwp-scrolltop{
                <?php if ($position === 'left') : ?>
                    left: 2rem;
                    right: auto;
                <?php else : ?>
                    right: 2rem;
                    left: auto;
                <?php endif; ?>
                z-index: 9999;
            }
        </style>
        <?php
    }

    // Customizer settings
    public function scroll_to_top_customizer($wp_customize) {
        $primary   = get_option('stwp-primary-color', $this->defaults['stwp-primary-color']);
        $secondary = get_option('stwp-secondary-color', $this->defaults['stwp-secondary-color']);
        $icon      = get_option('stwp-icon-color', $this->defaults['stwp-icon-color']);

        $wp_customize->add_section('stwp_scroll_top_section', array(
            'title'       => __('Scroll To Top', 'scroll-top-with-progress'),
            'description' => __('Scroll to top plugin will help you to enable Back to Top button on your WordPress website.', 'scroll-top-with-progress'),
            'priority'    => 160,
        ));

        // Colors
        foreach (['primary', 'secondary', 'icon'] as $color) {
            $option_name = "stwp-{$color}-color";
            $default     = ${$color};
            $wp_customize->add_setting($option_name, array(
                'default'   => $default,
                'type'      => 'option',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            ));
            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    $option_name,
                    array(
                        'label'   => ucfirst($color) . ' ' . __('Color', 'scroll-top-with-progress'),
                        'section' => 'stwp_scroll_top_section',
                        'settings'=> $option_name,
                    )
                )
            );
        }

        // Position
        $wp_customize->add_setting('stwp-scroll-position', array(
            'default'   => get_option('stwp-scroll-position', 'right'),
            'type'      => 'option',
            'transport' => 'refresh',
            'sanitize_callback' => array($this, 'sanitize_position'),
        ));
        $wp_customize->add_control('stwp-scroll-position', array(
            'label'   => __('Position', 'scroll-top-with-progress'),
            'section' => 'stwp_scroll_top_section',
            'type'    => 'radio',
            'choices' => array(
                'left'  => __('Left', 'scroll-top-with-progress'),
                'right' => __('Right', 'scroll-top-with-progress'),
            ),
        ));
    }

    // Sanitize position
    public function sanitize_position($input) {
        return in_array($input, array('left','right'), true) ? $input : 'right';
    }
}

// Initialize plugin
new STWP_Scroll_Top();

endif;
