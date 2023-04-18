$(function() {
	'use strict'
	
	// ______________LOADER
	$("#global-loader").fadeOut("slow");
	
	//Color-Theme
	$(document).on("click", "a[data-theme]", function() {
		$("head link#theme").attr("href", $(this).data("theme"));
		$(this).toggleClass('active').siblings().removeClass('active');
	});
	
	
	// This template is mobile first so active menu in navbar
	// has submenu displayed by default but not in desktop
	// so the code below will hide the active menu if it's in desktop
	
	
	// ______________Full screen
	$(document).on("click", ".fullscreen-button", function toggleFullScreen() {
		$('html').addClass('fullscreen-button');
		if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
			if (document.documentElement.requestFullScreen) {
				document.documentElement.requestFullScreen();
			} else if (document.documentElement.mozRequestFullScreen) {
				document.documentElement.mozRequestFullScreen();
			} else if (document.documentElement.webkitRequestFullScreen) {
				document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
			} else if (document.documentElement.msRequestFullscreen) {
				document.documentElement.msRequestFullscreen();
			}
		} else {
			$('html').removeClass('fullscreen-button');
			if (document.cancelFullScreen) {
				document.cancelFullScreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.webkitCancelFullScreen) {
				document.webkitCancelFullScreen();
			} else if (document.msExitFullscreen) {
				document.msExitFullscreen();
			}
		}
	})
	
	
	// ______________Cover Image
	$(".cover-image").each(function() {
		var attr = $(this).attr('data-image-src');
		if (typeof attr !== typeof undefined && attr !== false) {
			$(this).css('background', 'url(' + attr + ') center center');
		}
	});
	
	// ______________ PAGE LOADING
	$(window).on("load", function(e) {
		$("#global-loader").fadeOut("slow");
	})
	
	// ______________ BACK TO TOP BUTTON
	$(window).on("scroll", function(e) {
		if ($(this).scrollTop() > 0) {
			$('#back-to-top').fadeIn('slow');
		} else {
			$('#back-to-top').fadeOut('slow');
		}
	});
	$(document).on("click", "#back-to-top", function(e) {
		$("html").animate({
			scrollTop: 0
		}, 0);
		return false;
	});
	
	// ______________Toast
    $(".toast").toast();
		
	/* Headerfixed */
	$(window).on("scroll", function(e){
		if ($(window).scrollTop() >= 66) {
			$('main-header').addClass('fixed-header');
		}
		else {
			$('.main-header').removeClass('fixed-header');
		}
    });
	
	// ______________Search
	$('body, .main-header form[role="search"] button[type="reset"]').on('click keyup', function(event) {
		if (event.which == 27 && $('.main-header form[role="search"]').hasClass('active') ||
		$(event.currentTarget).attr('type') == 'reset') {
		closeSearch();
		}
	});
	function closeSearch() {
		var $form = $(' form[role="search"].active')
		$form.find('input').val('');
		$form.removeClass('active');
		$('body').removeClass('search-open');
	}
	// Show Search if form is not active // event.preventDefault() is important, this prevents the form from submitting
	$(document).on('click', ' form[role="search"]:not(.active) button[type="submit"]', function(event) {
		event.preventDefault();
		var $form = $(this).closest('form'),
		$input = $form.find('input');
		$form.addClass('active');
		$input.focus();
		$('body').addClass('search-open');
	});
	// if your form is ajax remember to call `closeSearch()` to close the search container
	$(document).on('click', ' form[role="search"].active button[type="submit"]', function(event) {
		event.preventDefault();
		var $form = $(this).closest('form'),
		$input = $form.find('input');
		$('#showSearchTerm').text($input.val());
		closeSearch()
		$('body').addClass('search-open');
	});
	
	// if your form is ajax remember to call `closeSearch()` to close the search container
	$(document).on('click', ' form[role="search"].active button[type="reset"]', function(event) {
		event.preventDefault();
		$('body').removeClass('search-open');
		var $form = $(this).closest('form'),
		$input = $form.find('input');
		$('#showSearchTerm').text($input.val());
		closeSearch()
	});
	
	//  item notes
	$('.thumb').click(function () {
        if(!$(this).hasClass('active'))
        {
            $(".thumb.active").removeClass("active");
            $(this).addClass("active");        
        }
    });
	
	const DIV_CARD = 'div.card';
	
	// ______________ Function for remove card
	$(document).on('click', '[data-bs-toggle="card-remove"]', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.remove();
		e.preventDefault();
		return false;
	});
	// ______________ Functions for collapsed card
	$(document).on('click', '[data-bs-toggle="card-collapse"]', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.toggleClass('card-collapsed');
		e.preventDefault();
		return false;
	});
	
	// ______________ Card full screen
	$(document).on('click', '[data-bs-toggle="card-fullscreen"]', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.toggleClass('card-fullscreen').removeClass('card-collapsed');
		e.preventDefault();
		return false;
	});
	/* ----------------------------------- */
	
	// Showing submenu in navbar while hiding previous open submenu
	$('.main-navbar .with-sub').on('click', function(e) {
		e.preventDefault();
		$(this).parent().toggleClass('show');
		$(this).parent().siblings().removeClass('show');
	});
	// this will hide dropdown menu from open in mobile
	$('.dropdown-menu .main-header-arrow').on('click', function(e) {
		e.preventDefault();
		$(this).closest('.dropdown').removeClass('show');
	});
	// this will show navbar in left for mobile only
	$('#mainNavShow, #azNavbarShow').on('click', function(e) {
		e.preventDefault();
		$('body').addClass('main-navbar-show');
	});
	// this will hide currently open content of page
	// only works for mobile
	$('#mainContentLeftShow').on('click touch', function(e) {
		e.preventDefault();
		$('body').addClass('main-content-left-show');
	});
	// This will hide left content from showing up in mobile only
	$('#mainContentLeftHide').on('click touch', function(e) {
		e.preventDefault();
		$('body').removeClass('main-content-left-show');
	});
	// this will hide content body from showing up in mobile only
	$('#mainContentBodyHide').on('click touch', function(e) {
		e.preventDefault();
		$('body').removeClass('main-content-body-show');
	})
	// navbar backdrop for mobile only
	$('body').append('<div class="main-navbar-backdrop"></div>');
	$('.main-navbar-backdrop').on('click touchstart', function() {
		$('body').removeClass('main-navbar-show');
	});
	// Close dropdown menu of header menu
	$(document).on('click touchstart', function(e) {
		e.stopPropagation();
		// closing of dropdown menu in header when clicking outside of it
		var dropTarg = $(e.target).closest('.main-header .dropdown').length;
		if (!dropTarg) {
			$('.main-header .dropdown').removeClass('show');
		}
		// closing nav sub menu of header when clicking outside of it
		if (window.matchMedia('(min-width: 992px)').matches) {
			// Navbar
			var navTarg = $(e.target).closest('.main-navbar .nav-item').length;
			if (!navTarg) {
				$('.main-navbar .show').removeClass('show');
			}
			// Header Menu
			var menuTarg = $(e.target).closest('.main-header-menu .nav-item').length;
			if (!menuTarg) {
				$('.main-header-menu .show').removeClass('show');
			}
			if ($(e.target).hasClass('main-menu-sub-mega')) {
				$('.main-header-menu .show').removeClass('show');
			}
		} else {
			//
			if (!$(e.target).closest('#mainMenuShow').length) {
				var hm = $(e.target).closest('.main-header-menu').length;
				if (!hm) {
					$('body').removeClass('main-header-menu-show');
				}
			}
		}
	});
	$('#mainMenuShow').on('click', function(e) {
		e.preventDefault();
		$('body').toggleClass('main-header-menu-show');
	})
	$('.main-header-menu .with-sub').on('click', function(e) {
		e.preventDefault();
		$(this).parent().toggleClass('show');
		$(this).parent().siblings().removeClass('show');
	})
	$('.main-header-menu-header .close').on('click', function(e) {
		e.preventDefault();
		$('body').removeClass('main-header-menu-show');
	})
	
	$(".card-header-right .card-option .fe fe-chevron-left").on("click", function() {
		var a = $(this);
		if (a.hasClass("icofont-simple-right")) {
			   a.parents(".card-option").animate({
				width: "35px",
			})
		} else {
		   a.parents(".card-option").animate({
			width: "180px",
			})
		}
		$(this).toggleClass("fe fe-chevron-right").fadeIn("slow")
	});
	
	 // ___________TOOLTIP	
	 var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	 var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	 return new bootstrap.Tooltip(tooltipTriggerEl)
	 })
	
	// __________POPOVER
	var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
	var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	  return new bootstrap.Popover(popoverTriggerEl)
	})
	
	
	
	// ______________horizontal-menu Active Class
	$(document).ready(function() {
		$(".horizontalMenu-list li a").each(function() {
			var pageUrl = window.location.href.split(/[?#]/)[0];
			if (this.href == pageUrl) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
			}
		});
	});
	
	
	// ______________Active Class
	$(document).ready(function() {
		$(".horizontalMenu-list li a").each(function() {
			var pageUrl = window.location.href.split(/[?#]/)[0];
			if (this.href == pageUrl) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
			}
		});
		$(".horizontal-megamenu li a").each(function() {
			var pageUrl = window.location.href.split(/[?#]/)[0];
			if (this.href == pageUrl) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().parent().parent().parent().parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
			}
		});
		$(".horizontalMenu-list .sub-menu .sub-menu li a").each(function() {
			var pageUrl = window.location.href.split(/[?#]/)[0];
			if (this.href == pageUrl) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
			}
		});
	});
	
	
	// ______________Cover Image
	$(".cover-image").each(function() {
		var attr = $(this).attr('data-image-src');
		if (typeof attr !== typeof undefined && attr !== false) {
			$(this).css('background', 'url(' + attr + ') center center');
		}
	});

	// ______________ SWITCHER-toggle ______________//
	
	$('.layout-setting').on("click", function(e) {
		if (document) {
			$('body').toggleClass('dark-theme');
		} else {
			$('body').removeClass('dark-theme');
			$('body').addClass('light-theme');
		}
	});

	

	  $('.default-menu').on('click', function() {
		var ww = document.body.clientWidth;
		if (ww >= 992) {
			$('body').removeClass('sidenav-toggled');
		}
	});


	/*Light Layout Start*/

	$(document).on("click", '#myonoffswitch1', function () {
		if (this.checked) {
			$('body').addClass('light-theme');
			$('#myonoffswitch3').prop('checked', true);
            $('#myonoffswitch6').prop('checked', true);
			$('body').removeClass('dark-theme');
			$('body').removeClass('transparent-theme');
			$('body').removeClass('light-menu');
			$('body').removeClass('dark-theme');
			$('body').removeClass('color-header');
			$('body').removeClass('dark-header'); 
			$('body').removeClass('dark-menu');
			$('body').removeClass('gradient-menu');
			$('body').removeClass('gradient-header');
			$('body').removeClass('color-menu');
			 localStorage.removeItem('darkPrimary')
			
			// remove light theme properties
			localStorage.removeItem('primaryColor')
			localStorage.removeItem('primaryHoverColor')
			localStorage.removeItem('primaryBorderColor')
			localStorage.removeItem('transparentBgImgPrimary')
			localStorage.removeItem('transparentBgImgprimaryTransparent')
			document.querySelector('html')?.style.removeProperty('--primary-bg-color', localStorage.darkPrimary);
			document.querySelector('html')?.style.removeProperty('--primary-bg-hover', localStorage.darkPrimary);
			document.querySelector('html')?.style.removeProperty('--primary-bg-border', localStorage.darkPrimary);
			document.querySelector('html')?.style.removeProperty('--dark-primary', localStorage.darkPrimary);
	
			// removing dark theme properties
			 localStorage.removeItem('darkPrimary')
			 localStorage.removeItem('darkprimaryTransparent');
			 localStorage.removeItem('transparentBgColor');
			 localStorage.removeItem('transparentThemeColor');
			 localStorage.removeItem('transparentPrimary');
	
			$('#myonoffswitch5').prop('checked', false);
            $('#myonoffswitch8').prop('checked', false);
            $('#myonoffswitchTransparent').prop('checked', false);

            checkOptions();
			const root = document.querySelector(':root');
			root.style = "";
			names()
		} else {
			$('body').removeClass('light-theme');
			localStorage.removeItem("light-theme");
		}
		localStorageBackup();
	});
	
	/*Light Layout End*/

	/*Dark Layout Start*/

	$(document).on("click", '#myonoffswitch2', function () {
		if (this.checked) {
			$('body').addClass('dark-theme');
			$('#myonoffswitch5').prop('checked', true);
            $('#myonoffswitch8').prop('checked', true);
			$('body').removeClass('light-theme');
			$('body').removeClass('transparent-theme');
			$('body').removeClass('light-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('gradient-menu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('dark-header');
			$('body').removeClass('color-header');
			$('body').removeClass('light-header');
		
		
		// remove light theme properties
			localStorage.removeItem('primaryColor')
			localStorage.removeItem('primaryHoverColor')
			localStorage.removeItem('primaryBorderColor')
			localStorage.removeItem('darkPrimary')
			localStorage.removeItem('transparentBgImgPrimary')
			localStorage.removeItem('transparentBgImgprimaryTransparent')
			document.querySelector('html')?.style.removeProperty('--primary-bg-color', localStorage.darkPrimary);
			document.querySelector('html')?.style.removeProperty('--primary-bg-hover', localStorage.darkPrimary);
			document.querySelector('html')?.style.removeProperty('--primary-bg-border', localStorage.darkPrimary);
			document.querySelector('html')?.style.removeProperty('--dark-primary', localStorage.darkPrimary);

			// removing light theme data 
			localStorage.removeItem('primaryColor')
			localStorage.removeItem('primaryHoverColor')
			localStorage.removeItem('primaryBorderColor')
			localStorage.removeItem('primaryTransparent');

			localStorage.removeItem('transparentBgColor');
			localStorage.removeItem('transparentThemeColor');
			localStorage.removeItem('transparentPrimary');

			$('#myonoffswitch3').prop('checked', false);
            $('#myonoffswitch6').prop('checked', false);
            $('#myonoffswitchTransparent').prop('checked', false);
            //
            checkOptions();

			const root = document.querySelector(':root');
			root.style = "";
			names()
		} else {
			$('body').removeClass('dark-theme');
			localStorage.removeItem("dark-theme");
		}
		localStorageBackup()
	});
	
	/*Dark Layout End*/


	/*Transparent Layout Start*/

	$(document).on("click", '#myonoffswitchTransparent', function () {
		if (this.checked) {
			$('body').addClass('transparent-theme');
			$('#myonoffswitch3').prop('checked', false);
            $('#myonoffswitch6').prop('checked', false);
            $('#myonoffswitch5').prop('checked', false);
            $('#myonoffswitch8').prop('checked', false);
			$('body').removeClass('dark-theme');
			$('body').removeClass('light-theme');
		// remove light theme properties
		localStorage.removeItem('primaryColor')
		localStorage.removeItem('primaryHoverColor')
		localStorage.removeItem('primaryBorderColor')

		// reseting the menu and header switcher
		
		document.querySelector('.lightMenu')?.classList.remove('d-none')
		document.querySelector('.lightMenu')?.classList.add('d-flex')
		document.querySelector('.lightHeader')?.classList.remove('d-none')
		document.querySelector('.lightHeader')?.classList.add('d-flex')
		
		document.querySelector('.darkMenu')?.classList.remove('d-none')
		document.querySelector('.darkMenu')?.classList.add('d-flex')
		document.querySelector('.darkHeader')?.classList.remove('d-none')
		document.querySelector('.darkHeader')?.classList.add('d-flex')

		// removing light theme data 
		localStorage.removeItem('darkPrimary');
		localStorage.removeItem('primaryColor')
		localStorage.removeItem('primaryHoverColor')
		localStorage.removeItem('primaryBorderColor')
		localStorage.removeItem('primaryTransparent');
		localStorage.removeItem('transparentPrimary');
		localStorage.removeItem('darkprimaryTransparent');
		localStorage.removeItem('transparentBgImgPrimary')
		localStorage.removeItem('transparentBgImgprimaryTransparent')
 			
		$('#myonoffswitch2').prop('checked', false);
		$('#myonoffswitch1').prop('checked', false);
		$('#myonoffswitchTransparent').prop('checked', true);
		//
		checkOptions();
		removeForTransparent();
		const root = document.querySelector(':root');
			root.style = "";
		names()
		} else {
			$('body').removeClass('transparent-theme');
			localStorage.removeItem("transparent-theme");
		}
		localStorageBackup()
		$('body').removeClass('bg-img1');
		$('body').removeClass('bg-img2');
		$('body').removeClass('bg-img3');
		$('body').removeClass('bg-img4');
	});

	/*Transparent Layout End*/

	/*Transparent Bg-Image Style Start*/
	$(document).on("click", '#bgimage1', function () {
		$('body').addClass('bg-img1');
		$('body').removeClass('bg-img2');
		$('body').removeClass('bg-img3');
		$('body').removeClass('bg-img4');
		$('body').removeClass('light-menu');
		$('body').removeClass('color-menu');
		$('body').removeClass('gradient-menu');
		$('body').removeClass('dark-menu');
		$('body').removeClass('dark-header');
		$('body').removeClass('color-header');
		$('body').removeClass('light-header');
		$('body').removeClass('gradient-header');
			
		document.querySelector('body').classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		document.querySelector('body')?.classList.remove('dark-theme');

		$('#myonoffswitch2').prop('checked', false);
		$('#myonoffswitch1').prop('checked', false);
		$('#myonoffswitchTransparent').prop('checked', true);
        checkOptions();
		removeForTransparent();
	})

	$(document).on("click", '#bgimage2', function () {
		$('body').addClass('bg-img2');
		$('body').removeClass('bg-img1');
		$('body').removeClass('bg-img3');
		$('body').removeClass('bg-img4');
		$('body').removeClass('light-menu');
		$('body').removeClass('color-menu');
		$('body').removeClass('gradient-menu');
		$('body').removeClass('dark-menu');
		$('body').removeClass('dark-header');
		$('body').removeClass('color-header');
		$('body').removeClass('light-header');
		$('body').removeClass('gradient-header');
			
		document.querySelector('body').classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		document.querySelector('body')?.classList.remove('dark-theme');

		$('#myonoffswitch2').prop('checked', false);
		$('#myonoffswitch1').prop('checked', false);
		$('#myonoffswitchTransparent').prop('checked', true);
        checkOptions();
		removeForTransparent();
	})

	$(document).on("click", '#bgimage3', function () {
		$('body').addClass('bg-img3');
		$('body').removeClass('bg-img1');
		$('body').removeClass('bg-img2');
		$('body').removeClass('bg-img4');
		$('body').removeClass('light-menu');
		$('body').removeClass('color-menu');
		$('body').removeClass('gradient-menu');
		$('body').removeClass('dark-menu');
		$('body').removeClass('dark-header');
		$('body').removeClass('color-header');
		$('body').removeClass('light-header');
		$('body').removeClass('gradient-header');
			
		document.querySelector('body').classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		document.querySelector('body')?.classList.remove('dark-theme');

		$('#myonoffswitch2').prop('checked', false);
		$('#myonoffswitch1').prop('checked', false);
		$('#myonoffswitchTransparent').prop('checked', true);
        checkOptions();
		removeForTransparent();
	})

	$(document).on("click", '#bgimage4', function () {
		$('body').addClass('bg-img4');
		$('body').removeClass('bg-img1');
		$('body').removeClass('bg-img2');
		$('body').removeClass('bg-img3');
		$('body').removeClass('light-menu');
		$('body').removeClass('color-menu');
		$('body').removeClass('gradient-menu');
		$('body').removeClass('dark-menu');
		$('body').removeClass('dark-header');
		$('body').removeClass('color-header');
		$('body').removeClass('light-header');
		$('body').removeClass('gradient-header');
				
		document.querySelector('body').classList.add('transparent-theme');
		document.querySelector('body')?.classList.remove('light-theme');
		document.querySelector('body')?.classList.remove('dark-theme');
		$('#myonoffswitch2').prop('checked', false);
		$('#myonoffswitch1').prop('checked', false);
		$('#myonoffswitchTransparent').prop('checked', true);
        checkOptions();
		removeForTransparent();
	})
	/*Transparent Bg-Image Style End*/

	/*Light Menu Start*/
    $(document).on("click", '#myonoffswitch3', function() {
        if (this.checked) {
            $('body').addClass('light-menu');
            $('body').removeClass('color-menu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('gradient-menu');
            localStorage.setItem("light-menu", "True");
        } else {
            $('body').removeClass('light-menu');
            localStorage.setItem("light-menu", "false");
        }
    });
	/*Light Menu End*/

    /*Color Menu Start*/
    $(document).on("click", '#myonoffswitch4', function() {
        if (this.checked) {
            $('body').addClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('gradient-menu');
            localStorage.setItem("color-menu", "True");
        } else {
            $('body').removeClass('color-menu');
            localStorage.setItem("color-menu", "false");
        }
    });
	/*Color Menu End*/

    /*Dark Menu Start*/
    $(document).on("click", '#myonoffswitch5', function() {
        if (this.checked) {
            $('body').addClass('dark-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('gradient-menu');
            localStorage.setItem("dark-menu", "True");
        } else {
            $('body').removeClass('dark-menu');
            localStorage.setItem("dark-menu", "false");
        }
    });
	/*Dark Menu End*/

	/*Gradient Menu Start*/
    $(document).on("click", '#myonoffswitch25', function() {
        if (this.checked) {
            $('body').addClass('gradient-menu');
            $('body').removeClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('dark-menu');
            localStorage.setItem("gradient-menu", "True");
        } else {
            $('body').removeClass('gradient-menu');
            localStorage.setItem("gradient-menu", "false");
        }
    });
	/*Gradient Menu End*/

	/*Light Header Start*/
    $(document).on("click", '#myonoffswitch6', function() {
        if (this.checked) {
            $('body').addClass('light-header');
			$('body').removeClass('color-header');
			$('body').removeClass('dark-header');
			$('body').removeClass('gradient-header');
            localStorage.setItem("light-header", "True");
        } else {
            $('body').removeClass('light-header');
            localStorage.setItem("light-header", "false");
        }
    });
	/*Light Header End*/

	/*Color Header Start*/
    $(document).on("click", '#myonoffswitch7', function() {
        if (this.checked) {
            $('body').addClass('color-header');
			$('body').removeClass('light-header');
			$('body').removeClass('dark-header');
			 $('body').removeClass('gradient-header');
            localStorage.setItem("color-header", "True");
        } else {
            $('body').removeClass('color-header');
            localStorage.setItem("color-header", "false");
        }
    });
	/*Color Header End*/

	/*Dark Header Start*/
    $(document).on("click", '#myonoffswitch8', function() {
        if (this.checked) {
            $('body').addClass('dark-header');
			$('body').removeClass('color-header');
			$('body').removeClass('light-header');
			 $('body').removeClass('gradient-header');
            localStorage.setItem("dark-header", "True");
        } else {
            $('body').removeClass('dark-header');
            localStorage.setItem("dark-header", "false");
        }
    });
	/*Dark Header End*/

	/*Gradient Header Start*/
    $(document).on("click", '#myonoffswitch26', function() {
        if (this.checked) {
            $('body').addClass('gradient-header');
			$('body').removeClass('dark-header');
			$('body').removeClass('color-header');
			$('body').removeClass('light-header');
            localStorage.setItem("gradient-header", "True");
        } else {
            $('body').removeClass('gradient-header');
            localStorage.setItem("gradient-header", "false");
        }
    });
	/*Gradient Header End*/

	/*Full Width Layout Start*/
	$(document).on("click", '#myonoffswitch9', function() {
        if (this.checked) {
            $('body').addClass('layout-fullwidth');
			scrollWidthChecker();
			$('body').removeClass('layout-boxed');
            localStorage.setItem("layout-fullwidth", "True");
        } else {
            $('body').removeClass('layout-fullwidth');
            localStorage.setItem("layout-fullwidth", "false");
        }
    });
	/*Full Width Layout End*/

	/*Boxed Layout Start*/
	$(document).on("click", '#myonoffswitch10', function() {
        if (this.checked) {
            $('body').addClass('layout-boxed');
			scrollWidthChecker();
			$('body').removeClass('layout-fullwidth');
            localStorage.setItem("layout-boxed", "True");
        } else {
            $('body').removeClass('layout-boxed');
            localStorage.setItem("layout-boxed", "false");
        }
    });
	/*Boxed Layout End*/

	/*Header-Position Styles Start*/
	$(document).on("click", '#myonoffswitch11', function() {
        if (this.checked) {
            $('body').addClass('fixed-layout');
			$('body').removeClass('scrollable-layout');
            localStorage.setItem("fixed-layout", "True");
        } else {
            $('body').removeClass('fixed-layout');
            localStorage.setItem("fixed-layout", "false");
        }
    });
	$(document).on("click", '#myonoffswitch12', function() {
        if (this.checked) {
            $('body').addClass('scrollable-layout');
			$('body').removeClass('fixed-layout');
            localStorage.setItem("scrollable-layout", "True");
        } else {
            $('body').removeClass('scrollable-layout');
            localStorage.setItem("scrollable-layout", "false");
        }
    });
	/*Header-Position Styles End*/

	
	/*Default Sidemenu Start*/
    $(document).on("click", '#myonoffswitch13', function() {
        if (this.checked) {
			$('body').addClass('default-menu');
			hovermenu();
			$('body').removeClass('closed-menu');
			$('body').removeClass('icontext-menu');
			$('body').removeClass('sideicon-menu');
			$('body').removeClass('sidenav-toggled');
			$('body').removeClass('hover-submenu');
			$('body').removeClass('hover-submenu1');
        } else {
            $('body').removeClass('default-menu');
        }
    });
	/*Default Sidemenu End*/

	
	/*Closed Sidemenu Start*/
    $(document).on("click", '#myonoffswitch30', function() {
        if (this.checked) {
			$('body').addClass('closed-menu');
			hovermenu();
			$('body').addClass('sidenav-toggled');
			$('body').removeClass('default-menu');
			$('body').removeClass('icontext-menu');
			$('body').removeClass('sideicon-menu');
			$('body').removeClass('hover-submenu');
			$('body').removeClass('hover-submenu1');
        } else {
            $('body').removeClass('default-menu');
			$('body').removeClass('sidenav-toggled');
        }
    });
	/*Closed Sidemenu End*/


	/*Hover Submenu Start*/
    $(document).on("click", '#myonoffswitch32', function() {
        if (this.checked) {
			$('body').addClass('hover-submenu');
			hovermenu();
			$('body').addClass('sidenav-toggled');
			$('body').removeClass('default-menu');
			$('body').removeClass('icontext-menu');
			$('body').removeClass('sideicon-menu');
			$('body').removeClass('closed-menu');
			$('body').removeClass('hover-submenu1');
        } else {
            $('body').removeClass('hover-submenu');
			$('body').removeClass('sidenav-toggled');
        }
    });
	/*Hover Submenu End*/

	/*Hover Submenu 1 Start*/
    $(document).on("click", '#myonoffswitch33', function() {
        if (this.checked) {
			$('body').addClass('hover-submenu1');
			hovermenu();
			$('body').addClass('sidenav-toggled');
			$('body').removeClass('default-menu');
			$('body').removeClass('icontext-menu');
			$('body').removeClass('sideicon-menu');
			$('body').removeClass('closed-menu');
			$('body').removeClass('hover-submenu');
        } else {
            $('body').removeClass('hover-submenu1');
			$('body').removeClass('sidenav-toggled');
        }
    });
	/*Hover Submenu 1 End*/

	
	/*Icon Text Sidemenu Start*/
	$(document).on("click", '#myonoffswitch14', function() {
        if (this.checked) {
			$('body').addClass('icontext-menu');
			icontext();
			$('body').addClass('sidenav-toggled');
			$('body').removeClass('default-menu');
			$('body').removeClass('sideicon-menu');
			$('body').removeClass('closed-menu');
			$('body').removeClass('hover-submenu');
			$('body').removeClass('hover-submenu1');

        } else {
            $('body').removeClass('icontext-menu');
			$('body').removeClass('sidenav-toggled');
        }
    });
	/*Icon Text Sidemenu End*/
	
	/*Icon Overlay Sidemenu Start*/
	$(document).on("click", '#myonoffswitch15', function() {
        if (this.checked) {
            $('body').addClass('sideicon-menu');
			hovermenu();
            $('body').addClass('sidenav-toggled');
			$('body').removeClass('default-menu');
			$('body').removeClass('icontext-menu');
			$('body').removeClass('closed-menu');
			$('body').removeClass('hover-submenu');
			$('body').removeClass('hover-submenu1');
        } else {
            $('body').removeClass('sideicon-menu');
			$('body').removeClass('sidenav-toggled');
        }
    });
	/*Icon Overlay Sidemenu End*/
	$(document).on("click", '#myonoffswitch34', function() {
        if (this.checked) {
			$('body').removeClass('horizontal');
			$('body').removeClass('horizontal-hover');
			$(".main-content").removeClass("horizontal-content");
			$(".main-content").addClass("app-content");
			$(".main-container").removeClass("container");
			$(".main-container").addClass("container-fluid");
			$(".main-header").removeClass("hor-header");
			$(".main-header").addClass("side-header");
			$(".app-sidebar").removeClass("horizontal-main")
			$(".main-sidemenu").removeClass("container")
			$('#slide-left').removeClass('d-none');
			$('#slide-right').removeClass('d-none');
			$('body').addClass('sidebar-mini');
			localStorage.setItem("sidebar-mini", "True");
			menuClick();
        } else {
            $('body').removeClass('sidebar-mini');
			localStorage.setItem("sidebar-mini", "False");
        }
    });

	$(document).on("click", '#myonoffswitch35', function() {
        if (this.checked) {
			
			$('body').addClass('horizontal');
			$(".main-content").addClass("horizontal-content");
			$(".main-content").removeClass("app-content");
			$(".main-container").addClass("container");
			$(".main-container").removeClass("container-fluid");
			$(".main-header").addClass("hor-header");
			$(".main-header").removeClass("side-header");
			$(".app-sidebar").addClass("horizontal-main")
			$(".main-sidemenu").addClass("container")
			$('body').removeClass('sidebar-mini');
			$('body').removeClass('sidenav-toggled');
			$('#slide-left').removeClass('d-none');
			$('#slide-right').removeClass('d-none');
			$('body').removeClass('horizontal-hover');
			$('body').removeClass('closed-menu');
			$('body').removeClass('hover-submenu');
			$('body').removeClass('hover-submenu1');
			$('body').removeClass('icontext-menu');
			$('body').removeClass('sideicon-menu');
			localStorage.setItem("horizantal", "True");
			document.querySelector('.horizontal .side-menu').style.flexWrap = 'noWrap'
			menuClick();
			scrollWidthChecker();
			checkForArrows();
        } else {
            $('body').removeClass('horizontal');
			localStorage.setItem("horizontal", "False");
        }
    });

	$(document).on("click", '#myonoffswitch111', function() {
        if (this.checked) {
			let li = document.querySelectorAll('.side-menu li')
			li.forEach((e,i)=>{
				e.classList.remove('is-expanded')
			})
			var animationSpeed = 300;
			// first level
			var parent = $("[data-bs-toggle='sub-slide']").parents('ul');
			var ul = parent.find('ul:visible').slideUp(animationSpeed);
			ul.removeClass('open');
			var parent1 = $("[data-bs-toggle='sub-slide2']").parents('ul');
			var ul1 = parent1.find('ul:visible').slideUp(animationSpeed);
			ul1.removeClass('open');
			// let dd = document.querySelectorAll('.sub-slide-menu');
			// dd.forEach((e,i)=>{
			// 	let eVal = e.classList[0]
			// 	var ul1 = $('.'+eVal).find('ul:visible').slideUp(animationSpeed);
			// 	ul1.removeClass('open');
			// })

			$('body').addClass('horizontal-hover');
			$('body').addClass('horizontal');
			let subNavSub = document.querySelectorAll('.sub-slide-menu');
			subNavSub.forEach((e) => {
				e.style.display = '';
			})
			let subNav = document.querySelectorAll('.slide-menu')
			subNav.forEach((e) => {
				e.style.display = '';
			})
			// $('#slide-left').addClass('d-none');
			// $('#slide-right').addClass('d-none');
			document.querySelector('.horizontal .side-menu').style.flexWrap = 'nowrap'
			$(".main-content").addClass("horizontal-content");
			$(".main-content").removeClass("app-content");
			$(".main-container").addClass("container");
			$(".main-container").removeClass("container-fluid");
			$(".main-header").addClass("hor-header");
			$(".main-header").removeClass("side-header");
			$(".app-sidebar").addClass("horizontal-main")
			$(".main-sidemenu").addClass("container")
			$('body').removeClass('sidebar-mini');
			$('body').removeClass('sidenav-toggled');
			$('body').removeClass('closed-menu');
			$('body').removeClass('hover-submenu');
			$('body').removeClass('hover-submenu1');
			$('body').removeClass('icontext-menu');
			$('body').removeClass('sideicon-menu');
			HorizontalHovermenu();
			checkForArrows();
        } else {
        }
    });
	
	$(document).on("click", '#myonoffswitch55', function() {
        if (this.checked) {
            $('body').addClass('rtl');
            $('body').removeClass('ltr');
			$("html[lang=en]").attr("dir", "rtl");
            localStorage.setItem("rtl", "True");
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.rtl.min.css"));

			var carousel = $('.owl-carousel');
				$.each(carousel ,function( index, element)  {
				// element == this
				var carouselData = $(element).data('owl.carousel');
				carouselData.settings.rtl = true; //don't know if both are necessary
				carouselData.options.rtl = true;
				$(element).trigger('refresh.owl.carousel');
			});
        } else {
            $('body').removeClass('rtl');
            $('body').addClass('ltr');
            localStorage.setItem("rtl", "false"); 
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.min.css"));
         }
    });
	
	$(document).on("click", '#myonoffswitch54', function() {
	
        if (this.checked) {
            $('body').addClass('ltr');
            $('body').removeClass('rtl');
			$("html[lang=en]").attr("dir", "ltr");
            localStorage.setItem("ltr", "True");
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.min.css"));
			var carousel = $('.owl-carousel');
			$.each(carousel ,function( index, element)  {
			// element == this
			var carouselData = $(element).data('owl.carousel');
			carouselData.settings.rtl = false; //don't know if both are necessary
			carouselData.options.rtl = false;
			$(element).trigger('refresh.owl.carousel');

		
		});
        } else {
            $('body').removeClass('ltr');
            $('body').addClass('rtl');
            localStorage.setItem("ltr", "false"); 
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.rtl.min.css"));
         }
    });
		
		// $(document).ready (function(){
		// 	let bodyRtl = $('body').hasClass('rtl');
		// 	if (bodyRtl) {
		// 			$('body').addClass('rtl');
		// 			localStorage.setItem("rtl", "True");
		// 			$("head link#style").attr("href", $(this));
		// 			(document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.rtl.min.css"));
		// 		} 
		// 		else {
		// 			$('body').removeClass('rtl');
		// 			localStorage.setItem("rtl", "false");
		// 			$("head link#style").attr("href", $(this));
		// 			(document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.min.css"));
		// 		};
		// });
		// $('body').addClass('horizontal');
		(function(){
			let bodyhorizontal = $('body').hasClass('horizontal');
			if (bodyhorizontal) {
				$('body').addClass('horizontal');
				$(".main-content").addClass("horizontal-content");
				$(".main-content").removeClass("app-content");
				$(".main-container").addClass("container");
				$(".main-container").removeClass("container-fluid");
				$(".main-header").addClass("hor-header");
				$(".main-header").removeClass("side-header");
				$(".app-sidebar").addClass("horizontal-main")
				$(".main-sidemenu").addClass("container")
				$('body').removeClass('sidebar-mini');
			} 
		})();
	
		
	
	
});

function resetData() {
	$('#myonoffswitch1').prop('checked', true);
	$('#myonoffswitch3').prop('checked', true);
	$('#myonoffswitch6').prop('checked', true);
	$('#myonoffswitch9').prop('checked', true);
	$('#myonoffswitch10').prop('checked', false);
	$('#myonoffswitch11').prop('checked', true);
	$('#myonoffswitch12').prop('checked', false);
	$('#myonoffswitch13').prop('checked', true);
	$('#myonoffswitch14').prop('checked', false);
	$('#myonoffswitch15').prop('checked', false);
	$('#myonoffswitch16').prop('checked', false);
	$('#myonoffswitch17').prop('checked', false);
	$('#myonoffswitch18').prop('checked', false);
	$('body')?.removeClass('bg-img4');
	$('body')?.removeClass('bg-img1');
	$('body')?.removeClass('bg-img2');
	$('body')?.removeClass('bg-img3');
	$('body')?.removeClass('transparent-theme');
	$('body')?.removeClass('dark-theme');
	$('body')?.removeClass('dark-menu');
	$('body')?.removeClass('light-menu');
	$('body')?.removeClass('color-menu');
	$('body')?.removeClass('dark-header');
	$('body')?.removeClass('light-header');
	$('body')?.removeClass('color-header');
	$('body')?.removeClass('layout-boxed');
	$('body')?.removeClass('icontext-menu');
	$('body')?.removeClass('sideicon-menu');
	$('body')?.removeClass('closed-menu');
	$('body')?.removeClass('hover-submenu');
	$('body')?.removeClass('hover-submenu1');
	$('body')?.removeClass('scrollable-layout');
	$('body')?.removeClass('sidenav-toggled');
}

function checkOptions(){
    // light header 
    if( document.querySelector('body').classList.contains('header-light')){
        $('#myonoffswitch6').prop('checked', true);
    }
    // color header 
    if(document.querySelector('body').classList.contains('color-header')){
        $('#myonoffswitch7').prop('checked', true);
    }
    // gradient header 
    if(document.querySelector('body').classList.contains('gradient-header')){
        $('#myonoffswitch26').prop('checked', true);
    }
    // dark header 
    if(document.querySelector('body').classList.contains('dark-header')){
        $('#myonoffswitch8').prop('checked', true);
    }

    // light menu
    if(document.querySelector('body').classList.contains('light-menu')){
        $('#myonoffswitch3').prop('checked', true);
    }
    // color menu
    if(document.querySelector('body').classList.contains('color-menu')){
        $('#myonoffswitch4').prop('checked', true);
    }
    // gradient menu
    if(document.querySelector('body').classList.contains('gradient-menu')) {
        $('#myonoffswitch25').prop('checked', true);
    }
    // dark menu
    if(document.querySelector('body').classList.contains('dark-menu')) {
        $('#myonoffswitch5').prop('checked', true);
    }
}

function removeForTransparent(){
	if( document.querySelector('body').classList.contains('header-light')){
        document.querySelector('body').classList.remove('header-light')
    }
    // color header 
    if(document.querySelector('body').classList.contains('color-header')){
        document.querySelector('body').classList.remove('color-header')
    }
    // gradient header 
    if(document.querySelector('body').classList.contains('gradient-header')){
        document.querySelector('body').classList.remove('gradient-header')
    }
    // dark header 
    if(document.querySelector('body').classList.contains('dark-header')){
        document.querySelector('body').classList.remove('dark-header')
    }

    // light menu
    if(document.querySelector('body').classList.contains('light-menu')){
        document.querySelector('body').classList.remove('light-menu')
    }
    // color menu
    if(document.querySelector('body').classList.contains('color-menu')){
        document.querySelector('body').classList.remove('color-menu')
    }
    // gradient menu
    if(document.querySelector('body').classList.contains('gradient-menu')) {
        document.querySelector('body').classList.remove('gradient-menu')
    }
    // dark menu
    if(document.querySelector('body').classList.contains('dark-menu')) {
        document.querySelector('body').classList.remove('dark-menu')
    }
}