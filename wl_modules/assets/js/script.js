jQuery(document).ready(function($){
	// alert('HELLO WORLD');

	/*------------------------------------
	add more author
	------------------------------------*/

		$('.add_more_author').click(function(e) {
		    e.preventDefault();
		    // alert('add more');

		    $(".add_more_author_div").append( '<div class="remove"> <label for="first_name">First Name</label><input placeholder="Enter name" type="text" name="first_name[]" aria-label="First name" class="form-control"><label for="last_name">Last Name</label><input placeholder="Enter Last Name" type="text" name="last_name[]" aria-label="Last name" class="form-control">'+ '<button class="button remove_more_author">Remove</button> </div>' );
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

		// first make it empty

		$('#doi_redirect_to_url').val('');
		$('#conference_title').val('');
		$('#proceedings_title').val('');
		$('#publication_date').val('');
		$('#conference_paper_title').val('');

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

                $('#conference_title').val(response.data.res.conference_title);
                $('#proceedings_title').val(response.data.res.proceedings_title);
                $('#publication_date').val(response.data.res.publication_date);
                $('#conference_paper_title').val(response.data.res.conference_paper_title);
                $('#doi_redirect_to_url').val(response.data.res.post_permalink);

                /*for Add more data*/
                if( response.data.res.authors ){
                	var append_html_data = '';

                	/*Remove Previous data*/

                	$(".remove").remove();

                	/*End Remove previous data*/

                	$.each(response.data.res.authors, function(index, value){
                	        console.log("Contributor " + (index+1) + ": " + value);

                	        append_html_data += '<div class="remove"> <label for="first_name">First Name</label><input placeholder="Enter name" type="text" name="first_name[]" aria-label="First name" class="form-control" value="'+response.data.res.authors[index]['first_name']+'"><label for="last_name">Last Name</label><input placeholder="Enter Lastname" type="text" name="last_name[]" value="'+response.data.res.authors[index]['last_name']+'" aria-label="Last name" class="form-control">'+ '<button class="button remove_more_author">Remove</button> </div>';
                	  
                	});

                	$(".add_more_author_div").append(append_html_data);

                }
                

                /*end for add more data*/

                /*newly added*/
                if( response.success && response.data.has_data == 2 ){
                	$('#doi_redirect_to_url').val(response.data.res.post_permalink);
                }
                

                /*newly added*/

                else if( response.success && response.data.has_data == 1 ){
                	// do stuff here
                }

            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('AJAX request failed: ' + errorThrown);
            }
        });


	});

});