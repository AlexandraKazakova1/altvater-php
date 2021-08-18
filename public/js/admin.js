$(document).ready(function(){
	open_edit();
});

function regenerate(){
    var btn = $('#btn_regenerate');
    
    if(btn.length > 0){
        var lock = false;
        
        btn.off();
        btn.unbind();
        
        btn.on('click', function(e){
            e.preventDefault();
            
            if(!lock){
                lock = true;
                
                btn.addClass('disable');
                
                $.ajax({
                    url: '/ajax/admin/jslangs',
                    type: "get",
                    dataType: "json",
                    success: function(resp) {
                        lock = false;
                        btn.removeClass('disable');
                        
                        if(resp.status){
                            alert('Файл успешно обновлен');
                        }else{
                            alert('Произошла ошибка');
                        };
                    },
                    error: function(){
                        lock = false;
                        btn.removeClass('disable');
                        
                        alert('Произошла ошибка');
                    }
                });
            };
            
            return false;
        });
    }
};

function open_edit(){
	$('body').on('click', 'table.table.grid-table td', function(e){
		var current = $(this);
		
		if(!current.hasClass('column-__actions__') && e.target.tagName != "A"){
			var tr = current.parents('tr');
			
			window.location.href = window.location.href+'/'+tr.attr("data-key")+'/edit';
		}
	});
};
