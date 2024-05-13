jQuery(document).ready(function($){

	var post_id = front_ajax_wl.post_id;
	var post_doi = front_ajax_wl.post_doi;

	// alert('front_post_loading '+post_doi);

	$("#publication-doi .et_pb_text_inner p").html('DOI: '+'<a href="'+post_doi+'">'+post_doi+'</a>');

});