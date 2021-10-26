$(document).ready (function() {
	var csrf = $('meta[name="csrf-token"]').attr('content');
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': csrf,
		}
	});

    slidersConfig();
    faqSlide();
    burgerMenu();
    breadcrumbs();
    callBackForm();
    slideScroll();
    modalFade();
    logIn();
    create();
    passRecovery();
    passRecovery3();
    passVerificationForm();
    checkCookies();
    createModal();
    servicesCalc();
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
        // $('.dropdown__list').slideUp(300);
        $(this).toggleClass('act');
        $(this).parent().children('.dropdown__list').slideToggle(300);
    });
    $(document).mouseup(function (e){
        if (!$('.dropdown__list').is(e.target) 
        && $('.dropdown__list').has(e.target).length === 0) {
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
                rangelength: "Введіть більше двох символів"
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
					url: '/ajax/user/callback',
                    method: "POST",
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

						if(response.status = true){;
							form.trigger('reset');
                            window.location.href = '/account';
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
					}
				});
			};
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
    $('#calculator').click(function() {
        $('.modal').modal('hide');
        $('#servicesCalc').modal('show');
    });
    $('.calc-btn').click(function() {
        $('.modal').modal('hide');
        $('#servicesCalc').modal('show');
    });
    


    closeBtn.click(function() {
        $(this).parents('.modal').modal('hide');
    });
};

function logIn() {
	var form = jQuery("#log__in-form");

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
            userEmail: {
                required: true,
                email: true
            },
            userPassword: {
                required: true,
                rangelength: [8, 24]
            }
        },
        messages: {
            userEmail: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
            userPassword: {
                required: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength: "Введіть 8-24 символи"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/login',
                    method: "POST",
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

						if(response.status = true){;
							form.trigger('reset');
                            window.location.href = '/account';
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
					}
				});
			};
			return false;
	    }
    });
};

function create() {
	var form = jQuery("#create-form");

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
            userName: {
                required: true,
				minlength: 5
            },
            userTel: {
                required: true,
				minlength: 8
            },
            userEmail: {
                required: true,
                email: true
            },
            userPassword: {
                required: true,
                rangelength: [8, 24]
            },
            userPasswordConfirm: {
                required: true,
                rangelength: [8, 24],
                equalTo: ".password"
            },
            userAgree: {
                required: true
            }
        },
        messages: {
            userName: {
                required: "Введіть своє Ім'я та Прізвище",
				minlength: "Введіть більше 5 символів"
            },
            userTel: {
                required: "Введіть свій контактний телефон",
				minlength: "Введіть номер в форматі +380999999999"
            },
            userEmail: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
            userPassword: {
                required: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength: "Введіть 8-24 символи"
            },
            userPasswordConfirm: {
                required: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength: "Введіть 8-24 символи",
                equalTo: "Паролі не співпадають"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/registration',
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
						
						if(response.status = true){;
							form.trigger('reset');
                            window.location.href = '/account';
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
					}
				});
			};
			
			return false;
	    }
    });
};

function passRecovery() {
	var form = jQuery("#pass__recovery-form");

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
            userEmail: {
                required: true,
                email: true
            }
        },
        messages: {
            userEmail: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/recovery',
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
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
					}
				});
			};
			
			return false;
	    }
    });
};

function passRecovery3() {
	var form = jQuery("#pass__recovery-form3");

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
            userPassword: {
                required: true,
                rangelength: [8, 24]
            },
            userPasswordConfirm: {
                required: true,
                rangelength: [8, 24],
                equalTo: ".password-2"
            }
        },
        messages: {
            userPassword: {
                required: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength: "Введіть 8-24 символи"
            },
            userPasswordConfirm: {
                required: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength: "Введіть 8-24 символи",
                equalTo: "Паролі не співпадають"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/new-password',
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
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
					}
				});
			};
			
			return false;
	    }
    });
};

function passVerificationForm() {
    $(".verifCode").keyup(function () {
        if (this.value.length == this.maxLength) {
            $(this).next('.verifCode').focus();
        }
    });

	var form = jQuery("#pass__verification-form");

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
        // rules: {
        //     verifCode: {
        //         required: true
        //     }
        // },
        // messages: {
        //     verifCode: {
        //         required: 'Введите код'
        //     }
        // },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/verification',
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
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
					}
				});
			};
			
			return false;
	    }
    });
};

function checkCookies() {
    let cookieDate = localStorage.getItem('cookieDate');
    let cookieNotification = document.getElementById('cookie_notification');
    let cookieBtn = cookieNotification.querySelector('.cookie_accept');

    // Если записи про кукисы нет или она просрочена на 1 год, то показываем информацию про кукисы
    if( !cookieDate || (+cookieDate + 31536000000) < Date.now() ){
        cookieNotification.classList.add('show');
    }

    // При клике на кнопку, в локальное хранилище записывается текущая дата в системе UNIX
    cookieBtn.addEventListener('click', function(){
        localStorage.setItem( 'cookieDate', Date.now() );
        cookieNotification.classList.remove('show');
    })
}

function createModal() {
    $('.btn-individual').click(function() {
        $('.btn-entity').removeClass('act');
        $('#create-form__entity').removeClass('act');
        $('.btn-individual').addClass('act');
        $('#create-form__individual').addClass('act');
    });
    $('.btn-entity').click(function() {
        $('.btn-individual').removeClass('act');
        $('#create-form__individual').removeClass('act');
        $('.btn-entity').addClass('act');
        $('#create-form__entity').addClass('act');
    });
}
// function calcPopup() {
// 	$('.select2').select2({
//         minimumResultsForSearch: -1,
// 		// dropdownParent: $('.select')
//         // placeholder: 'Select an option'
//     });
// }

function servicesCalc() {
	var form = jQuery("#servicesCalc-form");
	
	if(!form.length){
		return false;
	};
	
	form.find('select[name="type"]').on('change', function(e){
		console.log('e:', e);
		
		var current = $(e.target);
		
		console.log('current:', current);
	});
};
