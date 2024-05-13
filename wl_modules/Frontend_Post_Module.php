<?php

/**
 *
 * @package Frontend_Post_Module
 * @author Arafat | Arafat.dml@gmail.com
 * @find me in fiver fiverr.com/web_lover
 * @date April/08/2024 01:34 Am
 * 
 */


class Frontend_Post_Module {

    private static $instance = null;

    private function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_post_module_script_loading' ) );
        add_action( 'wp_ajax_wl_frontend_post_ajax', array( $this, 'wl_handle_frontend_post_ajax' ) );
        add_action( 'wp_ajax_nopriv_wl_frontend_post_ajax', array( $this, 'wl_handle_frontend_post_ajax' ) );
    }

    public function frontend_post_module_script_loading(){

        if( is_single() ){

            # Get the post meta
            $post_doi = get_post_meta( get_the_ID(), 'post_doi' );

            wp_enqueue_script('frontend_post_module_ajax', DOI_CREATOR_URL. 'wl_modules/assets/js/frontend_script.js', array('jquery'));
                wp_localize_script('frontend_post_module_ajax', 'front_ajax_wl', array(
                    'post_id' => get_the_ID(),
                    'post_doi' => $post_doi,
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('my-nonce'),
                ));
        }

    }

    public function wl_handle_frontend_post_ajax(){
        
        // Nonce verification
        if ( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'my-nonce' ) ) {
            wp_send_json_error('Nonce verification failed');
        }

        // do other things
        // pr($_POST);

        $post_id = isset($_POST['data']['post_id']) ? absint( $_POST['data']['post_id'] ) : '';

        $res = get_post_meta( $post_id, 'DOI' );

        if( isset($res[0]) && $res[0] ){
            $res = $res[0];
            wp_send_json_success(
                array(
                    'has_data' => 1,
                    'res' => $res,
                )
            );
        }else{
            wp_send_json_success(
                array(
                    'has_data' => 0,
                    'res' => [],
                )
            );
        }

        
        wp_die();

    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


}