<div id="myModalDetail-customer" class="modal fade" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail customer</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="bs-callout bs-callout-warning">
                      Information details of customer and shipping address.
                    </div>
                    <div class="col-sm-12 no-padding">
                        <div class="col-sm-8">
                            <form id="form-save-data-edit-sku" class="form-horizontal" action="{{route('control_edit_product_sku_process')}}" method="post">
                                <div class="form-group">
                                    <label for="sku_code" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-9" style="padding-top: 8px;">
                                        : <span class="display-name-detail">Yudik</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sku_code" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9" style="padding-top: 8px;">
                                        : <span class="display-email-detail">email</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sku_code" class="col-sm-3 control-label">Phone number</label>
                                    <div class="col-sm-9" style="padding-top: 8px;">
                                        : <span class="display-phone-number-detail">phone</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sku_code" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9" style="padding-top: 8px;">
                                        : <span class="display-status-detail">Status</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-4">
                            <p><label>Click button below to reset password of this customer :</label></p>
                            <form id="form-reset-password-customer" class="form-horizontal" action="{{route('control_action_customer')}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" class="form-control" name="customer_id" id="customer_id" value=""/>
                                <input type="hidden" class="form-control" name="form_action" id="form_action" value="reset-password"/>
                                <button type="submit" class="btn btn-primary btn-reset-password-customer"> Reset password <i class="fa fa-password"></i></button><br>
                                <img class="none" style="margin-top: 18px; margin-right: 10px;" id="loader-reset-password-customer" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive col-sm-12 no-padding">
                        <hr>
                        <table id="displayData" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Country</th>
                                    <th>Province</th>
                                    <th>City/Subdistric</th>
                                    <th>Detail of address</th>
                                </tr>
                            </thead>
                            <tbody class="detail-shipping-address">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>