<?php
/**
 * Elementor Gastenboek WordPress Plugin
 *
 * @package ElementorGastenboek
 *
 * Plugin Name: Elementor Speeltuinwesterkwartier Gastenboek
 * Description: Gastenboek voor de gastenboek widget
 * Version:     1.0.0
 * Author:      Sebastiaan Heerema
 Text Domain: elementor-gastenboek
 */
 define( 'ELEMENTOR_GASTENBOEK', __FILE__ );

 add_action( 'admin_init', 'register_Settings' );
 add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
        add_options_page( 'Settings', 'openingstijd aanpassen', 'manage_options', 'openingstijd-aanpassen', 'Settings' );
    }

    function Settings(){
        ?>
        <div class="wrap">
            <h1>Openingstijd aanpassen</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'my-settings-group' ); ?> 
                <?php do_settings_sections( 'my-settings-group' ); ?>
                <p>Verander de openingstijden</p>
                <p>(bijvoorbeeld 9:00 - 17:00 uur)</p>
                
                <span>Maandag:&nbsp;&nbsp;&nbsp; </span><input type="text" name="maandag" id="beginjaar" value="<?php echo esc_attr( get_option('maandag') ); ?>" /><br>
                
                <span>Dinsdag:&nbsp;&nbsp;&nbsp;&nbsp; </span><input type="text" name="dinsdag" id="beginjaar" value="<?php echo esc_attr( get_option('dinsdag') ); ?>" /><br>
                
                <span>Woensdag:&nbsp; </span><input type="text" name="woensdag" id="beginjaar" value="<?php echo esc_attr( get_option('woensdag') ); ?>" /><br>
                
                <span>Donderdag: </span><input type="text" name="donderdag" id="beginjaar" value="<?php echo esc_attr( get_option('donderdag') ); ?>" /><br>
                
                <span>Vrijdag:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><input type="text" name="vrijdag" id="beginjaar" value="<?php echo esc_attr( get_option('vrijdag') ); ?>" /><br>
                
                <span>Zaterdag:&nbsp;&nbsp;&nbsp;&nbsp; </span><input type="text" name="zaterdag" id="beginjaar" value="<?php echo esc_attr( get_option('zaterdag') ); ?>" /><br>
                
                <span>Zondag:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><input type="text" name="zondag" id="beginjaar" value="<?php echo esc_attr( get_option('zondag') ); ?>" /><br>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php
    }

/**
 * Include the Elementor_Awesomesauce class.
 */
 require plugin_dir_path( ELEMENTOR_GASTENBOEK ) . 'class-elementor-gastenboek.php';

