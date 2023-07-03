
    <div class="table-responsive col-sm-12 no-padding">
        <table id="displayData" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Code</th>
                    <th>Symbol</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    $no = ($data_currency->currentpage()-1) * $data_currency->perpage() + 1;
                    foreach ($data_currency as $key) 
                    {
                        echo '
                            <tr>';
                                if($key->status_permanent != '1')
                                {
                                    echo '<td width="20px"><input value="'.$key->currency_id.'" type="checkbox" class="minimal" name="data'.$no.'" id="data'.$no.'"></td>';
                                }
                                else
                                {
                                    echo '<td></td>';
                                }
                        echo '
                                <td>'.$no.'</td>
                                <td>'.$key->code.'</td>
                                <td>'.$key->symbol.'</td>
                                <td>'.\App\Helper\Common_helper::convert_to_format_currency($key->rate).'</td>
                                <td>'.\App\Helper\Common_helper::status_default($key->status).'</td>';

                                if($key->status_permanent != '1')
                                {
                                    echo '
                                        <td>
                                            <a class="btn btn-success btn-sm" title="Edit data" href="'.route('control_edit_currency').'/'.$key->currency_id.'"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm" title="Delete data" href="'.route('control_action_currency').'/'.$key->currency_id.'" data-confirm="Are you sure delete this data ?"><i class="fa fa-trash"></i></a>
                                        </td>
                                    ';
                                }
                                else
                                {
                                    echo '
                                        <td>
                                            <a class="btn btn-success btn-sm" title="Edit data" href="'.route('control_edit_currency').'/'.$key->currency_id.'"><i class="fa fa-edit"></i></a>
                                        </td>
                                    ';
                                }
                        echo '
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
                                <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_currency')}}" data-status="1">Active</a></li>
                                <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_currency')}}" data-status="0">Not Active</a></li>
                                <li><a href="javascript:void(0);" class="actionAll" data-url="{{route('control_action_currency')}}" data-status="delete">Delete</a></li>
                            </ul>
                            <img class="none" style="margin-top: 0px; margin-right: 10px;" id="loader" src="{{asset(env('URL_IMAGE').'loader.gif')}}" alt="Loading...." title="Loading...." />
                        </div> 
                    </th>
                </tr>
            </tfoot>
        </table>
        <div class="col-sm-12 text-center">
            {{$data_currency->links()}}
        </div>
        <div class="both-space-md"></div>
    </div>
