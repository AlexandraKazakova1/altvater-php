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
    $('.breadcrumbs__item').click(function() {
        $(this).toggleClass('show');
        $(this).children('.dropdown__list').slideToggle(300);
    });
};

function callBackForm() {
    $("#callback-form").validate({
        rules: {
            username: {
                required: true,
                rangelength: [2, 100]
            },
            useremail: {
                required: true,
                email: true
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
            rule: {
                required: "Підтвердіть свою згоду"
            }
        },
        focusInvalid: false,
        errorClass: "error"
    });

    $('#callback-form').submit(function(e) {
        e.preventDefault()
        if ($('#username').hasClass('error')) {
            return false;
        } else if ($('#useremail').hasClass('error')) {
            return false;
        } else {
            $.ajax({
                url: "/ajax/callback",
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function(data) {
                    console.log(data);
                    console.log('ok');
                    $('#callback-form').trigger('reset');
                    $('#answer-msg').text(data.msg);
                },
                error: function (data) {
                    console.log(data);
                    console.log('error');
                },
                complete: function () {
                    console.log('end');
                }
            })
            return false;
        }
    });
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
