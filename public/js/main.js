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
    
    createIndividual();
    createEntity();
    
    passRecovery();
    passRecovery3();
    passVerificationForm();
    checkCookies();
    createModal();
    servicesCalc();
    scrollUp();
});

function scrollUp() {
    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop:0
        },800);
    })
    $(window).scroll(function() {
        if ($(this).scrollTop()>200) {
            $('.scrollup').fadeIn();
        }
        else {
            $('.scrollup').fadeOut();
        }
    });
}

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
            name: {
                required: true,
                rangelength: [2, 100]
            },
            email: {
                required: true,
                email: true
            },
			// message: {
			// 	minlength: 8,
			// 	maxlength: 2000
			// },
            rule: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Введіть своє ім'я",
                rangelength: "Введіть більше двох символів"
            },
            email: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
			// message: {
			// 	minlength: "Введіть більше 8 символів",
			// 	maxlength: "Введено більше 2000 символів"
			// },
            rule: {
                required: "Підтвердіть свою згоду"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/callback',
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
                        responseMsg(form, response);

						if(response.status){;
							form.trigger('reset');
                            window.location.href = '/account';
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
                        responseMsg(form, err);
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
    // $('.recovery-1').click(function() {
    //     $('.modal').modal('hide');
    //     $('#recovery-modal-2').modal('show');
    // });
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
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                rangelength: [8, 24]
            }
        },
        messages: {
            email: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
            password: {
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

                        responseMsg(form, response);

						if(response.status){;
							form.trigger('reset');
                            window.location.href = '/account';
						}else{
							if(response.payload.sms){
								openActivationModal(response.payload);
								
								$('.modal').modal('hide');
								$('#verification-modal').modal('show');
                            }
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
                        responseMsg(form, err);
					}
				});
			};
			return false;
	    }
    });
};

function createIndividual() {
	var form = jQuery("#create-form__individual");

    if(!form.length){
		return false;
	};

	var lock = false,
    btn = form.find('button[type="submit"]');

    form.validate({
		onkeyup			: false,
        focusCleanup	: true,
        focusInvalid	: false,
        errorClass		: "error",
        rules			: {
            name			: {
                required		: true,
				minlength		: 2,
				maxlength		: 100
            },
            phone			: {
                required		: true,
				minlength		: 12,
				maxlength		: 13,
            },
            email			: {
                required		: true,
                email			: true
            },
            password		: {
                required		: true,
                rangelength		: [8, 24]
            },
            confirm_password: {
                required		: true,
                rangelength		: [8, 24],
                equalTo			: "#password-individual"
            },
            agree			: {
                required		: true
            }
        },
        messages		: {
            name			: {
                required		: "Введіть Ім'я та Прізвище",
				minlength		: "Введіть більше 2 символів",
				maxlength		: "Можна ввести до 100 символів"
            },
            phone			: {
                required		: "Введіть контактний телефон",
				minlength		: "Введіть мінімум 12 символів",
				maxlength		: "Можна ввести до 13 символів"
            },
            email			: {
                required		: "Введіть e-mail!",
                email			: "Адреса має бути типу name@domain.com"
            },
            password		: {
                required		: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength		: "Введіть 8-24 символи"
            },
            confirm_password: {
                required		: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength		: "Введіть 8-24 символи",
                equalTo			: "Паролі не співпадають"
            },
            agree			: {
                required		: 'Підтвердіть що ви даєте згоду'
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

                        responseMsg(form, response);
						
						if(response.status){
							openActivationModal(response.payload);
                            $('.modal').modal('hide');
                            $('#verification-modal').modal('show');
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);

                        responseMsg(form, err);
					}
				});
			};
			
			return false;
	    }
    });
};

