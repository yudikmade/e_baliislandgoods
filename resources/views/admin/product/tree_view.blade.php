@extends('admin.layout.template')

@section('style')
<style type="text/css">
    .tree-category{
        border: none;
    }
    .tree-category li:first-child{
        border-top: none;
    }
    .tree-category li{
        border-bottom: none;
        border-left: none;
        border-right: none;
        border-color: #efefef;
    }
    .tree-category li:hover{
        background: #eee;
    }
    .tree-category li.tree1 strong{
        padding-left: 2px;

    }
</style>
@stop

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {{$title_page}}
            <small></small>
        </h1>
            <ol class="breadcrumb">
            <?php echo $breadcrumbs;?>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{$title_form}}</h3>
                    </div>
                    <div class="box-body">
                        <div class="bs-callout bs-callout-warning">
                              {{$information}}
                        </div>
                        <?php
                            echo '<ul class="list-group tree-category">';
                            array_walk_recursive($data_result, function ($item, $key) {

                                $firstData = 1;
                                echo '
                                    <li class="list-group-item tree'.$firstData.'">
                                        <strong><i class="fa fa-caret-right"></i> '.$item->category.'</strong>
                                        <a title="Edit data" href="'.route('control_edit_product_category').'/'.$item->category_id.'" class="pull-right">
                                            <button class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
                                        </a>
                                    </li>
                                ';

                                foreach($item->sub_categories as $key)
                                {
                                    $secondData = 2;
                                    echo '
                                        <li class="list-group-item tree'.$secondData.'">
                                            '.$key->category.'
                                            <a title="Edit data" href="'.route('control_edit_product_category').'/'.$key->category_id.'" class="pull-right">
                                                <button class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
                                            </a>
                                        </li>
                                    ';

                                    foreach($key->sub_categories as $key1)
                                    {
                                        $secondData = 3;
                                        echo '
                                            <li class="list-group-item tree'.$secondData.'">
                                                '.$key1->category.'
                                                <a title="Edit data" href="'.route('control_edit_product_category').'/'.$key1->category_id.'" class="pull-right">
                                                    <button class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
                                                </a>
                                            </li>
                                        ';

                                        foreach($key1->sub_categories as $key2)
                                        {
                                            $secondData = 4;
                                            echo '
                                                <li class="list-group-item tree'.$secondData.'">
                                                    '.$key2->category.'
                                                    <a title="Edit data" href="'.route('control_edit_product_category').'/'.$key2->category_id.'" class="pull-right">
                                                        <button class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
                                                    </a>
                                                </li>
                                            ';
                                        }
                                    }
                                }
                            });
                            echo '</ul>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function(e){
            var paddingLeft = -20;
            for (var i = 1; i <= 20; i++) 
            {
                paddingLeft += 20;
                $('.tree-category').find('.tree'+i).css('paddingLeft', paddingLeft+'px');
            }
        });
    </script>
@stop