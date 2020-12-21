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
class Nieuwsbrief extends Widget_Base {
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
		return 'Nieuwsbrief';
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
		return __( 'Nieuwsbrief', 'elementor-nieuwsbrief' );
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
		return array( 'Nieuwsbrief' );
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
				'label' => __( 'Content', 'elementor-gastenboek' ),
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
        $error ="";
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
                        $email = $conn->real_escape_string($email);
                        $today = date('Y-m-d H:i:s');
                        
                        $sql = "INSERT INTO mFD13_newsletter
                        (email, id, created) 
                        VALUES ('$email', NULL, '$today')";
                        
                        if($conn->query($sql)){
                            $error ="
                            <script type='text/javascript'>
                                var element = document.getElementById('form');
                                element.scrollIntoView(false);
                            </script>
                            
                            <br><div style='border-radius:10px; border:3px solid green; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center; '><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Uw bent succesvol ingeschreven. Er is een bevestigings mail verstuurt.</p></div> <br>";
                            
                            
                            
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
                            $mail->Subject = 'Nieuwsbrief speeltuin Westerkwartier';
                            $mail->Body    = 'Uw heeft zich succesful ingeschreven op de nieuwsbrief van speeltuin westerkwartier<br><br>In de nieuwsbrief kunt uw verschillende dingen verwachten. Dit gaat van mails over activiteiten maar ook aankondegingen.<br><br> Afmelden? <a href="#">klik hier</a>';
                            $mail->AltBody = 'Uw heeft zich succesful ingeschreven op de nieuwsbrief van speeltuin westerkwartier<br><br>In de nieuwsbrief kunt uw verschillende dingen verwachten. Dit gaat van mails over activiteiten maar ook aankondegingen.<br><br> Afmelden? <a href="https://speeltuinwesterkwartier.nl/e-mail-verwijderen/">klik hier</a>';

                            $mail->send();
                        }
                        else{
                            $error ="
                            <script type='text/javascript'>
                                var element = document.getElementById('form');
                                element.scrollIntoView(false);
                            </script>
                            
                            <br><div style='border-radius:10px; border:3px solid red; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Er is iets fout gegaan</p></div> <br>";
                        } 
                    }else{
                        $error ="
                        <script type='text/javascript'>
                            var element = document.getElementById('form');
                            element.scrollIntoView(false);
                        </script>
                        
                        <br><div style='border-radius:10px; border:3px solid orange; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Email is niet ingevult.</p></div><br>
                        ";
                    }
        }
        ?>
        <form method=post style="color:black;">
            <label style='font-size:2.5rem; color:black;'>Abboneer op onze nieuwsbrief</label><input style="border-radius:10px; font-size:2rem;"  type=email name=email value=email/>
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
        <form method=post style="color:black;">
            <label style='font-size:2.5rem; color:black;'>Abboneer op onze nieuwsbrief</label><input style="border-radius:10px; font-size:2rem;"  type=email name=email value=email/>
            <input type=submit name=add style="border-radius:10px; color:black; background-color:white; font-size:2rem; width:100%; margin-top:1vh;" value="Inschrijven"/>
            <?php echo $error; ?>
        </form>
        <?php
        require('dbconnect.php');
            
	}
}

