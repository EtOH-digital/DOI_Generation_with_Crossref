<?php
if( ! class_exists('Doi_Creator_API') ){
    class Doi_Creator_API
    {
        function generate_doi()
        {
            if (isset($_POST['doi_nonce']) && $_POST['doi_nonce'] && wp_verify_nonce($_POST['doi_nonce'], 'submit_doi_nonce')) {

                // pr( $_POST );
                $post_id          = absint( $_POST['post_id'] );

                # Update Post Meta
                update_post_meta( $post_id, 'DOI', $_POST );

                $conference_title = isset($_POST['conference_title']) ? sanitize_text_field( $_POST['conference_title'] ) : '';
                $proceedings_title = isset($_POST['proceedings_title']) ? sanitize_text_field( $_POST['proceedings_title'] ) : '';
                $publisher = isset($_POST['publisher']) ? sanitize_text_field( $_POST['publisher'] ) : '';
                $publication_date = isset($_POST['publication_date']) ? sanitize_text_field( $_POST['publication_date'] ) : '';
                $conference_paper_title = isset($_POST['conference_paper_title']) ? sanitize_text_field( $_POST['conference_paper_title'] ) : '';
                $doi_redirect_to_url = esc_url($_POST['doi_redirect_to_url']);

                $authors_arr = array();
                if( $_POST['first_name'] && $_POST['last_name'] ){

                    $name_length = count( $_POST['first_name'] );

                    for ($i = 0; $i < $name_length; $i++) { 
                        
                        if( !empty( $_POST['first_name'][$i] ) && !empty( $_POST['last_name'][$i] )  ){
                            $authors_arr[$i]['first_name'] = sanitize_text_field( $_POST['first_name'][$i] );
                            $authors_arr[$i]['last_name'] = sanitize_text_field( $_POST['last_name'][$i] );
                        }

                    }

                }


                # call your func here
                

                $response = Admin_Post_Module::get_instance()->create_single_conference_xml_file( 
                        $post_id,
                        $conference_title,
                        $proceedings_title,
                        $publisher,
                        $publication_date,
                        $conference_paper_title,
                        $authors_arr,
                        $doi_redirect_to_url
                    );



                $response = Admin_Post_Module::get_instance()->if_auto_submit_then_submit_xml( $post_id );

                # end Call here 

            }else{
                $response = array('success' => false, 'msg' => 'Security nonce mismatched, reload and try again!', 'file_url' => '');
            }

            wp_send_json($response);
            wp_die();
            
        }


        function submit_doi()
        {
            if (isset($_POST['post_id']) && $_POST['post_id']) {

                $post_id = absint( $_POST['post_id'] );

                Admin_Post_Module::get_instance()->submit_doi( $post_id );
            }
        }

    }


}
