<div class="col-md-4 col-md-offset-4 forgot-pass {{$show}}">
    <div class="panel panel-default no-radius">
        <div class="panel-body">
            <div class="text-center">
                <img height="40px" src="{{asset(env('URL_IMAGE').'logo.png')}}">
                <?=$data_header?>
                <div class="panel-body">
                  	<img class="none" style="margin-top: 5px; margin-bottom: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="" title="Loading..." />

                    <div class="show-notif none"></div>

                    <form class="form" id="form-reset-password" action="{{route('control_forgot_password_process')}}" method="post">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon no-radius"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                    <input id="emailReset" name="email" placeholder="Email" class="form-control no-radius" oninvalid="setCustomValidity('Please insert email correctly!')" onchange="try{setCustomValidity('')}catch(e){}"  type="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="no-padding col-xs-6">
                                    @if($show == '')
                                        <a href="{{route('control')}}">
                                            <input class="btn btn-lg btn-danger no-radius btn-block back-login" value="Back" type="button">        
                                        </a>
                                    @else
                                        <input class="btn btn-lg btn-danger no-radius btn-block back-login" value="Back" type="button">        
                                    @endif
                                </div>
                                <div class="no-padding col-xs-6">
                                	<input type="submit" id="btn-reset-password" class="btn btn-lg btn-primary login no-radius btn-block" value="Send" />
                                </div>
                                                    
                            </div>
                        </fieldset>
                    </form>
                                  
                </div>
            </div>
        </div>
    </div>
    <?=$copy_right?>
</div>