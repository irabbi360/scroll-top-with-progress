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

 /*
 * Plugin Option Page Function
 */
function stwp_add_theme_page(){
add_menu_page( 'Scroll To Top Option for Admin', 'Scroll To Top', 'manage_options', 'stwp-plugin-option', 'stwp_create_page', 'dashicons-arrow-up-alt', 101 );
}
add_action( 'admin_menu', 'stwp_add_theme_page' );

/*
* Plugin Option Page Style
*/
function stwp_add_theme_css(){
  wp_enqueue_style( 'stwp-admin-style', plugins_url( 'css/stwp-admin-style.css', __FILE__ ), false, "1.0.0");
}
add_action('admin_enqueue_scripts', 'stwp_add_theme_css');

/**
 * Plugin Callback
 */
function stwp_create_page(){

    // Default colors
    $defaults = array(
        'stwp-primary-color'   => '#BDE162',
        'stwp-secondary-color' => '#F5F2F0',
        'stwp-icon-color'      => '#000000',
    );

    // Reset handling
    if ( isset($_POST['stwp_reset_colors']) && check_admin_referer('stwp_reset_action','stwp_reset_nonce') ) {
        foreach( $defaults as $key => $val ) {
            update_option($key, $val);
        }
        echo '<div class="updated"><p>Colors have been reset to default.</p></div>';
    }

    ?>
      <div class="stwp_main_area">
        <div class="stwp_body_area stwp_common">
          <h3 id="title"><?php print esc_attr( '🎨 Scroll Top Customizer' ); ?></h3>
          <form action="options.php" method="post">
            <?php wp_nonce_field('update-options'); ?>

            <!-- Primary Color -->
            <label for="stwp-primary-color"><?php print esc_attr( 'Primary Color' ); ?></label>
            <small>Add your Primary Color</small>
            <input type="color" name="stwp-primary-color" 
                   value="<?php echo esc_attr( get_option('stwp-primary-color', $defaults['stwp-primary-color']) ); ?>">

            <!-- Secondary Color -->
            <label for="stwp-secondary-color"><?php print esc_attr( 'Secondary Color' ); ?></label>
            <small>Add your Secondary Color</small>
            <input type="color" name="stwp-secondary-color" 
                   value="<?php echo esc_attr( get_option('stwp-secondary-color', $defaults['stwp-secondary-color']) ); ?>">

            <!-- Icon Color -->
            <label for="stwp-icon-color"><?php print esc_attr( 'Icon Color' ); ?></label>
            <small>Add your Icon Color</small>
            <input type="color" name="stwp-icon-color" 
                   value="<?php echo esc_attr( get_option('stwp-icon-color', $defaults['stwp-icon-color']) ); ?>">

            <!-- Button Position -->
            <label for="stwp-scroll-position"><?php echo esc_attr(__('Button Position')); ?></label>
            <small>Where do you want to show your button position?</small>
            <select name="stwp-scroll-position" id="stwp-scroll-position">
              <option value="left" <?php selected(get_option('stwp-scroll-position'), 'left'); ?>>Left</option>
              <option value="right" <?php selected(get_option('stwp-scroll-position'), 'right'); ?>>Right</option>
            </select>

            <input type="hidden" name="action" value="update">
            <input type="hidden" name="page_options" value="stwp-primary-color, stwp-secondary-color, stwp-icon-color, stwp-scroll-position">

            <p>
              <input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Changes', 'stwp') ?>">
            </p>
          </form>

          <!-- Reset Form -->
          <form method="post">
            <?php wp_nonce_field('stwp_reset_action','stwp_reset_nonce'); ?>
            <p>
              <input type="submit" name="stwp_reset_colors" class="button button-secondary" value="<?php _e('Reset to Default Colors', 'stwp') ?>">
            </p>
          </form>
        </div>

        <div class="stwp_sidebar_area stwp_common">
          <h3 id="title"><?php print esc_attr( '👩‍💻 About Author' ); ?></h3>
          <p><img src="<?php print plugin_dir_url(__FILE__) . '/img/author.jpeg' ?>" class="img-author" alt=""></p>
          <p>I'm <strong><a href="https://github.com/irabbi360/" target="_blank">Fazle Rabbi</a></strong>. I'd like to introduce myself as a Full-stack web application developer. Almost 5 years of experience in front end & back-end development. I'm a confident, driven, trustworthy, hard-working individual with a flexible attitude to work and acquire new skills. I can work on my own initiative or as part of a team in management and leadership with a dedication to success.</p>
          <h5 id="title"><?php print esc_attr( 'Watch Help Video' ); ?></h5>
          <p><a href="https://www.youtube.com/@CodingXpress" target="_blank" class="btn">Watch On YouTube</a></p>
        </div>
      </div>
    <?php
}

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
    // Default colors
    $primary   = get_option('stwp-primary-color', '#BDE162');
    $secondary = get_option('stwp-secondary-color', '#F5F2F0');
    $icon      = get_option('stwp-icon-color', '#000000');


    // Section
    $wp_customize->add_section('stwp_scroll_top_section', array(
        'title'       => __('Scroll To Top', 'stwp'),
        'description' => __('Scroll to top plugin will help you to enable Back to Top button on your WordPress website.', 'stwp'),
        'priority'    => 160,
    ));

    // Primary Color
    $wp_customize->add_setting('stwp-primary-color', array(
        'default'   => $primary,
        'type'      => 'option',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'stwp-primary-color',
            array(
                'label'   => __('Primary Color', 'stwp'),
                'section' => 'stwp_scroll_top_section',
                'settings'=> 'stwp-primary-color',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting('stwp-secondary-color', array(
        'default'   => $secondary,
        'type'      => 'option',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'stwp-secondary-color',
            array(
                'label'   => __('Secondary Color', 'stwp'),
                'section' => 'stwp_scroll_top_section',
                'settings'=> 'stwp-secondary-color',
            )
        )
    );

    // Icon Color
    $wp_customize->add_setting('stwp-icon-color', array(
        'default'   => $icon,
        'type'      => 'option',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'stwp-icon-color',
            array(
                'label'   => __('Icon Color', 'stwp'),
                'section' => 'stwp_scroll_top_section',
                'settings'=> 'stwp-icon-color',
            )
        )
    );

    // Position Setting
    $wp_customize->add_setting('stwp-scroll-position', array(
        'default'   => get_option('stwp-scroll-position', 'right'), // default position
        'type'      => 'option',
        'transport' => 'refresh',
        'sanitize_callback' => 'stwp_sanitize_position',
    ));

    // Position Control (radio)
    $wp_customize->add_control('stwp-scroll-position', array(
        'label'   => __('Position', 'stwp'),
        'section' => 'stwp_scroll_top_section',
        'type'    => 'radio',
        'choices' => array(
            'left'  => __('Left', 'stwp'),
            'right' => __('Right', 'stwp'),
        ),
    ));
}

// Theme CSS Customization
function stwp_theme_color_customize(){
    $primary   = get_option('stwp-primary-color', '#BDE162');
    $secondary = get_option('stwp-secondary-color', '#F5F2F0');
    $icon      = get_option('stwp-icon-color', '#000000');
  ?>
  <style>
        .stwp-scrolltop:before {
            background: conic-gradient(
                <?php echo esc_attr( $primary ); ?> var(--stwp-scroll-progress),
                <?php echo esc_attr( $secondary ); ?> 0
            );
        }
        .stwp-scrolltop {
            color: <?php echo esc_attr( $icon ); ?>;
        }
    </style>

  <?php
}
add_action('wp_head', 'stwp_theme_color_customize');

// sanitize position
function stwp_sanitize_position($input) {
    $valid = array('left', 'right');
    return in_array($input, $valid, true) ? $input : 'right';
}

// position custom css
add_action('wp_head', 'stwp_scrolltop_custom_css');
function stwp_scrolltop_custom_css() {
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