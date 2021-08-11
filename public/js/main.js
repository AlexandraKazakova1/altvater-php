$(document).ready (function() {
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

    $('.reply').slideToggle();
    $('.question').click(function() {
        $(this).toggleClass('active');
        $(this).parent().children('.reply').slideToggle(300);
    });

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

    $('#callback-form').validate({
        rules: {
            username: {
                required: true,
                minlength: 2
            },
            useremail: {
                required: true,
                email: true
            }
        }
    });
    $("#callback-form").validate({
        rules: {
            username: {
                required: true,
                minlength: 2
            },
            useremail: {
                required: true,
                email: true
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
            }
        },
        focusInvalid: true,
        errorClass: "input_error"
    });
    

    // $('#callback-form').submit(function(e) {
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
    //             success: function(data) {
    //             console.log(data);
    //                 console.log('ok');
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
});