(function () {
	"use strict";

	var slideMenu = $('.side-menu');

	// Toggle Sidebar
	$(document).on('click', '[data-bs-toggle="sidebar"]', function (event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});


	var toggleSidebar = function () {
		var w = $(window);
		if (w.outerWidth() <= 1024) {
			$("body").addClass("sidebar-gone");
			$(document).off("click", "body").on("click", "body", function (e) {
				if ($(e.target).hasClass('sidebar-show') || $(e.target).hasClass('search-show')) {
					$("body").removeClass("sidebar-show");
					$("body").addClass("sidebar-gone");
					$("body").removeClass("search-show");
				}
			});
		} else {
			$("body").removeClass("sidebar-gone");
		}
	}
	toggleSidebar();
	$(window).resize(toggleSidebar);

	//p-scroll
	const ps1 = new PerfectScrollbar('.app-sidebar', {
		useBothWheelAxes: true,
		suppressScrollX: true,
	});



	//sticky-header
	$(window).on("scroll", function (e) {
		if ($(window).scrollTop() >= 70) {
			$('.main-header').addClass('fixed-header');
			$('.main-header').addClass('visible-title');
		}
		else {
			$('.main-header').removeClass('fixed-header');
			$('.main-header').removeClass('visible-title');
		}
	});
})();

function hovermenu() {

	$(".app-sidebar").hover(function () {
		if ($('.app').hasClass('sidenav-toggled')) {
			$('.app').addClass('sidenav-toggled-open');
		}
	}, function () {
		if ($('.app').hasClass('sidenav-toggled')) {
			$('.app').removeClass('sidenav-toggled-open');
		}
	});
}

// ______________ICON-TEXT JS start
function icontext() {
	$(".app-sidebar").off("mouseenter mouseleave");

	$(document).on('click', ".app-sidebar", function (event) {
		if ($('body').hasClass('sidenav-toggled') == true) {
			$('body').addClass('sidenav-toggled-open');
		}
	});

	$(document).on('click', ".main-content", function (event) {
		$('body').removeClass('sidenav-toggled-open');
	});
}

//________________Horizontal js
jQuery(function () {
	'use strict';
	document.addEventListener("touchstart", function () { }, false);
	jQuery(function () {
		jQuery('body').wrapInner('<div class="horizontalMenucontainer" />');
	});
}());



let slideLeft = document.querySelector(".slide-left");
let slideRight = document.querySelector(".slide-right");
slideLeft?.addEventListener("click", () => {
	slideClick()
}, true)
slideRight?.addEventListener("click", () => { slideClick() }, true)
function slideClick() {
	let slide = document.querySelectorAll(".slide");
	let slideMenu = document.querySelectorAll(".slide-menu");
	slide.forEach((element, index) => {
		if (element.classList.contains("is-expanded") == true) {
			element.classList.remove("is-expanded")
		}
	});
	slideMenu.forEach((element, index) => {
		if (element.classList.contains("open") == true) {
			element.classList.remove("open");
			element.style.display = "none";
		}
	});
}


// horizontal arrows
var sideMenu = $(".side-menu");
var slide = "0px";
var leftsideLimit = -100;

// get horizontal width
var horizontalMenu = function () {
	return $('.horizontal-main').innerWidth();
}
var HorizontalWidthSize = horizontalMenu();

var slideLimit;

function horizontalMenuLimit() {
	HorizontalWidthSize = horizontalMenu()
	if ((HorizontalWidthSize) >= '1600') {
		slideLimit = -400
	}
	else if ((HorizontalWidthSize) >= '1150') {
		slideLimit = -600
	}
	else if ((HorizontalWidthSize) >= '1050') {
		slideLimit = -800
	}
	else if ((HorizontalWidthSize) >= '768') {
		slideLimit = -1000
	} else {
		slideLimit = -500
	}
}
horizontalMenuLimit();

function scrollWidthChecker() {
	let appsidebarValue = document.querySelector(".app-sidebar").scrollWidth;
	let appSidebarvlauediv15per = appsidebarValue / 15
	let sidemenuValue = document.querySelector('.side-menu').scrollWidth;
	if (appsidebarValue - appSidebarvlauediv15per >= sidemenuValue) {
		$("#slide-left").addClass("d-none");
		$("#slide-right").addClass("d-none");
	}
	else {
		$("#slide-right").removeClass("d-none");
		$("#slide-left").removeClass("d-none");
	}
}
scrollWidthChecker();

