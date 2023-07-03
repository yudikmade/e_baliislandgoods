
    <div class="table-responsive col-sm-12 no-padding">
        <table id="displayData" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    $no = ($data_categories->currentpage()-1) * $data_categories->perpage() + 1;
                    foreach ($data_categories as $key) 
                    {
                        echo '
                            <tr>
                                <td width="20px"><input value="'.$key->category_id.'" type="checkbox" class="minimal" name="data'.$no.'" id="data'.$no.'"></td>
                                <td>'.$no.'</td>
                                <td>'.$key->category.'</td>
                                <td>'.\App\Helper\Common_helper::check_image($key->image, array('ori' => '/category/', 'thumb' => '/category/thumb/')).'</td>
                                <td>'.\App\Helper\Common_helper::status_default($key->status).'</td>
                                <td>
                                    <a class="btn btn-success btn-sm" title="Edit data" href="'.route('control_edit_product_category').'/'.$key->category_id.'"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-danger btn-sm" title="Delete data" href="'.route('control_action_product_category').'/'.$key->category_id.'" data-confirm="Are you sure delete this data ?"><i class="fa fa-trash"></i></a>
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
                                <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_product_category')}}" data-status="1">Active</a></li>
                                <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_product_category')}}" data-status="0">Not Active</a></li>
                                <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_product_category')}}" data-status="delete">Delete</a></li>
                            </ul>
                            <img class="none" style="margin-top: 0px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                        </div> 
                    </th>
                </tr>
            </tfoot>
        </table>
        <div class="col-sm-12 text-center">
            {{$data_categories->links()}}
        </div>
        <div class="both-space-md"></div>
    </div>
