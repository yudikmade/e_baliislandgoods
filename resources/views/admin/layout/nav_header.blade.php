<header class="main-header">
    <a class="logo" target="_blank" href="{{url('')}}">
        <span class="logo-mini"><b>{{env('AUTHOR_SITE_SHORT')}}</b></span>
        <span class="logo-lg">{{env('AUTHOR_SITE')}}</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img width="20px" src="{{asset(env('URL_IMAGE').'user.png')}}" class="img-circle" alt=""> 
                        <span class="hidden-xs">{{Session::get(env('SES_BACKEND_NAME'))}}</span>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{asset(env('URL_IMAGE').'user.png')}}" class="img-circle" alt="User Image">
                            <p>
                                {{Session::get(env('SES_BACKEND_NAME'))}}
                                <small>Registered since {{Session::get(env('SES_BACKEND_REGISTERED'))}}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('control_profile')}}" class="btn btn-primary btn-flat"><i class="fa fa-user"></i> Profil</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('control_logout')}}" class="btn btn-danger btn-flat"><i class="fa fa-sign-out"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<aside class="main-sidebar">
    <section class="sidebar">
        
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset(env('URL_IMAGE').'user.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{Session::get(env('SES_BACKEND_NAME'))}}</p>
                <a title="{{Session::get('SES_BACKEND_EMAIL')}}" href="mailto:{{Session::get('SES_BACKEND_EMAIL')}}"><i class="fa fa-circle text-success"></i> {{Session::get(env('SES_BACKEND_EMAIL'))}}</a>
            </div>
        </div>
        
        <ul class="sidebar-menu">
        <?php 

            if(Session::get(env('SES_BACKEND_CATEGORY')) == '1'){
                $navStart=array(
                    1=>'<li class="treeview ',
                    2=>'<li class="treeview ',
                    3=>'<li class="treeview ',
                );

                $navFinish=array(
                    1=>'">
                        <a href="'.route('control_dashboard').'"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                    </li>',
                    2=>'">
                        <a href="#">
                            <i class="fa fa-list"></i> <span>Products</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="'.route('control_products').'"><i class="fa fa-file-text-o"></i> Products</a></li>
                            <li><a href="'.route('control_add_products').'"><i class="fa fa-plus"></i> Add new product</a></li>
                        </ul>  
                    </li>',
                    3=>'">
                        <a href="'.route('control_detail_transactions').'"><i class="fa fa-list"></i> <span>Transactions</span></a>
                    </li>'
                );
                
                $finish = 3;
                
                for ($i = 1; $i <= $finish ; $i++) 
                { 
                    echo $navStart[$i];
                    if($i == $menu_order)
                    {
                    echo "active";
                    }
                    echo $navFinish[$i];  
                }
            } else {
                // master category

                $navStart=array(
                    1=>'<li class="treeview ',
                    2=>'<li class="treeview ',
                    3=>'<li class="treeview ',
                    4=>'<li class="treeview ',
                    5=>'<li class="treeview ',
                    6=>'<li class="treeview ',
                    7=>'<li class="treeview ',
                );

                $masterTax_ = '';
                if(isset($masterTax))
                    $masterTax_ = $masterTax;

                $masterShippingCost_ = '';
                if(isset($masterShippingCost))
                    $masterShippingCost_ = $masterShippingCost;

                $masterInformation_ = '';
                if(isset($masterInformation))
                    $masterInformation_ = $masterInformation;
                
                $masterSocial_ = '';
                if(isset($masterSocial))
                    $masterSocial_ = $masterSocial;

                $masterCurrency_ = '';
                if(isset($masterCurrency))
                    $masterCurrency_ = $masterCurrency;

                $masterFaq_ = '';
                if(isset($masterFaq))
                    $masterFaq_ = $masterFaq;

                $masterCoupon_ = '';
                if(isset($masterCoupon))
                    $masterCoupon_ = $masterCoupon;

                $masterSlide_ = '';
                if(isset($masterSlide))
                    $masterSlide_ = $masterSlide;

                $navFinish=array(
                    1=>'">
                        <a href="'.route('control_dashboard').'"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                    </li>',
                    2=>'">
                        <a href="#">
                            <i class="fa fa-list"></i> <span>Products</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="'.route('control_product_categories').'"><i class="fa fa-file-text-o"></i> Data category</a></li>
                            <li><a href="'.route('control_add_product_category').'"><i class="fa fa-plus"></i> Add category</a></li>
                            <li><a href="'.route('control_products').'"><i class="fa fa-file-text-o"></i> Data products</a></li>
                            <li><a href="'.route('control_add_products').'"><i class="fa fa-plus"></i> Add new product</a></li>
                        </ul>  
                    </li>',
                    3=>'">
                        <a href="#"><i class="fa fa-file-text-o"></i> Transactions <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="'.route('control_transactions').'"><i class="fa fa-file-text-o"></i> Data transactions</a></li>
                        </ul>
                    </li>',
                    4=>'">
                        <a href="'.route('control_customers').'"><i class="fa fa-users"></i> <span>Customers</span></a>
                    </li>',
                    5=>'">
                        <a href="#">
                            <i class="fa fa-file-text-o"></i> <span>Data Master</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="'.$masterTax_.'">
                                <a href="'.route('control_info_tax').'"><i class="fa fa-file-text-o"></i> Tax</a>
                            </li>
                            <li class="'.$masterCurrency_.'">
                                <a href="'.route('control_currency').'"><i class="fa fa-file-text-o"></i> Currency</a>
                            </li>
                            <li class="'.$masterShippingCost_.'">
                                <a href="'.route('control_shipping_cost').'"><i class="fa fa-file-text-o"></i> Shipping Cost</a>
                            </li>
                            <li class="'.$masterFaq_.'">
                                <a href="#"><i class="fa fa-file-text-o"></i> Data FAQ <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="'.route('control_faq').'"><i class="fa fa-file-text-o"></i> FAQ</a></li>
                                    <li><a href="'.route('control_add_faq').'"><i class="fa fa-plus"></i> Add new FAQ</a></li>
                                </ul>
                            </li>
                            <li class="'.$masterCoupon_.'">
                                <a href="#"><i class="fa fa-file-text-o"></i> Data Coupon <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="'.route('control_coupon').'"><i class="fa fa-file-text-o"></i> Coupon</a></li>
                                    <li><a href="'.route('control_add_coupon').'"><i class="fa fa-plus"></i> Add new coupon</a></li>
                                </ul>
                            </li>
                            <li class="'.$masterSlide_.' none">
                                <a href="#"><i class="fa fa-picture-o"></i> Data slide <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="'.route('control_slide').'"><i class="fa fa-file-text-o"></i> Slide</a></li>
                                    <li><a href="'.route('control_add_slide').'"><i class="fa fa-plus"></i> Add new slide</a></li>
                                </ul>
                            </li>
                            <li class="'.$masterInformation_.'">
                                <a href="#"><i class="fa fa-list"></i> Information <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="'.route('control_info_contact').'"><i class="fa fa-file-text-o"></i> Contact Us</a></li>
                                    <li><a href="'.route('control_info_terms_of_payment').'"><i class="fa fa-file-text-o"></i> Terms of Payment</a></li>
                                    <li><a href="'.route('control_info_shipping_and_return').'"><i class="fa fa-file-text-o"></i> Shipping & Return</a></li>
                                    <li><a href="'.route('control_info_privacy_policy').'"><i class="fa fa-file-text-o"></i> Privacy Policy</a></li>
                                </ul>
                            </li>
                            <li class="'.$masterSocial_.'">
                                <a href="#"><i class="fa fa-file-text-o"></i> Data Social Media <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="'.route('control_social_media').'"><i class="fa fa-file-text-o"></i> Social media</a></li>
                                    <li><a href="'.route('control_add_social_media').'"><i class="fa fa-plus"></i> Add Social Media</a></li>
                                </ul>
                            </li>
                        </ul>  
                    </li>',
                    7=>'">
                        <a href="'.route('control_subscribe').'"><i class="fa fa-users"></i> <span>Subscriber</span></a>
                    </li>',
                );
                
                $finish = 5;
                
                for ($i = 1; $i <= $finish ; $i++) 
                { 
                    echo $navStart[$i];
                    if($i == $menu_order)
                    {
                    echo "active";
                    }
                    echo $navFinish[$i];  
                }
            }
        ?>
        </ul>
    </section>
</aside>