$(window).resize(
	() => {
		HorizontalWidthSize = horizontalMenu()
		horizontalMenuLimit()
		scrollWidthChecker();
		HorizontalHovermenu();
		if (window.innerWidth >= 768) {
			if (document.querySelector("body").classList.contains("sidenav-toggled") && document.querySelector("body").classList.contains("horizontal")) {
				document.querySelector("body").classList.remove("sidenav-toggled")
			}
		}
	}
)

function checkForArrows(){
	setTimeout(()=>{
		let appsidebarValue = document.querySelector(".app-sidebar").scrollWidth;
		let appSidebarvlauediv15per = appsidebarValue / 15
		let sidemenuValue = document.querySelector('.side-menu').scrollWidth;
		if (appsidebarValue - appSidebarvlauediv15per >= sidemenuValue) {
			$("#slide-left").addClass("d-none");
			$("#slide-right").addClass("d-none");
		}
		else {
			$("#slide-right").removeClass("d-none");
			$("#slide-left").removeClass("d-none");
		}
	}, 600);
}

$("#slide-left").addClass("d-none");

$(document).on("click", ".ltr #slide-left", function () {
	horizontalMenuLimit()
	var currentPosition = parseInt(sideMenu.css("marginLeft"));
	if (currentPosition < 0) {
		slide = '100px';
		$("#slide-right").removeClass("d-none");
		sideMenu.stop(false, true).animate({
			marginLeft: "+=" + slide
		}, {
			duration: 400
		})
		if (currentPosition >= leftsideLimit) {
			$("#slide-left").addClass("d-none");
		}
	}
	horizontalMenuLimit()
});
$(document).on("click", ".ltr #slide-right", function () {
	horizontalMenuLimit();
	slide = '100px'
	var currentPosition = parseInt(sideMenu.css("marginLeft"));
	if (currentPosition >= slideLimit) {
		$("#slide-left").removeClass("d-none");
		sideMenu.stop(false, true).animate({
			marginLeft: "-=" + slide
		}, {
			duration: 400
		})
	}
	if (currentPosition <= slideLimit) {
		$("#slide-right").addClass("d-none");
	}
});

$(document).on("click", ".rtl #slide-left", function () {
	horizontalMenuLimit()
	slide = '100px';
	var currentPosition = parseInt(sideMenu.css("marginRight"));
	console.log(currentPosition);
	if (currentPosition <= 0) {
		$("#slide-right").removeClass("d-none");
		sideMenu.stop(false, true).animate({
			// marginLeft : 0,
			marginRight: "+=" + slide
		}, {
			duration: 400
		})
		if (currentPosition >= leftsideLimit) {
			$("#slide-left").addClass("d-none");
		}
	}
});
$(document).on("click", ".rtl #slide-right", function () {
	horizontalMenuLimit();
	slide = '100px';
	var currentPosition = parseInt(sideMenu.css("marginRight"));
	if (currentPosition >= slideLimit) {
		$("#slide-left").removeClass("d-none");
		sideMenu.stop(false, true).animate({
			marginLeft: 0,
			marginRight: "-=" + slide
		}, {
			duration: 400
		})
	}
	if (currentPosition <= slideLimit) {
		$("#slide-right").addClass("d-none");
	}
});


