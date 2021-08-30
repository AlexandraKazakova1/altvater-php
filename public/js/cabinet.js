$(document).ready (function() {

    calendar();
    modalFade();
    dragAndDrop();

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

    $('.custom-select').select2({
        minimumResultsForSearch: 1000 
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