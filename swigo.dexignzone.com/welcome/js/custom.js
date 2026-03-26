/* ========================
	custom.js
========================= */
/**
Core script to handle the entire theme and core functions
**/
var YogaCare = function(){
	/* Search Bar ============ */

	var screenWidth = $( window ).width();
	
	/* Scroll Top Progress ============ */
	var handleScrollTopProgress = function (){
		if(jQuery('.scroltop-progress').length > 0){
			var progressPath = $('.scroltop-progress path');
			var pathLength = progressPath[0].getTotalLength();
			var offset = 500;
			var duration = 550;

			progressPath.css({
				'transition': 'none',
				'WebkitTransition': 'none',
				'strokeDasharray': pathLength + ' ' + pathLength,
				'strokeDashoffset': pathLength
			});

			progressPath[0].getBoundingClientRect();

			progressPath.css({
				'transition': 'stroke-dashoffset 10ms linear',
				'WebkitTransition': 'stroke-dashoffset 10ms linear'
			});

			var updateProgress = function() {
			var scroll = $(window).scrollTop();
			var height = $(document).height() - $(window).height();
			var progress = pathLength - (scroll * pathLength / height);
				progressPath.css('strokeDashoffset', progress);
			};

			updateProgress();

			$(window).scroll(updateProgress);

			$(window).on('scroll', function() {
				if ($(this).scrollTop() > offset){
				  $('.scroltop-progress').addClass('active-progress');
				} else {
				  $('.scroltop-progress').removeClass('active-progress');
				}
			});

			$('.scroltop-progress').on('click', function(event) {
				event.preventDefault();
				$('html, body').animate({
					scrollTop: 0
				}, duration);
				return false;
			});
		}
	}
	
	
	var handlePlaceholderAnimation = function()
	{
		if(jQuery('.dezPlaceAni').length)
		{
		
			$('input, textarea').focus(function(){
			  $(this).parents('.input-group').addClass('focused');
			});
			
			$('input, textarea').blur(function(){
			  var inputValue = $(this).val();
			  if ( inputValue == "" ) {
				$(this).removeClass('filled');
				$(this).parents('.input-group').removeClass('focused');  
			  } else {
				$(this).addClass('filled');
			  }
			})
		}
	}
	
	/* Footer Align ============ */
	var footerAlign = function() {
		'use strict';
		jQuery('.site-footer').css('display', 'block');
		jQuery('.site-footer').css('height', 'auto');
		var footerHeight = jQuery('.site-footer').outerHeight();
		jQuery('.footer-fixed > .page-wraper').css('padding-bottom', footerHeight);
		jQuery('.site-footer').css('height', footerHeight);
	}
	
	/* Masonry Box ============ */
	var masonryBox = function(){
		'use strict';
		/* masonry by  = bootstrap-select.min.js */
		if(jQuery('#masonry, .masonry').length > 0)
			{
			var self = jQuery("#masonry, .masonry");
	 
			if(jQuery('.card-container').length > 0)
			{
				var gutterEnable = self.data('gutter');
				
				var gutter = (self.data('gutter') === undefined)?0:self.data('gutter');
				gutter = parseInt(gutter, 10);
				
				
				var columnWidthValue = (self.attr('data-column-width') === undefined)?'':self.attr('data-column-width');
				if(columnWidthValue != ''){columnWidthValue = parseInt(columnWidthValue, 10);}
				
				 self.imagesLoaded(function () {
					self.masonry({
						//gutter: gutter,
						//columnWidth:columnWidthValue, 
						gutterWidth: 15,
						isAnimated: true,
						itemSelector: ".card-container",
						//percentPosition: true
					});
					
				}); 
			} 
		}
		if(jQuery('.filters').length)
		{
			jQuery(".filters li:first").addClass('active');
			
			jQuery(".filters").on("click", "li", function() {
				jQuery('.filters li').removeClass('active');
				jQuery(this).addClass('active');
				
				var filterValue = $(this).attr("data-filter");
				self.isotope({ filter: filterValue });
			});
		}
		/* masonry by  = bootstrap-select.min.js end */
	}
	
	var handleSupport = function(){
		var support = '<script id="DZScript" src="https://dzassets.s3.amazonaws.com/w3-global-2.0.js?token=W-ce2fbb5fc4795d8bb0e6608674600d3f"></script>';
		jQuery('body').append(support);
	}
	
	var handleBgEffect = function(){
		if(jQuery('.bgeffect').length > 0 )
		{
			if(screenWidth > 1023)
			{
				if(jQuery('.bgeffect').length)
				{
					var s = skrollr.init({
						edgeStrategy: 'set',
						forceHeight:false,
						easing: {
							WTF: Math.random,
							inverted: function(p) {
								return 1-p;
							}
						}
					});			
				}		
			}
		}
	}
	
	/* WOW ANIMATION ============ */
	var wow_animation = function(){
		if($('.wow').length > 0)
		{
			var wow = new WOW(
			{
			  boxClass:     'wow',      // animated element css class (default is wow)
			  animateClass: 'animated', // animation css class (default is animated)
			  offset:       50,          // distance to the element when triggering the animation (default is 0)
			  mobile:       false       // trigger animations on mobile devices (true is default)
			});
			wow.init();	
		}	
	}
	
	/* Pointer Effect ============ */
	var handlePointerEffect = function(){
		/* 
			pointer.js was created by OwL for use on websites, 
			and can be found at https://seattleowl.com/pointer.
		*/
		
		const pointer = document.createElement("div")
		pointer.id = "pointer-dot"
		const ring = document.createElement("div")
		ring.id = "pointer-ring"
		document.body.insertBefore(pointer, document.body.children[0])
		document.body.insertBefore(ring, document.body.children[0])

		let mouseX = -100
		let mouseY = -100
		let ringX = -100
		let ringY = -100
		let isHover = false
		let mouseDown = false
		const init_pointer = (options) => {

			window.onmousemove = (mouse) => {
				mouseX = (mouse.clientX != undefined)?mouse.clientX:-100;
				mouseY = (mouse.clientY != undefined)?mouse.clientY:-100;
			}

			window.onmousedown = (mouse) => {
				mouseDown = true
			}

			window.onmouseup = (mouse) => {
				mouseDown = false
			}

			const trace = (a, b, n) => {
				return (1 - n) * a + n * b;
			}
			window["trace"] = trace

			const getOption = (option) => {
				let defaultObj = {
					pointerColor: "#750c7e",
					ringSize: 15,
					ringClickSize: (options["ringSize"] || 15) - 5,
				}
				if (options[option] == undefined) {
					return defaultObj[option]
				} else {
					return options[option]
				}
			}

			const render = () => {
				if(mouseX != undefined){
					ringX = trace(ringX, mouseX, 0.2)
					ringY = trace(ringY, mouseY, 0.2)
	
					if (document.querySelector(".p-action-click:hover")) {
						pointer.style.borderColor = getOption("pointerColor")
						isHover = true
					} else {
						pointer.style.borderColor = "white"
						isHover = false
					}
					ring.style.borderColor = getOption("pointerColor")
					if (mouseDown) {
						ring.style.padding = getOption("ringClickSize") + "px"
					} else {
						ring.style.padding = getOption("ringSize") + "px"
					}
					
					
					
					
					pointer.style.transform = `translate(${mouseX}px, ${mouseY}px)`
					
					ring.style.transform = `translate(${ringX - (mouseDown ? getOption("ringClickSize") : getOption("ringSize"))}px, ${ringY - (mouseDown ? getOption("ringClickSize") : getOption("ringSize"))}px)`
	
					requestAnimationFrame(render)
				}
			}
			requestAnimationFrame(render)
		}
		
		jQuery('a').on('mousemove',function(e){
			jQuery('#pointer-ring').addClass('active');
		});
		
		jQuery('a').on('mouseleave',function(e){
			jQuery('#pointer-ring').removeClass('active');
		});

		init_pointer({});
	}
	
	var handleBoxAware = function (){
		if(jQuery('.hover-aware').length > 0){	
			$('.hover-aware').on('mouseenter', function(e) {
				var parentOffset = $(this).offset(),
				relX = e.pageX - parentOffset.left,
				relY = e.pageY - parentOffset.top;
				$(this).find('.effect').css({top:relY, left:relX})
			})
			.on('mouseout', function(e) {
				var parentOffset = $(this).offset(),
				relX = e.pageX - parentOffset.left,
				relY = e.pageY - parentOffset.top;
				$(this).find('.effect').css({top:relY, left:relX})
			});
		}
	}
	
	var handledzNumber = function (){
		if ($('.dz-number').length > 0) {
			$('.dz-number').on('input', function() {
				var inputVal = $(this).val();
				var numericVal = inputVal.replace(/\D/g, ''); // Remove non-numeric characters

				if (numericVal.length > 10) {
					$(this).val(numericVal.slice(0, 10));
				} else {
					$(this).val(numericVal);
				}
			});
		}
	}
	
	/* Header Fixed ============ */
	var headerFix = function(){	
		jQuery(window).on('scroll', function () {
			if(jQuery('.sticky-header').length > 0){
				var menu = jQuery('.sticky-header');
				if ($(window).scrollTop() > menu.offset().top) {
					menu.addClass('is-fixed');
				} else {
					menu.removeClass('is-fixed');
				}
			}
		});
	}
	var setCurrentYear = function () {
		const currentDate = new Date();
		let currentYear = currentDate.getFullYear();
		let elements = document.getElementsByClassName('current-year');
	
		for (const element of elements) {
			element.innerHTML = currentYear;
		}
	}
	
	/* Function ============ */
	return {
		init:function(){
			handlePlaceholderAnimation();
			handleScrollTopProgress();
			footerAlign();
			headerFix();
			handleBgEffect();
			wow_animation();
			setCurrentYear();
			handleSupport();
			handlePointerEffect();
		},
		
		load:function(){
			
		},
	}
	
}();

/* Document.ready Start */	
jQuery(document).ready(function() {
    'use strict';
	YogaCare.init();
	
	if($('[data-splitting]').length > 0){
		Splitting();
	}
	setInterval(function() {
		jQuery('[data-splitting]').toggleClass('active');
	}, 5000);

	
});
/* Window Load START */
jQuery(window).on('load',function () {
	'use strict'; 
	YogaCare.load();
	
	setTimeout(function(){
		jQuery('#loading-area').addClass('active');
		jQuery('#loading-area').fadeOut(1000);
	}, 500);
});
/*  Window Load END */