function menuClick() {
	$("[data-bs-toggle='slide']").off('click');
	$("[data-bs-toggle='sub-slide']").off('click')
	$("[data-bs-toggle='sub-slide2']").off('click')
	$("[data-bs-toggle='slide']").on('click', function (e) {
		var $this = $(this);
		var checkElement = $this.next();
		var animationSpeed = 300,
			slideMenuSelector = '.slide-menu';
		if (checkElement.is(slideMenuSelector) && checkElement.is(':visible')) {
			checkElement.slideUp(animationSpeed, function () {
				checkElement.removeClass('open');
			});
			checkElement.parent("li").removeClass("is-expanded");
		}
		else if ((checkElement.is(slideMenuSelector)) && (!checkElement.is(':visible'))) {
			var parent = $this.parents('ul').first();
			var ul = parent.find('ul:visible').slideUp(animationSpeed);
			ul.removeClass('open');
			var parent_li = $this.parent("li");
			checkElement.slideDown(animationSpeed, function () {
				checkElement.addClass('open');
				parent.find('li.is-expanded').removeClass('is-expanded');
				parent_li.addClass('is-expanded');
			});
		}
		if (checkElement.is(slideMenuSelector)) {
			e.preventDefault();
		}
	});

	// Activate sidebar slide toggle
	$("[data-bs-toggle='sub-slide']").on('click', function (e) {
		var $this = $(this);
		var checkElement = $this.next();
		var animationSpeed = 300,
			slideMenuSelector = '.sub-slide-menu';
		if (checkElement.is(slideMenuSelector) && checkElement.is(':visible')) {
			checkElement.slideUp(animationSpeed, function () {
				checkElement.removeClass('open');
			});
			checkElement.parent("li").removeClass("is-expanded");
		}
		else if ((checkElement.is(slideMenuSelector)) && (!checkElement.is(':visible'))) {
			var parent = $this.parents('ul').first();
			var ul = parent.find('ul:visible').slideUp(animationSpeed);
			ul.removeClass('open');
			var parent_li = $this.parent("li");
			checkElement.slideDown(animationSpeed, function () {
				checkElement.addClass('open');
				parent.find('li.is-expanded').removeClass('is-expanded');
				parent_li.addClass('is-expanded');
			});
		}
		if (checkElement.is(slideMenuSelector)) {
			e.preventDefault();
		}
	});

	// Activate sidebar slide toggle
	$("[data-bs-toggle='sub-slide2']").on('click', function (e) {
		var $this = $(this);
		var checkElement = $this.next();
		var animationSpeed = 300,
			slideMenuSelector = '.sub-slide-menu1';
		if (checkElement.is(slideMenuSelector) && checkElement.is(':visible')) {
			checkElement.slideUp(animationSpeed, function () {
				checkElement.removeClass('open');
			});
			checkElement.parent("li").removeClass("is-expanded");
		}
		else if ((checkElement.is(slideMenuSelector)) && (!checkElement.is(':visible'))) {
			var parent = $this.parents('ul').first();
			var ul = parent.find('ul:visible').slideUp(animationSpeed);
			ul.removeClass('open');
			var parent_li = $this.parent("li");
			checkElement.slideDown(animationSpeed, function () {
				checkElement.addClass('open');
				parent.find('li.is-expanded').removeClass('is-expanded');
				parent_li.addClass('is-expanded');
			});
		}
		if (checkElement.is(slideMenuSelector)) {
			e.preventDefault();
		}
	});
}

function HorizontalHovermenu() {
	let value = document.querySelector('body').classList.contains('horizontal-hover')
	if (value && window.innerWidth >= 768) {
		$("[data-bs-toggle='slide']").off('click');
		$("[data-bs-toggle='sub-slide']").off('click')
		$("[data-bs-toggle='sub-slide2']").off('click')
		slideClick()
	}
	else {
		menuClick();
	}
}
HorizontalHovermenu();

// for Icon-text Menu
//icontext(); 

// default layout
hovermenu();

// To remove expanded menu on click 'body'
$(document).on('click', '.horizontal-content', function () {
	$(".app-sidebar li a").each(function () {
		$(this).next().slideUp(300, function () {
			$(this).next().removeClass('open');
		});
		$(this).parent("li").removeClass("is-expanded");
	})
})



// ______________Active Class
var position = window.location.pathname.split('/');
$(".app-sidebar li a").each(function () {
	var $this = $(this);
	var pageUrl = $this.attr("href");

	if (pageUrl) {
		if (position[position.length - 1] == pageUrl) {
			$(this).addClass("active");
			$(this).parent().prev().addClass("active"); // add active to li of the current link
			$(this).parent().parent().prev().addClass("active"); // add active class to an anchor
			$(this).parent().parent().parent().parent().prev().addClass("active");
			$(this).parent().parent().parent().parent().parent().addClass("is-expanded");
			$(this).parent().parent().prev().click(); // click the item to make it drop
			return false;
		}
	}
});
if ($('.slide-item').hasClass('active')) {
	$('.app-sidebar').animate({
		scrollTop: $('a.slide-item.active').offset().top - 600
	}, 600);
}
if ($('.sub-side-menu__item').hasClass('active')) {
	$('.app-sidebar').animate({
		scrollTop: $('a.sub-side-menu__item.active').offset().top - 600
	}, 600);
}