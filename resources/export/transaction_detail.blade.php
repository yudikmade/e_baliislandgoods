<table id="displayData" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Transaction Code</th>
            <th>Product Name</th>
            <th>Product Code</th>
            <th>SKU Code</th>
            <th>Size</th>
            <th>Color</th>
            <th>Qty</th>
            <th>Symbol</th>
            <th>Code</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Total</th>
            <th>Status Item</th>
            <th>Status Transaction</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        
        <?php
            $no = ($data_result->currentpage()-1) * $data_result->perpage() + 1;
            foreach ($data_result as $key) 
            {
                $formatPrice = \App\Helper\Common_helper::currency_details_format_split(
                        array(
                            'transaction_id' => $key->transaction_id,
                            'nominal' => $key->price,
                            'transaction' => true
                        )
                    );

                $formatPriceTotal = \App\Helper\Common_helper::currency_details_format_split(
                        array(
                            'transaction_id' => $key->transaction_id,
                            'nominal' => \App\Helper\Common_helper::set_discount(($key->price * $key->qty), $key->discount),
                            'transaction' => true
                        )
                    );


                echo '
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.$key->transaction_code.'</td>
                        <td>'.$key->product_name.'</td>
                        <td>'.$key->product_code.'</td>
                        <td>'.$key->sku_code.'</td>
                        <td>'.$key->size.'</td>
                        <td>'.$key->color_name.'</td>
                        <td>'.$key->qty.'</td>

                        <td>'.$formatPrice[1].'</td>
                        <td>'.$formatPrice[2].'</td>
                        <td>'.$formatPrice[0].'</td>
                        <td>'.$key->discount.' </td>
                        <td>'.$formatPriceTotal[0].'</td>
                        <td>'.\App\Helper\Common_helper::trans_detail_status($key->status, true).'</td>
                        <td>'.\App\Helper\Common_helper::transaction_status($key->status_transaction, $key->payment_status, true).'</td>
                        <td>'.\App\Helper\Common_helper::date_default($key->transaction_date).'</td>
                    </tr>
                ';
                $no++;
            }    
        ?>
        
    </tbody>
</table>