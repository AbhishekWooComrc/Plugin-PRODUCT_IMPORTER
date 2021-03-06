(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	// $(document).ready(function(e){
	// 	// alert(ced_ajax_object.ajaxurl);
	// 	$('#upload_json').click(function(e){
	// 		e.preventDefault();
	// 		var fd = new FormData();
	// 		var files = $('#fileToUpload')[0].files;
	// 		// Check file selected or not
	// 		if(files.length > 0 ) {
	// 			alert('aa jaaa');
	// 		   	fd.append('file',files[0]);
	// 		   	var action = 'ced_json_file_upload';
	// 		   	$.ajax({
	// 				url: ced_ajax_object.ajaxurl,
	// 				type: 'post',
	// 				data: {
	// 					data:fd,action:action
	// 				},
	// 				contentType: false,
	// 				processData: false,
	// 				success: function(response){
	// 				if(response != 0){
	// 					alert(response);
	// 					//   $("#img").attr("src",response); 
	// 					//   $(".preview img").show(); // Display image element
	// 				}else{
	// 					alert('file not uploaded');
	// 				}
	// 				}
	// 		 });
	// 	  }else{
	// 		 alert("Please select a file.");
	// 	  }
				
			
				
	// 	});
	// });
	$(document).ready(function(){
		$('#json_file_name').change(function(e){
			var json_file_name = $(this).val();
			var action = 'ced_ajax_for_json_file_name';
			$.ajax({
				url : ced_ajax_object.ajaxurl,
				type: 'post',
				data : {
					file_name: json_file_name,
					action:action
				},
				success: function(response) {
					$("#data").html(response);
					console.log(response);
				}
			})
		})

		// ajax for import the product content
		$(document).on("click",'.import_product',function(){
			var sku = $(this).data('sku');
			var item_id = $(this).data('id');
			var json_file_name = $('#json_file_name').val();
			var action = 'ced_ajax_action_for_product_import';
			var element = this;
			$.ajax({
				url : ced_ajax_object.ajaxurl,
				type : 'post',
				data : {
					sku_id : sku,
					item : item_id,
					file_name : json_file_name,
					action : action
				},
				success : function(response) {
					if (response == '1') {
						$(element).prop('disabled', true);
						$(element).prop("value", "Already Imported");
						$(element).css( "background-color", "green");
						$(element).css( "color", "white");
					} else {
						alert('Product Not imported Successfully !! Concern');
					}
					console.log(response);
				}
			});
		});
		
		// ajax for bulk data insertion
		if($('#upload_json').length) {
			$(document).on('click', '#doaction', function(e){
				e.preventDefault();
				var element = this;
				var bulk_action = $('#bulk-action-selector-top').val();
				if (bulk_action != 'bulk_insert') {
					alert('PLEASE SELECT BULK ATCION BEFORE APPLYING');
				} else {
					var json_file_name = $('#json_file_name').val();
					var action = 'ced_ajax_action_for_product_bulk_import';
					var item_id_array = new Array();
					$("input:checked").each(function() {
					item_id_array.push($(this).val());
					});
					$.ajax({
						url : ced_ajax_object.ajaxurl,
						type : 'post',
						data : {
							item : item_id_array,
							file_name : json_file_name,
							action : action
						},
						success : function(response) {
							// if (response == '1') {
								$(element).prop('disabled', true);
								// $(element).prop("value", "Already Imported");
								$(element).css( "background-color", "green");
								$(element).css( "color", "white");
							// // } else {
							// 	alert('Product Not imported Successfully !! Concern');
							// }
							console.log(response);
							// alert(response);
						}
					});
					
					
				}
				
			})
		}
	});

})( jQuery );
