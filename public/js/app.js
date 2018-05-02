jQuery(function($){
	var alert = $('#flash');
	if(alert.length > 0){
		alert.hide().slideDown(500);

		alert.delay(2500).slideUp(500);

		alert.find('.close').click(function(e){
			alert.slideUp(500);
		})
	}

    var focus = $('#focus');
    if (focus.length > 0) {
        $('html, body').animate({
            scrollTop: focus.offset().top
        }, 500);
    }

    var app = {
        formSubmit : function() {
            var form = $('#upload-form');
            if (form.length > 0)
            {
                form.on('submit', function(event){
                    var valideForm = true;
                    var errorMessage = '';
                    if ($('#files')[0].files.length == 0)
                    {
                        errorMessage = `${errorMessage} Veuillez sélectionner au moins 1 image à uploader. `;
                        console.log(errorMessage);
                        valideForm = false;
                    }

                    if ($('#category').val() == 'none')
                    {
                        if (errorMessage != '')
                            errorMessage = `${errorMessage} <br>`;

                        errorMessage = ` ${errorMessage} Aucune catégorie sélectionné.`;
                        valideForm = false;
                    }


                    if (valideForm == false)
                    {
                        $('#response').html('<div class="alert alert-error">'+errorMessage+'</div>');
                        return false;
                    }
                });
            }
        },
    };

    app.formSubmit();
});
