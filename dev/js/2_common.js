jQuery(document).ready(function ($) {
//	$('.left-sidebar h4.title').click(function(){
//		if($(this).parent().hasClass('open')){
//			$(this).find('i').removeClass('ic-minus').addClass('ic-plus')
//			$(this).parent().removeClass('open');
//			$(this).parent().find('.hide').slideUp();
//		}else{
//			$(this).find('i').removeClass('ic-plus').addClass('ic-minus')
//			$(this).parent().addClass('open');
//			$(this).parent().find('.hide').slideDown();
//		}
//	});
//	$('.left-sidebar h4.title').each(function(){
//		if($(this).parent().hasClass('open')){
//			$(this).find('i').removeClass('ic-plus').addClass('ic-minus');
//			$(this).parent().find('.hide').slideDown();
//		}
//	});
	if($('.comment-form'.length)){
		$('textarea').attr('placeholder','Message*')
		$('#author').attr('placeholder','Name*')
		$('#email').attr('placeholder','Email*')
	}
	$('#featured-more').click(function(e){
	if($('.features-items').hasClass('open')){
		$(this).text('Show More')
	}else{
		$(this).text('Show Less')
	}
	e.preventDefault();
		$('.features-items').toggleClass('open');
	});
	if($('.testimonial').length){
		$('.testimonial').wrapAll('<div class="testimonial-slider"></div>');
		$('.testimonial-slider').slick({})
	}
	var heightTopHead = $('.top-head').height()
	$(document).on("scroll",function(){
		if($(document).scrollTop()>heightTopHead){
			$("header").addClass("fixed-header");
		}
		else{
			$("header").removeClass("fixed-header");
		}
	});
	$('.burger').click(function(){
		$(this).toggleClass('close');
		$('body').toggleClass('modal-open');
	})
	if($('.car-make-logos .items').length){
		$('.car-make-logos .items').slick({
			dots: true,
			speed: 300,
			slidesToShow: 8,
			slidesToScroll: 1,
			prevArrow:'<i class="icon-left"></i>',
			nextArrow:'<i class="icon-right"></i>',
		});
	}

	$(window).on('resize', function () {
		if($(window).width() < 992){
			if($('#filter-form-sidebar').length){
				$('#filter-form-sidebar').each(function(){
					if($('.filter-dropdown').length == 0){
						$(this).wrap('<div class="filter-dropdown"></div>');
						if($('.filter-dropdown').length){
							$('#filter-form-sidebar').before('<h4 class="title ic-plus">Filter</h4>')
						}
						$('.filter-dropdown > h4').click(function(){
							if($(this).hasClass('ic-plus')){
								$(this).removeClass('ic-plus').addClass('ic-minus');
								$(this).next().slideDown();
							}else{
								$(this).removeClass('ic-minus').addClass('ic-plus');
								$(this).next().slideUp();
							}
						});
					}
				});

			}

		}else{
			if($('#filter-form-sidebar').length){
				if($('.filter-dropdown').length ){
					$('.filter-dropdown > h4.title').remove();
					$('#filter-form-sidebar').unwrap().show();
				}
			}
		}
	});
	if($(window).width() < 992){
		if($('#filter-form-sidebar').length){

			$('#filter-form-sidebar').each(function(){
				$(this).wrap('<div class="filter-dropdown"></div>');
				if($('.filter-dropdown').length){
					$('#filter-form-sidebar').before('<h4 class="title ic-plus">Filter</h4>')
				}

			});
			$('.filter-dropdown > h4').click(function(){
				if($(this).hasClass('ic-plus')){
					$(this).removeClass('ic-plus').addClass('ic-minus');
					$(this).next().slideDown();
				}else{
					$(this).removeClass('ic-minus').addClass('ic-plus');
					$(this).next().slideUp();
				}
			})
		}

	}
});
