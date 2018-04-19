jQuery(function($){
	var alert = $('#flash');
	if(alert.length > 0){
		alert.hide().slideDown(500);

		alert.delay(2500).slideUp(500);

		alert.find('.close').click(function(e){
			alert.slideUp(500);
		})
	}
});
