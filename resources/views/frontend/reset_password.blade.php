@extends('frontend.layout.template')

@section('style')
<style type="text/css">
    .go-to-forgot{
        color: #F7941D;
    }
    p.please-insert-new-password {
        font-size: 12px;
    }
    #new_password-error,
    #retype_new_password-error {
        position: absolute;
        top:43px;
    }
</style>
@stop

@section('content')
    <div class="container">
    <div class="featurette-divider"></div><br><br>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6 login-form">
                @if($expired == 'next')
                <h4><b>Reset Password</b></h4>
                <p class="please-insert-new-password">Please enter a new password.</p>
                @endif
                <br>
                @if($expired == 'next')
                <form id="reset-pass-form" action="{{route('user_reset_password_process')}}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="reset_key" id="reset_key" value="{{$reset_key}}">
                    <input type="hidden" name="form_action" id="form_action" value="login_action">
                    <div class="mb-4">
                        <label for="exampleInputPassword1" class="form-label">Password <span>*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="new_password" id="new_password" />
                            <span class="input-group-text">
                            <i class="fa fa-eye" id="togglePassword" style="cursor: pointer"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="exampleInputPassword1" class="form-label">Repeat Password <span>*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="retype_new_password" id="retype_new_password" />
                            <span class="input-group-text">
                            <i class="fa fa-eye" id="togglePassword2" style="cursor: pointer"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4 col-5">
                                <button id="reset-pass-form-btn" type="submit" class="btn btn-primary btn-black col-md-5">RESET</button>
                            </div>
                        </div>
                    </div>
                </form>
                @else 
                <h5 class="alert alert danger text-center">Sorry, the active period of the reset key has expired.<br>Click <a class="go-to-forgot" href="{{route('user_login')}}">here</a> to reset again or log into the system.</h5>
                @endif
            </div>
        </div>
    </div>

    <div class="featurette-divider"></div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        $("#reset-pass-form").validate({
          rules :{
            new_password :{
                required : true,
            },
            retype_new_password :{
                required : true,
            },
          },
          messages: {
            new_password: {
                required: 'New Password is required!',
            },
            retype_new_password: {
                required: 'Repeat Password is required!',
            },
          },
          errorElement: 'small',
          submitHandler: function(form) {
              $("#reset-pass-form-btn").attr('disabled', 'disabled');
              var formData = new FormData(form);
              $.ajax({
                  url: form.action,
                  type: form.method,
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function(response) {
                      $("#reset-pass-form-btn").removeAttr('disabled');
                      if(response.trigger == "yes"){
                        toastr.success(response.notif, '', {timeOut: 3000});
                        setTimeout(function(){ 
                            window.location.href = '{{url('/login')}}';
                        }, 1000);
                      }else{
                        toastr.warning(response.notif);
                      }
                  },
                  error: function()
                  {
                      $("#reset-pass-form-btn").removeAttr('disabled');
                  }            
              });
          }
      });
    });
</script>
<script>
    const togglePassword = document
        .querySelector('#togglePassword');

    const password = document.querySelector('#new_password');

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
<script>
    const togglePassword2 = document
        .querySelector('#togglePassword2');

    const password2 = document.querySelector('#retype_new_password');

    togglePassword2.addEventListener('click', () => {

        // Toggle the type attribute using
        // getAttribure() method
        const type = password
            .getAttribute('type') === 'password' ?
            'text' : 'password';

        password2.setAttribute('type', type);

        // Toggle the eye and bi-eye icon
        document.getElementById('togglePassword2').classList.toggle("fa-eye-slash");
    });
</script>
@stop
