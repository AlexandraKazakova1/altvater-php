$(document).ready (function() {

    calendar();
    customSelect();
    modalFade();
    dragAndDrop();
    accountsActs();
    settingsPage();
    logIn();
    create();
    passRecovery();
    passRecovery3();
    passVerificationForm();
    contractIndividual();
    contractEntity();
    addAddress();
    requestForm();
    // pdfViever();

    $('.add').click(function() {
        $('.modal').modal('hide');
        $('#orderService').modal('show');
    });
    var elemCount  = document.getElementsByClassName("contracts__list")[0].childElementCount;
    console.log(elemCount);

});

function calendar() {
    const labels = [
        'Понеділок',
        'Вівторок',
        'Середа',
        'Четвер',
        "П'ятниця",
        'Субота',
        'Неділя',
    ];
    const data = {
        labels: labels,
        datasets: [{
            backgroundColor: 'rgba(47, 94, 151, 0.2)',
            borderColor: '#2F5E97',
            fill: true,
            cubicInterpolationMode: 'monotone',
            tension: 0.4,
            data: [60, 20, 100, 90, 200, 150, 195, 120],
        }]
    };
    const config = {
        type: 'line',
        data: data,
        options: {
            radius: 0,
            plugins: {
                legend: {
                    display: false,
                }
            },
            responsive: true,
            interaction: {
                intersect: false,
            },
            scales: {
                x: {
                    display: false,
                },
                y: {
                    display: false,
                    suggestedMin: 0,
                    suggestedMax: 200.20
                }
            }
        },
    };
    var myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
};

