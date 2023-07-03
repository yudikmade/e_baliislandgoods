@extends('admin.layout.template_auth')

@section('content')
<div class="container login">
    <div class="row">
        <div class="row">
            <div class="col-sm-12 both-space-sm"></div>
            <div class="col-sm-12 both-space-sm"></div>
            <?=$view_form?>
        </div>
    </div>
</div>
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.display-forgot').click(function(){
                $('.login-admin').fadeOut(300, function(){
                    $('.forgot-pass').fadeIn(300);
                });    
            });
            
            $('.back-login').click(function(){
                $('.forgot-pass').fadeOut(300, function(){
                    $('.login-admin').fadeIn(300);
                });   
            });

            $("#btn-save-new-password").click(function(){
                $('#form-new-password').ajaxForm({
                    beforeSubmit:  function()
                    {              
                        var count=0; 
                        if($("#retype_new_password").val()=="" ) 
                        {           
                            $("#retype_new_password").focus();      
                            count++;
                        }
                        
                        if($("#new_password").val()=="" ) 
                        {           
                            $("#new_password").focus();       
                            count++;
                        }
                        
                        if(count!=0)
                        {
                            return false;
                        }
                    },
                    beforeSend: function(){
                        $("#loader").fadeIn();
                        $("#btn-save-new-password").attr('disabled', 'disabled');
                    },
                    success: function() 
                    {
                        $("#btn-save-new-password").removeAttr('disabled');
                        
                    },
                    complete: function(response, textStatus, XMLHttpRequest) 
                    {
                        var responseText = jQuery.parseJSON(response.responseText);
                        
                        $('.show-notif').html(responseText.notif);
                        $('#loader').fadeOut(function(){
                            $('.show-notif').fadeIn();
                        });

                        if(responseText.trigger=="yes")
                        {
                            $("#new_password").val("");
                            $("#retype_new_password").val("");
                        }
                        else
                        {
                            setTimeout(function(){$('.show-notif').fadeOut();}, 4000);
                        }
                    },
                    error: function()
                    {
                        $("#btn-save-new-password").removeAttr('disabled');
                        
                        var notif = ''+
                        '<div class="alert alert-danger">'+
                            '<strong>Error,</strong> please try again.'+
                        '</div>';

                        $('.show-notif').html(notif);
                        $('#loader').fadeOut(function(){
                            $('.show-notif').fadeIn();
                        });
                        setTimeout(function(){$('.show-notif').fadeOut();}, 4000);
                    }
                });
            });

            $("#btn-reset-password").click(function(){
                $('#form-reset-password').ajaxForm({
                    beforeSubmit:  function()
                    {               
                        if($(this).find('input[name=email]').val()=="") 
                        {             
                            $(this).find('input[name=email]').focus();      
                            // return false;
                        }
                    },
                    beforeSend: function(){
                        $("#loader").fadeIn();
                        $("#btn-reset-password").attr('disabled', 'disabled');
                    },
                    success: function() 
                    {
                        $("#btn-reset-password").removeAttr('disabled');
                        
                    },
                    complete: function(response, textStatus, XMLHttpRequest) 
                    {
                        var responseText = jQuery.parseJSON(response.responseText);
                        
                        $('.show-notif').html(responseText.notif);
                        $('#loader').fadeOut(function(){
                            $('.show-notif').fadeIn();
                        });
                        setTimeout(function(){$('.show-notif').fadeOut();}, 4000);
                    },
                    error: function()
                    {
                        $("#btn-reset-password").removeAttr('disabled');
                        
                        var notif = ''+
                        '<div class="alert alert-danger">'+
                            '<strong>Error,</strong> please try again.'+
                        '</div>';

                        $('.show-notif').html(notif);
                        $('#loader').fadeOut(function(){
                            $('.show-notif').fadeIn();
                        });
                        setTimeout(function(){$('.show-notif').fadeOut();}, 4000);
                    }
                });
            });
        });
    </script>
@stop