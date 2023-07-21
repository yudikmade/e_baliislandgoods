<div id="myModalEdit-sku" class="modal fade" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-save-data-edit-sku" class="form-horizontal" action="{{route('control_edit_product_sku_process')}}" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit product - SKU</h4>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="bs-callout bs-callout-warning">
                          Please edit SKU (Stock Keeping Unit) the form below.
                        </div>
                        <input type="hidden" class="form-control" name="product_id" id="product_id" value=""/>
                        <input type="hidden" class="form-control" name="sku_id" id="sku_id" value=""/>
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
                                        <input type="text" autocomplete="off" class="form-control colorpicker" autocomplete="off" name="color" id="color" value=""/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="product_name" class="col-sm-12">Order Data</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="new_order" id="new_order" value=""  />
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="status" class="col-sm-12"><span class="text-danger">*</span>Status</label>
                                <div class="col-sm-12" style="padding-top: 8px;">
                                    <input type="radio" class="minimal" name="status" id="statusActive" value="1"/> <label for="statusActive">Active</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" class="minimal" name="status" id="statusNotActive" value="0"/> <label for="statusNotActive">Not Active</label><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="save" id="btn-save-data-edit-sku" class="btn btn-primary btn-lg btn-save pull-right">Save</button>
                    <img class="pull-right none" style="margin-top: 18px; margin-right: 10px;" id="loader-edit-sku" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                    <button type="button" class="btn btn-danger btn-lg pull-left" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>