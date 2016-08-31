<div class="box no-margin">
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> Add/Eedit Offer Group</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/productcatalog/groups')?>" class="btn-link"><i class="cus-arrow-undo"></i> Cancel</a>
		</div>
    </div>
    <div class="box-content">
		<?php echo form_open('admin/productcatalog/edit_group/'. ((isset($group['group_id']))? $group['group_id'] : ''), array('id'=>'frm_edit_group','name'=>'frm_edit_group','class'=>'form-horizontal')); ?>
		<input type="hidden" name="group_id" value="<?php echo set_value('group_id', ((isset($group['group_id']))? $group['group_id']:'')); ?>" />
	    <div class="cust-holder no-margin">
	    	<h3 class="box-hd">General Offer Group Information</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Group Name</td><td class="input-field"><input id="group_name" name="group_name" type="text" value="<?php echo set_value('group_name', ((isset($group['group_name']))? $group['group_name']:'')); ?>" /></td><td><?php echo form_error('web_name'); ?></td></tr>
	    			<tr><td class="input-label required">Offer Type</td><td class="input-field"><?php echo form_dropdown('offer_type', $offer_types, $default = ((isset($group['offer_type']))? $group['offer_type']:''), 'id="offer_type"');?></td><td><?php echo form_error('offer_type'); ?></td></tr>
	    			<tr><td class="input-label">Assign to CustId</td><td class="input-field"><input id="CustId" name="CustId" type="text" value="<?php echo set_value('CustId', ((isset($group['CustId']))? $group['CustId']:'')); ?>" /></td><td><?php echo form_error('CustId'); ?></td></tr>
	    			<tr><td class="input-label">Order Weight</td><td class="input-field"><?php echo form_dropdown('weight', range(0, 12), $default = ((isset($group['weight']))? $group['weight']:''), 'id="weight"');?></td><td><?php echo form_error('weight'); ?></td></tr>
	    		</table>
	    	</div>
	    </div>
		
		<div class="cust-holder">
	    	<h3 class="box-hd"><span data-toggle="collapse" data-target="#remark_container"><i class="caret" style="vertical-align: middle;"></i> Group description</span> <a class="btn-link pull-right" data-toggle="collapse" href="#remark_container" ><i class="icon-edit"></i> Edit</a></h3>
	    	<div id="remark_container" class="box-content-no collapse out">
		    	<?php echo form_error('addition'); ?>
		    	<textarea name="addition" id="addition" class="editor"><?php echo set_value('addition', ((isset($group['addition']))? $group['addition']:'')); ?></textarea>
	    	</div>
	    </div>
	    
	    <div class="submit-block">
	    	<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
	    	<a href="<?php echo site_url('admin/productcatalog/groups/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
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