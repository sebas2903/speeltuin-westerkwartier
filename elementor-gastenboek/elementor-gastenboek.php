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
 add_action( 'admin_menu', 'my_plugin_menu1' );

function my_plugin_menu1() {
        add_options_page( 'Settingstest', 'E-mails ophalen', 'manage_options', 'E-mails ophalen', 'Settingstest' );
    }

    function Settingstest(){
        ?>
        <p>Hier onder zijn alle e-mails die zijn ingeschreven op de nieuwsbrief.<br> kopieer deze in het adresbalk van de mail en verstuur de mail om zo ze allemaal een nieuwsbrief te sturen.<br><br>Het is handig om helemaal aan het eind van de email een link te zetten naar https://speeltuinwesterkwartier.nl/uitschrijven/ waar mensen zich kunnen uitschrijven.</p>
        <?php
        $error ="";
        require('widgets/dbconnect.php');
        $sql = "SELECT email FROM mFD13_newsletter";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
        // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<br>".$row["email"];
            }
        }
    }

/**
 * Include the Elementor_Awesomesauce class.
 */
 require plugin_dir_path( ELEMENTOR_GASTENBOEK ) . 'class-elementor-gastenboek.php';

