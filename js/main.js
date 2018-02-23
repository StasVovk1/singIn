(function(){

	checkCookie ();

	$('form#registracia button').on('click', function (e){
		var form = $(this).parents('form');
		var input = form.find(':input');
		var data = checkInputValid (input,form);
		if (data){
			ajax(data,form);
		}
		return false;
	});

	$('form#loginIn button').on('click', function (e){
		var form = $(this).parents('form');
		var input = form.find(':input');
		var data = checkInputValid (input,form);
		if (data){
			ajax(data,form);
		}
		return false;
	});



	function messError (form,messedg){
		if (messedg){
			form.children('#error-messedg').text(messedg).show();
		}else {
			form.children('#error-messedg').hide().text('');
		}
	}

	function checkInputValid (input,form){
		var messedgError = '';
		var data = {};
		for (var i = 0; i < input.length-1; i++){
			if (!input[i].validity.valid){
				input[i].classList.add('input-error');
				messedgError = input[i].validationMessage;
				input[i].value = '';
			}else {
				if (input[i].value.trim().length !== 0){
					if (input[i].name == 'secondPassword'){
						if (input[i].value != input[i-1].value){
							messedgError = 'Повторите правильно пароль!';
							input[i].classList.add('input-error');
							input[i-1].classList.add('input-error');
						}else {
							input[i-1].classList.remove('input-error');
							input[i].classList.remove('input-error');
							data[input[i].name] = input[i].value;
						}
					}else {
						input[i].classList.remove('input-error');
						data[input[i].name] = input[i].value;
					}
				}else {
					input[i].classList.add('input-error');
					messedgError = 'Заполните эти поля!';
					input[i].value = '';					
				}
			}			
		}
		messError (form, messedgError);
		if (messedgError){
			return false;
		}else {
			return data;
		}
		
	}

	function ajax (data,form){	
		$.ajax({
      url: "main.php",
      type: "POST",
      data: data,
      dateType: 'json',
      success: function(json){
				console.log(json);
				data = JSON.parse(json);
				if (data.error == 0){
					$('.messedg').text(data.mess);
					$('#helloUser').removeClass('hide');
					$('.container').hide();
					form.trigger('reset');
				}else {
					messError (form, data.mess);
				}
      },
      error: function(json){				
				console.log(json);
      }
  	});
  	return true;
	}


	function checkCookie (){
		var data = {
			'checkCookie' : 1
		}
		$.ajax({
      url: "main.php",
      type: "POST",
      data: data,
      dateType: 'json',
      success: function(json){
				data = JSON.parse(json);
				if (data.error == 0){
					$('#helloUser').removeClass('hide');
					$('.messedg').text(data.mess);
					$('.container').hide();
				}else {
					console.log(json);
				}
      },
      error: function(json){				
				console.log(json);
      }
  	});
	}

})();