$(document).ready (function() {
	var csrf = $('meta[name="csrf-token"]').attr('content');
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': csrf,
		}
	});
	
	//calendar();
	customSelect();
	modalFade();
	//dragAndDrop();
	accountsActs();
	settingsPage();
	
	contracts();
	contractIndividual();
	contractEntity();
	connect_contract();
	
	bills();
	
	myAddress();
	timepicker();
	select2();
	maskPhone();
	
	contractsArchive();
	
	changePasswordForm();
	settingsForm();
	requestForm();
	
	requestMsg();
	menuToggle();
	
	ordersSelect();
	orderServiceForm();
	newOrderForm();
	
	//$('#addcontract').modal('show');
	
	$('#to_help').on('click', function(e){
		window.location.href = '/account/messages';
	});
});

function menuToggle(){
	$('.menu-icon').click(function() {
		$('.sidebar__menu').toggleClass('act');
	});
	
	$(document).mouseup(function (e){
		if (!$('.sidebar__menu, .menu__icon').is(e.target) 
		&& $('.sidebar__menu, .menu__icon').has(e.target).length === 0) {
			$('.sidebar__menu').removeClass('act');
		}
	});
};

function calendar(){
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

function customSelect(){
	$('.custom-select').select2({
		minimumResultsForSearch: 1000 
	});
	
	$('.custom-select2').select2({
		minimumResultsForSearch: 1000,
		dropdownParent: $("#newOrder-form")
	});
};

function modalFade(){
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
	
	// $('#orderService').modal('show');
	$('.order-btn').click(function() {
		$('.modal').modal('hide');
		$('#orderService').modal('show');
	});
	
	$('.header .add').click(function() {
		$('.modal').modal('hide');
		$('#orderService').modal('show');
	});
	
	$('.pinned').click(function() {
		$('.modal').modal('hide');
		$('#create__contract-modal-2').modal('show');
	});
	
	$('.orders__item').click(function() {
		$('.modal').modal('hide');
		$('#orderInfo').modal('show');
	});
	
	closeBtn.click(function() {
		$(this).parents('.modal').modal('hide');
	});
};

function initMap(lat, lng){
	var place = new google.maps.LatLng(lat, lng);
	
	var map = new google.maps.Map(document.getElementById("map"), {
		zoom	: 16,
		center	: place
	});
	
	const marker = new google.maps.Marker({
		position: place,
		map		: map
	});
}; 

function dragAndDrop(){
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

function accountsActs(){
	if($('#accountsToggle').hasClass('act')) {
		$('.acts__content').fadeOut(200);
		$('.accounts__content').fadeIn(200);
	} else if($('#actsToggle').hasClass('act')) {
		$('.accounts__content').fadeOut(200);
		$('.acts__content').fadeIn(200);
	};
	
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

function settingsPage(){
	if($('#general').hasClass('act')) {
		$('.security__content').fadeOut(1);
		$('.general__content').fadeIn(1);
	} else if($('#security').hasClass('act')) {
		$('.general__content').fadeOut(1);
		$('.security__content').fadeIn(1);
	};
	
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

function ordersSelect(){
	if($('#all').hasClass('act')) {
		$('.orders__item').fadeIn(200);
	} else if($('#processed').hasClass('act')) {
		$('.orders__item').fadeOut(1);
		$('.processed').fadeIn(200);
	} else if($('#performed').hasClass('act')) {
		$('.orders__item').fadeOut(1);
		$('.performed').fadeIn(200);
	} else if($('#ready').hasClass('act')) {
		$('.orders__item').fadeOut(1);
		$('.ready').fadeIn(200);
	} else if($('#planned').hasClass('act')) {
		$('.orders__item').fadeOut(1);
		$('.planned').fadeIn(200);
	};
	
	$('#all').click(function() {
		$('.selector ul li').removeClass('act');
		$(this).addClass('act');
		$('.orders__item').fadeOut(1);
		$('.orders__item').fadeIn(200);
	});
	
	$('#processed').click(function() {
		$('.selector ul li').removeClass('act');
		$(this).addClass('act');
		$('.orders__item').fadeOut(1);
		$('.processed').fadeIn(200);
	});
	
	$('#performed').click(function() {
		$('.selector ul li').removeClass('act');
		$(this).addClass('act');
		$('.orders__item').fadeOut(1);
		$('.performed').fadeIn(200);
	});
	
	$('#ready').click(function() {
		$('.selector ul li').removeClass('act');
		$(this).addClass('act');
		$('.orders__item').fadeOut(1);
		$('.ready').fadeIn(200);
	});
	
	$('#planned').click(function() {
		$('.selector ul li').removeClass('act');
		$(this).addClass('act');
		$('.orders__item').fadeOut(1);
		$('.planned').fadeIn(200);
	});
};

//

function contractsArchive(){
	if($('#contractsToggle').hasClass('act')) {
		$('.archive__content').fadeOut(200);
		$('.contracts__content').fadeIn(200);
	} else if($('#archiveToggle').hasClass('act')) {
		$('.contracts__content').fadeOut(200);
		$('.archive__content').fadeIn(200);
	};
	
	$('#contractsToggle').click(function() {
		$(this).addClass('act');
		$('.archive__content').fadeOut(200);
		$('.contracts__content').fadeIn(200);
		$('#archiveToggle').removeClass('act');
	});
	
	$('#archiveToggle').click(function() {
		$(this).addClass('act');
		$('.contracts__content').fadeOut(200);
		$('.archive__content').fadeIn(200);
		$('#contractsToggle').removeClass('act');
	});
};

function contractIndividual(){
	var form = jQuery("#create__contract__individual");
	
	if(!form.length){
		return false;
	};
	
	var lock = false,
		btn = form.find('button[type="submit"]');
	
	var modal = $('#response');
	
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
			email			: {
				required		: true,
				email			: true
			},
			phone			: {
				required		: true,
				minlength		: 9,
				maxlength		: 13
			},
			addresses		: {
				required		: true,
				minlength		: 10,
				maxlength		: 150
			},
			index			: {
				required		: true,
				minlength		: 5,
				maxlength		: 6
			}
		},
		messages		: {
			name			: {
				required		: "Введіть ваше ПІБ",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			email			: {
				required		: "Введіть свій e-mail!",
				email			: "Адреса має бути типу name@domain.com"
			},
			phone			: {
				required		: "Введіть ваш номер телефону",
			},
			addresses		: {
				required		: "Введіть вашу адресу",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			index			: {
				required		: "Введіть ваш поштовий індекс",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			}
		},
		submitHandler	: function() {
			if(!lock){               
				$.ajax({
					type		: "POST",
					url			: '/ajax/cabinet/contracts/add',
					method		: "POST",
					data		: form.serialize(),
					dataType	: "json",
					beforeSend	: function(request){
						lock = true;
						
						btn.attr('disabled', true);
						
						form.find('label.error').text('').hide();
						form.find('.responseMsg').text('');
						
						modal.modal('hide');
					},
					success		: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
						btn.attr('disabled', false);
						
						if(response.status){
							form.trigger('reset');
							
							form.parents('.modal').modal('hide');
							
							modal.find('.responseMsg').text(response.message);
							modal.modal('show');
							
							$('#active-contracts').trigger('reload');
						}else{
							responseMsg(form, response);
						}
					},
					error		: function(err){
						console.log('error');
						console.log(err);
						
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

function contractEntity(){
	var form = jQuery("#create__contract-form__entity");
	
	if(!form.length){
		return false;
	};
	
	var lock = false,
		btn = form.find('button[type="submit"]');
	
	var modal = $('#response');
	
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
			contact			: {
				required		: true,
				minlength		: 2
			},
			addresses		: {
				required		: true,
				minlength		: 10
			},
			email			: {
				required		: true,
				email			: true
			},
			phone			: {
				required		: true,
				minlength		: 9,
				maxlength		: 13
			},
			extra_phone		: {
				required		: false,
			},
			ipn				: {
				required		: true,
				minlength		: 10,
				maxlength		: 12
			},
			edrpou			: {
				required		: true,
				minlength		: 8,
				maxlength		: 50
			},
			index			: {
				required		: true,
				minlength		: 5,
				maxlength		: 6
			}
		},
		messages		: {
			company_name	: {
				required		: "Введіть назву компанії",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			name			: {
				required		: "Введіть ПІБ контактної особи",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			addresses		: {
				required		: "Введіть вашу юридичну адресу",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			email			: {
				required		: "Введіть e-mail!",
				email			: "Адреса має бути типу name@domain.com"
			},
			phone			: {
				required		: "Введіть номер телефону",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			extra_phone		: {
				required		: "Введіть додатковий номер телефону"
			},
			ipn				: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			edrpou			: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			index			: {
				required		: "Введіть ваш поштовий індекс",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			}
		},
		submitHandler	: function() {
			if(!lock){
				$.ajax({
					type		: "POST",
					url			: '/ajax/cabinet/contracts/add',
					method		: "POST",
					data		: form.serialize(),
					dataType	: "json",
					beforeSend	: function(request){
						lock = true;
						
						btn.attr('disabled', true);
						
						form.find('label.error').text('').hide();
						form.find('.responseMsg').text('');
					},
					success		: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
						btn.attr('disabled', false);
						
						if(response.status){
							form.trigger('reset');
							
							form.parents('.modal').modal('hide');
							
							modal.find('.responseMsg').text(response.message);
							modal.modal('show');
							
							$('#active-contracts').trigger('reload');
						}else{
							responseMsg(form, response);
						}
					},
					error		: function(err){
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

function connect_contract(){
	var form = jQuery("#addcontract-form");
	
	if(!form.length){
		return false;
	};
	
	var lock = false,
		btn = form.find('button[type="submit"]');
	
	var modal = $('#response');
		
	if(form.attr('data-type') == 'individual'){
		var rules = {
			name			: {
				required		: true,
				minlength		: 2,
				maxlength		: 100
			},
			number			: {
				required		: true,
				minlength		: 2,
				maxlength		: 30
			}
		};
	}else{
		var rules = {
			edrpou			: {
				required		: true,
				minlength		: 8,
				maxlength		: 30
			},
			number			: {
				required		: true,
				minlength		: 2,
				maxlength		: 30
			}
		};
	};
	
	form.validate({
		onkeyup			: false,
		focusCleanup	: true,
		focusInvalid	: false,
		errorClass		: "error",
		rules			: rules,
		messages		: {
			name			: {
				required		: "Введіть ПІБ контактної особи",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			edrpou			: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			},
			number			: {
				required		: "Введіть номер договору",
				minlength		: "Некоректні дані",
				maxlength		: "Некоректні дані"
			}
		},
		submitHandler	: function() {
			if(!lock){
				$.ajax({
					type		: "POST",
					url			: '/ajax/cabinet/contracts/connect',
					method		: "POST",
					data		: form.serialize(),
					dataType	: "json",
					beforeSend	: function(request){
						lock = true;
						
						btn.attr('disabled', true);
						
						form.find('label.error').text('').hide();
						form.find('.responseMsg').text('');
					},
					success		: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
						btn.attr('disabled', false);
						
						if(response.status){
							form.trigger('reset');
							
							form.parents('.modal').modal('hide');
							
							modal.find('.responseMsg').text(response.message);
							modal.modal('show');
						}else{
							responseMsg(form, response);
						}
					},
					error		: function(err){
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

//

function contracts(){
	var page = $('#contracts');
	
	if(!page.length){
		return false;
	};
	
	var active_contracts	= $('#active-contracts');
	var archive_contracts	= $('#archive-contracts');
	
	var sort_active_contracts	= $('#sort-active-contracts');
	var sort_archive_contracts	= $('#sort-archive-contracts');
	
	sort_active_contracts.on('change', function(){
		var value = $(this).val();
		
		loadContracts('active', value, 0, active_contracts.attr('data-limit'), active_contracts);
	});
	
	sort_archive_contracts.on('change', function(){
		var value = $(this).val();
		
		loadContracts('archive', value, 0, archive_contracts.attr('data-limit'), archive_contracts);
	});
	
	var more_active		= active_contracts.find('button.more');
	var more_archive	= archive_contracts.find('button.more');
	
	more_active.on('click', function(){
		var value = sort_active_contracts.val();
		var count = active_contracts.find('.contract__item').length;
		
		loadContracts('active', value, count, active_contracts.attr('data-limit'), active_contracts);
	});
	
	more_archive.on('click', function(){
		var value = sort_archive_contracts.val();
		var count = archive_contracts.find('.contract__item').length;
		
		loadContracts('archive', value, count, archive_contracts.attr('data-limit'), archive_contracts);
	});
	
	active_contracts.on('reload', function(){
		$('#contractsToggle').addClass('act');
		
		$('.archive__content').fadeOut(200);
		$('.contracts__content').fadeIn(200);
		
		$('#archiveToggle').removeClass('act');
		
		sort_active_contracts.val('date');
		sort_active_contracts.trigger('change');
	});
	
	$('#add_contract').click(function() {
		$('.modal').modal('hide');
		
		var m = $('#create__contract-modal-1');
		
		if(m.length > 0){
			m.modal('show');
		}else{
			m = $('#create__contract-modal-2');
			
			if(m.length > 0){
				m.modal('show');
			}
		};
		
		//$('#create__contract-modal-1').modal('show');
		//$('#create__contract-modal-2').modal('show');
	});
	
	$('#pinned_contract').click(function() {
		$('.modal').modal('hide');
		
		$('#addcontract').modal('show');
	});
};

function loadContracts(type, sort, offset, limit, container){
	$.ajax({
		type		: "GET",
		url			: '/ajax/cabinet/contracts/'+type,
		data		: {
			sort		: sort,
			offset		: offset,
			limit		: limit
		},
		dataType	: "json",
		beforeSend	: function(request){
			container.find('button.more').hide();
			
			if(offset < 1){
				container.find('.contract__item').remove();
				container.attr('data-show', 0);
				
				$('#count_'+type).text(0);
			}
		},
		success		: function(response){
			console.log('response:');
			//console.log(response);
			
			if(response.status){
				container.find('button.more').before(response.payload.html);
				
				if(offset < 1){
					if(response.payload.count > 4){
						container.find('button.more').show();
					}
				}else{
					var count = container.find('.contract__item').length;
					
					if(count == response.payload.count){
						container.find('button.more').hide();
					}
				};
				
				$('#count_'+type).text(response.payload.count);
			}
		},
		error		: function(err){
			console.log('error');
		}
	});
};

//

function bills(){
	var page = $('#bills');
	
	if(!page.length){
		return false;
	};
	
	var bills_list	= $('#bills-list');
	var acts_list	= $('#acts-list');
	
	var sort_bills	= $('#sort-bills');
	var sort_acts	= $('#sort-acts');
	
	sort_bills.on('change', function(){
		var value = $(this).val();
		
		loadBills(value, 0, bills_list.attr('data-limit'), bills_list);
	});
	
	sort_acts.on('change', function(){
		var value = $(this).val();
		
		loadActs(value, 0, acts_list.attr('data-limit'), acts_list);
	});
	
	var more_bills		= bills_list.find('button.more');
	var more_acts		= acts_list.find('button.more');
	
	more_bills.on('click', function(){
		var value = sort_bills.val();
		
		var count = bills_list.find('.accounts__item').length;
		
		loadBills(value, count, bills_list.attr('data-limit'), bills_list);
	});
	
	more_acts.on('click', function(){
		var value = sort_acts.val();
		
		var count = acts_list.find('.acts__item').length;
		
		loadActs(value, count, acts_list.attr('data-limit'), acts_list);
	});
};

function loadBills(sort, offset, limit, container){
	$.ajax({
		type		: "GET",
		url			: '/ajax/cabinet/bills',
		data		: {
			sort		: sort,
			offset		: offset,
			limit		: limit
		},
		dataType	: "json",
		beforeSend	: function(request){
			container.find('button.more').hide();
			
			if(offset < 1){
				container.find('.accounts__item').remove();
				
				container.attr('data-show', 0);
				
				$('#count_bills').text(0);
			}
		},
		success		: function(response){
			console.log('response:');
			//console.log(response);
			
			if(response.status){
				container.find('button.more').before(response.payload.html);
				
				if(offset < 1){
					if(response.payload.count > 4){
						container.find('button.more').show();
					}
				}else{
					var count = container.find('.accounts__item').length;
					
					if(count == response.payload.count){
						container.find('button.more').hide();
					}
				};
				
				$('#count_bills').text(response.payload.count);
			}
		},
		error		: function(err){
			console.log('error');
		}
	});
};

function loadActs(sort, offset, limit, container){
	$.ajax({
		type		: "GET",
		url			: '/ajax/cabinet/acts',
		data		: {
			sort		: sort,
			offset		: offset,
			limit		: limit
		},
		dataType	: "json",
		beforeSend	: function(request){
			container.find('button.more').hide();
			
			if(offset < 1){
				container.find('.acts__item').remove();
				container.attr('data-show', 0);
				
				$('#count_acts').text(0);
			}
		},
		success		: function(response){
			console.log('response:');
			//console.log(response);
			
			if(response.status){
				container.find('button.more').before(response.payload.html);
				
				if(offset < 1){
					if(response.payload.count > 4){
						container.find('button.more').show();
					}
				}else{
					var count = container.find('.acts__item').length;
					
					if(count == response.payload.count){
						container.find('button.more').hide();
					}
				};
				
				$('#count_acts').text(response.payload.count);
			}
		},
		error		: function(err){
			console.log('error');
		}
	});
};

//

function loadOrders(){
	var page = $('#orders__list');
	
	if(!page.length){
		return false;
	};
	
	$.ajax({
		type		: "GET",
		url			: '/ajax/cabinet/orders',
		data		: {},
		dataType	: "json",
		beforeSend	: function(request){
			page.html('');
			
			$('#count_orders').text(0);
			
			$('.selector ul li').removeClass('act');
			
			$('#all').addClass('act');
			
			$('.orders__item').fadeOut(1);
			$('.orders__item').fadeIn(200);
		},
		success		: function(response){
			console.log('response:');
			//console.log(response);
			
			if(response.status){
				page.html(response.payload.html);
				
				$('#count_orders').text(response.payload.count);
			}
		},
		error		: function(err){
			console.log('error');
		}
	});
};

//

function myAddress(){
	var form = $("#add__address-form");
	
	if(!form.length){
		return false;
	};
	
	var added_file		= $('#addedFile');
	var control_file	= $('#control-file');
	
	var images = [];
	
	var n = 0;
	
	control_file.on('change', function(e){
		e.preventDefault();
		
		var file = e.target.files[0];
		var mime = file.type.split('/');
		
		if(mime[0] != 'image'){
			return false;
		};
		
		console.log('file:');
		console.log(file);
		
		var reader = new FileReader();
		
		reader.onload = function(e) {
			console.log('onload:');
			console.log(e);
			
			images[n] = {
				'name'	: file.name,
				'mime'	: file.type,
				'data'	: b64EncodeUnicode(e.target.result)
			};
			
			added_file.append('<li data-n="'+n+'"><span>'+file.name+'</span><button class="remove-img" data-n="'+n+'" type="button"></button></li>');
			
			n++;
		};
		
		reader.onerror = function(e) {
			console.log('onerror:');
			console.log(e);
		};
		
		reader.readAsBinaryString(file);
		
		//images.append(name, blob, file.name);
	});
	
	form.on('click', 'button.remove-img', function(){
		var current = $(this);
		var n = current.attr('n');
		
		delete images[n];
		
		form.find('li[data-n="'+n+'"]').remove();
	});
	
	var lock = false,
		btn = form.find('button[type="submit"]');
	
	var modal = $('#response');
	
	var addresses__list = $('#addresses__list');
	
	var info_modal = $('#address__info-modal');
	
	form.validate({
		onkeyup			: false,
		focusCleanup	: true,
		focusInvalid	: false,
		errorClass		: "error",
		rules			: {
			name			: {
				required		: true,
				minlength		: 2
			},
			addresses		: {
				required		: true,
				minlength		: 4
			}
		},
		messages		: {
			name			: {
				required		: "Введіть назву компанії",
				minlength		: "Некоректні дані"
			},
			addresses		: {
				required		: "Введіть вашу адресу",
				minlength		: "Некоректні дані"
			}
		},
		submitHandler	: function() {
			if(!lock){
				var form_data = {
					name		: form.find('input[name="name"]').val(),
					addresses	: form.find('input[name="addresses"]').val(),
					images		: images
				};
				
				$.ajax({
					type		: "POST",
					url			: '/ajax/cabinet/add-address',
					method		: "POST",
					data		: JSON.stringify(form_data),
					dataType	: "json",
					contentType	: "application/json; charset=utf-8",
					beforeSend	: function(request){
						lock = true;
						
						btn.attr('disabled', true);
						form.find('label.error').text('').hide();
						
						form.find('.responseMsg').text('');
						
						//modal.modal('hide');
					},
					success		: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
						btn.attr('disabled', false);
						
						if(response.status){
							form.trigger('reset');
							
							images = [];
							added_file.html('');
							
							form.parents('.modal').modal('hide');
							
							modal.find('.responseMsg').text(response.message);
							modal.modal('show');
							
							var el = addresses__list.find('.list__item[data-id="0"]').clone();
							
							el.attr('data-id', response.payload.id);
							el.find('.item__text .name').text(response.payload.name);
							el.find('.btn-more').attr({
								'data-id'		: response.payload.id,
								'data-lat'		: response.payload.lat,
								'data-lng'		: response.payload.lng,
								'data-name'		: response.payload.name,
								'data-address'	: response.payload.addresses
							});
							el.css('display', 'flex');
							
							addresses__list.append(el);
							
							addresses_images[response.payload.id] = response.payload.images;
						}else{
							responseMsg(form, response);
						}
					},
					error		: function(err){
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
	
	addresses__list.on('click', '.btn-address__info', function(){
		var current = $(this);
		
		$('.modal').modal('hide');
		
		var dataLat = $(this).attr('data-lat');
		var dataLng = $(this).attr('data-lng');
		
		var id = current.attr('data-id');
		
		info_modal.find('.remove').attr('data-id', id);
		info_modal.find('.modal-body .address').text(current.attr('data-address'));
		
		var imgs = "";
		
		for(var i = 0; i < addresses_images[id].length; i++){
			imgs += '<img src="'+addresses_images[id][i]+'" alt="" />';
		};
		
		info_modal.find('.galery').html(imgs);
		
		info_modal.modal('show');
		
		initMap(dataLat, dataLng);
	});
	
	info_modal.on('click', 'button.remove', function(){
		var current = $(this);
		
		var id = current.attr('data-id');
		
		$.ajax({
			type		: "POST",
			url			: '/ajax/cabinet/remove-address',
			data		: JSON.stringify({
				id	: id
			}),
			dataType	: "json",
			contentType	: "application/json; charset=utf-8",
			beforeSend	: function(request){
				
			},
			success		: function(response){
				console.log('response:');
				console.log(response);
				
				if(response.status){
					info_modal.modal('hide');
					
					addresses__list.find('.list__item[data-id="'+id+'"]').remove();
					
					delete addresses_images[id];
				}
			},
			error		: function(err){
				console.log('error');
			}
		});
	});
};

function b64EncodeUnicode(str){
	return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function toSolidBytes(match, p1) {
		return String.fromCharCode('0x' + p1);
	}));
};

function requestForm(){
	var form = jQuery("#requestForm");
	
	if(!form.length){
		return false;
	};
	
	var lock = false,
		btn = form.find('button[type="submit"]');
	
	var modal = $('#response');
	
	form.validate({
		onkeyup	: false,
		focusCleanup: true,
		focusInvalid: false,
		errorClass: "error",
		rules: {
			phone: {
				required: true
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
					url: '/ajax/cabinet/request',
					method: "POST",
					data: form.serialize(),
					dataType: "json",
					beforeSend: function(request){
						lock = true;
						
						btn.attr('disabled', true);
						form.find('label.error').text('').hide();
						
						form.find('.responseMsg').text('');
					},
					success: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
						btn.attr('disabled', false);
						
						if(response.status){
							form.trigger('reset');
							
							images = [];
							added_file.html('');
							
							modal.find('.responseMsg').text(response.message);
							modal.modal('show');
						}else{
							responseMsg(form, response);
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

function msgRequestForm(){
	var form = jQuery("#msgRequestForm");
	
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
			text: {
				required: true,
				maxlength: 1000
			}
		},
		messages: {
			text: {
				required: "Це поле обов'язкове для заповнення",
				maxlength: "Введіть не більше 1000 символів"
			}
		},
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/cabinet/request/:id',
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

function settingsForm(){
	var form = jQuery("#settingsForm");
	
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
			surname			: {
				required		: true,
				minlength		: 2,
				maxlength		: 50
			},
			middlename		: {
				required		: true,
				minlength		: 2,
				maxlength		: 50
			},
			extra_phone		: {
				required		: false,
				minlength		: 10,
				maxlength		: 13,
			},
			address			: {
				required		: true,
				minlength		: 8,
				maxlength		: 150
			},
			index			: {
				required		: true,
				minlength		: 5,
				maxlength		: 6
			}
		},
		messages		: {
			name			: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Введіть мінімум 2 символи",
				maxlength		: "Введіть максимум 100 символи"
			},
			surname			: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Введіть мінімум 2 символи",
				maxlength		: "Введіть максимум 100 символи"
			},
			middlename		: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Введіть мінімум 2 символи",
				maxlength		: "Введіть максимум 100 символи"
			},
			email			: {
				required		: "Введіть свій e-mail!",
				email			: "Адреса має бути типу name@domain.com"
			},
			phone			: {
				required		: "Це поле обов'язкове для заповнення",
			},
			extra_phone		: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Введіть мінімум 10 символів",
				maxlength		: "Введіть максимум 13 символів"
			},
			address			: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Введіть не менше 8 символів",
				maxlength		: "Введіть максимум 150 символів"
			},
			index			: {
				required		: "Це поле обов'язкове для заповнення",
				minlength		: "Введіть не менше 5 символів",
				maxlength		: "Введіть не більше 6 символів"
			}
		},
		submitHandler	: function() {
			if(!lock){
				$.ajax({
					type		: "POST",
					url			: '/ajax/user/settings',
					method		: "POST",
					data		: form.serialize(),
					dataType	: "json",
					beforeSend	: function(request){
						lock = true;
						
						btn.attr('disabled', true);
						form.find('label.error').text('').hide();
					},
					success		: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
						btn.attr('disabled', false);
						
						responseMsg(form, response);
						
						// if(response.status){
						//     responseMsg(form, response);
						// }
					},
					error		: function(err){
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

function changePasswordForm(){
	var form = jQuery("#changePasswordForm");
	
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
			password		: {
				required		: true,
				rangelength		: [8, 24]
			},
			new_password	: {
				required		: true,
				rangelength		: [8, 24]
			},
			confirm_password: {
				required		: true,
				rangelength		: [8, 24],
				equalTo			: "#new_password"
			}
		},
		messages: {
			password: {
				required: "Це поле обов'язкове для заповнення",
				rangelength	: "Введіть 8-24 символи"
			},
			new_password: {
				required: "Це поле обов'язкове для заповнення",
				rangelength	: "Введіть 8-24 символи"
			},
			confirm_password: {
				required	: "Це поле обов'язкове для заповнення",
				equalTo		: "Паролі не співпадають",
				rangelength	: "Введіть 8-24 символи"
			}
		},
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: ' /ajax/user/change-password',
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
						
						if(response.status){
							form.trigger('reset');
							// responseMsg(form, response);
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

function orderServiceForm(){
	var form = jQuery("#orderService-form");
	
	if(!form.length){
		return false;
	};
	
	var lock = false,
		btn = form.find('button[type="submit"]');
	
	var modal = $('#response');
	
	form.validate({
		onkeyup			: false,
		focusCleanup	: true,
		focusInvalid	: false,
		errorClass		: "error",
		rules			: {
			service			: {
				required		: true
			},
			date			: {
				required		: true
			},
			city			: {
				required		: true
			},
			time			: {
				required		: true
			},
			comment			: {
				required		: true,
			}
		},
		messages		: {
			service: {
				required: "Це поле обов'язкове для заповнення",
			},
			date: {
				required: "Це поле обов'язкове для заповнення",
			},
			city: {
				required: "Це поле обов'язкове для заповнення",
			},
			time: {
				required: "Це поле обов'язкове для заповнення",
			},
			comment: {
				required: "Це поле обов'язкове для заповнення",
			}
		},
		submitHandler	: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/cabinet/order-service',
					method: "POST",
					data: form.serialize(),
					dataType: "json",
					beforeSend: function(request){
						lock = true;
						
						btn.attr('disabled', true);
						
						form.find('label.error').text('').hide();
						form.find('.responseMsg').text('');
						
						//modal.modal('hide');
					},
					success: function(response){
						console.log('response:');
						console.log(response);
						
						lock = false;
						btn.attr('disabled', false);
						
						if(response.status){
							form.trigger('reset');
							
							form.parents('.modal').modal('hide');
							
							modal.find('.responseMsg').text(response.message);
							modal.modal('show');
							
							loadOrders();
						}else{
							responseMsg(form, response);
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

function newOrderForm(){
	var form = jQuery("#newOrder-form");
	
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
			service: {
				required: true
			},
			date: {
				required: true
			},
			city: {
				required: true
			},
			time: {
				required: true
			},
			comment: {
				required: true,
			}
		},
		messages: {
			service: {
				required: "Це поле обов'язкове для заповнення",
			},
			date: {
				required: "Це поле обов'язкове для заповнення",
			},
			city: {
				required: "Це поле обов'язкове для заповнення",
			},
			time: {
				required: "Це поле обов'язкове для заповнення",
			},
			comment: {
				required: "Це поле обов'язкове для заповнення",
			}
		},
		submitHandler: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/cabinet/order-service',
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
						
						if(response.status){
							form.trigger('reset');
							
							form.parents('.modal').modal('hide');
							
							modal.find('.responseMsg').text(response.message);
							modal.modal('show');
							
							loadOrders();
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

function timepicker(){
    $('.timepicker').timepicker({
        timeFormat: 'HH:mm',
        interval: 60,
        minTime: '5:00',
        maxTime: '23:00pm',
        defaultTime: '10:00',
        startTime: '5:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
};

function select2(){
	$('.custom-select').select2({
		minimumResultsForSearch: -1
	});
};

function maskPhone(){
	var el = $('input[type="tel"]');
	
	if(!el.length){
		return false;
	};
	
	el.mask('+380999999999');
};

function requestMsg(){
	var form = jQuery("#msgRequestForm");
	
	if(!form.length){
		return false;
	};
	
	var lock	= false,
		btn		= form.find('button[type="submit"]');
	
	form.validate({
		onkeyup			: false,
		focusCleanup	: true,
		focusInvalid	: false,
		errorClass		: "error",
		rules			: {
			text			: {
				required		: true,
				minlength		: 1,
				maxlength		: 2500
			}
		},
		messages		: {
			text			: {
				required		: 'Введіть повідомлення',
				minlength		: 'Поле не може бути пустиим',
				maxlength		: 'Максимальна кількість символів 2500'
			}
		},
		submitHandler	: function() {
			if(!lock){
				$.ajax({
					type: "POST",
					url: '/ajax/cabinet/request/:id',
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
							responseMsg(form, response);
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

function responseMsg(form, response){
	form.find('.responseMsg').text(response.message);
};
