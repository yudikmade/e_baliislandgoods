<div id="myModalRegister" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    		<input type="hidden" name="actionLocation" id="actionLocation" value="{{route('process_shipping_location')}}">
    		<form id="form-create-account" action="{{route('user_register')}}" method="post">
    			{{ csrf_field() }}
    			<input class="form-control" name="from" id="from" type="hidden" value="{{$from}}">
		      	<div class="modal-body">
              <div class="container">
              <br>
		      		<div class="row">
						<div class="col-sm-12">
							<h4>Create a new account here.</h4>
							<p>&nbsp;</p>
						</div>
			      		<div class="col-sm-6">
						  	<div class="form-group">
						    	<label for="first_name" class="form-label">First Name <span>*</span></label>
						    	<input type="text" class="form-control" id="first_name" name="first_name">
						    	<!-- <small class="notif-first_name error none"><i>Please insert first name!</i></small> -->
						  	</div>
						  	<div class="form-group">
						    	<label for="last_name" class="form-label">Last Name <span>*</span></label>
						    	<input type="text" class="form-control" id="last_name" name="last_name">
						    	<!-- <small class="notif-last_name error none"><i>Please insert last name!</i></small> -->
						  	</div>
						  	<div class="form-group">
						  		<div class="row">
							  		<div class="col-sm-12">
							  			<label for="phone_prefix" class="form-label">Phone Number <span>*</span></label>
						  			</div>
						  			<div class="col-sm-12">
								  		<div class="col-sm-4 col-4 no-pdg" style="float:left">
									  		<!-- <select style="width: 100%;" class="select2 form-control pull-left" name="phone_prefix" id="phone_prefix"> -->
											<select style="width: 100%;" class="form-control pull-left" name="phone_prefix" id="phone_prefix">
			                        			<option value="">Prefix</option>
			                        			@foreach($phone_prefix as $phone)
			                        				<option value="{{$phone->country_phone_id}}">{{$phone->name}} ({{$phone->phone_prefix}})</option>
			                        			@endforeach
			                				</select>
			                				<!-- <small class="notif-phone_prefix error none"><i>Please choose phone prefix!</i></small> -->
		                				</div>
		                				<div class="col-sm-12 visible-xs" style="height: 20px;"></div>
		                				<div class="col-sm-8 col-8 no-pdg" style="float:left">
		                					<label for="phone_number" class="visible-xs">No</label>
		            						<input type="text" class="form-control" name="phone_number" id="phone_number">
		            						<!-- <small class="notif-phone_number error none"><i>Please insert phone number!</i></small> -->
		        						</div>
	        						</div>
        						</div>
        					</div>
					  	</div>
					  	<div class="col-sm-6">
						  	<div class="form-group">
						    	<label for="email" class="form-label">Email <span>*</span></label>
						    	<input type="email" class="form-control" id="email" name="email">
						    	<!-- <small class="notif-email error none"><i>Please insert email!</i></small> -->
						  	</div>
						  	<div class="form-group">
						    	<label for="password" class="form-label">Password <span>*</span></label>
						    	<input type="password" class="form-control" id="password" name="password">
						    	<!-- <small class="notif-password error none"><i>Please insert password!</i></small> -->
						  	</div>
						  	<div class="form-group">
						    	<label for="password_r" class="form-label">Repeat Password <span>*</span></label>
						    	<input type="password" class="form-control" id="password_r" name="password_r">
						    	<!-- <small class="notif-password_r error none"><i>Sorry, password is not match!</i></small> -->
						  	</div>
					  	</div>
					  	<div class="col-sm-12">
					  		<br><hr><br>
					  	</div>
					  	<div class="col-sm-6">
				  			<div class="form-group">
						    	<label for="country" class="form-label">Region <span>*</span></label>
						    	<select class="form-control select2" id="country_reg" style="width: 100%;" name="country_reg">
						    		<option value="">Choose region</option>
										@foreach($country  as $countries)
											<option value="{{$countries->id}}">{{$countries->branch_name}}</option>
										@endforeach
						    	</select>
						  	</div>
						  	<div class="form-group none province-options">
						  		<label for="city" class="form-label">Province <span>*</span></label>
									<select class="form-control select2" style="width: 100%;" name="province_reg" id="province_reg">
										<option value="">Choose Province</option>
									</select>
								</div>
						  	<div class="form-group">
						  		<label for="city" class="form-label">City <span>*</span></label>
									<select class="form-control select2" style="width: 100%;" name="city_reg" id="city_reg">
										<option value="">Choose City</option>
									</select>
								</div>

								<div class="form-group">
									<label for="district_reg" class="form-label">(Sub) District</label>
									<input type="text" class="form-control" id="district_reg" name="district_reg" value="" />
								</div>
							</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="address" class="form-label">Address <span>*</span></label>
								<textarea class="form-control no-radius" rows="2" name="address_reg" id="address_reg"></textarea>
							</div>

							<div class="form-group">
								<label for="postalcode" class="form-label">Postal Code <span>*</span></label>
								<input type="text" class="form-control" id="postalcode_reg" name="postalcode_reg" value="" />
							</div>
					  	</div>
				  	</div>
          </div> <!-- end container -->
		      	</div>
		      	<div class="modal-footer">
              <div class="button-modal-center">
                <center>
		      		    <input type="hidden" name="form_action" id="form_action" value="create_account">
		      		    <button type="button" class="btn btn-border-black2 btn-md" data-bs-dismiss="modal">Close</button>
		        	    <button type="submit" class="btn btn-primary btn-md" id="form-create-account-btn">Create</button>
                </center>
              </div>
		      	</div>
	      	</form>
    	</div>
  	</div>
</div>
