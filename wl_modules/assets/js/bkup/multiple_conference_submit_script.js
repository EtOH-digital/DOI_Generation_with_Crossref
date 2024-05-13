jQuery(document).ready(function($){
	// alert('HELLO WORLD');

	/*------------------------------------
	add more author
	------------------------------------*/

		$('.add_more_author').click(function(e) {
		    e.preventDefault();
		    // alert('add more');

		    $(".add_more_author_div").append( '<div class="remove"> <label for="conference_paper_contributors_fname_more">Name</label><input placeholder="Enter name" type="text" name="conference_paper_contributors_fname_more[]" aria-label="First name" class="form-control"><label for="conference_paper_contributors_sname_more">Surname</label><input placeholder="Enter surname" type="text" name="conference_paper_contributors_sname_more[]" aria-label="Last name" class="form-control">'+ '<button class="button remove_more_author">Remove</button> </div>' );
		});
		
		//Remove The current Div
		$('.add_more_author_div').on('click','.remove_more_author',function() {
			$(this).closest(".remove").remove();
	   });

	/*------------------------------------
	end add more author
	------------------------------------*/


	$('.createDOI').on('click', function(){
		
		// add by web_lover
		var title =  $(this).data('posttitle');
		$('#series_title').val(title);
		// end add by web_lover

		var post_id = $(this).data('postid');
		// alert('u hit btn '+post_id);
		// wl_test_data

		// wl_admin_post_ajax

		// when we click to a post first empty previous value
		// $('#series_issn').val('');
		$('#proceeding_title').val('');
		$('#proceeding_publisher').val('');
		$('#proceeding_publisher_place').val('');
		$('#proceeding_publish_date').val('');
		$('#conference_paper_contributors_fname').val('');
		$('#conference_paper_contributors_sname').val('');
		$('#conference_paper_title').val('');
		$('#conference_paper_doi_data').val('');
		$('#series_doi_data').val('');
		$('#series_contributors_fname').val('');
		$('#series_contributors_sname').val('');
		$('#series_number').val('');
		$('#conference_volume').val('');
		$('#conference_isbn').val('');
		$('#conference_date_start').val('');
		$('#conference_date_end').val('');
		$('#conference_location').val('');
		$('#conference_acronym').val('');
		$('#conference_theme').val('');
		$('#conference_sponsor').val('');
		$('#conference_number').val('');
		$('#proceedings_subject').val('');
		$('#conference_publication_date').val('');
		$('#conference_pages').val('');
		$('#conference_funding').val('');
		$('#conference_license').val('');
		$('#conference_crossmark_version').val('');

		$('#conference_paper_doi_data').val('');
		$('#proceeding_publish_date_online').val('');
		// end empty previous value



		$.ajax({
            url: admin_ajax_wl.ajaxurl,
            type: 'POST',
            data: {
                action: 'wl_admin_post_ajax',
                nonce: admin_ajax_wl.nonce,
                data: {
                    post_id: post_id,
                }
            },
            success: function(response) {
                console.log(response);

                /*newly added*/
                if( response.success && response.data.has_data == 2 ){
                	$('#conference_paper_doi_data').val(response.data.res.post_permalink);
                }
                

                /*newly added*/

                else if( response.success && response.data.has_data == 1 ){
                	$('#series_title').val(response.data.res.series_title);
                	// $('#series_issn').val(response.data.res.series_issn);
                	$('#proceeding_title').val(response.data.res.proceeding_title);
                	$('#proceeding_publisher').val(response.data.res.proceeding_publisher);
                	$('#proceeding_publisher_place').val(response.data.res.proceeding_publisher_place);
                	$('#proceeding_publish_date').val(response.data.res.proceeding_publish_date);
                	$('#proceeding_publish_date_online').val(response.data.res.proceeding_publish_date_online);

                	$('#conference_paper_contributors_fname').val(response.data.res.conference_paper_contributors_fname);
                	$('#conference_paper_contributors_sname').val(response.data.res.conference_paper_contributors_sname);
                	$('#conference_paper_title').val(response.data.res.conference_paper_title);
                	$('#conference_paper_doi_data').val(response.data.res.conference_paper_doi_data);
                	$('#series_doi_data').val(response.data.res.series_doi_data);
                	$('#series_contributors_fname').val(response.data.res.series_contributors_fname);
                	$('#series_contributors_sname').val(response.data.res.series_contributors_sname);
                	$('#series_number').val(response.data.res.series_number);
                	$('#conference_volume').val(response.data.res.conference_volume);
                	$('#conference_isbn').val(response.data.res.conference_isbn);
                	$('#conference_date_start').val(response.data.res.conference_date_start);
                	$('#conference_date_end').val(response.data.res.conference_date_end);
                	$('#conference_location').val(response.data.res.conference_location);
                	$('#conference_acronym').val(response.data.res.conference_acronym);
                	$('#conference_theme').val(response.data.res.conference_theme);
                	$('#conference_sponsor').val(response.data.res.conference_sponsor);
                	$('#conference_number').val(response.data.res.conference_number);
                	$('#proceedings_subject').val(response.data.res.proceedings_subject);
                	$('#conference_publication_date').val(response.data.res.conference_publication_date);
                	$('#conference_pages').val(response.data.res.conference_pages);
                	$('#conference_funding').val(response.data.res.conference_funding);
                	$('#conference_license').val(response.data.res.conference_license);
                	$('#conference_crossmark_version').val(response.data.res.conference_crossmark_version);

                	$('#conference_paper_doi_data').val(response.data.res.post_permalink);

                	/*for Add more data*/
                	if( response.data.res.conference_paper_contributors_fname_more ){
                		var append_html_data = '';

                		/*Remove Previous data*/

                		$(".remove").remove();

                		/*End Remove previous data*/

                		$.each(response.data.res.conference_paper_contributors_fname_more, function(index, value){
                		        console.log("Contributor " + (index+1) + ": " + value);

                		        append_html_data += '<div class="remove"> <label for="conference_paper_contributors_fname_more">Name</label><input placeholder="Enter name" type="text" name="conference_paper_contributors_fname_more[]" aria-label="First name" class="form-control" value="'+response.data.res.conference_paper_contributors_fname_more[index]+'"><label for="conference_paper_contributors_sname_more">Surname</label><input placeholder="Enter surname" type="text" name="conference_paper_contributors_sname_more[]" value="'+response.data.res.conference_paper_contributors_sname_more[index]+'" aria-label="Last name" class="form-control">'+ '<button class="button remove_more_author">Remove</button> </div>';
                		  
                		});

                		$(".add_more_author_div").append(append_html_data);

                	}
                	

                	/*end for add more data*/

                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('AJAX request failed: ' + errorThrown);
            }
        });


	});

});