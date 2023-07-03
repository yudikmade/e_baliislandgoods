<table id="displayData" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Basic Price</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Stock</th>
            <th>Unit</th>
            <th>Weight</th>
            <th>Status</th>
            <th>Time Input</th>
            <th>Time Last Update</th>
            <th>#</th>
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
                        <td>'.$key->category['category'].'</td>
                        <td>'.$key->price_basic.'</td>
                        <td>'.$key->price.'</td>
                        <td>'.$key->discount.'</td>
                        <td>'.$key->stock.'</td>
                        <td>'.$key->unit.'</td>
                        <td>'.$key->weight.'</td>
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