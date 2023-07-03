$(document).ready(function() {

	$(document).on('keypress', '.currency', function(e){
        var verified = (e.which == 46 || e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
        if (verified) 
        {
            e.preventDefault();
        }
    });

    $('.currency').keyup(function(){
    	var symbol = "";
    	if($('.display-'+$(this).attr('id')).attr('add-symbol') != undefined)
    	{
    		symbol = $('.display-'+$(this).attr('id')).attr('add-symbol');
    	}
        $('.display-'+$(this).attr('id')).text(displayFormatRp($(this).val()) + symbol);
    });

	//confirmasi delete per satu data
	$('a[data-confirm]').click(function(e) {
		var href = $(this).attr('href');
		if (!$('#dataConfirmModal').length) {
			$('#modalConfirm').html(
				'<div id="dataConfirmModal" class="modal fade" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">'+
					'<div class="modal-dialog">'+
						'<div class="modal-content">'+
							'<div class="modal-body"></div>'+
							'<div class="modal-footer">'+
								'<a class="btn btn-danger no-radius" data-dismiss="modal">No <i class="fa fa-close"></i></a>'+
								'<a class="btn btn-success no-radius" id="dataConfirmOK">Yes <i class="fa fa-check"></i></a>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'
			);
		}	 
		
		$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
		$('#dataConfirmOK').attr('href', href);
		$('#dataConfirmModal').modal({show:true});
		return false;
	});

	//cek dan un cek all data
	$('#checkAll').on('ifChecked', function(event){
		$("tbody tr").each(function(index){
			if($(this).closest("tr").find("td input").val()!=undefined)
			{
				$(this).closest("tr").find("td input").iCheck('check');
			}
		});
    });
    $('#checkAll').on('ifUnchecked', function(event){
		$("tbody tr").each(function(index){
			if($(this).closest("tr").find("td input").val()!=undefined)
			{
				$(this).closest("tr").find("td input").iCheck('uncheck');
			}
		});
    });

    //hapus check all
    $('.actionAll').click(function(){
    	var urlAction = $(this).attr('data-url');
    	var dataStatus = $(this).attr('data-status');
    	$('#actionAllProcess').attr('data-url', urlAction);
    	$('#actionAllProcess').attr('data-status', dataStatus);
    	$('#myModalConfirmationAppActionAll').modal({show:true});
	});

    $('#actionAllProcess').click(function(){
    	var urlAction = $(this).attr('data-url');
    	var dataStatus = $(this).attr('data-status');
    	var dataSend=[];
    	$("tbody tr").each(function(index){
			if($(this).closest("tr").find("td input").prop("checked"))
			{
				var idData=$(this).closest("tr").find("td input").val();
				dataSend.push([idData]);
			}
		});
		if(dataSend.length>0)
		{
			$('#myModalConfirmationAppActionAll').modal('toggle');
			$('#loader').show();
            $.ajax({
                url: urlAction,
                dataType: 'json',
                type: 'POST',
                data: {"data": dataSend, "status": dataStatus, '_token': $('input[name=_token]').val()},
                success: function(response, textStatus, XMLHttpRequest)
                {
					$('#loader').fadeOut();
		        	if(response.trigger=="yes")
		        	{
		            	toastr.success(response.notif);
		            	$("tbody tr").each(function(index){
							if($(this).closest("tr").find("td input").val()!=undefined)
							{
								$(this).closest("tr").find("td input").iCheck('uncheck');
							}
						});
						$("tfoot tr").find("th input").iCheck('uncheck');

		        		setTimeout(function(){ location.reload(); }, 4000);
		        	}
		        	else
		        	{
		        		toastr.warning(response.notif);
		        	}
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    toastr.error('There is something wrong, please refresh page and try again.');
		           	$('#loader').fadeOut();
                }
            });
       	}
    });

    $(document).on('click', '#dataConfirmOK', function(e){
    	e.preventDefault();
    	var urlAction = $(this).attr('href');
    	$('#dataConfirmModal').modal('toggle');
    	$.ajax({
            url: urlAction,
            dataType: 'json',
            type: 'POST',
            data: {'_token': $('input[name=_token]').val()},
            success: function(response, textStatus, XMLHttpRequest)
            {
	        	if(response.trigger=="yes")
	        	{
	            	toastr.success(response.notif);
	        		setTimeout(function(){ location.reload(); }, 4000);
	        	}
	        	else
	        	{
	        		toastr.warning(response.notif);
	        	}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                toastr.error('There is something wrong, please refresh page and try again.');
            }
        });
    });

    $('.btn-detail-customer').click(function(e){
    	var urlAction = $(this).attr('data-href');
    	$.ajax({
            url: urlAction,
            dataType: 'json',
            type: 'POST',
            data: {'_token': $('input[name=_token]').val()},
            success: function(response, textStatus, XMLHttpRequest)
            {
	        	if(response.trigger=="yes")
	        	{
	        		$('.display-name-detail').text(response.customer[0].first_name+' '+response.customer[0].last_name);
	        		$('.display-email-detail').html('<a href="mailto:'+response.customer[0].email+'">'+response.customer[0].email+'</a>');
	        		$('.display-phone-number-detail').html('<a href="tel:'+response.customer[0].phone_number+'">'+response.customer[0].phone_number+'</a>');

	        		if(response.customer[0].status == '1')
	        		{
	        			$('.display-status-detail').html('<label class="label label-success">Active</label>');
	        		}
	        		else
	        		{
	        			$('.display-status-detail').html('<label class="label label-danger">Not Active</label>');
	        		}

	        		$('#form-reset-password-customer').find('input[name=customer_id]').val(response.customer[0].customer_id);

	        		var tmpData = '';
	        		for (var i = 0; i < response.shipping.length; i++) 
	        		{
	        			tmpData += '<tr>';
	        			tmpData += '<td>'+(i+1)+'</td>';
	        			tmpData += '<td>'+response.shipping[i].country_name+'</td>';
	        			tmpData += '<td>'+response.shipping[i].province_name+'</td>';
	        			tmpData += '<td>'+response.shipping[i].city_name+' - '+response.shipping[i].subdistrict_name+'</td>';
	        			tmpData += '<td>'+response.shipping[i].detail_address+'</td>';
	        			tmpData += '</tr>';
	        		}

	        		$('.detail-shipping-address').html(tmpData)
	            	$('#dataConfirmModal').modal('toggle');
	        	}
	        	else
	        	{
	        		toastr.warning(response.notif);
	        	}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                toastr.error('There is something wrong, please refresh page and try again.');
            }
        });

    	$('#myModalDetail-customer').modal('show');
    });

    $("#form-reset-password-customer").validate({
        submitHandler: function(form) {
            $("#loader-reset-password-customer").fadeIn();
            $("#btn-reset-password-customer").attr('disabled', 'disabled');
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#btn-reset-password-customer").removeAttr('disabled');
                    if(response.trigger == "yes")
                    {
                        toastr.success(response.notif);
                    }
                    else
                    {
                        toastr.warning(response.notif);
                    }
                    $('#loader-reset-password-customer').fadeOut();
                },
                error: function()
                {
                    $("#btn-reset-password-customer").removeAttr('disabled');
                    $('#loader-reset-password-customer').fadeOut();
                    toastr.error('There is something wrong, please refresh page and try again.');
                }            
            });
        }
    });


    $('.btn-edit-data-sku').click(function(e){

        $('#form-save-data-edit-sku').find('input[name=sku_id]').val($(this).attr('data-id'));
        $('#form-save-data-edit-sku').find('input[name=product_id]').val($(this).attr('data-product-id'));
        $('#form-save-data-edit-sku').find('input[name=sku_code]').val($(this).attr('data-code'));
        $('#form-save-data-edit-sku').find('input[name=color_name]').val($(this).attr('data-color-name'));
        $('#form-save-data-edit-sku').find('input[name=color]').val($(this).attr('data-color-hexa'));
        $('#form-save-data-edit-sku').find('input[name=new_order]').val($(this).attr('data-order'));
        $('#form-save-data-edit-sku').find('input[name=stock]').val($(this).attr('data-stock'));
        $('#form-save-data-edit-sku').find('input[name=size]').val($(this).attr('data-size'));

        if($(this).attr('data-status') == '1')
        {
            $('#form-save-data-edit-sku').find('input#statusActive').prop('checked', true).iCheck('update');
            $('#form-save-data-edit-sku').find('input#statusNotActive').prop('checked', false).iCheck('update');
        }
        else
        {
            $('#form-save-data-edit-sku').find('input#statusNotActive').prop('checked', true).iCheck('update');
            $('#form-save-data-edit-sku').find('input#statusActive').prop('checked', false).iCheck('update');
        }

        $('#form-save-data-edit-sku').find('.display-stock').text($(this).attr('data-stock'));

        $('#myModalEdit-sku').modal('show');
    });

    $("#form-save-data-edit-sku").validate({
        rules :{
            stock :{
                required : true,
            }
        },
        messages: {
            stock: {
                required: 'Please input stock of sku!',
            }
        },
        errorElement: 'small',
        submitHandler: function(form) {
            $("#loader-edit-sku").fadeIn();
            $("#btn-save-data-edit-sku").attr('disabled', 'disabled');
            var formData = new FormData(form);
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#btn-save-data-sku").removeAttr('disabled');
                    if(response.trigger == "yes")
                    {
                        toastr.success(response.notif);
                        setTimeout(function(){ location.reload(); }, 4000);
                    }
                    else
                    {
                        toastr.warning(response.notif);
                    }
                    $('#loader-edit').fadeOut();
                },
                error: function()
                {
                    $("#btn-save-data-edit-sku").removeAttr('disabled');
                    $('#loader-edit-sku').fadeOut();
                    toastr.error('There is something wrong, please refresh page and try again.');
                }            
            });
        }
    });
});


function displayFormatRp(data)
{
	data=data+"";
	var tmp=data.split('.');
	if(tmp.length==2)
	{
		return encodeRp(tmp[0])+"."+tmp[1];
	}
	else
	{
		return encodeRp(data);
	}
}

function encodeRp(bilangan)
{
	// return bilangan;
	var	number_string = bilangan.toString(),
	sisa 	= number_string.length % 3,
	rupiah 	= number_string.substr(0, sisa),
	ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
		
	if (ribuan) {
		separator = sisa ? ',' : '';
		rupiah += separator + ribuan.join(',');
	}

	return rupiah;
}