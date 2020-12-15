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
        $sql = "SELECT comment_author, comment_content, comment_date_gmt FROM mFD13_comments WHERE comment_post_ID = 5463 AND comment_approved = 1 ORDER BY comment_date_gmt DESC";
        $result = $conn->query($sql);
            
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "
                <div style='width:100%; border:1px solid #004020; border-radius:10px; margin-top:2vh;'>
                    <h4 style='color:black; margin:0px; margin-left:1vw; font-size:2.5rem; '>".$row["comment_author"]."</h4>
                    <p style='color:black; margin:0px; margin-left:1vw; font-size:1.5rem;'>".$row["comment_date_gmt"]."</p>
                    <p style='color:black; margin-top:3vh; margin-left:1vw;' margin-top:2vh; font-size:2rem;>".$row["comment_content"]."</p>
                </div>";
            }
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
        $sql = "SELECT comment_author, comment_content, comment_date_gmt FROM mFD13_comments WHERE comment_post_ID = 5463 AND comment_approved = 1 ORDER BY comment_date_gmt DESC";
        $result = $conn->query($sql);
            
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "
                <div>
                    <p style='color:black; margin:0;'>".$row["comment_author"]."</p>
                    <p style=color:black;>".$row["comment_date_gmt"]."</p>
                    <p style=color:black;>".$row["comment_content"]."</p>
                </div>";
            }
        }  
            
	}
}