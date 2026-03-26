/* JavaScript Document */
jQuery(document).ready(function() {
    'use strict';

	jQuery('.testimonial-two-dots').owlCarousel({
		loop:true,
		autoplaySpeed: 3000,
		navSpeed: 3000,
		paginationSpeed: 3000,
		slideSpeed: 3000,
		smartSpeed: 3000,
		autoplay: 3000,
		margin:30,
		nav:true,
		dots: true,
		navText: ['<i class="ti-arrow-left"></i>', '<i class="ti-arrow-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			767:{
				items:2
			},
			1280:{
				items:3
			},
			1680:{
				items:4
			}
		}
	})

	jQuery('.awards-carousel').owlCarousel({
		loop:true,
		autoplaySpeed: 3000,
		navSpeed: 3000,
		paginationSpeed: 3000,
		slideSpeed: 3000,
		smartSpeed: 3000,
		autoplay: 3000,
		margin:30,
		nav:true,
		dots: true,
		navText: ['<i class="ti-arrow-left"></i>', '<i class="ti-arrow-right"></i>'],
		responsive:{
			0:{
				items:2
			},
			480:{
				items:4
			},			
			
			991:{
				items:5
			},
			1000:{
				items:6
			}
		}
	})

	jQuery('.inner-frame').owlCarousel({
		loop:true,
		autoplaySpeed: 3000,
		navSpeed: 3000,
		paginationSpeed: 3000,
		slideSpeed: 3000,
		smartSpeed: 3000,
		autoplay: 3000,
		margin:30,
		autoWidth:true,
		nav:false,
		dots: false,
		navText: ['<i class="ti-arrow-left"></i>', '<i class="ti-arrow-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			},			
			
			991:{
				items:2
			},
			1000:{
				items:2
			}
		}
	})

	jQuery('.responsive-carousel').owlCarousel({
		loop:false,
		autoplaySpeed: 3000,
		navSpeed: 3000,
		paginationSpeed: 3000,
		slideSpeed: 3000,
		smartSpeed: 3000,
		autoplay: 3000,
		margin:30,
		autoWidth:true,
		nav:false,
		dots: false,
		navText: ['<i class="ti-arrow-left"></i>', '<i class="ti-arrow-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			},			
			
			991:{
				items:2
			},
			1000:{
				items:2
			}
		}
	})

	/*Custom Navigation work*/
	jQuery('#next-slide').on('click', function(){
	   $('.responsive-carousel').trigger('next.owl.carousel');
	});

	jQuery('#prev-slide').on('click', function(){
	   $('.responsive-carousel').trigger('prev.owl.carousel');
	});
	/*Custom Navigation work*/
	
});
/* Document .ready END */