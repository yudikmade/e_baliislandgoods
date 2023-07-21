<table id="displayData" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Transaction code</th>
            <th>Symbol</th>
            <th>Code</th>
            <th>Total</th>
            <th>Type of payment</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        
        <?php
            $no = 1;
            foreach ($data_result as $key) 
            {
                $formatPrice = \App\Helper\Common_helper::currency_details_format_split(
                                array(
                                    'transaction_id' => $key->transaction_id,
                                    'nominal' => $key->total_payment,
                                    'transaction' => true
                                )
                            );

                echo '
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.$key->first_name.', '.$key->last_name.'</td>
                        <td>'.$key->transaction_code.'</td>
                        <td>'.$formatPrice[1].'</td>
                        <td>'.$formatPrice[2].'</td>
                        <td>'.$formatPrice[0].'</td>
                        <td>'.\App\Helper\Common_helper::type_of_payment($key->type_payment, true).'</td>
                        <td>'.\App\Helper\Common_helper::date_default($key->transaction_date).'</td>
                        <td>'.\App\Helper\Common_helper::transaction_status($key->status, $key->payment_status, true).'</td>
                    </tr>
                ';
                $no++;
            }    
        ?>
        
    </tbody>
</table>