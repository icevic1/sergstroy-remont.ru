<div class="box no-margin"><?php //var_dump(Customer::input_hidden_addresses());?>
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/staff/save_kam')?>" class="btn-link"><i class="cus-add"></i> New PIC</a>
		</div>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/staff/kams/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Back</a>
		</div>
    </div>
    <div class="box-content">
		<?php echo form_open('admin/staff/save_kam/'. ((isset($loadedItem['user_id']))? $loadedItem['user_id'] : ''), array('id'=>'frm_edit_kam','name'=>'frm_edit_kam','class'=>'form-horizontal')); ?>
		<input type="hidden" name="user[user_id]" value="<?php echo set_value('user[user_id]', ((isset($loadedItem['user_id']))? $loadedItem['user_id']:'')); ?>" />
	    <div class="cust-holder">
	    	<h3 class="box-hd">General CSR Info</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">CSR Name</td><td class="input-field">
	    			<input id="name" name="user[name]" type="text" value="<?php echo set_value('user[name]', ((isset($loadedItem['name']))? $loadedItem['name']:'')); ?>" maxlength="64" />
	    			</td><td><?php echo form_error('user[name]'); ?></td></tr>
	    			<tr><td class="input-label">Departament</td><td class="input-field"><input id="department" name="user[department]" type="text" value="<?php echo set_value('user[department]', ((isset($loadedItem['department']))? $loadedItem['department']:'')); ?>" maxlength="256" /></td><td><?php echo form_error('user[department]'); ?></td></tr>
	    			<tr><td class="input-label">Operator</td><td class="input-field"><input id="operator" name="user[operator]" type="text" value="<?php echo set_value('user[operator]', ((isset($loadedItem['operator']))? $loadedItem['operator']:'')); ?>" maxlength="256" /></td><td><?php echo form_error('user[operator]'); ?></td></tr>
	    			<tr><td class="input-label required">Mobile No.</td><td class="input-field"><input id="mobile_no" name="user[mobile_no]" type="text" value="<?php echo set_value('user[mobile_no]', ((isset($loadedItem['mobile_no']))? $loadedItem['mobile_no']:'')); ?>" maxlength="256" /></td><td><?php echo form_error('user[mobile_no]'); ?></td></tr>
	    			<tr><td class="input-label">Home No.</td><td class="input-field"><input id="home_no" name="user[home_no]" type="text" value="<?php echo set_value('user[home_no]', ((isset($loadedItem['home_no']))? $loadedItem['home_no']:'')); ?>" maxlength="256" /></td><td><?php echo form_error('user[home_no]'); ?></td></tr>
	    			<tr><td class="input-label">Office No.</td><td class="input-field"><input id="office_no" name="user[office_no]" type="text" value="<?php echo set_value('user[office_no]', ((isset($loadedItem['office_no']))? $loadedItem['office_no']:'')); ?>" maxlength="256" /></td><td><?php echo form_error('user[office_no]'); ?></td></tr>
	    			<tr><td class="input-label required">Email</td><td class="input-field"><input id="email" name="user[email]" type="text" value="<?php echo set_value('user[email]', ((isset($loadedItem['email']))? $loadedItem['email']:'')); ?>" maxlength="128" /></td><td><?php echo form_error('user[email]'); ?></td></tr>
	    			<tr><td class="input-label">Password</td><td class="input-field"><input id="password" name="password" type="password" value="" maxlength="32" /></td><td><?php echo form_error('password'); ?></td></tr>
	    			<tr><td class="input-label">Role</td><td class="input-field"><input id="ad_role" name="user[ad_role]" type="text" value="<?php echo set_value('user[ad_role]', ((isset($loadedItem['ad_role']))? $loadedItem['ad_role']:'')); ?>" maxlength="256" /></td><td><?php echo form_error('user[ad_role]'); ?></td></tr>
	    			<tr><td class="input-label">Operation Type</td><td class="input-field"><input id="operation_type" name="user[operation_type]" type="text" value="<?php echo set_value('user[operation_type]', ((isset($loadedItem['operation_type']))? $loadedItem['operation_type']:'')); ?>" maxlength="256" /></td><td><?php echo form_error('user[operation_type]'); ?></td></tr>
	    			<tr><td></td><td align="right"><button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button></td><td></td></tr>
	    		</table>
	    	</div>
	    </div>
		<?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">

$(document).ready(function() {
	//valueNotEquals: '0'
	$("#frm_edit_kam").validate({
		//debug: true,
		errorElement: 'span',
        errorClass: 'error',
        rules: {	
        	"user[name]": {required: true, maxlength:64},
        	"user[mobile_no]":{required: true, maxlength:64},
        	"user[email]":{required: true, email: true, maxlength:128}
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
table.cust-inputs td.input-field {padding:0 0px 0 2px; background-color:#FFF;}
table.cust-inputs td:last-child {min-width:30%;padding:0 10px;}
/* input.datepicker {background-color: #FFFFE0;} */
td.required:before {color: #e32;margin-left: -9px;content: '* ';display:inline;}
table.cust-inputs td.required + td.input-field input, table.cust-inputs td.required + td.input-field select {background-color: #FFFFE0;}

.ui-datepicker-trigger {margin-left: -20px;}
div.ui-datepicker, .ui-datepicker input{font-size:75%;}
select.ui-datepicker-month, select.ui-datepicker-year {height:18px;line-height: 18px;padding:1px;}
button.ui-datepicker-current, button.ui-datepicker-close { height: 20px;line-height: initial;}
table.cust-address td.input-field:last-child {padding-right:10px;}

</style>