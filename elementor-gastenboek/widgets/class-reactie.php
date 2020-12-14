<?php
/**
 * reactie class.
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


// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */

class Reactie extends Widget_Base {
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
		return 'Gastenboek-reactie';
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
		return __( 'Gastenboek-reactie', 'elementor-gastenboek' );
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
		return array( 'Gastenboek-reactie' );
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
        require('dbconnect.php');
        $sql = "SELECT comment_author, comment_content FROM mFD13_comments WHERE comment_post_ID = 5463 AND comment_approved = 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc()
            
        if ($result->num_rows > 0) {
            echo "<table><tr><th>ID</th><th>Name</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo $row["comment_author"];
            }
        echo "</table>";
        }   

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
        require('dbconnect.php');
        $sql = "SELECT comment_author, comment_content FROM mFD13_comments WHERE comment_post_ID = 5463 AND comment_approved = 1;";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc()
        ?>
        <p style="color:black;"><?php echo $row["comment_author"]; ?></p>
        <?php
            
	}
}