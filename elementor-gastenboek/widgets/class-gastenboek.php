<?php
/**
 * Gastenboek class.
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
class Gastenboek extends Widget_Base {
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
		return 'gastenboek';
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
		return __( 'Gastenboek', 'elementor-gastenboek' );
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
		return array( 'gastenboek' );
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
		$settings = $this->get_settings_for_display();
        $error ="";
        if(isset($_POST['add'])) {
            if(!empty($_POST['naam'])){
                if(!empty($_POST['email'])){
                    if(!empty($_POST['bericht'])){
                        require('dbconnect.php');
                        function safe($waarde){
                        $waarde = trim($waarde);
                        $waarde = stripslashes($waarde);
                        $waarde = htmlspecialchars($waarde);
                        return $waarde;
                        }
                        
                        $naam = safe($_POST['naam']);
                        $naam = $conn->real_escape_string($naam);
                        $email = safe($_POST['email']);
                        $email = $conn->real_escape_string($email);
                        $bericht = safe($_POST['bericht']);
                        $bericht = $conn->real_escape_string($bericht);
                        $today = date('Y-m-d H:i:s');
                        
                        $sql = "INSERT INTO mFD13_comments
                        (comment_ID, comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_author_IP, comment_date, comment_date_gmt, comment_content, comment_karma, comment_approved, comment_agent, comment_type, comment_parent, user_id) 
                        VALUES (NULL, '5463', '$naam', '$email', '', '', '$today', '$today', '$bericht', '0', '0', '', 'comment', '0', '0')";
                        
                        if($conn->query($sql)){
                            $error ="<br><div style='border-radius:10px; border:3px solid green; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center; '><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>reactie is binnen, het moet wel nog verwerkt worden.</p></div <br>";
                            
                            $mail = new PHPMailer();

                            // Settings
                            $mail->IsSMTP();
                            $mail->CharSet = 'UTF-8';

                            $mail->Host       = "mail02.compleet.it"; // SMTP server example
                            $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                            $mail->SMTPAuth   = true;                  // enable SMTP authentication
                            $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
                            $mail->Username   = "site@speeltuinwesterkwartier.nl"; // SMTP account username example
                            $mail->Password   = "password";        // SMTP account password example
                            $mail->setFrom('site@speeltuinwesterkwartier.nl','Speeltuinwesterkwartier');
                            $mail->addAddress('site@speeltuinwesterkwartier.nl');

                            // Content
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = 'Reactie van '.$naam;
                            $mail->Body    = $naam.' heeft een reactie geplaats op speeltuinwesterkwartier.nl<br><br>Het bericht:'.$bericht;
                            $mail->AltBody = $naam.' heeft een reactie geplaats op speeltuinwesterkwartier.nl<br><br>Het bericht:'.$bericht;

                            $mail->send();
                        }
                        else{
                            $error ="<br><div style='border-radius:10px; border:3px solid red; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Er is iets fout gegaan</p></div> <br>";
                        } 
                    }else{
                        $error="<br><div style='border-radius:10px; border:3px solid orange; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Bericht is niet ingevult</p></div><br>";
                    }
                }else{
                    $error="<br><div style='border-radius:10px; border:3px solid orange; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Email is niet ingevult</p></div><br>";
                }
            }else{
                $error="<br><div style='border-radius:10px; border:3px solid orange; margin-top:2vh; margin-bottom:2vh; display:flex; align-items:center;'><p style='margin:0; font-size:2rem; line-height:2; display:flex; align-items:center;'>Naam is niet ingevult</p></div><br>";
            }
        }
        ?>
        <form method=post style="color:black;">
            <label style='font-size:2rem;'>Uw naam*</label><input style="border-radius:10px; font-size:2rem;"  type=text name=naam /> <br>
            <label style='font-size:2rem;'>Uw e-mailadres*</label><input style="border-radius:10px; font-size:2rem;"  type=email name=email /> <br>
            <label style='font-size:2rem;'>Uw bericht*</label><textarea style="border-radius:10px; font-size:2rem; width:100%; height:25vh; border-radius:10xpx;" name=bericht> </textarea><br>
            <input type=submit name=add style="border-radius:10px; color:white; background-color:#004020; font-size:2rem;" value="Reactie plaatsen"/>
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
            <label>Uw naam*</label><input style="border-radius:10px;"  type=text name=naam /> <br>
            <label>Uw e-mailadres*</label><input style="border-radius:10px;"  type=email name=email /> <br>
            <label>Uw bericht*</label><textarea style="border-radius:10px;" style="width:100%; border-radius:10xpx;" name=bericht> </textarea><br>
            <input type=submit name=add style="border-radius:10px; color:white; background-color:#004020;" value="Reactie plaatsen"/>
        </form>
        <?php
        require('dbconnect.php');
            
	}
}

