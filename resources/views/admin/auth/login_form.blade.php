<div class="col-md-4 col-md-offset-4 login-admin">
    <div class="panel panel-default no-radius">
        <div class="panel-body">
            <div class="text-center">
                <img height="100px" src="{{asset(env('URL_IMAGE').'logo.png')}}">
                <h3 class="text-center">Login here !</h3>
                <p>Please insert email and password</p>

                @if(Session::get('status') == 400)
                <div class="icon-remove-sign alert alert-danger text-left">
                    {{Session::get('notif')}}
                </div>
                @endif

                @if($errors->first())
                    <div class="icon-remove-sign alert alert-danger text-left">
                        @foreach ($errors->all() as $messages)
                            {{$messages}}<br>
                        @endforeach
                    </div>
                @endif

                <div class="panel-body">
                  
                    <form class="form" action="{{route('control_authentication')}}" method="post">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon no-radius"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                    <input id="email" value="{{$emailInput}}" name="email" placeholder="Email" class="form-control no-radius" oninvalid="setCustomValidity('Please insert email!')" onchange="try{setCustomValidity('')}catch(e){}" required="" type="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon no-radius"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                    <input id="password" value="" name="password" placeholder="Password" class="form-control no-radius" oninvalid="setCustomValidity('Please insert password!')" onchange="try{setCustomValidity('')}catch(e){}" required="" type="password">
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <div class="input-group">
                                    <input type="checkbox" class="minimal" name="remember" id="remember"> remember me?
                                </div>
                            </div> -->
                            <div class="form-group">
                                <input class="transition btn btn-lg btn-primary login no-radius btn-block" value="Login" type="submit">
                            </div>
                        </fieldset>
                    </form>
                    <div style="float:right; font-size: 80%; position: relative; top:-10px; cursor: pointer;"><a class="display-forgot">Forgot password?</a></div>         
                </div>
            </div>
        </div>
    </div>
    <?=$copy_right?>
</div>