
var ww = document.body.clientWidth;

$(document).ready(function() {
	$(".mobilemenu .nav li a").each(function() {
		if ($(this).next().length > 0) {
			$(this).addClass("parent");
		};
	})
	
	$(".mobilemenu .toggleMenu").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".nav").toggle();
	});
	adjustMenu();
})

$(window).bind('resize orientationchange', function() {
	ww = document.body.clientWidth;
	adjustMenu();
});

var adjustMenu = function() {
	if (ww < 991) {
		$(".mobilemenu .toggleMenu").css("display", "inline-block");
		if (!$(".mobilemenu .toggleMenu").hasClass("active")) {
			$(".mobilemenu .nav").hide();
		} else {
			$(".mobilemenu .nav").show();
		}
		$(".mobilemenu .nav li").unbind('mouseenter mouseleave');
		$(".mobilemenu .nav li a.parent").unbind('click').bind('click', function(e) {
			// must be attached to anchor element to prevent bubbling
			e.preventDefault();
			$(this).parent("li").toggleClass("hover");
		});
	} 
	else if (ww >= 991) {
		$(".mobilemenu .toggleMenu").css("display", "none");
		$(".mobilemenu .nav").show();
		$(".mobilemenu .nav li").removeClass("hover");
		$(".mobilemenu .nav li a").unbind('click');
		$(".mobilemenu .nav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
		 	// must be attached to li so that mouseleave is not triggered when hover over submenu
		 	$(this).toggleClass('hover');
		});
	}
}

