<?php 

$nonce = wp_create_nonce('submit_doi_nonce');

# add by web_lover

$options = get_option('doi_creator_options', []);

// pr($options);

$issn_val = NULL;
if( isset( $options['issn'] ) ){
    $issn_val = $options['issn'];
}

# end add by web_lover

?>
<form id="submit-doi-form" style="display: none;">
    <div id="myForm">
        <div class='wl_test_data'></div>

        <div class="info text-center" id="formTitle"></div>
        <div class="info text-center" id="successInfo"></div>

        <input type="hidden" name="doi_nonce" value="<?php echo $nonce ?>">
        <input type="hidden" name="post_id" value="" id="doi_post_id">

        <h3><?php _e('Series'); ?></h3>

        <label for="series_title"><?php _e('Title *'); ?></label>
        <input class="form-control" type="text" name="series_title" id="series_title" required>
<!--         <label for="series_issn"><?php _e('ISSN *'); ?></label>
        <input class="form-control" type="text" name="series_issn" id="series_issn" value="<?php  echo $issn_val; ?>" required>
 -->        <hr>

        <h3 class="mt-2"><?php _e('Proceeding level'); ?></h3>

<!--         <label for="proceeding_title"><?php _e('Proceeding Title *'); ?></label>
        <input class="form-control" type="text" name="proceeding_title" id="proceeding_title" required> -->
        

        <label for="proceeding_publish_date"><?php _e('Publication Date *'); ?></label>
        <input class="form-control" type="date" name="proceeding_publish_date" id="proceeding_publish_date" required>

        <label for="proceeding_publish_date_online"><?php _e('Publication Date Online *'); ?></label>
        <input class="form-control" type="date" name="proceeding_publish_date_online" id="proceeding_publish_date_online" required>


        <hr>

        <h3 class="mt-2"><?php _e('Conference Paper'); ?></h3>

        <label><b><?php _e('Contributors *'); ?></b></label>
        <br>
        <label for="conference_paper_contributors_fname"><?php _e('Name *'); ?></label>
        <input placeholder="Enter name" id="conference_paper_contributors_fname" type="text" name="conference_paper_contributors_fname" aria-label="First name" class="form-control">
        <label for="conference_paper_contributors_sname"><?php _e('Surname *'); ?></label>
        <input placeholder="Enter surname" id="conference_paper_contributors_sname" type="text" name="conference_paper_contributors_sname" aria-label="Last name" class="form-control">
       
        <label for="conference_paper_doi_data"><?php _e('Doi Data *'); ?></label>
        <input class="form-control" type="text" name="conference_paper_doi_data" id="conference_paper_doi_data" placeholder="doi redirect url" required>
        <hr>
       <!--  <div class="add_more_author_div">
            <h3 class="text-primary">More Authors</h3>
        </div> -->

        <!-- <div style="float: right; margin-top: 10px; margin-bottom:10px;" class="add_more_author"> <span class="button button-primary"> + Add More</span> </div> -->
        <hr>

        <br>
        <div id="viewXml"></div>
        <button id="submit-xml" type="button" class="button button-primary"><?php _e('Generate XML'); ?></button>
    </div>
</form>