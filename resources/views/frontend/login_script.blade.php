<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#show-forgot-password').click(function(){
            $('#section-login').fadeOut();
            setTimeout(function(){  $('#section-forgot-password').fadeIn() }, 500);
        })

        $('#hide-forgot-password').click(function(){
            $('#section-forgot-password').fadeOut()
            setTimeout(function(){  $('#section-login').fadeIn() }, 500);
        })

        var countrySelected = "";
        $('#city_reg').select2({
            placeholder: "Searching location",
            minimumInputLength: 3,
            ajax: {
                type: 'post',
                delay: 300,
                data: function (params) {
                    return {
                        search: params.term, // search term,
                        'data_id': $('#country_reg').val(),
                        'trigger': 'city_tgi',
                        '_token': $('input[name=_token]').val()
                    };
                },
                url: $('#actionLocation').val(),
                processResults: function (data) {
                    return {
                        results: $.map(data.notif, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.text,
                            };
                        })
                    };
                }
            }
        });

        // $('#country_reg').change(function(e){
	    // 	var data_id = $(this).val();
	    // 	var trigger = $(this).attr('id');

	    // 	// if($('#country_reg').val() == '236')
	    // 	// {
    	// 	// 	$('.select-national').fadeIn();
	    // 	// }
	    // 	// else
	    // 	// {
	    // 	// 	if($('#country_reg').val() != '')
	    // 	// 	{
	    // 	// 		$('.select-national').fadeOut();
	    // 	// 	}
	    // 	// }

        //     // $('.select-national').fadeIn();

	    // 	// if($('#country_reg').val() == '236')
	    // 	// {
	    // 		var urlAction = $('#actionLocation').val();
	    // 		var new_trigger = trigger.split('_reg');
		//         $.ajax({
		//             url: urlAction,
		//             dataType: 'json',
		//             type: 'POST',
		//             data: {
		//             	'data_id': data_id, 
		//             	'trigger': new_trigger[0],
		//             	'_token': $('input[name=_token]').val()
		//             },
		//             success: function(response, textStatus, XMLHttpRequest)
		//             {
		//                 if(response.trigger=="yes")
	    //             	{
	    //             		if(trigger == 'country_reg')
	    //             		{
        //                         $('#city_reg').html(response.notif);
        //                         // if(response.notif.length > 50){
                                    
        //                         // } else {
        //                         //     $('.select-national').fadeOut();
        //                         // }
	    //             		}
	    //             		// else if(trigger == 'province_reg')
	    //             		// {
	    //             		// 	$('#city_reg').html(response.notif);
	    //             		// }
	    //             		// else
	    //             		// {
	    //             		// 	$('#subdistrict_reg').html(response.notif);
	    //             		// }
		//                 }
		//                 else
		//                 {
		//                      toastr.warning(response.notif)
		//                 }
		//             },
		//             error: function(XMLHttpRequest, textStatus, errorThrown)
		//             {
		//             	toastr.remove();
		//                 toastr.error('There is something wrong, please refresh page and try again.');
		//             }
		//         });
	    // 	// }
	    // });

        $("#login-form").validate({
          rules :{
            login_email :{
                required : true,
            },
            login_pass :{
                required : true,
            },
          },
          messages: {
            login_email: {
                required: 'Email address is required!',
            },
            login_pass: {
                required: 'Password is required!',
            },
          },
          errorElement: 'small',
          submitHandler: function(form) {
              $("#login-form-btn").attr('disabled', 'disabled');
              var formData = new FormData(form);
              $.ajax({
                  url: form.action,
                  type: form.method,
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function(response) {
                      $("#login-form-btn").removeAttr('disabled');
                      if(response.trigger == "yes"){
                        toastr.success(response.notif, '', {timeOut: 3000});
                        if(response.next_path == ''){
                            setTimeout(function(){ 
                                window.location.href = '{{url('/')}}';
                            }, 1000);
                        }else {
                            setTimeout(function(){ 
                                window.location.href = response.next_path;
                            }, 1000);
                        }
                      }else{
                        toastr.warning(response.notif);
                      }
                  },
                  error: function()
                  {
                      $("#login-form-btn").removeAttr('disabled');
                  }            
              });
          }
      });

      $("#forgot-form").validate({
          rules :{
            login_forgot_email :{
                required : true,
            }
          },
          messages: {
            login_forgot_email: {
                required: 'Email address is required!',
            }
          },
          errorElement: 'small',
          submitHandler: function(form) {
              $("#forgot-form-btn").attr('disabled', 'disabled');
              var formData = new FormData(form);
              $.ajax({
                  url: form.action,
                  type: form.method,
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function(response) {
                      $("#forgot-form-btn").removeAttr('disabled');
                      if(response.trigger == "yes"){
                        toastr.success(response.notif);
                        $('#login_forgot_email').val('');
                      }else{
                        toastr.warning(response.notif);
                      }
                  },
                  error: function()
                  {
                      $("#forgot-form-btn").removeAttr('disabled');
                  }            
              });
          }
      });

      $("#form-create-account").validate({
          rules :{
            first_name :{
                required : true,
            },
            last_name :{
                required : true,
            },
            phone_prefix :{
                required : true,
            },
            phone_number :{
                required : true,
            },
            email :{
                required : true,
            },
            password :{
                required : true,
            },
            password_r :{
                required : true,
            },
            country_reg :{
                required : true,
            },
            city_reg :{
                required : true,
            },
            address_reg :{
                required : true,
            },
            postalcode_reg :{
                required : true,
            },
          },
          messages: {
            first_name: {
                required: 'Please input first name!',
            },
            last_name: {
                required: 'Please input last name!',
            },
            phone_number: {
                required: 'Please input phone number!',
            },
            phone_prefix: {
                required: 'Please choose prefix!',
            },
            email: {
                required: 'Please input email address!',
            },
            password: {
                required: 'Please input password!',
            },
            password_r: {
                required: 'Please retype password!',
            },
            country_reg: {
                required: 'Please choose region!',
            },
            city_reg: {
                required: 'Please choose city!',
            },
            address_reg: {
                required: 'Please input address!',
            },
            postalcode_reg: {
                required: 'Please input postal code!',
            },
          },
          errorElement: 'small',
          submitHandler: function(form) {
              $("#form-create-account-btn").attr('disabled', 'disabled');
              var formData = new FormData(form);
              $.ajax({
                  url: form.action,
                  type: form.method,
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function(response) {
                      $("#form-create-account-btn").removeAttr('disabled');
                      if(response.trigger == "yes"){
                        
                        $('#first_name').val('');
                        $('#last_name').val('');
                        $('#email').val('');
                        $('#phone_number').val('');
                        $('#password').val('');
                        $('#password_r').val('');

                        $('#address_reg').val('');
                        $('#postalcode_reg').val('');
                        $('#district_reg').val('');

                        // $('#phone_prefix').select2('val', '');
                        // $('#country_reg').select2('val', '');
                        // $('#province_reg').select2('val', '');
                        // $('#city_reg').select2('val', '');
                        // $('#subdistrict_reg').select2('val', '');

                        $('#phone_prefix').val('val', '');
                        $('#country_reg').val('val', '');
                        // $('#province_reg').val('val', '');
                        $('#city_reg').val('val', '');

                        toastr.success(response.notif, '', {timeOut: 3000});

                        setTimeout(function(){ 
                            location.reload();
                        }, 3000);

                      }else{
                        toastr.warning(response.notif);
                      }
                  },
                  error: function()
                  {
                      $("#form-create-account-btn").removeAttr('disabled');
                  }            
              });
          }
      });
    });
</script>
<script>
        const togglePassword = document
            .querySelector('#togglePassword');

        const password = document.querySelector('#login_pass');

        togglePassword.addEventListener('click', () => {

            // Toggle the type attribute using
            // getAttribure() method
            const type = password
                .getAttribute('type') === 'password' ?
                'text' : 'password';

            password.setAttribute('type', type);

            // Toggle the eye and bi-eye icon
             document.getElementById('togglePassword').classList.toggle("fa-eye-slash");
        });
</script>