function customSelect() {
    $('.custom-select').select2({
        minimumResultsForSearch: 1000 
    });
    $('.custom-select2').select2({
        minimumResultsForSearch: 1000,
        dropdownParent: $("#newOrder-form")
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

    $('.btn-add__address').click(function() {
        $('.modal').modal('hide');
        $('#add__address-modal').modal('show');
    });
    $('.btn-address__info').click(function() {
        $('.modal').modal('hide');
        $('#address__info-modal').modal('show');
        initMap();
    });
    // $('#orderService').modal('show');
    $('.order-btn').click(function() {
        $('.modal').modal('hide');
        $('#orderService').modal('show');
    });
    $('.add').click(function() {
        $('.modal').modal('hide');
        $('#create__contract-modal-1').modal('show');
    });
    $('.pinned').click(function() {
        $('.modal').modal('hide');
        $('#create__contract-modal-2').modal('show');
    });



    closeBtn.click(function() {
        $(this).parents('.modal').modal('hide');
    });
};

function dragAndDrop() {
    var dropZone = $('.file-wrap-drop');

	$('.form-control-file').focus(function() {
		$('label').addClass('focus');
	})
	.focusout(function() {
		$('label').removeClass('focus');
	});


	dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function(){
		return false;
	});

	dropZone.on('dragover dragenter', function() {
		dropZone.addClass('dragover');
	});

	dropZone.on('dragleave', function(e) {
		let dx = e.pageX - dropZone.offset().left;
		let dy = e.pageY - dropZone.offset().top;
		if ((dx < 0) || (dx > dropZone.width()) || (dy < 0) || (dy > dropZone.height())) {
			dropZone.removeClass('dragover');
		}
	});

	dropZone.on('drop', function(e) {
		dropZone.removeClass('dragover');
		let files = e.originalEvent.dataTransfer.files;
		sendFiles(files);
	});

	$('.form-control-file').change(function() {
		let files = this.files;
		sendFiles(files);
	});


	function sendFiles(files) {
		let maxFileSize = 5242880;
		let Data = new FormData();
		$(files).each(function(index, file) {
			if ((file.size <= maxFileSize) && ((file.type == 'image/png') || (file.type == 'image/jpeg'))) {
				Data.append('images[]', file);
			}
		});

		$.ajax({
			url: dropZone.attr('action'),
			type: dropZone.attr('method'),
			data: Data,
			contentType: false,
			processData: false,
			success: function(data) {
				alert ('Файлы были успешно загружены!');
			}
		});
	}
};

function accountsActs() {
    if($('#accountsToggle').hasClass('act')) {
        $('.acts__content').fadeOut(200);
        $('.accounts__content').fadeIn(200);
    } else if($('#actsToggle').hasClass('act')) {
        $('.accounts__content').fadeOut(200);
        $('.acts__content').fadeIn(200);
    }

    $('#accountsToggle').click(function() {
        $(this).addClass('act');
        $('.acts__content').fadeOut(200);
        $('.accounts__content').fadeIn(200);
        $('#actsToggle').removeClass('act');
    });
    $('#actsToggle').click(function() {
        $(this).addClass('act');
        $('.accounts__content').fadeOut(200);
        $('.acts__content').fadeIn(200);
        $('#accountsToggle').removeClass('act');
    });
};
function settingsPage() {
    if($('#general').hasClass('act')) {
        $('.security__content').fadeOut(1);
        $('.general__content').fadeIn(1);
    } else if($('#security').hasClass('act')) {
        $('.general__content').fadeOut(1);
        $('.security__content').fadeIn(1);
    }

    $('#general').click(function() {
        $(this).addClass('act');
        $('.security__content').fadeOut(1);
        $('.general__content').fadeIn(1);
        $('#security').removeClass('act');
    });
    $('#security').click(function() {
        $(this).addClass('act');
        $('.general__content').fadeOut(1);
        $('.security__content').fadeIn(1);
        $('#general').removeClass('act');
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

						if(response.status){
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

function contractIndividual() {
	var form = jQuery("#create__contract-form__individual");

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
				minlength: 8
            },
            userEmail: {
                required: true,
                email: true
            },
            userTel: {
                required: true,
            },
            userAddress: {
                required: true,
				minlength: 10
            },
            postIndex: {
                required: true,
				minlength: 5
            }
        },
        messages: {
            userName: {
                required: "Введіть ваше ПІБ",
				minlength: "Некоректні дані"
            },
            userEmail: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
            userTel: {
                required: "Введіть ваш номер телефону",
            },
            userAddress: {
                required: "Введіть вашу адресу",
				minlength: "Некоректні дані"
            },
            postIndex: {
                required: "Введіть ваш поштовий індекс",
				minlength: "Некоректні дані"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/contractIndividual',
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

						if(response.status){
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

function contractEntity() {
	var form = jQuery("#create__contract-form__entity");

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
				minlength: 8
            },
            userContactName: {
                required: true,
				minlength: 2
            },
            userEntityAddress: {
                required: true,
				minlength: 10
            },
            userEmail: {
                required: true,
                email: true
            },
            userTel: {
                required: true,
            },
            userAddTel: {
                required: true,
            },
            ipn: {
                required: true,
				minlength: 12,
				maxlength: 12
            },
            uedrpou: {
                required: true,
				minlength: 8,
				maxlength: 8
            },
            postIndex: {
                required: true,
				minlength: 5
            }
        },
        messages: {
            userName: {
                required: "Введіть назву компанії",
				minlength: "Некоректні дані"
            },
            userContactName: {
                required: "Введіть ПІБ контактної особи",
				minlength: "Некоректні дані"
            },
            userEntityAddress: {
                required: "Введіть вашу юридичну адресу",
				minlength: "Некоректні дані"
            },
            userEmail: {
                required: "Введіть e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
            userTel: {
                required: "Введіть номер телефону"
            },
            userAddTel: {
                required: "Введіть додатковий номер телефону"
            },
            ipn: {
                required: true,
				minlength: "Некоректні дані",
				maxlength: "Некоректні дані"
            },
            uedrpou: {
                required: true,
				minlength: "Некоректні дані",
				maxlength: "Некоректні дані"
            },
            postIndex: {
                required: "Введіть ваш поштовий індекс",
				minlength: "Некоректні дані"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/contractEntity',
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

						if(response.status){
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

function addAddress() {
	var form = jQuery("#add__address-form");

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
            placeName: {
                required: true,
				minlength: 2
            },
            placeAddress: {
                required: true,
				minlength: 10
            }
        },
        messages: {
            placeName: {
                required: "Введіть назву компанії",
				minlength: "Некоректні дані"
            },
            placeAddress: {
                required: "Введіть вашу адресу",
				minlength: "Некоректні дані"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/address',
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

						if(response.status){
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

function initMap() {
    var place = { lat: -25.344, lng: 131.036 };
    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: place,
    });
    const marker = new google.maps.Marker({
        position: place,
        map: map,
    });
}

function requestForm() {
	var form = jQuery("#request__form");

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
            theme: {
                required: true,
				minlength: 2
            },
            number: {
                required: true,
				minlength: 5
            },
            phone: {
                required: true,
            },
            header: {
                required: true,
				minlength: 2
            },
            text: {
                required: true,
				maxlength: 1000
            }
        },
        messages: {
            theme: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть більше 2 символів"
            },
            number: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть більше 5 символів"
            },
            phone: {
                required: "Це поле обов'язкове для заповнення",
            },
            header: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть більше 2 символів"
            },
            text: {
                required: "Це поле обов'язкове для заповнення",
				maxlength: "Введіть не більше 1000 символів"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/request',
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

						if(response.status){
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

function settingsForm() {
	var form = jQuery("#settings__form");

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
				minlength: 2
            },
            surname: {
                required: true,
				minlength: 2
            },
            middlename: {
                required: true,
				minlength: 2
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
            },
            address: {
                required: true,
				minlength: 8
            },
            index: {
                required: true,
				minlength: 5
            }
        },
        messages: {
            name: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть більше 2 символів"
            },
            surname: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть більше 2 символів"
            },
            middlename: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть більше 2 символів"
            },
            email: {
                required: "Введіть свій e-mail!",
                email: "Адреса має бути типу name@domain.com"
            },
            phone: {
                required: "Це поле обов'язкове для заповнення",
            },
            address: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть не менше 8 символів"
            },
            index: {
                required: "Це поле обов'язкове для заповнення",
				minlength: "Введіть не менше 5 символів"
            }
        },
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/user/settings',
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

						if(response.status){
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

// function pdfViever() {
//     PDFJS.getDocument('helloworld.pdf').then(function(pdf) {
//         // Using promise to fetch the page
//         pdf.getPage(1).then(function(page) {
//             var scale = 1.5;
//             var viewport = page.getViewport(scale);
        
//             //
//             // Prepare canvas using PDF page dimensions
//             //
//             var canvas = document.getElementById('the-canvas');
//             var context = canvas.getContext('2d');
//             canvas.height = viewport.height;
//             canvas.width = viewport.width;
        
//             //
//             // Render PDF page into canvas context
//             //
//             var renderContext = {
//                 canvasContext: context,
//                 viewport: viewport
//             };
//             page.render(renderContext);
//         });
//     });
// }