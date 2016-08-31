<div class="box no-margin">
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> Add/Eedit Offer Type</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/productcatalog/types')?>" class="btn-link"><i class="cus-arrow-undo"></i> Cancel</a>
		</div>
    </div>
    <div class="box-content">
		<?php echo form_open('admin/productcatalog/edit_type/'. ((isset($type['type_id']))? $type['type_id'] : ''), array('id'=>'frm_edit_group','name'=>'frm_edit_group','class'=>'form-horizontal')); ?>
		<input type="hidden" name="type_id" value="<?php echo set_value('type_id', ((isset($type['type_id']))? $type['type_id']:'')); ?>" />
	    <div class="cust-holder no-margin">
	    	<h3 class="box-hd">General Offer Type Information</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Type Name</td><td class="input-field"><input id="type_name" name="type_name" type="text" value="<?php echo set_value('type_name', ((isset($type['type_name']))? $type['type_name']:'')); ?>" /></td><td><?php echo form_error('type_name'); ?></td></tr>
	    		</table>
	    	</div>
	    </div>
	    <div class="submit-block">
	    	<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
	    	<a href="<?php echo site_url('admin/productcatalog/types/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
	    </div>
	    <?php echo form_close(); ?>  
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

	
}); // end ready
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