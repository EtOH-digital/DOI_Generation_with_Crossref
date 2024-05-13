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

        <label for="conference_title"><?php _e('Conference Title *'); ?></label>
        <input class="form-control" type="text" name="conference_title" id="conference_title" required>
        <hr>

        <label for="proceedings_title"><?php _e('Proceedings Title *'); ?></label>
        <input class="form-control" type="text" name="proceedings_title" id="proceedings_title" required>
        <hr>

        <label for="publisher"><?php _e('Publisher *'); ?></label>
        <input class="form-control" type="text" name="publisher" id="publisher" value="International Viticulture and Enology Society" required>
        <hr>

        <label for="publication_date"><?php _e('Publication Date *'); ?></label>
        <input class="form-control" type="date" name="publication_date" id="publication_date" required>
        <hr>

        <label for="conference_paper_title"><?php _e('Conference Paper Title *'); ?></label>
        <input class="form-control" type="text" name="conference_paper_title" id="conference_paper_title" required>
        <hr>

        <div class="add_more_author_div">
            <h3 class="text-primary">More Authors</h3>
        </div>

        <div style="float: right; margin-top: 10px; margin-bottom:10px;" class="add_more_author"> <span class="button button-primary"> + Add More</span> </div>
        <hr>

        <label for="doi_redirect_to_url"><?php _e('Doi Redirect to Url *'); ?></label>
        <input class="form-control" type="text" name="doi_redirect_to_url" id="doi_redirect_to_url" placeholder="doi redirect url" value="https://ives-openscience.eu/40134/"  required>
        <hr>

        <br>
        <div id="viewXml"></div>
        <button id="submit-xml" type="button" class="button button-primary"><?php _e('Generate XML'); ?></button>
    </div>
</form>