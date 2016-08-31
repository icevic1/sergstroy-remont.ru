<div class="box no-margin"><?php //var_dump(Customer::input_hidden_addresses());?>
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/customer/save_customer')?>" class="btn-link"><i class="cus-add"></i> New Customer</a>
		</div>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/customer/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Back</a>
		</div>
    </div>
    <div class="box-content">
    	<?php if( !empty($this->form_validation->error_array())) :?>
    	<div id="message">
		    <div style="padding: 5px;">
		        <div id="inner-message" class="alert fade in alert-error">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		            <ul class="list-group">
			            <?php foreach ($this->form_validation->error_array() as $errorItem) :?>
			            <li class="list-group-item list-group-item-danger"><?php echo $errorItem;?></li>
			            <?php endforeach;?>
		            </ul>
		        </div>
		    </div>
		</div>
		<?php endif;?>
		<?php echo form_open('admin/customer/save_customer/'. ((isset($loadedItem['WebCustId']))? $loadedItem['WebCustId'] : ''), array('id'=>'frm_edit_customer','name'=>'frm_edit_customer','class'=>'form-horizontal')); ?>
		<input type="hidden" name="customer[WebCustId]" value="<?php echo set_value('customer[WebCustId]', ((isset($loadedItem['WebCustId']))? $loadedItem['WebCustId']:'')); ?>" />
		<input type="hidden" name="customer[CustId]" value="<?php echo set_value('customer[CustId]', ((isset($loadedItem['CustId']))? $loadedItem['CustId']:'')); ?>" />
		<input type="hidden" id="addrIndex" name="addrIndex" value="0" />
	    <div class="cust-holder">
	    	<h3 class="box-hd">General Customer Info<a class="btn-link pull-right" href="<?php echo site_url('admin/customer/save_customer/');?>" ><i class="cus-add"></i> New Customer</a></h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Corporation Name</td><td class="input-field"><input id="CustName" name="customer[CustName]" type="text" value="<?php echo set_value('customer[CustName]', ((isset($loadedItem['CustName']))? $loadedItem['CustName']:'')); ?>" maxlength="256" /></td>
	    				<td class="input-label">Corporation Short Name</td><td class="input-field"><input id="CustShortName" name="customer[CustShortName]" type="text" value="<?php echo set_value('customer[CustShortName]', ((isset($loadedItem['CustShortName']))? $loadedItem['CustShortName']:'')); ?>" maxlength="256" /></td>
	    				<td class="input-label">Corporation Code</td><td class="input-field"><input id="CustCode" name="customer[CustCode]" type="text" readonly="readonly" value="<?php echo set_value('customer[CustCode]', ((isset($loadedItem['CustCode']))? $loadedItem['CustCode']:'')); ?>" maxlength="64" /></td>
	    			</tr>
	    			<tr><td class="input-label required">Corporation Type</td><td class="input-field"><?php echo form_dropdown('customer[CustType]', $CustTypes = array_replace(array(''=>'...'),Customer_model::$CustTypes), $default = ((isset($loadedItem['CustType']))? $loadedItem['CustType']:''), 'id="CustType"');?></td>
	    				<td class="input-label required">Certificate Type</td><td class="input-field"><?php echo form_dropdown('customer[CertificateTypeId]', $CertificateTypeIds = array_replace(array(''=>'...'),Customer_model::$CertificateTypes), $default = ((isset($loadedItem['CertificateTypeId']))? $loadedItem['CertificateTypeId']:''), 'id="CertificateTypeId"');?></td>
	    				<td class="input-label required">Certificate No.</td><td class="input-field"><input id="CertificateNumId" name="customer[CertificateNumId]" type="text" value="<?php echo set_value('customer[CertificateNumId]', ((isset($loadedItem['CertificateNumId']))? $loadedItem['CertificateNumId']:'')); ?>" maxlength="32" /></td>
	    			</tr>
	    			<tr><td class="input-label">Issued Date</td><td class="input-field"><input id="IssuedDate" name="customer[IssuedDate]" type="text" value="<?php echo set_value('customer[IssuedDate]', ((isset($loadedItem['IssuedDate']))? date('Y-m-d', strtotime($loadedItem['IssuedDate'])):'')); ?>" class="datepicker" /></td>
	    				<td class="input-label">BRN Expiry Date</td><td class="input-field"><input id="BrnExpiryDate" name="customer[BrnExpiryDate]" type="text" value="<?php echo set_value('customer[BrnExpiryDate]', ((isset($loadedItem['BrnExpiryDate']))? date('Y-m-d', strtotime($loadedItem['BrnExpiryDate'])):'')); ?>" class="datepicker" /></td>
	    				<td class="input-label">Industry</td><td class="input-field"><?php echo form_dropdown('customer[Industry]', $Industries = array(), $default = ((isset($loadedItem['Industry']))? $loadedItem['Industry']:''), 'id="Industry"');?></td>
	    			</tr>
	    			<tr><td class="input-label">Sub Industry</td><td class="input-field"><input id="SubIndustry" name="customer[SubIndustry]" type="text" value="<?php echo set_value('customer[SubIndustry]', ((isset($loadedItem['SubIndustry']))? $loadedItem['SubIndustry']:'')); ?>" maxlength="16" /></td>
	    				<td class="input-label">Phone No.</td><td class="input-field"><input id="CustPhoneNumber" name="customer[CustPhoneNumber]" type="text" value="<?php echo set_value('customer[CustPhoneNumber]', ((isset($loadedItem['CustPhoneNumber']))? $loadedItem['CustPhoneNumber']:'')); ?>" maxlength="64" /></td>
	    				<td class="input-label">Email</td><td class="input-field"><input id="CustEmail" name="customer[CustEmail]" type="text" value="<?php echo set_value('customer[CustEmail]', ((isset($loadedItem['CustEmail']))? $loadedItem['CustEmail']:'')); ?>" maxlength="128" /></td>
	    			</tr>
	    			<tr><td class="input-label">Fax No.</td><td class="input-field"><input id="CustFaxNumber" name="customer[CustFaxNumber]" type="text" value="<?php echo set_value('customer[CustFaxNumber]', ((isset($loadedItem['CustFaxNumber']))? $loadedItem['CustFaxNumber']:'')); ?>" /></td>
	    				<td class="input-label">Customer Guide</td><td class="input-field"><?php echo form_dropdown('customer[CustLevel]', $CustLevels = array(''=>'...', '1'=>'Normal','2'=>'VIP'), $default = ((isset($loadedItem['CustLevel']))? $loadedItem['CustLevel']:''), 'id="CustLevel"');?></td>
	    				<td class="input-label">Size Level</td><td class="input-field"><?php echo form_dropdown('customer[CustSize]', $CustSizes = array_replace(array(''=>'...'), Customer_model::$CustSizes), $default = ((isset($loadedItem['CustSize']))? $loadedItem['CustSize']:''), 'id="CustSize"');?></td>
	    			</tr>
	    			<tr><td class="input-label required">Register Date</td><td class="input-field"><input id="RegisterDate" name="customer[RegisterDate]" type="text" value="<?php echo set_value('customer[RegisterDate', ((isset($loadedItem['RegisterDate']))? date('Y-m-d', strtotime($loadedItem['RegisterDate'])):'')); ?>" class="datepicker" /></td>
	    				<td class="input-label">Register Capital</td><td class="input-field"><?php echo form_dropdown('customer[RegisterCapital]', $RegisterCapitals = Customer_model::webRegisterCapitals(), $default = ((isset($loadedItem['RegisterCapital']))? $loadedItem['RegisterCapital']:''), 'id="RegisterCapital"');?></td>
	    				<td class="input-label">Parent Customer</td><td class="input-field"><?php echo form_dropdown('customer[ParentCustId]', $ParentCustumers = array(''=>'...'), $default = ((isset($loadedItem['ParentCustId']))? $loadedItem['ParentCustId']:''), 'id="ParentCustId"');?></td>
	    			</tr>
	    			<tr><td class="input-label">Remark</td><td class="input-field"><input id="Remark" name="customer[Remark]" type="text" value="<?php echo set_value('customer[Remark]', ((isset($loadedItem['Remark']))? $loadedItem['Remark']:'')); ?>" maxlength="512" /></td>
	    				<td class="input-label">Customer Language</td><td class="input-field"><?php echo form_dropdown('customer[CustLanguage]', $Languages = array(''=>'...'), $default = ((isset($loadedItem['CustLanguage']))? $loadedItem['CustLanguage']:''), 'id="CustLanguage"');?></td>
	    				<td class="input-label">Written Language</td><td class="input-field"><?php echo form_dropdown('customer[CustWrittenLanguage]', $Languages = array(''=>'...'), $default = ((isset($loadedItem['CustWrittenLanguage']))? $loadedItem['CustWrittenLanguage']:''), 'id="CustWrittenLanguage"');?></td>
	    			</tr>
	    			<tr><td class="input-label">Agreement Number</td><td class="input-field"><input id="AgreementId" name="customer[AgreementId]" type="text" value="<?php echo set_value('customer[AgreementId]', ((isset($loadedItem['AgreementId']))? $loadedItem['AgreementId']:'')); ?>" maxlength="20" /></td>
	    				<td class="input-label required">TIN</td><td class="input-field"><input id="TIN" name="customer[TIN]" type="text" value="<?php echo set_value('customer[TIN]', ((isset($loadedItem['TIN']))? $loadedItem['TIN']:'')); ?>" maxlength="64" /></td>
	    				<td></td><td></td>
	    			</tr>
	    		</table>
	    	</div>
	    </div>
	    
	    <div class="cust-holder">
	    	<h3 class="box-hd">Customer Address <a class="btn-link pull-right" data-toggle="modal" href="#add_address" ><i class="cus-add"></i> New Customer Address</a></h3>
	    	<div class="box-content-no">
	    		<table id="cust-addresses" class="cust-addresses gradient-thead">
	    			<thead>
	    				<tr><th>Nationality</th><th>City/Province</th><th>Khan/District</th><th>Sangkat/Comune</th><th>Village/Group</th><th>Street</th><th>Block</th><th>House No.</th><th>Postcode</th><th>Address Type</th><th>Operation Type</th><th></th></tr>
	    			</thead>
	    			<tbody>
	    			<?php if (isset($loadedAddress) && $loadedAddress) {?>
	    			<?php foreach ($loadedAddress as $rowIndex => $AddressItem) {?>
	    				<tr data-rowindex="<?php echo $rowIndex;?>">
							<td><?php echo $AddressItem['Nationality'] . Customer::input_hidden_addresses($AddressItem, $rowIndex);?></td>
							<td><?php echo $AddressItem['City'].'/'.$AddressItem['Province'];?></td>
							<td><?php echo $AddressItem['Khan'].'/'. $AddressItem['District'];?></td>
							<td><?php echo $AddressItem['Sangkat'].'/'. $AddressItem['Comune'];?></td>
							<td><?php echo $AddressItem['Village'].'/'. $AddressItem['Group'];?></td>
							<td><?php echo $AddressItem['Street'];?></td>
							<td><?php echo $AddressItem['Block'];?></td>
							<td><?php echo $AddressItem['HouseNo'];?></td>
							<td><?php echo $AddressItem['PostCode'];?></td>
							<td><?php echo $AddressItem['AddressType'];?></td>
							<td></td>
							<td><a class="edit-addr-tr" href=""><i class="cus-page-white-edit" /></a> <a class="delete-addr-tr" href=""><i class="cus-delete" /></a></td>
						</tr>
	    			<?php }} else {?>
	    				<tr class="gray no-record"><td colspan="11" align="center">No Record.</td></tr>
	    			<?php }?>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    
	    <div class="cust-pic-holder">
	    	<h3 class="box-hd">Customer PIC <a class="btn-link pull-right" data-user_type="1" href="#add_pics_kams"><i class="cus-add"></i> New Customer PIC</a></h3>
	    	<div class="box-content-no">
	    		<table id="cust-pics" class="cust-pics gradient-thead compact table-striped bootstrap-datatable">
	    			<thead>
	    				<tr><th>Name</th><th>Salutation</th><th>Issued Date</th><th>Mobile No.</th><th>Home No.</th><th>Office No.</th><th>Email</th><th>Fax No.</th><th>Customer Language</th><th>Certificate Type</th><th>Certificate No.</th><th>PIC Type</th><th>Operation Type</th><th></th></tr>
	    			</thead>
	    			<tbody>
	    			<?php if (isset($loadedPICS) && $loadedPICS) {?>
	    			<?php foreach ($loadedPICS as $rowIndex => $rowItem) {?>
	    				<tr>
							<td><?php echo $rowItem['name'];?><input type="hidden" name="users_pic[<?php echo $rowItem['user_id']?>]" id="user_<?php echo $rowItem['user_id']?>" value="<?php echo $rowItem['user_id']?>" /></td>
							<td><?php echo $rowItem['salutation'];?></td>
							<td><?php echo $rowItem['issued_date'];?></td>
							<td><?php echo $rowItem['mobile_no'];?></td>
							<td><?php echo $rowItem['home_no'];?></td>
							<td><?php echo $rowItem['office_no'];?></td>
							<td><?php echo $rowItem['email'];?></td>
							<td><?php echo $rowItem['fax_no'];?></td>
							<td><?php echo $rowItem['language'];?></td>
							<td><?php echo $rowItem['CertificateTypeId'];?></td>
							<td><?php echo $rowItem['CertificateNumId'];?></td>
							<td><?php echo $rowItem['pic_type'];?></td>
							<td><?php echo $rowItem['operation_type'];?></td>
							<td><a class="delete-user-tr" href=""><i class="cus-delete" /></a></td>
						</tr>
	    			<?php }} else {?>
	    				<tr class="gray no-record"><td colspan="13" align="center">No Record.</td></tr>
	    			<?php }?>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    
	    <div class="cust-csr-holder">
	    	<h3 class="box-hd">Customer CSR <a class="btn-link pull-right" data-user_type="2" href="#add_pics_kams"><i class="cus-add"></i> New Customer CSR</a></h3>
	    	<div class="box-content-no">
	    		<table id="cust-csr" class="cust-csr gradient-thead compact table-striped bootstrap-datatable">
	    			<thead>
	    				<tr><th>CSR Name</th><th>Department</th><th>Operator</th><th>Mobile No.</th><th>Office No.</th><th>Home No.</th><th>Email</th><th>Role</th><th>Operation Type</th><th></th></tr>
	    			</thead>
	    			<tbody>
	    			<?php if (isset($loadedKAMS) && $loadedKAMS) {?>
	    			<?php foreach ($loadedKAMS as $rowIndex => $rowItem) {?>
	    				<tr>
							<td><?php echo $rowItem['name'];?><input type="hidden" name="users_kam[<?php echo $rowItem['user_id']?>]" id="user_<?php echo $rowItem['user_id']?>?>" value="<?php echo $rowItem['user_id']?>" /></td>
							<td><?php echo $rowItem['department'];?></td>
							<td><?php echo $rowItem['operator'];?></td>
							<td><?php echo $rowItem['mobile_no'];?></td>
							<td><?php echo $rowItem['office_no'];?></td>
							<td><?php echo $rowItem['home_no'];?></td>
							<td><?php echo $rowItem['email'];?></td>
							<td><?php echo $rowItem['ad_role'];?></td>
							<td><?php echo $rowItem['operation_type'];?></td>
							<td><a class="delete-user-tr" href=""><i class="cus-delete" /></a></td>
						</tr>
	    			<?php }} else {?>
	    				<tr class="gray no-record"><td colspan="13" align="center">No Record.</td></tr>
	    			<?php }?>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    
	    <div class="cust-attach-holder">
	    	<h3 class="box-hd">Attachment Info <a class="btn-link pull-right" href="#" ><i class="cus-attach"></i> Upload</a></h3>
	    	<div class="box-content-no">
	    		<table id="cust-attach" class="cust-attach gradient-thead">
	    			<thead>
	    				<tr><th>Attach No.</th><th>Attachment name</th><th>Attachment Type</th><th>Uploaded Date</th><th></th></tr>
	    			</thead>
	    			<tbody>
	    			<?php if (isset($loadedAttachments) && $loadedAttachments) {?>
	    			<?php foreach ($loadedAttachments as $rowIndex => $AddressItem) {?>
	    				<tr data-rowindex="<?php echo $rowIndex;?>">
							<td><?php echo $AddressItem['Street'];?></td>
							<td><?php echo $AddressItem['Block'];?></td>
							<td><?php echo $AddressItem['HouseNo'];?></td>
							<td><?php echo $AddressItem['PostCode'];?></td>
							<td><a class="edit-addr-tr" href=""><i class="cus-page-white-edit" /></a> <a class="delete-addr-tr" href=""><i class="cus-delete" /></a></td>
						</tr>
	    			<?php }} else {?>
	    				<tr class="gray no-record"><td colspan="4" align="center">No Record.</td></tr>
	    			<?php }?>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
		<input type="submit" value="Save" />
		<?php echo form_close(); ?>  
    </div>
