<div class="col-md-4 col-md-offset-4">
    <div class="panel panel-default no-radius">
        <div class="panel-body">
            <div class="text-center">
                <img height="100px" src="{{asset(env('URL_IMAGE').'logo.png')}}">
                <h3 class="text-center"></h3>
                <p>Please insert new password.</p>
                
                <img class="none" style="margin-top: 5px; margin-bottom: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="" title="Loading..." />
                
                <div class="show-notif none"></div>
    			
                <div class="panel-body">
                    <form class="form" id="form-new-password" action="{{route('control_reset_password_process')}}" method="post">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <div class="input-group">
                                	<input type="hidden" value="<?php echo $reset_key;?>" id="reset_key" name="reset_key" />
                                    <span class="input-group-addon no-radius"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                    <input id="new_password" name="new_password" placeholder="New password" class="form-control no-radius" oninvalid="setCustomValidity('Please insert new password!')" onchange="try{setCustomValidity('')}catch(e){}" required="" type="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon no-radius"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                    <input id="retype_new_password" name="retype_new_password" placeholder="Re-type new password" class="form-control no-radius" oninvalid="setCustomValidity('Please re-type new password!')" onchange="try{setCustomValidity('')}catch(e){}" required="" type="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <input id="btn-save-new-password" class="btn btn-lg btn-primary login no-radius btn-block" value="Save" type="submit">
                            </div>
                        </fieldset>
                    </form>         
                </div>
            </div>
        </div>
    </div>
    <?=$copy_right?>
</div>