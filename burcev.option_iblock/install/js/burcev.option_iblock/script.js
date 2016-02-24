$(document).ready(function(){
	var jqueryVersion = $.fn.jquery.match('([0-9]{1})\.([0-9]{1,2})\.([0-9]{1,2})');
	if(jqueryVersion[1]>=1){
		if(jqueryVersion[1]>1 || jqueryVersion[2]>7){
			jQuery.fn.extend({
				live: function( types, data, fn ) {
					jQuery( this.context ).on( types, this.selector, data, fn );
					return this;
				}
			});
		}
	}

	$('td.adm-detail-content-cell-r input[type="button"]').each(function(){
		if($(this).attr('value') == "..."){
            var onclick_params = $(this).attr('onclick');
            onclick_params = onclick_params.replace("?lang=ru&IBLOCK_ID=", "?lang=ru&m=y&IBLOCK_ID=");
            $(this).attr('onclick', onclick_params);
        }
	});
	
	$('.roll_the_deep').on('click', function(){
		alert('!');
		/*$.ajax({
		   url: "/bitrix/php_interface/include/burcev.option_iblock/ajax.php",
		   data: {f1: title},
		   success: function(data){
				if(data == "ok"){
				$.fancybox("Вы успешно записались. Наш специалист с вами свяжется");
				}else {
				$.fancybox("Что-то пошло не так..");
				}
		   }
		});*/
	});
});