</div>

<div class="modal hide fade" id="add_address">
	<div class="modal-header">
		<a href="#" class="close pull-right" data-dismiss="modal">×</a>
		<h4>Save Customer Address</h4>
	</div>
	<div class="modal-body">
		<form id="frm_save_address" name="frm_save_address">
			<input id="WebAddressId" name="WebAddressId" type="hidden" value="" />
			<input id="AddressId" name="AddressId" type="hidden" value="" />
			<div class="cust-holder">
		    	<div class="box-content-no">
		    		<table class="cust-inputs cust-address">
		    			<tr><td class="input-label">Nationality</td><td class="input-field"><input id="Nationality" name="Nationality" type="text" value="" maxlength="256" /></td></tr>
	    				<tr><td class="input-label">Province</td><td class="input-field"><input id="Province" name="Province" type="text" value="" maxlength="256" /></td></tr>
	    				<tr><td class="input-label">City</td><td class="input-field"><input id="City" name="City" type="text" value="" maxlength="64" /></td></tr>
		    			<tr><td class="input-label">District</td><td class="input-field"><input id="District" name="District" type="text" value="" maxlength="64" /></td></tr>
	    				<tr><td class="input-label">Khan</td><td class="input-field"><input id="Khan" name="Khan" type="text" value="" maxlength="64" /></td></tr>
	    				<tr><td class="input-label">Sangkat</td><td class="input-field"><input id="Sangkat" name="Sangkat" type="text" value="" maxlength="32" /></td></tr>
		    			<tr><td class="input-label">Comune</td><td class="input-field"><input id="Comune" name="Comune" type="text" value="" /></td></tr>
	    				<tr><td class="input-label">Village</td><td class="input-field"><input id="Village" name="Village" type="text" value="" /></td></tr>
	    				<tr><td class="input-label">Group</td><td class="input-field"><input id="Group" name="Group" type="text" value="" /></td></tr>
		    			<tr><td class="input-label">Street</td><td class="input-field"><input id="Street" name="Street" type="text" value="" maxlength="16" /></td></tr>
	    				<tr><td class="input-label">Block</td><td class="input-field"><input id="Block" name="Block" type="text" value="" maxlength="64" /></td></tr>
	    				<tr><td class="input-label">HouseNo</td><td class="input-field"><input id="HouseNo" name="HouseNo" type="text" value="" maxlength="128" /></td></tr>
		    			<tr><td class="input-label">Post Code</td><td class="input-field"><input id="PostCode" name="PostCode" type="text" value="" /></td></tr>
		    			<tr><td class="input-label">Address Type</td><td class="input-field"><?php echo form_dropdown('AddressType', $AddressTypes = array_replace(array(''=>'...'), Customer_model::$AddressTypes), $default = ((isset($loadedItem['AddressType']))? $loadedItem['AddressType']:''), 'id="AddressType"');?></td></tr>
		    		</table>
		    	</div>
	    	</div>
		</form>
	</div>
	<div class="modal-footer">
		<button id="save_address" class="btn" type="button" value="">Save</button>
		<a class="btn btn-danger" data-dismiss="modal" href="">Cancel</a>
	</div>																	
