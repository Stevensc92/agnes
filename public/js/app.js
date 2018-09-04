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
		transformDiv : function() {
            if ($("body").find("#listPicture").length == 0)
                return false;

			var listPicture = $('#listPicture');

			if (listPicture.length > 0) {
				$('.picture span').on('click', function() {
                    var picture = this;
                    var div = picture.parentElement.parentElement;
                    var id = div.getAttribute('data-id');
                    var $hidden = $("div").find(`[data-id="${id}"]`);
                    if ($hidden.hasClass('apply-shake'))
                    {
                        $hidden.removeClass('apply-shake').css({
                            'background-color' : 'inherit',
                            'color' : 'inherit'
                        });
                    }

                    if ($(this).hasClass('active')) {
                        $hidden.find('.fa-undo').parent().removeClass('hidden');
                        return false;
                    }

                    $hidden.find('.hidden').removeClass('hidden');

                    var field = this.getAttribute("data-type");
                    if (field == null)
                        return false;

                    var name = this.getAttribute("data-name");

                    if (field === "textarea") {
                        var html = "<textarea name="+name+">"+$(this).html()+"</textarea>";
                    } else {
                        var html = "<input type=\""+field+"\" name=\""+name+"\" value=\""+$(this).html()+"\" />";
                    }

                    $(this).addClass('active');
                    $(this).html(html).children().focus();
				});

				$('.fa-trash-alt').on('click', function() {
					var $id = $(this).parent().parent().parent().attr('data-id');
                    var div = $("div").find(`[data-id="${$id}"]`);

                    $.post(
						"/agnes/admin/picture/delete",
                        {
                            id: $id,
                        },
                        function(response) {
                            if (typeof response.success !== 'undefined')
                            {
                                div.html('Photo supprimé');
                            }
                            else
                            {
                                if (div.hasClass('apply.shake'))
                                    div.removeclass('apply-shake').css({
                                        "backround-color" : "inherit",
                                        "color" : "inherit"
                                    });

                                div.css({
                                    'background-color' : '#fd4747',
                                    'color' : 'white'
                                }).addClass('apply-shake');
                            }
                        },
                        "json"
					);
				});

                $('.fa-undo').on('click', function() {
                    var $id = $(this).parent().parent().parent().attr('data-id');
                    var div = $("div").find(`[data-id="${$id}"]`);

                    if (div.hasClass('apply-shake'))
                        div.removeClass('apply-shake');

                    div.css({
                        'background-color' : 'inherit',
                        'color' : 'inherit'
                    });

                    $("div").find(`[data-id="${$id}"]`).find('.active').each(function() {
                        console.log($(this).children()[0].getAttribute('type'));
                        var $html = this.getAttribute('data-default');

                        $(this).html($html);
                        // console.log($field);
                        $(this).removeClass('active');
                    });

                    $(this).parent().addClass('hidden');
                    return false;
                });

                $('.fa-check').on('click', function() {
                    console.log('on rentre dans le click sur lupdate');
                    var $id = $(this).parent().parent().parent().attr('data-id');
                    var data = [];

                    $("div").find(`[data-id="${$id}"]`).find('.active').each(function() {
                        var field = this.getAttribute('data-type');
                        var defaultValue = this.getAttribute('data-default');
                        console.log('on a trouvé un input actif : '+field);

                        if (field == 'textarea') {
                            console.log('input textarea trouvé');
                            var value = $(this).children().val();
                            if (value != defaultValue) {
                                data.push([
                                    'description',
                                    value
                                ]);
                            }
                        } else {
                            console.log('input text trouvé');
                            var value = $(this).children().val();
                            if (value != defaultValue) {
                                data.push([
                                    'filename',
                                    value,
                                    defaultValue,
                                ]);
                            }
                        }
                    });

                    console.log('valeur de data : ');
                    console.log(data);
                    if (data.length > 0)
                    {
                        console.log('on commence la requete ajax');
                        $.post(
                            "/agnes/admin/picture/update",
                            {
                                data,
                                id: $id,
                            },
                            function(response) {
                                if (typeof response.fail !== 'undefined')
                                {
                                    console.log('echec');
                                    var div = $("div").find(`[data-id="${response.id}"]`);
                                    if (div.hasClass('apply.shake'))
                                        div.removeclass('apply-shake').css({
                                            "backround-color" : "inherit",
                                            "color" : "inherit"
                                        });

                                    div.css({
                                        'background-color' : '#fd4747',
                                        'color' : 'white'
                                    }).addClass('apply-shake');


                                }
                                else
                                {
                                    console.log('success');
                                    console.log('data retournée :');
                                    console.log(response);
                                    var div = $("div").find(`[data-id="${response.id}"]`);

                                    if (div.hasClass('apply.shake'))
                                    {
                                        div.removeclass('apply-shake').css({
                                            "backround-color" : "inherit",
                                            "color" : "inherit"
                                        });
                                    }

                                    if (response.filename)
                                    {
                                        var input = div.find(`[data-name=filename]`);
                                        input.attr('data-default', response.filename);
                                        input.removeClass('active');
                                        input.html(response.filename);

                                        div.find(`.fa`).parent().addClass('hidden');
                                    }
                                    if (response.description)
                                    {
                                        var input = div.find(`[data-name=description]`);
                                        input.attr('data-default', response.description);
                                        input.removeClass('active');
                                        input.html(response.description);

                                        div.find(`.fa`).parent().addClass('hidden');
                                    }

                                    div.css({
                                        'background-color' : 'green',
                                        'color' : 'white'
                                    });
                                }
                            },
                            'json'
                        );
                    }
                    else
                        console.log('data vide pas de requete ajax');
                });
			}
		},
    };

    app.formSubmit();
    app.transformDiv();
});
