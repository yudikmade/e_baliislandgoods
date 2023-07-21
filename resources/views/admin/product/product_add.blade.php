@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<style type="text/css">
    [class^='select2'] {
        border-radius: 0px !important;
    }
    .select2-container .select2-selection{
        padding-bottom: 26px;
        padding-left: 5px;
    }
</style>
@stop

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {{$title_page}}
            <small></small>
        </h1>
            <ol class="breadcrumb">
            <?=$breadcrumbs?>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">{{$title_form}}</h3>
                    </div><!-- /.box-header -->
                    <input type="hidden" name="urlNext" id="urlNext" value="{{route('control_add_products_sku')}}">
                    <form id="form-save-data" class="form-horizontal" action="{{route('control_add_product_process')}}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="bs-callout bs-callout-warning">
                              Please input new product the form below.<br>
                              - System will generate product code if you let it blank.<br>
                              - Please input price in rupiah.<br>
                              - Please input unit name (ex. Kg|pcs|other).<br>
                              - You can upload multiple image.<br>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="product_name" class="col-sm-12"><span class="text-danger">*</span>Product name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="product_name" id="product_name" value=""  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="main_category" class="col-sm-12"><span class="text-danger">*</span>Category</label>
                                    <div class="col-sm-12">
                                        <select type="text" class="form-control select2 sub_category" target="sub_category1" name="main_category" id="main_category">
                                            <option value="">Choose category</option>
                                            <?php
                                                foreach ($data_categories as $key) 
                                                {
                                                    echo '<option value="'.$key->category_id.'">'.$key->category.'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="code_product" class="col-sm-12">Product code</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="code_product" id="code_product" value=""  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="price" class="col-sm-12"><span class="text-danger">*</span>Price <!-- (for item sale) --></label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control currency" name="price" id="price" value=""/>
                                            <span class="input-group-addon display-price"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="discount" class="col-sm-12">Discount</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control currency" name="discount" id="discount" value=""/>
                                            <span class="input-group-addon display-discount" add-symbol="%"></span>
                                        </div>
                                        <small class="text-info"><i>Let discount form blank if you don't want to give discount</i></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12"></div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="unit" class="col-sm-12"><span class="text-danger">*</span>Unit name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="unit" id="unit" value="pcs"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="weight" class="col-sm-12"><span class="text-danger">*</span>Weight</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control currency" name="weight" id="weight" value=""/>
                                            <span class="input-group-addon display-weight"></span>
                                        </div>
                                        <small class="text-info"><i>Please input in gram</i></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="order" class="col-sm-12"><span class="text-danger">*</span>Order (Sorting)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="order" id="order" value="{{$new_order}}"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <hr>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="up_image" class="col-sm-12">Size Chart</label>
                                    <div class="col-sm-12">
                                        <input class="filestyle" id="size_chart" type="file" name="size_chart" data-buttonName="btn-primary" data-buttonText=" Select image">
                                        <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB).</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <hr>
                                <div class="form-group">
                                    <label for="discount" class="col-sm-12">Product description</label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="description" id="description"></textarea>
                                        <input type="hidden" name="description_text" id="description_text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="save" id="btn-save-data" class="btn btn-primary pull-right btn-lg">Save</button> 
                            <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'upload/bootstrap-filestyle.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(":file").filestyle({buttonName: "btn-primary"});

        var company_profile = document.getElementById("description");
                CKEDITOR.replace(company_profile,{
                language:'en-gb'
            });

        $('.select2').select2();

        $("#form-save-data").validate({
            rules :{
                product_name :{
                    required : true,
                },
                price :{
                    required : true,
                },
                unit :{
                    required : true,
                },
                order :{
                    required : true,
                }
            },
            messages: {
                product_name: {
                    required: 'Please input product name!',
                },
                price: {
                    required: 'Please input product price!',
                },
                unit: {
                    required: 'Please input unit name!',  
                },
                order :{
                    required : 'Please input order (sorting) product!',  
                }
            },
            errorElement: 'small',
            submitHandler: function(form) {

                CKEDITOR.instances['description'].updateElement();
                $('#description_text').val(CKEDITOR.instances['description'].editable().getText());

                $("#loader").fadeIn();
                $("#btn-save-data").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#btn-save-data").removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                            toastr.success(response.notif);
                            $('#product_name').val("");
                            $('#code_product').val("");
                            $('#price').val("");
                            $('#discount').val("");
                            $('#unit').val("");
                            $('#weight').val("");
                            $('#main_category').select2('val', '');

                            $('.bootstrap-filestyle input:eq( 0 )').val("");
                            CKEDITOR.instances['description'].setData("");
                            $('#description_text').val("");

                            $('#order').val(response.order);

                            setTimeout(function(){ location.href = $('#urlNext').val()+'/'+response.product_id;}, 4000);
                        }else{
                            toastr.warning(response.notif);
                        }
                        $('#loader').fadeOut();
                    },
                    error: function(){
                        $("#btn-save-data").removeAttr('disabled');
                        $('#loader').fadeOut();
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });
    });
</script>
@stop