$(function() {

    $('.home').css('margin-top',$('.navbar').outerHeight());

    AOS.init({
		duration: 800
	});

    $('.slider').slick({
		autoplay: true,
		dots: true,
		infinite: true,
		speed: 500,
		slidesToShow: 1
    });

// First we get the viewport height and we multiple it by 1% to get a value for a vh unit
let vh = window.innerHeight * 0.01;
// Then we set the value in the --vh custom property to the root of the document
document.documentElement.style.setProperty('--vh', `${vh}px`);

    $('section').each(function(i) {
        /* var alturaPadre = $(this).outerHeight();
        var alturaHijo = $(this).find('.hijo').outerHeight();
        if (alturaHijo <= alturaPadre) {
            $(this).find('.hijo').addClass('fijo');
        } */
        if (!$(this).hasClass('contacto')) {
            $(this).css('height', $(this).outerWidth() * 9 / 16);
        }
    });

    $('.menu-item').on('click', function(e) {
		e.preventDefault();
        var target = $(this).attr('href');
        $('.navbar-collapse').collapse('hide');
		$("html, body").animate({ 
			scrollTop: $('.'+target).offset().top - 78
		}, 500);
    });
    
    $(window).scroll(function() {
        if ($(window).width() > 767) {
            if ($(".navbar").offset().top > 10) {
                $(".navbar").css("background", "#fff");
            } else {
                $(".navbar").css("background", "transparent");
            }
        }
    });

	$("#contact-form").submit(function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		var form = $(this);
		var url = form.attr('action');
		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(), // serializes the form's elements.
		})
		.done(function(data) {
			$('.alert').addClass('active alert-primary');
			$('.alert').html(data);
			if(data == '¡Gracias! Nos pondremos en contacto contigo a la brevedad') {
				$('#contact-form').trigger("reset");
			}
			setTimeout(function(){
				$('.alert').removeClass('active alert-primary');
			}, 4000);
		})
		.fail(function() {
			$('.alert').addClass('active alert-danger');
			$('.alert').html('Ha ocurrido un error. Por favor vuelve a intentarlo más tarde.');
			setTimeout(function(){
				$('.alert').removeClass('active alert-danger');
			}, 4000);
		});
	});

});