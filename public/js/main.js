$(document).ready (function() {
    slidersConfig();
    faqSlide();
    burgerMenu();
    breadcrumbs();
    callBackForm();
    slideScroll();
    modalFade();
});

function slidersConfig() {
    $('.response__slider').slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1439,
                settings: {
                    dots: true,
                    arrows: false
                }
            }
        ]
    });
    $('.last-work__slider').slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1
    });
};

function faqSlide() {
    $('.reply').slideToggle();
    $('.question').click(function() {
        $(this).toggleClass('active');
        $(this).parent().children('.reply').slideToggle(300);
    });
};
 
function burgerMenu() {
    $('.menu__icon').click(function() {
        $(this).toggleClass('close')
        $(this).parent().children('.menu__body').slideToggle(300);
    });
    $(document).mouseup(function (e){
        if (!$('.menu__body, .menu__icon').is(e.target) 
        && $('.menu__body, .menu__icon').has(e.target).length === 0) {
            $('.menu__body').slideUp(300);
            $('.menu__icon').removeClass('close')
        }
    });
};

function breadcrumbs() {
    $('.breadcrumbs__toggle').click(function() {
        $('.dropdown__list').slideUp(300);
        $(this).toggleClass('act');
        $(this).parent().children('.dropdown__list').slideToggle(300);
    });
    $(document).mouseup(function (e){
        if (!$('.breadcrumbs__item, .breadcrumbs__toggle').is(e.target) 
        && $('.breadcrumbs__item, .breadcrumbs__toggle').has(e.target).length === 0) {
            $('.dropdown__list').slideUp(300);
            $('.breadcrumbs__toggle').removeClass('act')
        }
    });
};

function callBackForm() {
	var form = jQuery("#callback-form");

    if(!form.length){
		return false;
	};

	var lock = false,
    btn = form.find('button[type="submit"]');

    form.validate({
		onkeyup	: false,
        focusCleanup: true,
        focusInvalid: false,
        errorClass: "error",
        rules: {
            username: {
                required: true,
                rangelength: [2, 100]
            },
            useremail: {
                required: true,
                email: true
            },
			message: {
				minlength: 8,
				maxlength: 2000
			},
            rule: {
                required: true
            }
        },
        messages: {
            username: {
                required: "Введіть своє ім'я",
                minlength: "Введіть більше двох символів"
            },
            useremail: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
			message: {
				minlength: "Введіть більше 8 символів",
				maxlength: "Введено більше 2000 символів"
			},
            rule: {
                required: "Підтвердіть свою згоду"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: form.attr("action"),
                    data: form.serialize(),
                    dataType: "json",
                    beforeSend: function(request){
                        lock = true;
                        
                        btn.attr('disabled', true);
                        form.find('label.error').text('').hide();
					},
					success: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
                        btn.attr('disabled', false);
						
						if(response.status){;
							form.trigger('reset');
							
                            $('#callback-form').trigger('reset');
                            $('#answer-msg').text(response.msg);

							
							setTimeout('#answer-msg', 5000);
						}
                        // else{
						// 	status.text(response.msg).show();
						// }
					},
					error: function(err){
						lock = false;
                        btn.attr('disabled', false);
                        
                        // status.text(langs.set_up_failed).show();
					}
				});
			};
			
			return false;
	    }
    });
    
    // form.submit(function(e) {
    //     e.preventDefault()
    //     if ($('#username').hasClass('error')) {
    //         return false;
    //     } else if ($('#useremail').hasClass('error')) {
    //         return false;
    //     } else {
    //         $.ajax({
    //             url: "/ajax/callback",
    //             type: "POST",
    //             dataType: "json",
    //             data: $(this).serialize(),
    //             beforeSend: function(data){
    //                 form.find('label.error').text('').hide();
    //             },
    //             success: function(data) {
    //                 console.log(data);
    //                 console.log('ok');
    //                 $('#callback-form').trigger('reset');
    //                 $('#answer-msg').text(data.msg);
    //             },
    //             error: function (data) {
    //                 console.log(data);
    //                 console.log('error');
    //             },
    //             complete: function () {
    //                 console.log('end');
    //             }
    //         })
    //         return false;
    //     }
    // });
};

function slideScroll() {
	$(".scroll").click(function(event){     
		event.preventDefault();
		
		if($(this.hash).length > 0){
			$('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
		}else{
			window.location = '/'+this.hash;
		}
	});
};

function modalFade() {
    var closeBtn = $('.close')

    $('.btn-logIn').click(function() {
        $('.modal').modal('hide');
        $('#log__in-modal').modal('show');
    });
    $('.btn-reg').click(function() {
        $('.modal').modal('hide');
        $('#create-modal').modal('show');
    });

    $('.btn-forgot').click(function() {
        $('.modal').modal('hide');
        $('#recovery-modal-1').modal('show');
    });
    $('.recovery-1').click(function() {
        $('.modal').modal('hide');
        $('#recovery-modal-2').modal('show');
    });



    closeBtn.click(function() {
        $(this).parents('.modal').modal('hide');
    });
};
