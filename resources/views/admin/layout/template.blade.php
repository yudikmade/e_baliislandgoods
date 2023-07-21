<!doctype html>
<html class="no-js" lang="{{ Lang::getLocale() }}">
	<head>
		@include('admin.layout.head')
		@yield('style')
	</head>
	<body class="hold-transition skin-blue sidebar-mini">

		<!-- confirmasi delete all-->
		<div id="myModalConfirmationAppActionAll" class="modal fade" role="dialog">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-body">
		                <div class="form-group">
		                    Are you sure ?
		                </div>
		            </div>
		            <div class="modal-footer">
		                <a class="btn btn-danger no-radius" data-dismiss="modal">No <i class="fa fa-close"></i></a>
		                <button type="button" data-href="" class="btn btn-success no-radius" id="actionAllProcess" data-url="" data-status="">Yes <i class="fa fa-check"></i></button> 
		            </div>
		        </div>
		    </div>
		</div>
		<div class="wrapper">
			@include('admin.layout.nav_header')

			@yield('content')

			<div id="modalConfirm"></div>
		</div>


		@include('admin.layout.foot')
		@yield('script')
	</body>
</html>