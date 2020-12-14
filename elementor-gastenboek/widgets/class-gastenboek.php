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
?>
<style>
<?php include './includes/css/form.css'; ?>
</style>
<?php
namespace ElementorGastenboek\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;

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
                            $error ="<br>reactie is binnen, het moet wel nog verwerkt worden. <br>";
                        }
                        else{
                            $error ="<br> er is iets fout gegaan <br>";
                        } 
                    }else{
                        $error="<br>bericht is niet ingevult<br>";
                    }
                }else{
                    $error="<br>email is niet ingevult<br>";
                }
            }else{
                $error="<br>naam is niet ingevult<br>";
            }
        }
    ?>
        <form method=post style="color:black;">
            <label>Uw naam*</label><input  type=text name=naam /> <br>
            <label>Uw e-mailadres*</label><input  type=email name=email /> <br>
            <label>Uw bericht*</label><textarea class="txtarea-style" name=bericht> </textarea><br>
            <input type=submit name=add value=Reactie plaatsen/>
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
            <?php echo $error; ?>
            <label>Naam:</label><input  type=text name=naam /> <br>
            <label>email:</label><input  type=email name=email /> <br>
            <label>bericht:</label><input class="txtarea-style"  type=textarea name=bericht /> <br>
            <input type=submit name=add value=Reactie plaatsen/>
        </form>
        <?php
        require('dbconnect.php');
            
	}
}