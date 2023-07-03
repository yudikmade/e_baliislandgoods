@extends('admin.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'iCheck/all.css')}}">
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
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'colorpicker/bootstrap-colorpicker.min.css')}}">
<style type="text/css">
    .colorpicker{
        border-radius: 0;
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
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#product" data-toggle="tab">Product Information</a></li>
                        <li><a href="#image" data-toggle="tab">Product's Image</a></li>
                        <li><a href="#sku" data-toggle="tab">SKU (Stock Keeping Unit)</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="product">
                            <input type="hidden" name="urlGetSubCategory" id="urlGetSubCategory" value="{{route('control_get_product_category')}}">
                            @foreach($data_result as $key)
                            <form id="form-save-data" class="form-horizontal" action="{{route('control_edit_product_process')}}" method="post">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="bs-callout bs-callout-warning">
                                      Please edit product the form below.<br>
                                      <!-- - Please input price in rupiah.<br> -->
                                      - Please input unit name (ex. Kg|pcs|other).<br>
                                    </div>
                                    <input type="hidden" name="product_id" id="product_id" value="{{$key->product_id}}">
                                    <input type="hidden" name="form_action" id="update-data" value="update-data">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="status" class="col-sm-12"><span class="text-danger">*</span>Status product</label>
                                            <div class="col-sm-12" style="padding-top: 8px;">
                                                <?=\App\Helper\Common_helper::status_form_edit($key->status)?>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="code_product" class="col-sm-12">Product code</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="code_product" id="code_product" value="{{$key->product_code}}"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_name" class="col-sm-12"><span class="text-danger">*</span>Product name</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="product_name" id="product_name" value="{{$key->product_name}}"  />
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="basic_price" class="col-sm-12"><span class="text-danger">*</span>Basic Price</label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control currency" name="basic_price" id="basic_price" value="{{$key->price_basic}}"/>
                                                    <span class="input-group-addon display-basic_price">{{\App\Helper\Common_helper::convert_to_format_currency($key->price_basic)}}</span>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="price" class="col-sm-12"><span class="text-danger">*</span>Price <!-- (for item sale) --></label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control currency" name="price" id="price" value="{{$key->price}}"/>
                                                    <span class="input-group-addon display-price">{{\App\Helper\Common_helper::convert_to_format_currency($key->price)}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount" class="col-sm-12">Discount</label>
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control currency" name="discount" id="discount" value="{{$key->discount}}"/>
                                                    <span class="input-group-addon display-discount" add-symbol="%">{{\App\Helper\Common_helper::convert_to_format_currency($key->discount)}}</span>
                                                </div>
                                                <small class="text-info"><i>Let discount form blank if you don't want to give discount</i></small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="unit" class="col-sm-12"><span class="text-danger">*</span>Unit name</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="unit" id="unit" value="{{$key->unit}}"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="main_category" class="col-sm-12"><span class="text-danger">*</span>Main Category</label>
                                            <div class="col-sm-12">
                                                <select type="text" class="form-control select2 sub_category" target="sub_category1" name="main_category" id="main_category">
                                                    <option value="">Choose category</option>
                                                    <?php
                                                        foreach ($data_categories as $value) 
                                                        {
                                                            if($data_product_category[0][0] == $value->category_id)
                                                            {
                                                                echo '<option value="'.$value->category_id.'" selected>'.$value->category.'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$value->category_id.'">'.$value->category.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="sub_category1" class="col-sm-12">Sub Category (1)</label>
                                            <div class="col-sm-12">
                                                <select type="text" class="form-control select2 sub_category" target="sub_category2" name="sub_category1" id="sub_category1">
                                                    <?php
                                                        if(count($data_product_category) > 1)
                                                        {
                                                            echo '<option value="'.$data_product_category[1][0].'" selected>'.$data_product_category[1][1].'</option>';
                                                        }
                                                    ?>
                                                    <option value="">Choose sub category</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="sub_category2" class="col-sm-12">Sub Category (2)</label>
                                            <div class="col-sm-12">
                                                <select type="text" class="form-control select2 sub_category" target="sub_category3" name="sub_category2" id="sub_category2">
                                                    <?php
                                                        if(count($data_product_category) > 2)
                                                        {
                                                            echo '<option value="'.$data_product_category[2][0].'" selected>'.$data_product_category[2][1].'</option>';
                                                        }
                                                    ?>
                                                    <option value="">Choose sub category</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="sub_category3" class="col-sm-12">Sub Category (3)</label>
                                            <div class="col-sm-12">
                                                <select type="text" class="form-control select2" name="sub_category3" id="sub_category3">
                                                    <?php
                                                        if(count($data_product_category) > 3)
                                                        {
                                                            echo '<option value="'.$data_product_category[3][0].'" selected>'.$data_product_category[3][1].'</option>';
                                                        }
                                                    ?>
                                                    <option value="">Choose sub category</option>
                                                </select>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="order" class="col-sm-12"><span class="text-danger">*</span>Order (Sorting)</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" name="order" id="order" value="{{$key->order}}"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 no-padding">
                                        <hr>
                                        <div class="col-sm-6">
                                            <!-- <div class="form-group">
                                                <label for="book" class="col-sm-12"><span class="text-danger">*</span>Look book</label>
                                                <div class="col-sm-12">
                                                    <select type="text" class="form-control select2" name="book" id="book">
                                                        <?php
                                                            foreach ($data_book as $value) 
                                                            {
                                                                if($value->book_id == $key->book_id)
                                                                {
                                                                    echo '<option value="'.$value->book_id.'" selected>'.$value->book_title.'</option>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<option value="'.$value->book_id.'">'.$value->book_title.'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="weight" class="col-sm-12"><span class="text-danger">*</span>Weight</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control currency" name="weight" id="weight" value="{{$key->weight}}"/>
                                                        <span class="input-group-addon display-weight"></span>
                                                    </div>
                                                    <small class="text-info"><i>Please input in gram</i></small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="up_image" class="col-sm-12">Size Chart</label>
                                                <div class="col-sm-12">
                                                    <input class="filestyle" id="size_chart" type="file" name="size_chart" data-buttonName="btn-primary" data-buttonText=" Select image">
                                                    <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB).</small>
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($key->size_chart) && $key->size_chart != "")
                                            <div class="col-sm-6">
                                                <img id="show_size_chart_thumb" style="cursor: pointer;" width="100px" data-toggle="modal" data-target="#myModal" src="{{asset(env('URL_IMAGE').'product/thumb/'.$key->size_chart)}}">

                                                <div id="myModal" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">

                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Size Chart</h4>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img id="show_size_chart" style="margin: auto;" class="img-responsive" src="{{asset(env('URL_IMAGE').'product/'.$key->size_chart)}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif -->
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                        <div class="form-group">
                                            <label for="discount" class="col-sm-12">Product description</label>
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="description" id="description"><?=$key->description?></textarea>
                                                <input type="hidden" name="description_text" id="description_text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" name="save" id="btn-save-data" class="btn btn-primary pull-right btn-lg">Save</button> 
                                    <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader-save" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                                </div>
                            </form>
                            @endforeach
                        </div>
                        <div class="tab-pane" id="image">
                            @foreach($data_result as $key)
                            <form id="form-save-data-image" class="form-horizontal" action="{{route('control_edit_product_process')}}" method="post">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="bs-callout bs-callout-warning">
                                        You can upload multiple image.<br>
                                        Please select main image (with green frame), the main image will be the cover image of the product.<br>
                                        <span class="text-primary"><b>Click on image to set main image</b></span>
                                    </div>
                                    <input type="hidden" name="product_id" id="product_id" value="{{$key->product_id}}">
                                    <input type="hidden" name="form_action" id="upload-image" value="upload-image">
                                    <div class="form-group">
                                        <label for="up_image" class="col-sm-12"><span class="text-danger">*</span>Product's image</label>
                                        <div class="col-sm-5">
                                            <input class="filestyle" id="up_image" type="file" name="up_image[]" multiple="multiple" data-buttonName="btn-primary" data-buttonText=" Select image">
                                            <small class="text-primary">* Format jpg|.jpeg|.png (max. size 2MB), upload all images in one size for better result.</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 no-padding plc-image-product">
                                        @foreach ($data_image as $value)
                                            <div class="col-sm-2 data-image {{$value->order == '1' ? 'main' : '' }}">
                                                <img class="img-responsive select-main-image" data-id="{{$value->img_id}}" src="{{asset(env('URL_IMAGE').'product/thumb/'.$value->image)}}">
                                                <button type="button" class="btn btn-danger no-radius delete-image" data-id="{{$value->img_id}}">Delete <i class="fa fa-trash"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" name="save" id="btn-save-data-image" class="btn btn-primary pull-right btn-lg">Save</button> 
                                    <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader-image" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                                </div>
                            </form>
                            @endforeach
                        </div>

                        <div class="tab-pane" id="sku">
                            <div class="row">
                                <div class="table-responsive col-sm-12">
                                    <div class="bs-callout bs-callout-warning">
                                        You can edit product sku here.
                                    </div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalAdd-sku"><i class="fa fa-plus"></i> Add new SKU</button>
                                    <table id="displayData" class="table table-bordered table-striped" style="margin-top: 10px;">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>Size</th>
                                                <th>SKU code</th>
                                                <th>Color</th>
                                                <th>Stock</th>
                                                <th>Order</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                                $no = 1;
                                                foreach ($data_product_sku as $key) 
                                                {
                                                    echo '
                                                        <tr>
                                                            <td width="20px"><input value="'.$key->sku_id.'" type="checkbox" class="minimal" name="data'.$no.'" id="data'.$no.'"></td>
                                                            <td>'.$no.'</td>
                                                            <td>'.$key->size.'</td>
                                                            <td>'.$key->sku_code.'</td>
                                                            <td>
                                                                '.$key->color_name.'<br>
                                                                <div style="width: 25px; height: 25px; background: '.$key->color_hexa.'"></div>
                                                            </td>
                                                            <td>'.$key->stock.'</td>
                                                            <td>'.$key->order.'</td>
                                                            <td>
                                                                '.\App\Helper\Common_helper::data_date($key->date_in).'<br>
                                                                '.\App\Helper\Common_helper::data_date($key->last_update).'
                                                            </td>
                                                            <td>'.\App\Helper\Common_helper::status_default($key->status).'</td>
                                                            <td>
                                                                <a class="btn btn-success btn-sm btn-edit-data-sku" title="Edit data" href="javascript:void(0);" 
                                                                data-id="'.$key->sku_id.'" 
                                                                data-product-id="'.$key->product_id.'" 
                                                                data-code="'.$key->sku_code.'" 
                                                                data-color-name="'.$key->color_name.'" 
                                                                data-color-hexa="'.$key->color_hexa.'" 
                                                                data-order="'.$key->order.'" 
                                                                data-stock="'.$key->stock.'" 
                                                                data-size="'.$key->size.'" 
                                                                data-status="'.$key->status.'"
                                                                ><i class="fa fa-edit"></i></a>
                                                                <a class="btn btn-danger btn-sm" title="Delete data" href="'.route('control_action_product_sku').'/'.$key->sku_id.'" data-confirm="Are you sure delete this data ?"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                    ';
                                                    $no++;
                                                }    
                                            ?>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><input type="checkbox" class="minimal" name="checkAll" id="checkAll"> </th>
                                                <th colspan="7">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                        <ul class="dropdown-menu">
                                                            {{ csrf_field() }}
                                                            <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_product_sku')}}" data-status="1">Active</a></li>
                                                            <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_product_sku')}}" data-status="0">Not Active</a></li>
                                                            <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_product_sku')}}" data-status="delete">Delete</a></li>
                                                        </ul>
                                                        <img class="none" style="margin-top: 0px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                                                    </div> 
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="both-space-md"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('admin.product.modal_edit_sku');

<div id="myModalAdd-sku" class="modal fade" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-save-data-add-sku" class="form-horizontal" action="{{route('control_add_product_sku_process')}}" method="post">
                <div class="modal-header">
                    <button type="button" class="close btn-close-from-add-sku">&times;</button>
                    <h4 class="modal-title">Add product - SKU</h4>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="bs-callout bs-callout-warning">
                          Please input SKU (Stock Keeping Unit) the form below.<br>
                          System will generate product code if you let product code blank.<br>
                        </div>
                        <input type="hidden" class="form-control" name="product_id" id="product_id" value="<?=$data_result[0]->product_id?>"/>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sku_code" class="col-sm-12">SKU Code</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="sku_code" id="sku_code" value=""  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="stock" class="col-sm-12"><span class="text-danger">*</span>Stock</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control currency" name="stock" id="stock" value=""/>
                                        <span class="input-group-addon display-stock"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="col-sm-6 no-padding-left">
                                <div class="form-group">
                                    <label for="color_name" class="col-sm-12">Color Name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="color_name" id="color_name" value=""/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 no-padding-right">
                                <div class="form-group">
                                    <label for="color" class="col-sm-12">Pick Color</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control colorpicker" name="color" id="color" value=""/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="product_name" class="col-sm-12">Order Data</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="new_order" id="new_order" value="{{$new_order}}"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="status" class="col-sm-12">Size</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="size" id="size" value=""  />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="save" id="btn-save-data-add-sku" class="btn btn-primary btn-lg btn-save pull-right">Save</button>
                    <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader-add-sku" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                    <button type="button" class="btn btn-danger btn-lg pull-left btn-close-from-add-sku">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'upload/bootstrap-filestyle.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'iCheck/icheck.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.colorpicker').colorpicker({format: 'hex'});
        if(window.location.hash) 
        {
            var hash =window.location.hash.substring(1);
            $('.nav-tabs li').removeClass('active');
            $('.tab-pane').removeClass('active');
            if(hash == 'product')
            {
                $('.nav-tabs li:first').addClass('active');
                $('#product').addClass('active');
            }
            else if(hash == 'image')
            {
                $('.nav-tabs li:eq(1)').addClass('active');
                $('#image').addClass('active');
            }
            else
            {
                $('.nav-tabs li:eq(2)').addClass('active');
                $('#sku').addClass('active');
            }
        }

        $('.nav-tabs li a').click(function(e){
            var hash = $(this).attr('href');
            location.hash = hash;
        })


        $(":file").filestyle({buttonName: "btn-primary"});

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        var company_profile = document.getElementById("description");
                CKEDITOR.replace(company_profile,{
                language:'en-gb'
            });

        $('.select2').select2();

        $('.sub_category').change(function(e){
            var target = $(this).attr('target');
            var idSub = $(this).val();
            if(idSub != '')
            {
                $.ajax({
                    url: $('#urlGetSubCategory').val(),
                    dataType: 'json',
                    type: 'POST',
                    data: {"_token": $('input[name=_token]').val(), 'id' : idSub},
                    success: function(response) {
                        if(response.trigger == "yes")
                        {
                            // toastr.success(response.notif);
                            $('#'+target).html(response.notif);
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                    },
                    error: function()
                    {
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
            else
            {
                $('#'+target).html('<option value="">Choose sub category</option>');
            }
        });

        $(document).on('click', '.select-main-image', function(e){
            var idImg = $(this).attr('data-id');
            var element = $(this).parent('div');
            if(idImg != '')
            {
                $.ajax({
                    url: $('#form-save-data-image').attr('action'),
                    dataType: 'json',
                    type: 'POST',
                    data: {"_token": $('#form-save-data-image').find('input[name=_token]').val(), 'product_id': $('#form-save-data-image').find('input[name=product_id]').val(), 'id' : idImg, 'form_action': 'main-image'},
                    success: function(response) {
                        if(response.trigger == "yes")
                        {
                            toastr.success(response.notif);
                            $('.plc-image-product .data-image').removeClass('main');
                            element.addClass('main');
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                    },
                    error: function()
                    {
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
            else
            {
                toastr.warning('Please select image first.');
            }
        });

        $(document).on('click', '.delete-image', function(e){
            var idImg = $(this).attr('data-id');
            var element = $(this).parent('div');
            if(idImg != '')
            {
                $.ajax({
                    url: $('#form-save-data-image').attr('action'),
                    dataType: 'json',
                    type: 'POST',
                    data: {"_token": $('#form-save-data-image').find('input[name=_token]').val(), 'product_id': $('#form-save-data-image').find('input[name=product_id]').val(), 'id' : idImg, 'form_action': 'delete-image'},
                    success: function(response) {
                        if(response.trigger == "yes")
                        {
                            toastr.success(response.notif);
                            element.remove();
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                    },
                    error: function()
                    {
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
            else
            {
                toastr.warning('Please select image first.');
            }
        });

        $("#form-save-data-image").validate({
            rules :{
                up_image :{
                    required : true,
                }
            },
            messages: {
                up_image: {
                    required: 'Please upload product\'s image!',
                }
            },
            errorElement: 'small',
            submitHandler: function(form) {
                $("#loader-image").fadeIn();
                $("#btn-save-data-image").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#btn-save-data-image").removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                            toastr.success(response.notif);
                            $('.plc-image-product').append(response.image);
                            $('.bootstrap-filestyle input:eq( 0 )').val("");
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                        $('#loader-image').fadeOut();
                    },
                    error: function()
                    {
                        $("#btn-save-data-image").removeAttr('disabled');
                        $('#loader-image').fadeOut();
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });

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
                    required : 'Please input order product!',  
                }
            },
            errorElement: 'small',
            submitHandler: function(form) {

                CKEDITOR.instances['description'].updateElement();
                $('#description_text').val(CKEDITOR.instances['description'].editable().getText());

                $("#loader-save").fadeIn();
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
                            if(response.new_size_chart !== undefined){
                                $('#show_size_chart').attr('src', response.new_size_chart);
                                $('#show_size_chart_thumb').attr('src', response.new_size_chart_thumb);
                            }

                            toastr.success(response.notif);
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                        $('#loader-save').fadeOut();
                    },
                    error: function()
                    {
                        $("#btn-save-data").removeAttr('disabled');
                        $('#loader-save').fadeOut();
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });

        $('.btn-close-from-add-sku').click(function(e){
            location.reload();
        });

        $("#form-save-data-add-sku").validate({
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
                $("#loader-add-sku").fadeIn();
                $("#btn-save-data-add-sku").attr('disabled', 'disabled');
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#btn-save-data-add-sku").removeAttr('disabled');
                        if(response.trigger == "yes")
                        {
                            toastr.success(response.notif);
                            $('#form-save-data-add-sku').find('input[name=sku_code]').val("");
                            $('#orm-save-data-add-sku').find('input[name=stock]').val("");
                            $('#orm-save-data-add-sku').find('input[name=color_name]').val("");
                            $('#orm-save-data-add-sku').find('input[name=size]').val("");
                            $('#orm-save-data-add-sku').find('input[name=color]').val("");
                            $('#orm-save-data-add-sku').find('input[name=new_order]').val(response.new_order);
                        }
                        else
                        {
                            toastr.warning(response.notif);
                        }
                        $('#loader-add-sku').fadeOut();
                    },
                    error: function()
                    {
                        $("#btn-save-data-add-sku").removeAttr('disabled');
                        $('#loader-add-sku').fadeOut();
                        toastr.error('There is something wrong, please refresh page and try again.');
                    }            
                });
            }
        });
    });
</script>
@stop