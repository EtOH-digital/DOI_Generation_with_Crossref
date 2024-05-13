<?php
if( ! class_exists('Doi_Creator_API') ){
    class Doi_Creator_API
    {
        function generate_doi()
        {
            if (isset($_POST['doi_nonce']) && $_POST['doi_nonce'] && wp_verify_nonce($_POST['doi_nonce'], 'submit_doi_nonce')) {


                if (isset($_POST['post_id']) ) {

                    // pr($_POST); 
                    $publish_date = new DateTime($_POST['proceeding_publish_date']);

                    $publish_year = $publish_date->format('Y');
                    $publish_month = $publish_date->format('m');
                    $publish_day = $publish_date->format('d');

                    $online_date = new DateTime($_POST['proceeding_publish_date_online']);

                    $online_year = $online_date->format('Y');
                    $online_month = $online_date->format('m');
                    $online_day = $online_date->format('d');

                    $post_id = $_POST['post_id'];

                    $series_title = isset($_POST['series_title']) ? esc_attr($_POST['series_title']) : '';
                    $proceeding_publish_date = isset($_POST['proceeding_publish_date']) ? esc_attr($_POST['proceeding_publish_date']) : '';
                    $proceeding_publish_date_online = isset($_POST['proceeding_publish_date_online']) ? esc_attr($_POST['proceeding_publish_date_online']) : '';
                    
                    $conference_paper_contributors_fname = isset($_POST['conference_paper_contributors_fname']) ? esc_attr($_POST['conference_paper_contributors_fname']) : '';
                    $conference_paper_contributors_sname = isset($_POST['conference_paper_contributors_sname']) ? esc_attr($_POST['conference_paper_contributors_sname']) : '';

                    $conference_paper_doi_data = isset($_POST['conference_paper_doi_data']) ? esc_attr($_POST['conference_paper_doi_data']) : '';

                    //options data
                    update_post_meta($_POST['post_id'], 'DOI', $_POST);

                    $options = get_option('doi_creator_options', []);

                    // ----------------------------------------------
                    // add by web_lover
                    // ----------------------------------------------

                    // pr($_POST); die('die');


                    $deposit_user_name = isset($options['deposit_user_name']) ? esc_attr($options['deposit_user_name']) : '';
                    $deposit_password = isset($options['deposit_password']) ? esc_attr($options['deposit_password']) : '';
                    $issn = isset($options['issn']) ? esc_attr($options['issn']) : '';
                    // end add by web_lover

                    $login_id = isset($options['login_id']) ? esc_attr($options['login_id']) : '';
                    $api_password = isset($options['api_password']) ? esc_attr($options['api_password']) : '';
                    $deposit_endpoint = isset($options['deposit_endpoint']) ? esc_attr($options['deposit_endpoint']) : '';
                    $test_endpoint = isset($options['test_endpoint']) ? esc_attr($options['test_endpoint']) : '';
                    $email = isset($options['email']) ? esc_attr($options['email']) : '';
                    $api_env = isset($options['api_env']) ? esc_attr($options['api_env']) : '';
                    $auto_submit = isset($options['auto_submit']) && $options['auto_submit'] == 'yes';
                    $doi_prefix = isset($options['doi_prefix']) ? esc_attr($options['doi_prefix']) : '';

                    // add by web_lover

                    # First Check if we have The Doi Already
                    $doi_1 = get_post_meta( $post_id, 'post_doi_part', true );
                    if( !$doi_1 ){
                        # Now Create a new Post Doi
                        $doi_suffix_1 = wp_generate_password(8, false, false);
                        $doi_1 = $doi_prefix . '/' . $doi_suffix_1;

                        # Update the post meta
                        update_post_meta( $post_id, 'post_doi_part', $doi_1 );
                    }
                    

                    # Update the Post Meta
                    $post_doi = 'https://doi.org/'.$doi_1;
                    update_post_meta($_POST['post_id'], 'post_doi', $post_doi);

                    // ----------------------------------------------
                    // end add by web_lover
                    // ----------------------------------------------


                    #######################################################
                    # Web_Lover Generated XML
                    #######################################################

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
                    

                    $hash = md5($series_title);
                    // Extract first 23 characters
                    $alphanumeric_string = substr($hash, 0, 23);

                    $post_excerpt = get_post_field('post_excerpt', $post_id);

                    // Create a new SimpleXMLElement object
                    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
                    <doi_batch version="4.4.2" xmlns="http://www.crossref.org/schema/4.4.2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:jats="http://www.ncbi.nlm.nih.gov/JATS1" xsi:schemaLocation="http://www.crossref.org/schema/4.4.2 http://www.crossref.org/schema/deposit/crossref4.4.2.xsd"></doi_batch>');

                    // Add head element and its children
                    $head = $xml->addChild('head');
                    $head->addChild('doi_batch_id', $alphanumeric_string);
                    $head->addChild('timestamp', $wl_new_timestamp);
                    $depositor = $head->addChild('depositor');
                    $depositor->addChild('depositor_name', $deposit_user_name);
                    $depositor->addChild('email_address', $email);
                    $head->addChild('registrant', 'WEB-FORM');

                    // Add body element and its children
                    $body = $xml->addChild('body');
                    $conference = $body->addChild('conference');
                    $event_metadata = $conference->addChild('event_metadata');
                    $event_metadata->addChild('conference_name', 'IVES Conference Series vine  wine');
                    $event_metadata->addChild('conference_acronym', 'ives');
                    $event_metadata->addChild('conference_date', 'june/23/2019');

                    $proceedings_metadata = $conference->addChild('proceedings_metadata');
                    $proceedings_metadata->addChild('proceedings_title', 'IVES Conference Series vine  wine');
                    $publisher = $proceedings_metadata->addChild('publisher');
                    $publisher->addChild('publisher_name', 'IVES Conference Series vine  wine');
                    $publication_date_print = $proceedings_metadata->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
                    $publication_date_print->addAttribute('media_type', 'print');
                    $publication_date_print->addChild('month', '06');
                    $publication_date_print->addChild('day', '23');
                    $publication_date_print->addChild('year', '2019');
                    $publication_date_online = $proceedings_metadata->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
                    $publication_date_online->addAttribute('media_type', 'online');
                    $publication_date_online->addChild('month', '06');
                    $publication_date_online->addChild('day', '28');
                    $publication_date_online->addChild('year', '2019');
                    $proceedings_metadata->addChild('noisbn')->addAttribute('reason', 'archive_volume');
                    $doi_data = $proceedings_metadata->addChild('doi_data');
                    $doi_data->addChild('doi', '10.58233/ivescsvw');
                    $doi_data->addChild('resource', 'https://wp.simplerscript.com/about_ives/');

                    // Add conference_paper element and its children
                    $conference_paper = $conference->addChild('conference_paper');
                    $contributors = $conference_paper->addChild('contributors');
                    $person_name = $contributors->addChild('person_name');
                    $person_name->addChild('given_name', $conference_paper_contributors_fname);
                    $person_name->addChild('surname', $conference_paper_contributors_sname);
                    $person_name->addAttribute('sequence', 'first');
                    $person_name->addAttribute('contributor_role', 'author');
                    $titles = $conference_paper->addChild('titles');
                    $titles->addChild('title', $series_title);
                    // $abstract = $conference_paper->addChild('abstract', $post_excerpt);
                    // $abstract->addAttribute('xml:xml:lang', 'en');
                    $publication_date_print = $conference_paper->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
                    $publication_date_print->addAttribute('media_type', 'print');
                    $publication_date_print->addChild('month', $publish_month);
                    $publication_date_print->addChild('day', $publish_day);
                    $publication_date_print->addChild('year', $publish_year);
                    $publication_date_online = $conference_paper->addChild('publication_date', null, 'http://www.crossref.org/schema/4.4.2');
                    $publication_date_online->addAttribute('media_type', 'online');
                    $publication_date_online->addChild('month', $online_month);
                    $publication_date_online->addChild('day', $online_day);
                    $publication_date_online->addChild('year', $online_year);
                    $doi_data = $conference_paper->addChild('doi_data');
                    $doi_data->addChild('doi', $doi_1);
                    $doi_data->addChild('resource', $conference_paper_doi_data);

                    // Output the XML

                    $xmlFile = $xml->asXML();

                    #######################################################
                    # End Web_lover Generateed XML
                    ######################################################

                    $xml_filename = 'doi-' . $_POST['post_id'] . '.xml';
                    // Header('Content-type: application/vnd.crossref+xml');

                    $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir);
                    }
                    if (!file_exists($upload_dir . $xml_filename)) {
                        file_put_contents($upload_dir . $xml_filename, $xmlFile);
                    } else {
                        file_put_contents($upload_dir . $xml_filename, $xmlFile);

                    }
                    $filePath = $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/' . $xml_filename;
                    $fileUrl = $upload_dir = WP_CONTENT_URL . '/uploads/doi-files/' . $xml_filename;

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
                    if ( $auto_submit ) {
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
                                update_post_meta($_POST['post_id'], 'doi_submitted', 'yes');
                                update_post_meta($_POST['post_id'], 'doi_response', $response_body);

                                $response = array('success' => true, 'msg' => 'DOI file has submitted successfully!', 'code' => $response_code, 'body' => $response_body, 'file_url' => $fileUrl);
                            } else {
                                $response = array('success' => false, 'msg' => 'Something went to wrong!', 'file_url' => '');
                            }
                        }
                    } else {
                        update_post_meta($_POST['post_id'], 'doi_submitted', 'no');
                        $response = array('success' => true, 'msg' => 'DOI create successfully!', 'file_url' => $fileUrl);
                    }

                } else {
                    $response = array('success' => false, 'msg' => 'Required fields are missing!', 'file_url' => '');
                }
            } else {
                $response = array('success' => false, 'msg' => 'Security nonce mismatched, reload and try again!', 'file_url' => '');
            }
            wp_send_json($response);
            wp_die();
        }

        function submit_doi()
        {
            if (isset($_POST['post_id']) && $_POST['post_id']) {
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

                $xml_filename = 'doi-' . $_POST['post_id'] . '.xml';
                $filePath = $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/' . $xml_filename;

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
                        update_post_meta($_POST['post_id'], 'doi_submitted', 'yes');
                        update_post_meta($_POST['post_id'], 'doi_response', $response_body);
                        $response = array('success' => true, 'msg' => 'DOI submit successfully!', 'code' => $response_code, 'body' => $response_body);
                    } else {
                        $response = array('success' => false, 'msg' => 'Something went to wrong!');
                    }
                }
            } else {
                $response = array('success' => false, 'msg' => 'Something went to wrong!');
            }
            wp_send_json($response);
            wp_die();
        }
    }

}
