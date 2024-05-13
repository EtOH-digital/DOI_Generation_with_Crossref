<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://www.fiverr.com/mustafaje
 * @since      1.0.0
 *
 * @package    Doi_Creator
 * @subpackage Doi_Creator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Doi_Creator
 * @subpackage Doi_Creator/admin
 * @author     GM <gm.software.developer@gmail.com>
 */
class Doi_Creator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        $current_screen = get_current_screen();
        if( $current_screen && is_object($current_screen) && isset($current_screen->id) && $current_screen->id == 'edit-post' ){
            wp_enqueue_style( 'thickbox' );
        }
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/doi-creator-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/doi-creator-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name, 'doi_settings',
            array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        if ( get_post_type() === 'post' ) {
            wp_enqueue_script( 'thickbox' );
        }
	}

    function xml_form_html(){
        include_once DOI_CREATOR_PATH . 'admin/partials/xml-form.php';
    }

    function register_settings(){
        register_setting('doi-creator-settings', 'doi_creator_options');
    }

    function admin_menu(){
        add_options_page(
            'DOI Generation with Crossref Settings',
            'DOI Generation with Crossref',
            'manage_options',
            'doi-creator',
            array($this, 'main_settings')
        );
    }

    function main_settings(){
	    if( isset($_GET['page'], $_GET['tab']) && $_GET['page'] == 'doi-creator' && $_GET['tab'] == 'logs' ){
            include_once DOI_CREATOR_PATH . 'admin/partials/submission-logs.php';
        } else {
            include_once DOI_CREATOR_PATH . 'admin/partials/main-settings.php';
        }
    }

    function set_custom_edit_post_columns($columns) {
        $columns['create_doi'] = __( 'DOI Generation with Crossref' );
        return $columns;
    }

    function custom_post_column( $column, $post_id ) {
        switch ( $column ) {
            case 'create_doi' :
                $btn = '';
                $doi_submit = get_post_meta($post_id, 'doi_submitted', true);
                if( isset($doi_submit) && $doi_submit){
                    $btn .= '<a href="#TB_inline?&width=800&height=550&inlineId=submit-doi-form" data-postid="'.esc_attr($post_id).'" data-posttitle="'.esc_attr(get_the_title($post_id)).'" class="thickbox button button-primary createDOI">Re Create DOI</span>';
                } else{
                    $btn .= '<a href="#TB_inline?&width=800&height=550&inlineId=submit-doi-form" data-postid="'.esc_attr($post_id).'" data-posttitle="'.esc_attr(get_the_title($post_id)).'" class="thickbox button button-primary createDOI">Create DOI</span>';
                }
                $xml_filename = 'doi-' . $post_id . '.xml';
                $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/';
                if( file_exists($upload_dir.$xml_filename) ) {
                    $fileUrl = WP_CONTENT_URL . '/uploads/doi-files/'.$xml_filename;
                    $btn .= '<a class="button viewXml" href="'.$fileUrl.'" target="_blank">View Xml</a>';
                }
                if( isset($doi_submit) && $doi_submit == 'no' ) {
                    $btn .= '<span data-postid="'.esc_attr($post_id).'" class="button button-primary submitDOI">Submit DOI</span>';
                } else {
                    $btn .= '<span data-postid="'.esc_attr($post_id).'" class="button button-primary submitDOI">Re Submit DOI</span>';
                }
                echo $btn;
                break;
        }
    }
}
