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
    $('.callback__form').validate({
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
    $('.callback__form').submit(function(e) {
        e.preventDefault()
        if ($('#username').hasClass('error')) {
            return false;
        } else if ($('#userphone').hasClass('error')) {
            return false;
        } else {
            $.ajax({
                url: "/ajax/order",
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function(data) {
                console.log(data);
                    console.log('ok');
                },
                error: function (data) {
                    console.log(data);
                    console.log('error');
                },
                complete: function () {
                    console.log('end');
                }
            }).done(function(){
                $('#ordering-success-msg').modal('show');
            });
            return false;
        }
    });
});