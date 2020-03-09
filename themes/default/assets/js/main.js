$(document).ready(function() {

	$('a[data-href]').on('click',function(e){
		e.preventDefault();
		var $link = e.target.href.replace(site_url+'/','');
		$("html, body").animate({ scrollTop: $($link).offset().top }, 1000);
	});

    $(".accordion-title").tooggleAccordion();
    $(".toTop")["goto"](null, 800);
    $("#goHome")["goto"]("body", 800);
    $(".toFirst")["goto"]("#first", 800);
    $(window).scroll(function() {
        $(this).scrollTop() ? $(".toTop:hidden").stop(!0, !0).fadeIn() : $(".toTop").stop(!0, !0).fadeOut()
    })

    $('.menu-burger').on('click',function(){
    	$('.menu-burger').toggleClass('is-active');
    });

    // init animations
    AOS.init();
});