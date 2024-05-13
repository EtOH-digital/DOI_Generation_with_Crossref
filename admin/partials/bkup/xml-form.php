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
        <?php

        // $post_id = 42489;
        // $res = get_post_meta( $post_id, 'DOI' );
        // pr($res);

        // $res2 = get_post_meta( $post_id, 'doi_response' );
        // pr($res2);

        // pr($options);


        ?>

        <div class='wl_test_data'></div>

        <div class="info text-center" id="formTitle"></div>
        <div class="info text-center" id="successInfo"></div>

        <input type="hidden" name="doi_nonce" value="<?php echo $nonce ?>">
        <input type="hidden" name="post_id" value="" id="doi_post_id">

        <h3><?php _e('Series'); ?></h3>

        <label for="series_title"><?php _e('Title *'); ?></label>
        <input class="form-control" type="text" name="series_title" id="series_title" required>
        <label for="series_issn"><?php _e('ISSN *'); ?></label>
        <input class="form-control" type="text" name="series_issn" id="series_issn" value="<?php  echo $issn_val; ?>" required>
        <hr>

        <h3 class="mt-2"><?php _e('Proceeding level'); ?></h3>

        <label for="proceeding_title"><?php _e('Proceeding Title *'); ?></label>
        <input class="form-control" type="text" name="proceeding_title" id="proceeding_title" required>
        <label for="proceeding_publisher"><?php _e('Publisher *'); ?></label>
        <input class="form-control" type="text" name="proceeding_publisher" id="proceeding_publisher" required>
        <label for="proceeding_publisher_place"><?php _e('Publisher Place *'); ?></label>
        <input class="form-control" type="text" name="proceeding_publisher_place" id="proceeding_publisher_place" required>
        <label for="proceeding_publish_date"><?php _e('Publication Date *'); ?></label>
        <input class="form-control" type="date" name="proceeding_publish_date" id="proceeding_publish_date" required>
        <hr>

        <h3 class="mt-2"><?php _e('Conference Paper'); ?></h3>

        <label><b><?php _e('Contributors *'); ?></b></label>
        <br>
        <label for="conference_paper_contributors_fname"><?php _e('Name *'); ?></label>
        <input placeholder="Enter name" id="conference_paper_contributors_fname" type="text" name="conference_paper_contributors_fname" aria-label="First name" class="form-control">
        <label for="conference_paper_contributors_sname"><?php _e('Surname *'); ?></label>
        <input placeholder="Enter surname" id="conference_paper_contributors_sname" type="text" name="conference_paper_contributors_sname" aria-label="Last name" class="form-control">
        <label for="conference_paper_title"><?php _e('Titles *'); ?></label>
        <input class="form-control" type="text" name="conference_paper_title" id="conference_paper_title" required>
        <label for="conference_paper_doi_data"><?php _e('Doi Data *'); ?></label>
        <input class="form-control" type="text" name="conference_paper_doi_data" id="conference_paper_doi_data" placeholder="doi redirect url" required>
        <hr>
        <div class="add_more_author_div">
            <h3 class="text-primary">More Authors</h3>
        </div>

        <div style="float: right; margin-top: 10px; margin-bottom:10px;" class="add_more_author"> <span class="button button-primary"> + Add More</span> </div>
        <hr>

        <h3 class="mt-2"><?php _e('Series level'); ?></h3>

        <label for="series_doi_data"><?php _e('Doi Data'); ?></label>
        <input class="form-control" type="text" name="series_doi_data" id="series_doi_data" placeholder="doi redirect url">
        <label><b><?php _e('Contributors'); ?></b></label>
        <br>
        <label for="series_contributors_fname"><?php _e('Name'); ?></label>
        <input placeholder="Enter name" id="series_contributors_fname" type="text" name="series_contributors_fname" aria-label="First name" class="form-control">
        <label for="series_contributors_sname"><?php _e('Surname'); ?></label>
        <input placeholder="Enter surname" id="series_contributors_sname" type="text" name="series_contributors_sname" aria-label="Last name" class="form-control">
        <label for="series_number"><?php _e('Series Number'); ?></label>
        <input class="form-control" type="text" name="series_number" id="series_number">
        <label for="series_isbn"><?php _e('ISBN'); ?></label>
        <input class="form-control" type="text" name="series_isbn" id="series_isbn">
        <hr>

        <h3 class="mt-2"><?php _e('Conference level'); ?></h3>

        <label for="conference_volume"><?php _e('Volume'); ?></label>
        <input class="form-control" type="text" name="conference_volume" id="conference_volume">
        <label for="conference_isbn"><?php _e('ISBN'); ?></label>
        <input class="form-control" type="text" name="conference_isbn" id="conference_isbn">
        <label><b><?php _e('Conference Date'); ?></b></label><br>
        <label for="conference_date_start"><?php _e('Start Date'); ?></label>
        <input placeholder="Select start date" id="conference_date_start" type="date" name="conference_date_start" aria-label="First name" class="form-control">
        <label for="conference_date_end"><?php _e('End Date'); ?></label>
        <input placeholder="Select end date" id="conference_date_end" type="date" name="conference_date_end" aria-label="Last name" class="form-control">

        <label for="conference_location"><?php _e('Conference Location'); ?></label>
        <input class="form-control" type="text" name="conference_location" id="conference_location">
        <label for="conference_acronym"><?php _e('Conference Acronym'); ?></label>
        <input class="form-control" type="text" name="conference_acronym" id="conference_acronym">
        <label for="conference_theme"><?php _e('Conference Theme'); ?></label>
        <input class="form-control" type="text" name="conference_theme" id="conference_theme">
        <label for="conference_sponsor"><?php _e('Conference Sponsor'); ?></label>
        <input class="form-control" type="text" name="conference_sponsor" id="conference_sponsor">
        <label for="conference_number"><?php _e('Conference Number'); ?></label>
        <input class="form-control" type="text" name="conference_number" id="conference_number">
        <label for="proceedings_subject"><?php _e('Proceedings Subject'); ?></label>
        <input class="form-control" type="text" name="proceedings_subject" id="proceedings_subject">
        <hr>

        <h3 class="mt-2"><?php _e('Conference paper'); ?></h3>
        <label for="conference_publication_date"><?php _e('Conference Publication Date'); ?></label>
        <input class="form-control" type="date" name="conference_publication_date" id="conference_publication_date">
        <label for="conference_pages"><?php _e('Pages'); ?></label>
        <input class="form-control" type="text" name="conference_pages" id="conference_pages">
        <label for="conference_funding"><?php _e('Funding'); ?></label>
        <input class="form-control" type="text" name="conference_funding" id="conference_funding">
        <label for="conference_license"><?php _e('License'); ?></label>
        <input class="form-control" type="text" name="conference_license" id="conference_license">

        <label for="conference_crossmark_version"><?php _e('Crossmark Version'); ?></label>
        <input class="form-control" type="text" name="conference_crossmark_version" id="conference_crossmark_version">
        <br>
        <div id="viewXml"></div>
        <button id="submit-xml" type="button" class="button button-primary"><?php _e('Generate XML'); ?></button>
    </div>
</form>