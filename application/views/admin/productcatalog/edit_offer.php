<div class="box no-margin">
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> Add/Edit Offer</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/productcatalog/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Cancel</a>
		</div>
    </div>
    <div class="box-content">
		<?php echo form_open('admin/productcatalog/edit_offer/'. ((isset($offer['web_offer_id']))? $offer['web_offer_id'] : ''), array('id'=>'frm_edit_offer','name'=>'frm_edit_offer','class'=>'form-horizontal')); ?>
		<input type="hidden" name="web_offer_id" value="<?php echo set_value('web_offer_id', ((isset($offer['web_offer_id']))? $offer['web_offer_id']:'')); ?>" />
		<input type="hidden" id="addrIndex" name="addrIndex" value="<?php echo (isset($loadedFreeUnits)? count($loadedFreeUnits): '0');?>" />
	    <div class="cust-holder no-margin">
	    	<h3 class="box-hd">General Offer Information</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Web Offer Name</td><td class="input-field"><input id="web_name" name="web_name" type="text" value="<?php echo set_value('web_name', ((isset($offer['web_name']))? $offer['web_name']:'')); ?>" /></td><td><?php echo form_error('web_name'); ?></td></tr>
	    			<tr><td class="input-label">NGBSS OfferingId</td><td class="input-field"><input id="offering_id" name="offering_id" type="text" value="<?php echo set_value('offering_id', ((isset($offer['offering_id']))? $offer['offering_id']:'')); ?>" /></td><td><?php echo form_error('offering_id'); ?></td></tr>
	    			<tr><td class="input-label">NGBSS Offer Name</td><td class="input-field"><input id="offering_name" name="offering_name" type="text" value="<?php echo set_value('offering_name', ((isset($offer['offering_name']))? $offer['offering_name']:'')); ?>" /></td><td><?php echo form_error('offering_name'); ?></td></tr>
	    			<tr><td class="input-label">NGBSS Offer Code</td><td class="input-field"><input id="offering_code" name="offering_code" type="text" value="<?php echo set_value('offering_code', ((isset($offer['offering_code']))? $offer['offering_code']:'')); ?>" /></td><td><?php echo form_error('offering_code'); ?></td></tr>
	    			<tr><td class="input-label">Offer Cost</td><td class="input-field"><input id="offer_cost" name="offer_cost" type="text" value="<?php echo set_value('offer_cost', ((isset($offer['offer_cost']))? $offer['offer_cost']:'')); ?>" /></td><td><?php echo form_error('offer_cost'); ?></td></tr>
	    			<tr><td class="input-label">Offer Credit</td><td class="input-field"><input id="offer_credit" name="offer_credit" type="text" value="<?php echo set_value('offer_credit', ((isset($offer['offer_credit']))? $offer['offer_credit']:'')); ?>" /></td><td><?php echo form_error('offer_credit'); ?></td></tr>
	    			<tr><td class="input-label">Payment Method</td><td class="input-field"><input id="payment_method" name="payment_method" type="text" value="<?php echo set_value('payment_method', ((isset($offer['payment_method']))? $offer['payment_method']:'')); ?>" /></td><td><?php echo form_error('payment_method'); ?></td></tr>
	    			<tr><td class="input-label required">Offer Group</td><td class="input-field"><?php echo form_dropdown('group_id', $offer_groups, $default = ((isset($offer['group_id']))? $offer['group_id']:''), 'id="group_id"');?></td><td><?php echo form_error('group_id'); ?></td></tr>
	    			<tr><td class="input-label required">Offer Type</td><td class="input-field"><?php echo form_dropdown('offer_type', $offer_types, $default = ((isset($offer['offer_type']))? $offer['offer_type']:''), 'id="offer_type"');?></td><td><?php echo form_error('offer_type'); ?></td></tr>
	    		</table>
	    	</div>
	    </div>
		
		<div class="cust-holder">
	    	<h3 class="box-hd"><span data-toggle="collapse" data-target="#remark_container"><i class="caret" style="vertical-align: middle;"></i> Offer description</span> <a class="btn-link pull-right" data-toggle="collapse" href="#remark_container" ><i class="icon-edit"></i> Edit</a></h3>
	    	<div id="remark_container" class="box-content-no collapse out">
		    	<?php echo form_error('remark'); ?>
		    	<textarea name="remark" id="remark" class="editor"><?php echo set_value('remark', ((isset($offer['remark']))? $offer['remark']:'')); ?></textarea>
	    	</div>
	    </div>
	    
		<div class="free-unit-holder">
	    	<h3 class="box-hd">Offer Free Units <a class="btn-link pull-right" data-toggle="modal" href="#add_address" ><i class="icon-plus-sign"></i> Add new</a></h3>
	    	<div class="box-content-no">
	    		<table id="cust-addresses" class="cust-addresses gradient-thead">
	    			<thead>
	    				<tr><th>ID</th><th>Free Unit Name</th><th>Amount</th><th>Amount Unit</th><th>Cost</th><th>Duration</th><th>Duration Unit</th><th>Published</th><th></th></tr>
	    			</thead>
	    			<tbody>
	    			<?php if (isset($loadedFreeUnits) && $loadedFreeUnits) {?>
	    			<?php foreach ($loadedFreeUnits as $rowIndex => $item) {?>
	    				<tr data-rowindex="<?php echo $rowIndex;?>">
							<td><?php echo $item['value_id'] . Productcatalog::input_hidden_freeunits($item, $rowIndex);?></td>
							<td><?php echo $item['value_name'];?></td>
							<td><?php echo $item['amount'];?></td>
							<td><?php echo $item['unit'];?></td>
							<td><?php echo $item['cost'];?></td>
							<td><?php echo $item['duration'];?></td>
							<td><?php echo $item['duration_unit'];?></td>
							<td><?php echo ($item['show_inlist'] == 1 ? 'Yes': 'No');?></td>
							<td><a class="edit-addr-tr" href=""><i class="cus-page-white-edit"></i> Edit</a> <a class="delete-addr-tr" href=""><i class="cus-delete"></i> Delete</a></td>
						</tr>
	    			<?php }} else {?>
	    				<tr class="gray no-record"><td colspan="11" align="center">No Record.</td></tr>
	    			<?php }?>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    <div class="submit-block">
	    	<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
	    	<a href="<?php echo site_url('admin/productcatalog/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
	    </div>
	    <?php echo form_close(); ?>  
    </div>
