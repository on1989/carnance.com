jQuery(document).ready(function ($) {
	$('.left-sidebar h4.title').click(function () {
		if ($(this).parent().hasClass('open')) {
			$(this).find('i').removeClass('ic-minus').addClass('ic-plus')
			$(this).parent().removeClass('open');
			$(this).parent().find('.hide').slideUp();
		} else {
			$(this).find('i').removeClass('ic-plus').addClass('ic-minus')
			$(this).parent().addClass('open');
			$(this).parent().find('.hide').slideDown();
		}
	});
	$('.left-sidebar h4.title').each(function () {
		if ($(this).parent().hasClass('open')) {
			$(this).find('i').removeClass('ic-plus').addClass('ic-minus');
			$(this).parent().find('.hide').slideDown();
		}
	});
	$('.show-filters').click(function () {
		if ($(this).hasClass('open')) {
			$(this).removeClass('open');
			$('.filters .hidden-filters').removeClass('open').slideUp()
		} else {
			$(this).addClass('open');
			$('.filters .hidden-filters').addClass('open').slideDown();
		}
	});
	$('p').filter(function () {
		return this.innerHTML == '&nbsp;';
	}).remove();
	/**
	 * Open select range
	 */
	$('select').select2();
	if ($('.select-range').length) {
		$('.select-range p').click(function () {
			if($(this).parents('.select-range').hasClass('open')) {
				$(this).parents('.select-range').removeClass('open');
				$(this).next().hide();
			} else {
				$('.select-range').removeClass('open');
				$('.select-range .range-wrapper').hide();
				$(this).parents('.select-range').addClass('open');
				$(this).next().show();
			}
		});
		//HIDE CLICK BODY
	}
	$(document).mouseup(function (e) {
		var folder = $(".select-range");

		if (!folder.is(e.target) && folder.has(e.target).length === 0) {
			folder.removeClass('open');
			folder.find('.range-wrapper').hide();
		}
	});
	/**
	 * Init range slider
	 */

	var instance;
	$('.js-range-slider').each(function () {
		var $range = $(this),
			$inputFrom = $(this).parents('.range-wrapper').find(".js-input-from"),
			$inputTo = $(this).parents('.range-wrapper').find(".js-input-to"),
			min = $range.data('min'),
			max = $range.data('max'),
			step = $range.data('step'),
			postfix = $range.data('postfix'),
			from = ($(this).data('from') ? $(this).data('from') : 0),
			to = 0;
		if ($(this).attr('id') == 'car_year') {
			console.log("year")
			$range.ionRangeSlider({
				type: "double",
				min: min,
				max: max,
				from: from,
				onStart: updateInputs,
				onChange: updateInputs,
				item: $range,
				step: step,
				prettify_enabled: true,
				prettify_separator: "",
				values_separator: "-",
				input_values_separator: '-',
				force_edges: true,
			});
		} else {
			$range.ionRangeSlider({
				type: "double",
				min: min,
				max: max,
				from: from,
				to: 500000,
				onStart: updateInputs,
				onChange: updateInputs,
				item: $range,
				step: step,
				prettify_enabled: true,
				prettify_separator: ".",
				values_separator: "-",
				input_values_separator: '-',
				force_edges: true,
				postfix: postfix,

			});
		}


		instance = $range.data("ionRangeSlider");

		function updateInputs(data) {
			from = data.from;
			to = data.to;

			$inputFrom.prop("value", from);
			$inputTo.prop("value", to);
		}

		$inputFrom.on("input", function () {
			var val = $(this).prop("value");

			// validate
			if (val < min) {
				val = min;
			} else if (val > to) {
				val = to;
			}

			instance.update({
				from: val
			});
		});

		$inputTo.on("input", function () {
			var val = $(this).prop("value");

			// validate
			if (val < from) {
				val = from;
			} else if (val > max) {
				val = max;
			}

			instance.update({
				to: val
			});
		});

	});

	/**
	 * Init Gallery
	 */
	if ($('.gallery').length) {
		$('.gallery').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.gallery-nav',
			infinity: false,
		});

		$('.gallery-nav').slick({
			slidesToShow: 5,
			slidesToScroll: 1,
			asNavFor: '.gallery',
			dots: false,
			infinity: false,
			centerMode: true,
			focusOnSelect: true,
			centerPadding: '0',
			prevArrow: '<i class="ic-prev-slide"></i>',
			nextArrow: '<i class="ic-next-slide"></i>',
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 3
					}
				}
			]
		});
	}

	/**
	 * Gallery navigation
	 */
	$(document).on('afterShow.fb', function (e, instance, slide) {
		$('.fancybox-button--arrow_right').click(function () {
			$('.gallery').slick('slickNext');
			$('.gallery-nav').slick('slickNext');
		})
		$('.fancybox-button--arrow_left').click(function () {
			$('.gallery').slick('slickPrev');
			$('.gallery-nav').slick('slickPrev');
		});
	});

	/**
	 * Ajax append Year and Model by Mark from horizons form
	 */
	$("#make, #model").change(function (event) {
		let data = {
			action: 'get_filters_car_horizons',
			make: $(this).val(),
		}
		let el = $(this);
		$('#mileage').val('');
		$('#car_year').val('');
		$('#price').val('');

		$.ajax({
			type: 'POST',
			url: jwaCarFilter.ajaxUrl,
			data: data,
			success: function (res) {
				if ($(el).attr('id') == "make") {
					$('#model option').remove();
					$('#model').append('<option value="0">Model</option>');
					$.each(res.data.make, function (i, val) {
						$('#model').append(`<option value="${val.slug}">${val.name}</option>`);
					});
				}

				let yearsEl = $("#car_year").data("ionRangeSlider");

				yearsEl.update({
					min: res.data.year.min,
					max: res.data.year.max,
					to: res.data.year.max,
					from: res.data.year.min,
				});

				let mileageEL = $('#mileage').data("ionRangeSlider");

				mileageEL.update({
					max: res.data.mileage,
					to: res.data.mileage,
					from: 0,
				});
				$('#mileage').parent().parent().find('.extra-controls .js-input-to').val(res.data.mileage)

				let priceEL = $('#price').data("ionRangeSlider");

				priceEL.update({
					max: res.data.price,
					to: res.data.price,
					from: 0,
				});
				$('#price').parent().parent().find('.extra-controls .js-input-to').val(res.data.price)
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log('error...', xhr);
				//error logging
			},
		});
	});

	/**
	 * Ajax append Year and Model by Mark from checkbox
	 */
	$(".make .checkbox input[type=checkbox]").change(function (event) {

		let make = [];
		$.each($(".make .checkbox input[type=checkbox]:checked"), function (i, val) {
			make.push($(val).val());
		});

		let data = {
			action: 'get_filters_car',
			make: make,
		}

		// console.log(data)
		$.ajax({
			type: 'POST',
			url: jwaCarFilter.ajaxUrl,
			data: data,
			success: function (res) {
				$('#model .checkbox').remove()
				$.each(res.data.make, function (i, val) {
					let html = `<div class="checkbox"><input id="${val.slug}" value="${val.slug}" type="checkbox"><label class="ic-check" for="${val.slug}">${val.name}</label></div>`;
					$('#model').append(html);
					$('.model').removeClass('disable');
				});

				let year = Object.values(res.data.year);
				year.sort(function (a, b) {
					return a - b;
				});

				$('.year .checkbox').remove()
				$.each(year, function (i, val) {
					let html = `<div class="checkbox"><input id="${val}" value="${val}" type="checkbox" name="jwa_filter[car-year][]"><label class="ic-check" for="${val}">${val}</label></div>`;
					$('#year ').append(html);
				});

			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log('error...', xhr);
				//error logging
			},
		});
	});


	if ($('.radius').length) {
		$('.radius').IncrementBox({
			timeout: 75,
			cursor: true,
		});
		$(".radius-num").keypress(function (event) {
			event = event || window.event;
			if (event.charCode && event.charCode != 0 && event.charCode != 46 && (event.charCode < 48 || event.charCode > 57)) {
				return false;
			}
		});
	}

});


