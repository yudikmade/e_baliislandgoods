<table id="displayData" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>SKU code</th>
            <th>Color</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Time Input</th>
            <th>Time Last Update</th>
        </tr>
    </thead>
    <tbody>
        
        <?php
            $no = 1;
            foreach ($data_result as $key) 
            {
                echo '
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.$key->product_code.'</td>
                        <td>'.$key->product_name.'</td>
                        <td>'.$key->size.'</td>
                        <td>'.$key->sku_code.'</td>
                        <td>'.$key->color_name.'</td>
                        <td>'.$key->stock.'</td>
                        <td>'.\App\Helper\Common_helper::status_default($key->status, true).'</td>
                        <td>'.\App\Helper\Common_helper::date_default($key->date_in).'</td>
                        <td>'.\App\Helper\Common_helper::date_default($key->last_update).'</td>
                    </tr>
                ';
                $no++;
            }    
        ?>
        
    </tbody>
</table>