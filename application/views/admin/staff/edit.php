<div class="box no-margin"><?php //var_dump(Customer::input_hidden_addresses());?>
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/staff/edit')?>" class="btn-link"><i class="cus-add"></i> New PIC</a>
		</div>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/staff')?>" class="btn-link"><i class="cus-arrow-undo"></i> Back</a>
		</div>
    </div>
    <div class="box-content">
		<?php echo form_open('admin/staff/edit/'. ((isset($loadedItem['user_id']))? $loadedItem['user_id'] : ''), array('id'=>'frm_edit_pic','name'=>'frm_edit_pic','class'=>'form-horizontal')); ?>
		<input type="hidden" name="user[user_id]" value="<?php echo set_value('user[user_id]', ((isset($loadedItem['user_id']))? $loadedItem['user_id']:'')); ?>" />
	    <div class="cust-holder">
	    	<h3 class="box-hd">General User Information</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Имя</td><td class="input-field"><input id="name" name="user[name]" type="text" value="<?php echo set_value('user[name]', ((isset($loadedItem['name']))? $loadedItem['name']:'')); ?>" maxlength="64" /></td><td><?php echo form_error('user[name]'); ?></td></tr>
	    			<tr><td class="input-label required">Телефон</td><td class="input-field"><input id="mobile_no" name="user[mobile_no]" type="text" value="<?php echo set_value('user[mobile_no]', ((isset($loadedItem['mobile_no']))? $loadedItem['mobile_no']:'')); ?>" maxlength="64" /></td><td><?php echo form_error('user[mobile_no]'); ?></td></tr>
	    			<tr><td class="input-label">Адрес</td><td class="input-field"><input id="address" name="user[address]" type="text" value="<?php echo set_value('user[address]', ((isset($loadedItem['address']))? $loadedItem['address']:'')); ?>" maxlength="64" /></td><td><?php echo form_error('user[address]'); ?></td></tr>
<!--	    			<tr><td class="input-label">Office No.</td><td class="input-field"><input id="office_no" name="user[office_no]" type="text" value="--><?php //echo set_value('user[office_no]', ((isset($loadedItem['office_no']))? $loadedItem['office_no']:'')); ?><!--" maxlength="64" /></td><td>--><?php //echo form_error('user[office_no]'); ?><!--</td></tr>-->
	    			<tr><td class="input-label">Емайл</td><td class="input-field"><input id="email" name="user[email]" type="text" value="<?php echo set_value('user[email]', ((isset($loadedItem['email']))? $loadedItem['email']:'')); ?>" maxlength="128" /></td><td><?php echo form_error('user[email]'); ?></td></tr>
<!--	    			<tr><td class="input-label">Password</td><td class="input-field"><input id="password" name="password" type="password" value="" maxlength="32" autocomplete="off"/></td><td>--><?php //echo form_error('password'); ?><!--</td></tr>-->
<!--	    			<tr><td class="input-label">Fax No.</td><td class="input-field"><input id="fax_no" name="user[fax_no]" type="text" value="--><?php //echo set_value('user[fax_no]', ((isset($loadedItem['fax_no']))? $loadedItem['fax_no']:'')); ?><!--" maxlength="64" /></td><td>--><?php //echo form_error('user[fax_no]'); ?><!--</td></tr>-->
	    			<tr><td class="input-label">Роль</td><td class="input-field"><?php echo form_dropdown('roles[]', $roles = array_replace(array(''=>'...'), Acl::simpleRoleArray()), $default = ((isset($loadedRoles))? $loadedRoles:''), 'id="role_id"');?></td><td><?php echo form_error('roles[]'); ?></td></tr>
	    			<tr><td></td><td align="right"><button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button></td><td></td></tr>
	    		</table>
	    	</div>
	    </div>
		<?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	
	$("#frm_edit_pic").validate({
// 		debug: true,
		errorElement: 'span',
        errorClass: 'error',
        rules: {	
        	"user[name]": {required: true, maxlength:64},
        	//"user[mobile_no]":{required: true, maxlength:64},
        	"user[email]":{required: true, email: true, maxlength:128}
        },
        errorPlacement: function (error, element) {
        	element.parent().next().html(error); //error.insertAfter(element);
        }
    });
        
	$(".datepicker").datepicker({
		showOn: "button",
		buttonImage: "ui-icon-calendar",
		showButtonPanel: true,
        buttonImageOnly: true,
		buttonImage: "<?php echo base_url();?>public/images/calendar.gif",
		dateFormat: "yy-mm-dd",
		firstDay: 1,
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        //showOn: "both",
	});


	$(document).ajaxSend(function(event, request, settings) {
	    $('#ajax-preloader').show();
	});

	$(document).ajaxComplete(function(event, request, settings) {
	    $('#ajax-preloader').hide();
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

.default-filter select, .default-filter input {width: auto;}

</style>