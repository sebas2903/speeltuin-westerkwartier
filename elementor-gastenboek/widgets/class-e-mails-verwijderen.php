<?php
/**
 * Nieuwsbrief class.
 *
 * @category   Class
 * @package    ElementorGastenboek
 * @subpackage WordPress
 * @author     Sebastiaan Heerema
 * @copyright  2020 Sebastiaan Heerema
 * @since      1.0.0
 * php version 7.3.9
 */
namespace ElementorGastenboek\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class Emailverwijderen extends Widget_Base {
	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'E-mail verwijderen';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Emailverwijderen', 'elementor-emailverwijderen' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-pencil';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}
	
	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return array( 'Emailverwijderen' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'elementor-emailverwijderen' ),
			)
		);

        $this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
    if(isset($_POST['add'])) {
        if(!empty($_POST['email'])){
        require('dbconnect.php');
        
        function safe($waarde){
            $waarde = trim($waarde);
            $waarde = stripslashes($waarde);
            $waarde = htmlspecialchars($waarde);
            return $waarde;
        }
        
        $email = safe($_POST['email']);
        $emai = $conn->real_escape_string($email);

        
        $sql = "DELETE FROM mFD13_newsletter WHERE email = ('$email')";
        
        if($conn->query($sql)){
            $error ="<br><div style='border-radius:10px; border:3px solid green; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center; '><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Uw verzoek is verwerkt.</p></div><br>";
            
            $mail = new PHPMailer(true);

            // Settings
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';

            $mail->Host       = "mail02.compleet.it"; 
            $mail->SMTPAuth   = true;                  
            $mail->SMTPSecure = "ssl";
            $mail->Port       = 465;                    
            $mail->Username   = "site@speeltuinwesterkwartier.nl"; 
            $mail->Password   = $SMTPWW;      
            $mail->setFrom('site@speeltuinwesterkwartier.nl','Speeltuinwesterkwartier');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);                                  
            $mail->Subject = 'Uitschrijven van nieuwsbrief speeltuin Westerkwartier';
            $mail->Body    = 'Uw heeft zich succesful Uitgeschreven op de nieuwsbrief van speeltuin westerkwartier<br><br>Uw krijgt nu geen e-mails meer over activiteiten of anderen zaken van de speeltuin<br><br> Toch weer aan willen melden? dit kan altijd op de website <a href="speeltuinwesterkwartier.nl">Speeltuin westerkwartier.nl</a>';
            $mail->AltBody = 'Uw heeft zich succesful Uitgeschreven op de nieuwsbrief van speeltuin westerkwartier<br><br>Uw krijgt nu geen e-mails meer over activiteiten of anderen zaken van de speeltuin<br><br> Toch weer aan willen melden? dit kan altijd op de website <a href="speeltuinwesterkwartier.nl">Speeltuin westerkwartier.nl</a>';

            $mail->send();
        }
        else{
            $error ="<br><div style='border-radius:10px; border:3px solid red; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Er is iets fout gegaan</p></div> <br>";
        }
    }
    else{
        $error ="<br><div style='border-radius:10px; border:3px solid orange; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Email is niet ingevult.</p></div><br>";
    }
}
        
        ?>
        <form method=post id="form" style="color:black;">
            <label style='font-size:2.5rem; color:black;'>Verwijder uw account</label><input style="border-radius:10px; font-size:2rem;"  type=email name=email placeholder=email/>
            <input type=submit name=add style="border-radius:10px; color:black; background-color:white; font-size:2rem; width:100%; margin-top:1vh;" value="Inschrijven"/>
            <?php echo $error; ?>
        </form>
        <?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
        ?>
        <form method=post id="form" style="color:black;">
            <label style='border-radius:10px; font-size:2rem;'>Verwijder uw account</label><input style="border-radius:10px; font-size:2rem;"  type=email name=email placeholder=email/>
            <input type=submit name=add style="border-radius:10px; color:white; background-color:#004020; font-size:2rem;" value="Account verwijderen"/>
            <?php echo $error; ?>
        <?php
        require('dbconnect.php');
            
	}
}