</div>
<div class="clearfix"></div>
<div class="modal hide fade" id="add_address">
	<div class="modal-header">
		<a href="#" class="close pull-right" data-dismiss="modal">×</a>
		<h4>Free Offer details</h4>
	</div>
	<div class="modal-body">
		<form id="frm_save_address" name="frm_save_address">
			<input id="value_id" name="value_id" type="hidden" value="" />
			<input id="web_offer_id" name="web_offer_id" type="hidden" value="" />
			<div class="cust-holder">
		    	<div class="box-content-no">
		    		<table class="cust-inputs cust-address">
		    			<tr><td class="input-label">Free Unit Name</td><td class="input-field"><input id="value_name" name="value_name" type="text" value="" maxlength="256" /></td></tr>
	    				<tr><td class="input-label">Amount</td><td class="input-field"><input id="amount" name="amount" type="text" value="" maxlength="256" /></td></tr>
	    				<tr><td class="input-label">Unit</td><td class="input-field"><input id="unit" name="unit" type="text" value="" maxlength="64" /></td></tr>
		    			<tr><td class="input-label">Cost</td><td class="input-field"><input id="cost" name="cost" type="text" value="" maxlength="64" /></td></tr>
	    				<tr><td class="input-label">Duration</td><td class="input-field"><input id="duration" name="duration" type="text" value="" maxlength="64" /></td></tr>
	    				<tr><td class="input-label">Duration Unit</td><td class="input-field"><input id="duration_unit" name="duration_unit" type="text" value="" maxlength="32" /></td></tr>
		    			<tr><td class="input-label">Show in List</td><td class="input-field"><input id="show_inlist" name="show_inlist" type="checkbox" value="1" /></td></tr>
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
<div class="clearfix"></div>
<script type="text/javascript">
$(document).ready(function() {
	
	 $("#frm_edit_offer").validate({
     	errorElement: 'span',
         errorClass: 'error',
         rules: {	
         	SubjectName: {required: true, minlength:2, maxlength:100},
         	TypeID:{required: true, valueNotEquals: '0'},
         	GroupID:{required: true, valueNotEquals: '0'},
         	close_role_id:{required: true, valueNotEquals: '0'}
         },
	     errorPlacement: function (error, element) {
	     	element.parent().next().html(error); //error.insertAfter(element);
	     }
     }); 

	 $('#add_address').modal({
	        backdrop: true,
	        keyboard: true,
	        show: false,
	    }).css({
	       'width': function () { 
	           return ($(document).width() * .6) + 'px';  
	       },
	       'margin-left': function () { 
	           return -($(this).width() / 2); 
	       }
	});

	$(document).on('click', '.edit-addr-tr', function(e) {
		e.preventDefault();
		$(this).parent('td').parent('tr').children('td:first').children('input[type="hidden"]').map(function( index ) {
			var popInputName = $(this).data('originalname');
			var popInputVal = $(this).val();
			if ($('#' + popInputName).length > 0) {
				
				if ('show_inlist' == popInputName) {
					$('#' + popInputName).prop('checked', true);
				} else {
					$('#' + popInputName).val(popInputVal);
				}
			} else {
				console.log('element:'+'#' + popInputName+' not found!');
			}
			//console.log(popInputName, popInputVal);
		});

		$('#save_address').val($(this).parent('td').parent('tr').data('rowindex'));
		$("#add_address").modal("show");
    });

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

	$(document).on('click', '.delete-addr-tr', function(e) {
		e.preventDefault();
		var $this = $(this);
		if (false == confirm('You are sure to delete free unit record!')) return false;

		var value_id = $this.parent('td').parent('tr').children('td:first').children('input[data-originalname="value_id"]').val();
		
		if (value_id) {
			$.ajax({
			    url:"<?php echo site_url('admin/productcatalog/ajax_delete_freeunit/')?>",  
			    type: "get",
			    data: {"value_id":value_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
			    success:function(data) {
			    	$this.parent('td').parent('tr').remove();
			    }
			  });
		} else $this.parent('td').parent('tr').remove();
		
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
		var inputName = "freeunits["+rowindex+"]["+fieldName+"]";
		var inputID = "freeunit_"+rowindex+"_"+fieldName;
		
		inputs += '<input type="hidden" name="'+inputName+'" id="'+inputID+'" value="'+value+'" data-originalname="'+fieldName+'" />';
	});
	
	var tds = '<tr data-rowindex="'+ rowindex +'">'+
				'<td>'+ addrValues.value_id + inputs +'</td>'+
				'<td>'+ addrValues.value_name + '</td>'+
				'<td>'+ addrValues.amount + '</td>'+
				'<td>'+ addrValues.unit + '</td>'+
				'<td>'+ addrValues.cost + '</td>'+
				'<td>'+ addrValues.duration + '</td>'+
				'<td>'+ addrValues.duration_unit + '</td>'+
				'<td>'+ ((addrValues.show_inlist == 1)? "Yes": "No") + '</td>'+
				'<td><a class="edit-addr-tr" href=""><i class="cus-page-white-edit" /> Edit</a> <a class="delete-addr-tr" href=""><i class="cus-delete" /> Delete</a></td>'+
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
/*table.cust-inputs td.input-field {padding:0 10px 0 2px; background-color:#FFF;}
table.cust-inputs td.input-field:last-child {padding-right:0;}*/
table.cust-inputs td.input-field {padding:0 0px 0 2px; background-color:#FFF;}
table.cust-inputs td:last-child {min-width:30%;padding:0 10px;}

/*---Address table---*/
table.cust-addresses tbody tr td {padding:0 5px; border: 1px solid #cccccc;}
table.cust-addresses tbody tr td:last-child {text-align: center;}

.ui-datepicker-trigger {margin-left: -20px;}
div.ui-datepicker, .ui-datepicker input{font-size:75%;}
select.ui-datepicker-month, select.ui-datepicker-year {height:18px;line-height: 18px;padding:1px;}
button.ui-datepicker-current, button.ui-datepicker-close { height: 20px;line-height: initial;}
table.cust-address td.input-field:last-child {min-width:60%;padding-right:10px;}

.free-unit-holder, .cust-csr-holder, .cust-attach-holder {margin-top: 10px;}
.default-filter select, .default-filter input {width: auto;}
table.cust-inputs input[type="checkbox"] {width: unset;}
.submit-block {margin-top: 10px;text-align: right;}
</style>