function createEntity() {
	var form = jQuery("#create-form__entity");
	
    if(!form.length){
		return false;
	};
	
	var lock = false,
    btn = form.find('button[type="submit"]');
	
    form.validate({
		onkeyup			: false,
        focusCleanup	: true,
        focusInvalid	: false,
        errorClass		: "error",
        rules			: {
            company_name	: {
                required		: true,
				minlength		: 2,
				maxlength		: 100
            },
            name			: {
                required		: true,
				minlength		: 2,
				maxlength		: 100
            },
            addresses		: {
                required		: true,
				minlength		: 5,
				maxlength		: 100
            },
            phone			: {
                required		: true,
				minlength		: 12,
				maxlength		: 13,
            },
            extra_prone		: {
                required		: false,
				minlength		: 12,
				maxlength		: 13,
            },
            email			: {
                required		: true,
                email			: true
            },
            password		: {
                required		: true,
                rangelength		: [8, 24]
            },
            confirm_password: {
                required		: true,
                rangelength		: [8, 24],
                equalTo			: "#password-entity"
            },
            ipn				: {
                required		: true,
				minlength		: 10,
				maxlength		: 10,
				number			: true
            },
            uedrpou			: {
                required		: true,
				minlength		: 8,
				maxlength		: 50
            },
            index			: {
                required		: true,
				minlength		: 6,
				maxlength		: 10,
				number			: true
            },
            agree			: {
                required		: true
            }
        },
        messages		: {
            company_name	: {
                required		: "Введіть назву",
				minlength		: "Введіть більше 2 символів",
				maxlength		: "Можна ввести до 100 символів"
            },
            name			: {
                required		: "Введіть контактну особу",
				minlength		: "Введіть більше 2 символів",
				maxlength		: "Можна ввести до 100 символів"
            },
            addresses		: {
                required		: "Введіть адресу",
				minlength		: "Введіть більше 5 символів",
				maxlength		: "Можна ввести до 100 символів"
            },
            phone			: {
                required		: "Введіть контактний телефон",
				minlength		: "Введіть мінімум 12 символів",
				maxlength		: "Можна ввести до 13 символів"
            },
            extra_prone		: {
                required		: "Введіть контактний телефон",
				minlength		: "Введіть мінімум 12 символів",
				maxlength		: "Можна ввести до 13 символів"
            },
            email			: {
                required		: "Введіть e-mail!",
                email			: "Адреса має бути типу name@domain.com"
            },
            password		: {
                required		: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength		: "Введіть 8-24 символи"
            },
            confirm_password: {
                required		: "Введіть пароль використовуючи A-Z a-z 0-9",
                rangelength		: "Введіть 8-24 символи",
                equalTo			: "Паролі не співпадають"
            },
            ipn				: {
                required		: "Введіть ІПН",
				minlength		: "Код має містити 10 цифр",
				maxlength		: "Код має містити 10 цифр",
				number			: "Код має містити 10 цифр"
            },
            uedrpou			: {
                required		: "Введіть ЄДРПОУ",
				minlength		: "Мінімальна довжина 8 символів",
				maxlength		: "Максимальна довжина 50 символів"
            },
            ipn				: {
                required		: "Введіть індекс",
				minlength		: "Індекс має містити 5 цифр",
				maxlength		: "Індекс має містити 5 цифр",
				number			: "Індекс має містити 5 цифр"
            },
            agree			: {
                required		: 'Підтвердіть що ви даєте згоду'
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
                        
                        responseMsg(form, response);
						
						if(response.status){
							openActivationModal(response.payload);
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
                        responseMsg(form, err);
					}
				});
			};
			
			return false;
	    }
    });
};

var timerResend;

function openActivationModal(data){
	// відкриття модального вікна
	
	var form = $('#pass__verification-form');
	
	var input_token = form.find('input[name="token"]');
	
	input_token.val(data.token);
	form.find('span.number').text(data.phone_format);
	
	var sendAgain = form.find('.sendAgain');
	
	sendAgain.on('click', function(e){
		e.preventDefault();		
        
        // деактивація кнопки
		form.$('.sendAgain').addClass('disable')
		
		startTimer(() => {
            // активація кнопки
            form.$('.sendAgain').removeClass('disable')
		});
		
		var token = input_token.val();
		
		// ajax code resend
		
		form.find('input[name="verifCode"]').val('');
	});
	
	startTimer(() => {
		// активація кнопки
        form.$('.sendAgain').removeClass('disable')
	});
};

function startTimer(callback){
	if(timerResend){
		clearTimeout(timerResend);
	};
	
	var timestamp = 2 * 60;
	
	var hours;
	var minutes;
	var seconds; 
	
	var el_seconds = $('#seconds');
	
	timerResend = setInterval(() => {
		timestamp -= 1;
		
		if(timestamp < 1){
			clearTimeout(timerResend);
			
			callback();
		};
		
		hours	= Math.floor(timestamp / 60 / 60);
		minutes	= Math.floor(timestamp / 60) - (hours * 60);
		seconds = timestamp % 60;
		
		if(minutes < 10){
			minutes = '0'+minutes;
		};
		
		if(seconds < 10){
			seconds = '0'+seconds;
		};
		
		el_seconds.text('('+minutes+':'+seconds+')с');
	}, 1000);
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
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
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
                        
                        responseMsg(form, response);
						
						if(response.status){;
                            $('.modal').modal('hide');
                            $('#recovery-modal-2').modal('show');
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
                        responseMsg(form, err);
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
                        
                        responseMsg(form, response);
						
						if(response.status){;
							form.trigger('reset');
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
                        responseMsg(form, err);
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
        rules: {
            verifCode: {
                required: true
            }
        },
        messages: {
            verifCode: {
                required: 'Введите код'
            }
        },
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
                        
                        responseMsg(form, response);
						
						if(response.status){;
							form.trigger('reset');
                            window.location.href = '/account';
						}
					},
					error: function(err){
						console.log('error');
						lock = false;
                        btn.attr('disabled', false);
                        responseMsg(form, err);
					}
				});
			};
			
			return false;
	    }
    });
};

function responseMsg(form, response) {
    form.find('.responseMsg').text(response.message)
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
};

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
