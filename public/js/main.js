$(document).ready (function() {
    $('.response__slider').slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1
    });
    $('.reply').slideToggle();
    $('.question').click(function() {
        $(this).toggleClass('active');
        $(this).parent().children('.reply').slideToggle(300);
    });
});