
    <div class="table-responsive col-sm-12 no-padding">
        <table id="displayData" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    $no = ($data_result->currentpage()-1) * $data_result->perpage() + 1;
                    foreach ($data_result as $key) 
                    {
                        echo '
                            <tr>
                                <td>'.$no.'</td>
                                <td>'.$key->category.'</td>
                                <td>'.\App\Helper\Common_helper::convert_to_format_currency($key->cost).'</td>
                                <td>
                                    <a class="btn btn-success btn-sm" title="Edit data" href="'.route('control_edit_shipping_cost').'/'.$key->shipping_cost_id.'"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        ';
                        $no++;
                    }    
                ?>
                
            </tbody>
        </table>
        <div class="col-sm-12 text-center">
            {{$data_result->links()}}
        </div>
        <div class="both-space-md"></div>
    </div>