</div>

<div class="modal hide fade" id="add_pics_kams">
	<div class="modal-header">
		<a href="#" class="close pull-right" data-dismiss="modal">×</a>
		<h4>Choose Customer PIC</h4>
	</div>
	<div class="modal-body">
		<form id="frm_choose_user" name="frm_choose_user">
			<input id="WebCustId" name="WebCustId" type="hidden" value="<?php echo ((isset($loadedItem['WebCustId']))? $loadedItem['WebCustId'] : '');?>" />
			<input id="user_type" name="user_type" type="hidden" value="" />
			<div class="users-holder"></div>
		</form>
	</div>
	<div class="modal-footer">
		<button id="add_choosed_user" class="btn" type="button" value="">Add Choosed</button>
		<a class="btn btn-danger" data-dismiss="modal" href="">Cancel</a>
	</div>
	<div id="ajax-preloader-modal" class="bg_opacity hide">
		<img class="ajax-loader" alt="waiting..." src="/public/images/ajax-loader.gif" />
	</div>																	
</div>

<div id="ajax-preloader" class="bg_opacity hide">
	<img class="ajax-loader" alt="waiting..." src="/public/images/ajax-loader.gif" />
</div>

<script type="text/javascript">
var usersList = {};


$(document).ready(function() {
	
	$(document).on('mouseup', "button#save_address", function(e) {
		e.preventDefault();
		
		var values = {};
		var addrIndex = $('input#addrIndex').val();
		var editIndex = $('#save_address').val();
		var addrValues = $('#frm_save_address').serializeArray();
		var editMode = false;
		
		$.each(addrValues, function(i, field) {
		    values[field.name] = field.value;
		});

		if (editIndex != '' && $('#cust-addresses tbody tr[data-rowindex="' + editIndex + '"]').length > 0) {
			$('#cust-addresses tbody tr[data-rowindex="' + editIndex + '"]').replaceWith(address_row(values, editIndex));
		} else {
			$("#cust-addresses tbody tr.no-record").remove();
			$("#cust-addresses tbody").append(address_row(values, addrIndex));

			$('input#addrIndex').val( function(i, oldval) {
		        return ++oldval;
		    });
		}

		$('#save_address').val('');
		$("#add_address").modal("hide");
		$('#frm_save_address')[0].reset();
		return true;
	});
        
	$(document).on('click', 'a[href="#add_pics_kams"]', function(e){
		e.preventDefault();
		$("#add_pics_kams .modal-body .users-holder").html('');
		//$('#add_pics_kams #ajax-preloader-modal').show();
		
		var user_type = $(this).data('user_type');
		var WebCustId = $('#frm_edit_customer input[name="customer[WebCustId]"]').val();
		$("#add_choosed_user").val(user_type);
		
		$.ajax({
		    url:"<?php echo site_url('admin/staff/ajax_get_nocustomer_users/')?>",  
		    type: "post",
		    dataType: "json",
		    data: {"WebCustId":WebCustId, "user_type":user_type, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
		    success:function(data) {
		    	usersList = data.usersList;

		    	$("#add_pics_kams .modal-body .users-holder").html(data.table);
		    	
		    	if (user_type == 1) {
		    		$('table#cust-pics tbody tr td:first-child input[type="hidden"]').map(function() {
			    		if ($('#add_pics_kams table tbody tr[data-user_id="'+this.value+'"]').length > 0) {
			    			$('#add_pics_kams table tbody tr[data-user_id="'+this.value+'"]').remove();
				    	}
					});
		    	} else if (user_type == 2) {
		    		$('table#cust-csr tbody tr td:first-child input[type="hidden"]').map(function() {
			    		if ($('#add_pics_kams table tbody tr[data-user_id="'+this.value+'"]').length > 0) {
			    			$('#add_pics_kams table tbody tr[data-user_id="'+this.value+'"]').remove();
				    	}
					});
			    }
			    
		    	if($('#add_pics_kams .users-holder table tbody tr').not('.no-record').length == 0 && $('#add_pics_kams .users-holder table tbody tr.no-record').length > 0) {
		    		$('#add_pics_kams .users-holder table tbody tr.no-record').show();
		    	} else {
		    		$('#add_pics_kams .users-holder table tbody tr.no-record').hide();
			    }

		    	$('#add_pics_kams').modal('show');
		    }
		});
    });

	//$("#add_pics_kams").on('shown', function(){
	
    //});
    
    $(document).on('click', 'button#add_choosed_user', function(e){
		e.preventDefault();
// console.log($(this).val());
		var user_type = $(this).val();
		if ($('#frm_choose_user input:checkbox:checked').length > 0) {

			if (user_type == 1) {
				$("#cust-pics tbody tr.no-record").hide();
				$('#frm_choose_user input:checkbox:checked').map(function() {
					if ($("#user_" + this.value).length == 0) {
						$("#cust-pics tbody").append(pic_row(usersList['user_' + this.value]));
					}
				});
			} else if (user_type == 2) {
				$("#cust-csr tbody tr.no-record").hide();
				$('#frm_choose_user input:checkbox:checked').map(function() {
					if ($("#user_" + this.value).length == 0) {
						$("#cust-csr tbody").append(kam_row(usersList['user_' + this.value]));
					}
				});
			}
			
		}
		
		

		$('#add_pics_kams').modal('hide');
    });
    
	$(document).on('click', '.delete-user-tr', function(e) {
		e.preventDefault();
		if (false == confirm('You are sure to remove this record!')) return false;

		$(this).parent('td').parent('tr').remove();
    });
    
	$(document).on('click', '.delete-addr-tr', function(e) {
		e.preventDefault();
		var $this = $(this);
		if (false == confirm('You are sure to delete Customer Address!')) return false;

		var WebAddressId = $this.parent('td').parent('tr').children('td:first').children('input[data-originalname="WebAddressId"]').val();
		
		if (WebAddressId) {
			$.ajax({
			    url:"<?php echo site_url('admin/customer/ajax_delete_customer_address/')?>",  
			    type: "get",
			    data: {"WebAddressId":WebAddressId, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
			    success:function(data) {
			    	$this.parent('td').parent('tr').remove();
			    }
			  });
		} else $this.parent('td').parent('tr').remove();
		
    });
    
	$(document).on('click', '.edit-addr-tr', function(e) {
		e.preventDefault();
		$(this).parent('td').parent('tr').children('td:first').children('input[type="hidden"]').map(function( index ) {
			var popInputName = $(this).data('originalname');
			var popInputVal = $(this).val();
			if ($('#' + popInputName).length > 0) {
				$('#' + popInputName).val(popInputVal)
			} else {
				console.log('element:'+'#' + popInputName+' not found!');
			}
			//console.log(popInputName, popInputVal);
		});

		$('#save_address').val($(this).parent('td').parent('tr').data('rowindex'));
		$("#add_address").modal("show");
    });
    
	$("#frm_edit_macc").validate({
        rules: {	
        	macc_name: {required: true, minlength:2, maxlength:100},
        	company_id:{required: true,minlength:1, maxlength:4}
        }
    });
        
	$(".datepicker").datepicker({
		showOn: "button",
		buttonImage: "ui-icon-calendar",
		showButtonPanel: true,
        //buttonImageOnly: false,
		buttonImage: "<?php echo base_url();?>public/images/calendar.gif",
	    buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		timeFormat:  'HH:mm:ss',
		defaultTime: 'now',
		firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        //showOn: "both",
	});

	$('#add_address, #add_pics_kams').modal({
        backdrop: true,
        keyboard: true,
        show: false,
       /*  buttons: {
            "Cancel": function() { $(this).dialog("close"); },
            "No": function() { $(this).dialog("close"); logOff("0"); },
            "Yes": function() { $(this).dialog("close"); logOff("1"); }         
         }, */
    }).css({
       'width': function () { 
           return ($(document).width() * .6) + 'px';  
       },
       'margin-left': function () { 
           return -($(this).width() / 2); 
       }
});

	$(document).ajaxSend(function(event, request, settings) {
	    $('#ajax-preloader').show();
	});

	$(document).ajaxComplete(function(event, request, settings) {
	    $('#ajax-preloader').hide();
	});
}); // end ready


function address_row(addrValues, rowindex) 
{
	var inputs = '';
	$.each(addrValues, function(fieldName, value) {
		var inputName = "customer_address["+rowindex+"]["+fieldName+"]";
		var inputID = "customeraddress_"+rowindex+"_"+fieldName;
		
		inputs += '<input type="hidden" name="'+inputName+'" id="'+inputID+'" value="'+value+'" data-originalname="'+fieldName+'" />';
	});
	
	var tds = '<tr data-rowindex="'+ rowindex +'">'+
				'<td>'+ addrValues.Nationality + inputs +'</td>'+
				'<td>'+ addrValues.City +'/'+ addrValues.Province + '</td>'+
				'<td>'+ addrValues.Khan +'/'+ addrValues.District + '</td>'+
				'<td>'+ addrValues.Sangkat +'/'+ addrValues.Comune + '</td>'+
				'<td>'+ addrValues.Village +'/'+ addrValues.Group + '</td>'+
				'<td>'+ addrValues.Street + '</td>'+
				'<td>'+ addrValues.Block + '</td>'+
				'<td>'+ addrValues.HouseNo + '</td>'+
				'<td>'+ addrValues.PostCode + '</td>'+
				'<td>'+ addrValues.AddressType + '</td>'+
				'<td>'+ '' + '</td>'+
				'<td><a class="edit-addr-tr" href=""><i class="cus-page-white-edit" /></a> <a class="delete-addr-tr" href=""><i class="cus-delete" /></a>'+ '' + '</td>'+
			'</tr>';
	
	return tds;
}

function pic_row(userInfo) 
{
	var input = '';
	input = '<input type="hidden" name="users_pic['+ userInfo.user_id +']" id="user_'+ userInfo.user_id +'" value="'+userInfo.user_id + '" />';
	
	var tds = '<tr>'+
				'<td>'+ userInfo.name + input +'</td>'+
				'<td>'+ userInfo.salutation + '</td>'+
				'<td>'+ userInfo.issued_date + '</td>'+
				'<td>'+ userInfo.mobile_no + '</td>'+
				'<td>'+ userInfo.home_no + '</td>'+
				'<td>'+ userInfo.office_no + '</td>'+
				'<td>'+ userInfo.email + '</td>'+
				'<td>'+ userInfo.fax_no + '</td>'+
				'<td>'+ userInfo.language + '</td>'+
				'<td>'+ userInfo.CertificateTypeId + '</td>'+
				'<td>'+ userInfo.CertificateNumId + '</td>'+
				'<td>'+ userInfo.pic_type + '</td>'+
				'<td>'+ userInfo.operation_type + '</td>'+
				'<td><a class="delete-user-tr" href=""><i class="cus-delete" /></a></td>'+
			'</tr>';
	
	return tds;
}

function kam_row(userInfo) 
{
	var input = '';
	input = '<input type="hidden" name="users_kam['+ userInfo.user_id +']" id="user_'+ userInfo.user_id +'" value="'+userInfo.user_id + '" />';
	
	var tds = '<tr>'+
				'<td>'+ userInfo.name + input +'</td>'+
				'<td>'+ userInfo.department + '</td>'+
				'<td>'+ userInfo.operator + '</td>'+
				'<td>'+ userInfo.mobile_no + '</td>'+
				'<td>'+ userInfo.office_no + '</td>'+
				'<td>'+ userInfo.home_no + '</td>'+
				'<td>'+ userInfo.email + '</td>'+
				'<td>'+ userInfo.ad_role + '</td>'+
				'<td>'+ userInfo.operation_type + '</td>'+
				'<td><a class="delete-user-tr" href=""><i class="cus-delete" /></a></td>'+
			'</tr>';
	
	return tds;
}

</script>

<style>
h3.box-hd { background-color:#DEDCDD;color:#595959;font-size: 12px;padding:2px 10px;line-height: 20px;height: 20px;}
h3.box-hd a {color: inherit;}
table.cust-inputs {width: 100%;	border-collapse: separate;border-spacing: 1px 1px;}
table.cust-inputs td {font-size: 12px;}
table.cust-inputs td.input-label {padding:3px 10px 3px 12px; background-color:#F1F1F1;white-space: nowrap;}
table.cust-inputs input {height: 12px;padding: 3px;}
table.cust-inputs select {height: 20px;padding: 0;}
table.cust-inputs input, table.cust-inputs select {margin:0;font-size: 12px; border-color:#BFCECC; width:-moz-available;width:-webkit-fill-available; width:fill-available;border-radius: 0;}
table.cust-inputs td.input-field {padding:0 10px 0 2px; background-color:#FFF;}
table.cust-inputs td.input-field:last-child {padding-right:0;}
/* input.datepicker {background-color: #FFFFE0;} */
td.required:before {color: #e32;margin-left: -9px;content: '* ';display:inline;}
table.cust-inputs td.required + td.input-field input, table.cust-inputs td.required + td.input-field select {background-color: #FFFFE0;}
/*---Address table---*/

table.cust-addresses tbody tr td {padding:0 5px; border: 1px solid #cccccc;}
table.cust-addresses tbody tr td:last-child {text-align: center;}

.ui-datepicker-trigger {margin-left: -20px;}
div.ui-datepicker, .ui-datepicker input{font-size:75%;}
select.ui-datepicker-month, select.ui-datepicker-year {height:18px;line-height: 18px;padding:1px;}
button.ui-datepicker-current, button.ui-datepicker-close { height: 20px;line-height: initial;}
table.cust-address td.input-field:last-child {padding-right:10px;}

.cust-pic-holder, .cust-csr-holder, .cust-attach-holder {margin-top: 10px;}
.default-filter select, .default-filter input {width: auto;}

</style>