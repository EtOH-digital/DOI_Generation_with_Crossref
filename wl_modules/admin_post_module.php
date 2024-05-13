<?php

/**
 *
 * @package Admin_Post_Module
 * @author Arafat | Arafat.dml@gmail.com
 * @find me in fiver fiverr.com/web_lover
 * @date April/07/2024 01:34 Am
 * 
 */


class Admin_Post_Module {

    private static $instance = null;

    public $file_save_dir = '/uploads/doi-files/';

    private function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_post_module_script_loading' ) );
        add_action( 'wp_ajax_wl_admin_post_ajax', array( $this, 'wl_handle_admin_post_ajax' ) );
    }

    public function admin_post_module_script_loading(){
        wp_enqueue_script('admin_post_module_ajax', DOI_CREATOR_URL. 'wl_modules/assets/js/script.js', array('jquery'));
            wp_localize_script('admin_post_module_ajax', 'admin_ajax_wl', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('my-nonce')
            ));
    }

    function set_file_save_dir( $dir ){
        $this->file_save_dir = $dir;
    }

    function wl_get_authors($str){

        $authors = [];
        // Define the regex pattern to match author names
        $pattern = '/<span>(.*?)<\/span>/';
        // Perform the regex match
        if (preg_match_all($pattern, $str, $matches)) {
            // Loop through matches and extract author names
            foreach ($matches[1] as $match) {
                // Split the match by comma to get individual author names
                $author_names = explode(',', $match);
                // Trim each author name and add to the array
                foreach ($author_names as $author_name) {
                    $author_name = preg_replace('/<sup>.*?<\/sup>/', '', $author_name);

                    $authors[] = trim($author_name);
                }
            }

            $authors = array_filter( $authors );

        }

        return $authors;

    }

    /*==============================================================
    =            web_lover For Single Conference Module            =
    ==============================================================*/
    
    
    function conference_title( $post_id ){

        $parent_category_slug = 'ives-conference-series'; // Replace with the slug of the parent category

        // Get the categories of the post
        $post_categories = get_the_category( $post_id );

        // Find the parent category and its children
        $target_category = 'IVES Conference Series';
        foreach ( $post_categories as $category ) {
            if ( cat_is_ancestor_of( get_category_by_slug( $parent_category_slug )->term_id, $category ) ) {
                $target_category = $category->name;
                break;
            }
        }

        return $target_category;

    }

    function proceedings_title( $post_content ){

        preg_match('/<p>Issue:\s*(.*?)<\/p>/', $post_content, $matches);

        $proceedings_title = '';

        if( isset($matches[1]) ){
            $proceedings_title = $matches[1];
        }

        return $proceedings_title;

    }

    function get_contributer($post_content){

        $file_path = __DIR__.'/../my_html_content.txt';

        file_put_contents($file_path, $post_content);

        $authors_arr = array();

        // Remove <sup> tags and their contents
        $html_string_cleaned = preg_replace('/<sup>.*?<\/sup>/', '', $post_content);

        // Extract authors' names using regular expression
        preg_match_all('/<span>(.*?)<\/span>/', $html_string_cleaned, $matches);

        $authors_array = array();

        if(isset($matches[1])) {
            
            foreach($matches[1] as $authors) {
                // Split comma-separated names
                $individual_authors = explode(',', $authors);
                
                foreach ($individual_authors as $author) {
                    // Separate first name and last name based on white space
                    $names = explode(' ', trim($author));
                    $first_name = isset($names[0]) ? $names[0] : '';
                    $last_name = isset($names[1]) ? $names[1] : '';

                    if( $first_name && $last_name ){
                        $authors_arr[] = array('first_name' => $first_name, 'last_name' => $last_name);
                    }
                    

                }
            }
        
        }

        return $authors_arr;

    }

    function force_get_contributer($html_content) {
        
            $html_content = preg_replace('/<p\s+[^>]*>/', '<p>', $html_content);
            $html_content = preg_replace('/<span[^>]*>.*?<\/span>/', '', $html_content);


            // Regular expression pattern to match the first <p> tag with the specified style
            // $pattern = '/<p\s+style="font-weight:\s*400;\s*">(.*?)<\/p>/s';

            $pattern = '/<h2>Authors<\/h2>.*?<p>(.*?)<\/p>/s';


            // Match the first paragraph with the specified style using regular expression
            if (preg_match($pattern, $html_content, $matches)) {
                // Return the content of the first matching paragraph

                $author_text = $matches[1];

                $author_text = preg_replace('/<sup>.*?<\/sup>/', '', $author_text);

                // Remove HTML tags from author names
                $author_text = strip_tags($author_text);
                
                // Extract author names using comma as delimiter
                $authors = explode(',', $author_text);
                $authors = array_map('trim', $authors);

                // pr($authors);
                
                // Separate first name and last name based on space
                foreach ($authors as &$author) {

                    $author = trim($author, " \t\n\r\0\x0B\xC2\xA0");

                    $name_parts = explode(' ', $author, 2); // Split at the first space
                    $author = [
                        'first_name' => $name_parts[0],
                        'last_name' => isset($name_parts[1]) ? $name_parts[1] : ''
                    ];
                }
                
                return $authors;

            } else {
                // Return empty string if no matching paragraph found
                return [];
            }

    }


    function create_or_get_doi( $post_id ){

        $options = get_option('doi_creator_options', []);
        $doi_prefix = isset($options['doi_prefix']) ? esc_attr($options['doi_prefix']) : '';

        $post_doi_suffix = get_post_meta( $post_id, 'post_doi_suffix', true );
        if( !$post_doi_suffix ){
            $post_doi_suffix = wp_generate_password(8, false, false);
            update_post_meta( $post_id, 'post_doi_suffix', $post_doi_suffix );
        }

        $doi = $doi_prefix . '/' . $post_doi_suffix;

        # Update the Post Meta
        $post_doi = 'https://doi.org/'.$doi;
        update_post_meta($post_id, 'post_doi', $post_doi);

        return $doi;

    }

    function get_doi_redirect_to_url(){
        $doi_redirect_to = 'https://ives-openscience.eu/40134/';

        return $doi_redirect_to;
    }

    function create_or_get_timestamp( $update_new_timstamp = false ){

        if( $update_new_timstamp ){
            # Update a New Timestamo
            update_option('wl_doi_timestamp', $update_new_timstamp);
        }

        # Time stamp creat issue on submit so We need to Increment it 
        # and save it so that It can not create any issue
        $wl_timestamp = get_option('wl_doi_timestamp');

        if( !$wl_timestamp ){
            update_option('wl_doi_timestamp', '20240420075239011');
        }
        $wl_timestamp = get_option('wl_doi_timestamp');

        $wl_new_timestamp = $wl_timestamp + 1;

        # Update the Option
        update_option('wl_doi_timestamp', $wl_new_timestamp);

        return $wl_new_timestamp;

    }

    function create_doi_batch_id( $conference_paper_title ){

        $hash = md5($conference_paper_title);

        // Extract first 23 characters
        $alphanumeric_string = substr($hash, 0, 23);

        return $alphanumeric_string;

    }

    function get_xml_filename_for_doi( $post_id ){
        $filename = 'doi-' . $post_id . '.xml';

        return $filename;
    }


    function get_xml_file_save_path( $post_id ){

        $xml_filename = $this->get_xml_filename_for_doi( $post_id );

        # Keep it same Because Many File Manuaaly Using it 
        $xml_file_save_to = WP_CONTENT_DIR . $this->file_save_dir;

        # If the directory not exist create it
        if (!is_dir($xml_file_save_to)) {
            mkdir($xml_file_save_to);
        }

        return $xml_file_save_to.$xml_filename;
    }

    function get_xml_file_url( $post_id ){

        $xml_filename = $this->get_xml_filename_for_doi( $post_id );

        $xml_file_url =  WP_CONTENT_URL . $this->file_save_dir . $xml_filename;

        return $xml_file_url;
    }

    # If you pass a key it will return only that value
    function get_api_config_data( $key = false ){

        $options = get_option('doi_creator_options', []);

        $deposit_user_name  = isset($options['deposit_user_name']) ? esc_attr($options['deposit_user_name']) : '';
        $deposit_password   = isset($options['deposit_password']) ? esc_attr($options['deposit_password']) : '';
        $issn               = isset($options['issn']) ? esc_attr($options['issn']) : '';


        $login_id           = isset($options['login_id']) ? esc_attr($options['login_id']) : '';
        $api_password       = isset($options['api_password']) ? esc_attr($options['api_password']) : '';

        $deposit_endpoint   = isset($options['deposit_endpoint']) ? esc_attr($options['deposit_endpoint']) : '';
        $test_endpoint      = isset($options['test_endpoint']) ? esc_attr($options['test_endpoint']) : '';

        $email              = isset($options['email']) ? esc_attr($options['email']) : '';

        $api_env            = isset($options['api_env']) ? esc_attr($options['api_env']) : '';

        $auto_submit        = isset($options['auto_submit']) && $options['auto_submit'] == 'yes';
        $doi_prefix         = isset($options['doi_prefix']) ? esc_attr($options['doi_prefix']) : '';



        $config_arr =  array(
            'deposit_user_name' => $deposit_user_name,
            'deposit_password'  => deposit_password,
            'issn'              => $issn,
            'login_id'          => $login_id,
            'api_password'      => $api_password,
            'deposit_endpoint'  => $deposit_endpoint,
            'test_endpoint'     => $test_endpoint,
            'email'             => $email,
            'api_env'           => $api_env,
            'auto_submit'       => $auto_submit,
            'doi_prefix'        => $doi_prefix,

        );

        # if we want a specific single key val
        if( $config_arr[$key] ){

            # if key val found then return
            if( isset(  $config_arr[$key] ) ){
                return $config_arr[$key];
            }

            # We do not found key val return emtpy for that key
            return '';

        }

        # Otherwise Return the whole config arr
        return $config_arr;


    }

    # Save Doi Submit Log
    function doi_submit_log_save( $post_id, $response_body ){

        $date_time      = date("Y-m-d H:i:s");

        $options        = get_option('doi_creator_options', []);
        $api_env        = isset($options['api_env']) ? esc_attr($options['api_env']) : '';
        $generated_doi  = get_post_meta( $post_id, 'post_doi', true );

        $data_to_save = array (
            'date_time'     => $date_time,
            'api_mode'      => $api_env,
            'generated_doi' => $generated_doi,
            'response_body' => 'Your batch submission was successfully received',
        );

        update_post_meta($post_id, 'doi_submitted', 'yes');
        update_post_meta($post_id, 'doi_response', json_encode( $data_to_save ));

        return true;

    }

    function if_auto_submit_then_submit_xml( $post_id ){

        $options = get_option('doi_creator_options', []);

        $deposit_user_name  = isset($options['deposit_user_name']) ? esc_attr($options['deposit_user_name']) : '';
        $deposit_password   = isset($options['deposit_password']) ? esc_attr($options['deposit_password']) : '';
        $issn               = isset($options['issn']) ? esc_attr($options['issn']) : '';


        $login_id           = isset($options['login_id']) ? esc_attr($options['login_id']) : '';
        $api_password       = isset($options['api_password']) ? esc_attr($options['api_password']) : '';

        $deposit_endpoint   = isset($options['deposit_endpoint']) ? esc_attr($options['deposit_endpoint']) : '';
        $test_endpoint      = isset($options['test_endpoint']) ? esc_attr($options['test_endpoint']) : '';

        $email              = isset($options['email']) ? esc_attr($options['email']) : '';

        $api_env            = isset($options['api_env']) ? esc_attr($options['api_env']) : '';

        $auto_submit        = isset($options['auto_submit']) && $options['auto_submit'] == 'yes';
        $doi_prefix         = isset($options['doi_prefix']) ? esc_attr($options['doi_prefix']) : '';

        if( $auto_submit ){

            /*================================================================
            =            Generating file submit data by web_lover            =
            ================================================================*/

            if (isset($api_env) && $api_env == 'test') {
                $url = $test_endpoint . '?operation=doMDUpload&login_id=' . $login_id . '&login_passwd=' . $api_password;
            } else {

                $login_id = $deposit_user_name;
                $api_password = $deposit_password;

                $url = $deposit_endpoint . '?operation=doMDUpload&login_id=' . $login_id . '&login_passwd=' . $api_password;
            }

            $xml_filename = $this->get_xml_filename_for_doi( $post_id );
            
            $filePath =  $this->get_xml_file_save_path( $post_id );
            $fileUrl = $this->get_xml_file_url( $post_id );

            //sent request
            $boundary = wp_generate_password(24);
            $headers = array(
                'Accept' => 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*',
                'Accept-Language' => 'en-us',
                'Content-Type' => 'multipart/form-data; application/json; text/xml; application/xml; boundary=' . $boundary,
                'Content-Disposition' => 'form-data; name="fname"; filename=' . $xml_filename
            );


            $payload = '--' . $boundary;
            $payload .= "\r\n";
            $payload .= 'Content-Disposition: form-data; name="' . $xml_filename .
                '"; filename="' . basename($filePath) . '"' . "\r\n";
            $payload .= "\r\n";
            $payload .= file_get_contents($filePath);
            $payload .= "\r\n";
            
            $payload .= '--' . $boundary . '--';
            
            /*=====  End of Generating file submit data by web_lover  ======*/

            $response = wp_remote_post(
                $url,
                array(
                    'method'    => 'POST',
                    'headers'   => $headers,
                    'body'      => $payload,
                    'filename'  => $xml_filename
                )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $response = array('success' => false, 'msg' => $error_message, 'file_url' => '');
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                if ($response_code === 200) {
                    $response_body = wp_remote_retrieve_body($response);
                    /*if( strpos($response_body,'SUCCESS') !== -1 ){

                    }*/

                    # Save Log File
                    $this->doi_submit_log_save( $post_id, $response_body );

                    $response = array('success' => true, 'msg' => 'DOI file has submitted successfully!', 'code' => $response_code, 'body' => $response_body, 'file_url' => $fileUrl);
                } else {
                    $response = array('success' => false, 'msg' => 'Something went to wrong!', 'file_url' => '');
                }
            }

            return $response;

        }

        # Genric Response
        return array(
            'success'   => false,
            'msg'       => 'XML File Generated Successfully ! Auto Submit Is disabled by plugin setting So it is not Auto Submitted',
            'file_url'  => ''
        );


    }


    function submit_doi( $post_id )
    {
        if (isset($post_id) && $post_id) {
            //options data
            $options = get_option('doi_creator_options', []);

            // add by web_lover
            $deposit_user_name = isset($options['deposit_user_name']) ? esc_attr($options['deposit_user_name']) : '';
            $deposit_password = isset($options['deposit_password']) ? esc_attr($options['deposit_password']) : '';
            $issn = isset($options['issn']) ? esc_attr($options['issn']) : '';
            // end add by web_lover
            
            $login_id = isset($options['login_id']) ? esc_attr($options['login_id']) : '';
            $api_password = isset($options['api_password']) ? esc_attr($options['api_password']) : '';
            $deposit_endpoint = isset($options['deposit_endpoint']) ? esc_attr($options['deposit_endpoint']) : '';
            $test_endpoint = isset($options['test_endpoint']) ? esc_attr($options['test_endpoint']) : '';
            $api_env = isset($options['api_env']) ? esc_attr($options['api_env']) : '';

            $xml_filename = $this->get_xml_filename_for_doi( $post_id );

            $filePath = $this->get_xml_file_save_path( $post_id );

            //sent request
            $boundary = wp_generate_password(24);
            $headers = array(
                'Accept' => 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*',
                'Accept-Language' => 'en-us',
                'Content-Type' => 'multipart/form-data; application/json; text/xml; application/xml; boundary=' . $boundary,
                'Content-Disposition' => 'form-data; name="fname"; filename=' . $xml_filename
            );

            if ($filePath) {
                $payload = '--' . $boundary;
                $payload .= "\r\n";
                $payload .= 'Content-Disposition: form-data; name="' . $xml_filename .
                    '"; filename="' . basename($filePath) . '"' . "\r\n";
                $payload .= "\r\n";
                $payload .= file_get_contents($filePath);
                $payload .= "\r\n";
            }
            $payload .= '--' . $boundary . '--';

            if (isset($api_env) && $api_env == 'test') {
                $url = $test_endpoint . '?operation=doMDUpload&login_id=' . $login_id . '&login_passwd=' . $api_password;
            } else {

                $login_id = $deposit_user_name;
                $api_password = $deposit_password;


                $url = $deposit_endpoint . '?operation=doMDUpload&login_id=' . $login_id . '&login_passwd=' . $api_password;
            }
            $response = wp_remote_post(
                $url,
                array(
                    'method' => 'POST',
                    'headers' => $headers,
                    'body' => $payload,
                    'filename' => $xml_filename
                )
            );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                wp_send_json_error($error_message);
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                if ($response_code === 200) {
                    $response_body = wp_remote_retrieve_body($response);

                    # Save Log File
                    $this->doi_submit_log_save( $post_id, $response_body );

                    $response = array('success' => true, 'msg' => 'DOI submit successfully!', 'code' => $response_code, 'body' => $response_body);
                } else {
                    $response = array('success' => false, 'msg' => 'Something went to wrong!');
                }
            }
        } else {
            $response = array('success' => false, 'msg' => 'post id not found . Not able to submit file');
        }
        wp_send_json($response);
        wp_die();
    }


    function create_single_conference_xml_file( 
        $post_id,
        $conference_title,
        $proceedings_title,
        $publisher,
        $publication_date,
        $conference_paper_title,
        $authors_arr,
        $doi_redirect_to_url
    )
    {

        # Variable goes Here

        $publication_date_year          = date('Y', strtotime($publication_date));
        $publication_date_month         = date('m', strtotime($publication_date));
        $publication_date_day           = date('d', strtotime($publication_date));

        $create_or_get_timestamp        = $this->create_or_get_timestamp();
        $create_doi_batch_id            = $this->create_doi_batch_id( $conference_paper_title );
        $create_or_get_doi              = $this->create_or_get_doi( $post_id );

        $get_xml_filename_for_doi       = $this->get_xml_filename_for_doi( $post_id );
        $get_xml_file_path              = $this->get_xml_file_save_path( $post_id );

        $options = get_option('doi_creator_options', []);

        $deposit_user_name  = isset($options['deposit_user_name']) ? esc_attr($options['deposit_user_name']) : '';
        $email              = isset($options['email']) ? esc_attr($options['email']) : '';

        # End variable

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
        <doi_batch version="4.4.2" xmlns="http://www.crossref.org/schema/4.4.2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:jats="http://www.ncbi.nlm.nih.gov/JATS1" xsi:schemaLocation="http://www.crossref.org/schema/4.4.2 http://www.crossref.org/schema/deposit/crossref4.4.2.xsd"></doi_batch>');

        // Add head element and its children
        $head = $xml->addChild('head');
        $head->addChild('doi_batch_id', $create_doi_batch_id);
        $head->addChild('timestamp', $create_or_get_timestamp);
        $depositor = $head->addChild('depositor');
        $depositor->addChild('depositor_name', $deposit_user_name);
        $depositor->addChild('email_address', $email);
        $head->addChild('registrant', 'WEB-FORM');

        // Add body element and its children
        $body = $xml->addChild('body');
        $conference = $body->addChild('conference');
        $event_metadata = $conference->addChild('event_metadata');
        $event_metadata->addChild('conference_name', $conference_title);
        $event_metadata->addChild('conference_acronym', 'ives');
        $event_metadata->addChild('conference_date', 'june/23/2019');

        $proceedings_metadata = $conference->addChild('proceedings_metadata');
        $proceedings_metadata->addChild('proceedings_title', $proceedings_title);
        $publisher_obj = $proceedings_metadata->addChild('publisher');
        $publisher_obj->addChild('publisher_name', $publisher);
        $publication_date_print = $proceedings_metadata->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
        $publication_date_print->addAttribute('media_type', 'print');
        $publication_date_print->addChild('month', $publication_date_month);
        $publication_date_print->addChild('day', $publication_date_day);
        $publication_date_print->addChild('year', $publication_date_year);
        $publication_date_online = $proceedings_metadata->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
        $publication_date_online->addAttribute('media_type', 'online');
        $publication_date_online->addChild('month', $publication_date_month);
        $publication_date_online->addChild('day', $publication_date_day);
        $publication_date_online->addChild('year', $publication_date_year);
        $proceedings_metadata->addChild('noisbn')->addAttribute('reason', 'simple_series');


        // Add conference_paper element and its children
        $conference_paper = $conference->addChild('conference_paper');

        # Loop data
        // additional

        if( $authors_arr ){
            $contributors = $conference_paper->addChild('contributors');
            foreach( $authors_arr as $k => $v ){

                $first_name = $v['first_name'];
                $last_name = $v['last_name'];
                if($k == 0){
                    
                    $person_name = $contributors->addChild('person_name');
                    $person_name->addChild('given_name', $first_name);
                    $person_name->addChild('surname', $last_name);
                    $person_name->addAttribute('sequence', 'first');
                    $person_name->addAttribute('contributor_role', 'author');
                }else{

                    $dynamic_var_name = 'person_name_'.$key;

                    # Using dynamic variable name
                    $$dynamic_var_name = $contributors->addChild('person_name');
                    $$dynamic_var_name->addChild('given_name', $first_name);
                    $$dynamic_var_name->addChild('surname', $last_name);
                    $$dynamic_var_name->addAttribute('sequence', 'additional');
                    $$dynamic_var_name->addAttribute('contributor_role', 'author');
                }

            }

        }


        # End Author Loop Data

        $titles = $conference_paper->addChild('titles');
        $titles->addChild('title', $conference_paper_title);

        $publication_date_print = $conference_paper->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
        $publication_date_print->addAttribute('media_type', 'print');
        $publication_date_print->addChild('month', $publication_date_month);
        $publication_date_print->addChild('day', $publication_date_day);
        $publication_date_print->addChild('year', $publication_date_year);
        $publication_date_online = $conference_paper->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
        $publication_date_online->addAttribute('media_type', 'online');
        $publication_date_online->addChild('month', $publication_date_month);
        $publication_date_online->addChild('day', $publication_date_day);
        $publication_date_online->addChild('year', $publication_date_year);
        $doi_data = $conference_paper->addChild('doi_data');
        $doi_data->addChild('doi', $create_or_get_doi);
        $doi_data->addChild('resource', $doi_redirect_to_url);

        // Output the XML

        $xmlFile = $xml->asXML();

        # Save Xml File Path
        $res = file_put_contents($get_xml_file_path, $xmlFile);

        if($res){
            return true;
        }

        return false;

    }


    
    /*=====  End of web_lover For Single Conference Module  ======*/
    


    public function wl_handle_admin_post_ajax(){
        
        // Nonce verification
        if ( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'my-nonce' ) ) {
            wp_send_json_error('Nonce verification failed');
        }

        $post_id = isset($_POST['data']['post_id']) ? absint( $_POST['data']['post_id'] ) : '';

        // $post_id = 42475;

        $has_post_meta_doi = get_post_meta( $post_id, 'DOI' );

        $post_permalink = get_permalink( $post_id );
        // pr($post_permalink);

        $post = get_post($post_id);

        # Get Authors
        $authors_arr = $this->get_contributer( $post->post_content );
        if( !$authors_arr ){
            $authors_arr = $this->force_get_contributer( $post->post_content );
        }
        

        # End Getting Authors

        /*=================================================================
        =            For Single Conference Module by web_lover            =
        =================================================================*/
        
        $conference_title               = $this->conference_title($post_id);
        $proceedings_title              = $this->proceedings_title( $post->post_content );
        $publisher                      = 'International Viticulture and Enology Society';
        $publication_date               = date('Y-m-d', strtotime($post->post_date));
        $publication_date_year          = date('Y', strtotime($post->post_date));
        $publication_date_month         = date('m', strtotime($post->post_date));
        $publication_date_day           = date('d', strtotime($post->post_date));
        $conference_paper_title         = $post->post_title;
        $contributer_arr                = $authors_arr;
        $create_or_get_timestamp        = $this->create_or_get_timestamp();
        $create_doi_batch_id            = $this->create_doi_batch_id( $conference_paper_title );
        $create_or_get_doi              = $this->create_or_get_doi( $post_id );
        $get_doi_redirect_to_url        = $this->get_doi_redirect_to_url();

        $get_xml_filename_for_doi       = $this->get_xml_filename_for_doi( $post_id );


        
        /*=====  End of For Single Conference Module by web_lover  ======*/
        
        $res = array(
            'conference_title' => $conference_title,
            'proceedings_title' => $proceedings_title,
            'publisher' => $publisher,
            'publication_date' => $publication_date,
            'conference_paper_title' => $conference_paper_title,
            'authors' => $contributer_arr,
            'post_permalink' => $get_doi_redirect_to_url,
        );

        // pr( $has_post_meta_doi ); die('checkign post_mata_doi');

        if( isset($has_post_meta_doi[0]) && $has_post_meta_doi[0] ){

            $res = array_merge( $res, $has_post_meta_doi[0] );

            wp_send_json_success(
                array(
                    'has_data' => 1,
                    'res' => $res,
                )
            );
        }else{
            wp_send_json_success(
                array(
                    'has_data' => 2,
                    'res' => $res,
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