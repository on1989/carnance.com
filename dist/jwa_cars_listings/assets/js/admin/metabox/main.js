jQuery(document).ready(function ($) {

	/**
	 * Ajax Model
	 */
	$('#mark').change(function (e) {
		let markID = $(this).val();
		let data = {
			action: 'get_model',
			make: markID,
		}

		$.ajax({
			type: 'POST',
			url: jwa_car.url_ajax,
			data: data,
			success: function (res) {
				console.log(res)
				$('#trim option').remove();
				$('#trim').append('<option value="none">Select Trim</option>');
				$('#model option').remove();
				$('#model').append('<option value="none">Select Model</option>');
				$.each(res.data.model, function (i, val) {
					let option = '<option value="' + val.term_id + '">' + val.name + '</option>';
					$('#model').append(option);
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log('error...', xhr);
				//error logging
			}
		});
	});

	/**
	 * Ajax Trim
	 */
	$('#model').change(function (e) {
		let modelID = $(this).val();
		let data = {
			action: 'get_trim',
			model: modelID,
		}

		$.ajax({
			type: 'POST',
			url: jwa_car.url_ajax,
			data: data,
			success: function (res) {
				console.log(res)
				$('#trim option').remove();
				$('#trim').append('<option value="none">Select Trim</option>');
				$.each(res.data.trim, function (i, val) {
					let option = '<option value="' + val.term_id + '">' + val.name + '</option>';
					$('#trim').append(option);
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log('error...', xhr);
				//error logging
			}
		});
	});

	/**
	 * Upload Images Gallery
	 */
	$('body').on('click', '#upload-image', function (e) {

		e.preventDefault();

		let button = $(this),
			custom_uploader = wp.media({
				title: 'Insert image',
				library: {
					// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
					type: 'image'
				},
				button: {
					text: 'Use this image' // button label text
				},
				multiple: true
			}).on('select', function () { // it also has "open" and "close" events
				let attachment = custom_uploader.state().get('selection').toJSON();
				console.log(attachment);
				let galleryArray = [];
				$.each(attachment, function (i, val) {
					let html = '<div class="col"><img src="' + val.url + '" class="img-thumbnail" style="width: 33%;' +
						' height:auto;" alt="gallery"></div>';
					$('#image-wrapper').append(html);
					galleryArray.push(val.id);
				});
				let oldID = [$('#gallery_id').val()];
				oldID.push(galleryArray);
				$('#gallery_id').val(oldID);
			}).open();

	});

	/**
	 * Remove gallery, remove post meta
	 */
	$('#remove-all-images').on('click', function (e) {
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: jwa_car.url_ajax,
			data: {action: 'jwa_remove_gallery', post_id: $('#post_id').val()},
			success: function (res) {
				$('#gallery_id').val();
				$('#image-wrapper .col').remove();
			},
			error: function (xhr, ajaxOptions, thrownError) {

			},
		});
	});

});
