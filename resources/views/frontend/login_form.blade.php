<div class="row">
    <div class="col-md-6 login-form">
        <div id="section-login">
            <h4><b>Login</b></h4>
            <br>
            <form id="login-form" action="{{route('user_process_authentication')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="form_action" id="form_action" value="login_action">
                <input class="form-control" name="from" id="from" type="hidden" value="{{$from}}">
                <div class="mb-3">
                <label for="login_email" class="form-label">Email Address <span>*</span></label>
                <input type="email" class="form-control" id="login_email" name="login_email" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password <span>*</span></label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="login_pass" id="login_pass" />
                        <span class="input-group-text">
                        <i class="fa fa-eye" id="togglePassword" style="cursor: pointer"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-4 col-5">
                            <button id="login-form-btn" type="submit" class="btn btn-primary col-md-5">LOG IN</button>
                        </div>
                        <!-- <div class="col-md-8 col-7">
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Remember me</label>
                            </div>
                        </div> -->
                    </div>
                </div>
            </form>

            <div class="lost-password"><a id="show-forgot-password" class="pointer">Forgot Password ?</a></div>
        </div>
        <div id="section-forgot-password">
            <h4><b>Forgot Password</b></h4>
            <br>
            <form id="forgot-form" action="{{route('user_forgot_password')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="form_action" id="form_action" value="forgot_action">
                <div class="mb-3">
                <label for="login_email" class="form-label">Email Address <span>*</span></label>
                <input type="email" class="form-control" name="login_forgot_email" id="login_forgot_email" aria-describedby="emailHelp" autocomplete="off">
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-12">
                            <button id="forgot-form-btn" type="submit" class="btn btn-primary col-md-5">RESET</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="lost-password"><a id="hide-forgot-password" class="pointer">Back</a></div>
        </div>
    </div>
    <div class="col-md-6 register-form" align="center">
        <h4><b>Register</b></h4>
        <br>
        <p class="text-center">
            Creating an account will allow you to check out faster, access your order history and track new orders.
        </p>
        <!-- <a href="javascript:void(0);" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#myModalRegister"> -->
            <button type="button" class="btn btn-primary btn-create-accout" data-bs-toggle="modal" data-bs-target="#myModalRegister">REGISTER NOW</button>
        <!-- </a> -->
    </div>
</div>