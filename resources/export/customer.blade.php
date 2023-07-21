<table id="displayData" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone number</th>
            <th>Status</th>
            <th>Time Register</th>
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
                        <td>'.$key->first_name.' '.$key->last_name.'</td>
                        <td>'.$key->email.'</td>
                        <td>'.$key->phone_number.'</td>
                        <td>'.\App\Helper\Common_helper::status_default($key->status, true).'</td>
                        <td>'.\App\Helper\Common_helper::date_default($key->register_date).'</td>
                        <td>'.\App\Helper\Common_helper::date_default($key->last_update).'</td>
                    </tr>
                ';
                $no++;
            }    
        ?>
        
    </tbody>
</table>