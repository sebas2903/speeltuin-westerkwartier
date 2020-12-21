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
        add_options_page( 'Settingstest', 'E-mails ophalen', 'manage_options', 'E-mails ophalen', 'Settingstest' );
    }

    function Settingstest(){
    ?>
        <p>hallo</p>
    <?php
    }

/**
 * Include the Elementor_Awesomesauce class.
 */
 require plugin_dir_path( ELEMENTOR_GASTENBOEK ) . 'class-elementor-gastenboek.php';

