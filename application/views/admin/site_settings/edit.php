<div class="box no-margin"><?php //var_dump(Customer::input_hidden_addresses());?>
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Back</a>
		</div>
    </div>
    <div class="box-content">
		<?php echo form_open('', array('id'=>'frm_edit_settings','name'=>'frm_edit_settings','class'=>'form-horizontal')); ?>
	    <div class="cust-holder">
	    	<h3 class="box-hd">Основные настройки сайта</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr>
						<td class="input-label">Название</td>
						<td class="input-field"><input id="site_name" name="loadedSettings[site_name]" type="text" value="<?php echo set_value('loadedSettings[site_name]', ((isset($loadedItem['site_name']))? $loadedItem['site_name']:'')); ?>" maxlength="128" /></td>
						<td><?php echo form_error('loadedSettings[site_name]'); ?></td>
					</tr>
					<tr>
						<td class="input-label">Заголовак</td>
						<td class="input-field"><input id="title" name="loadedSettings[title]" type="text" value="<?php echo set_value('loadedSettings[title]', ((isset($loadedItem['title']))? $loadedItem['title']:'')); ?>" maxlength="256" /></td>
						<td><?php echo form_error('loadedSettings[title]'); ?></td>
					</tr>
					<tr>
						<td class="input-label">Email</td>
						<td class="input-field"><input id="email" name="loadedSettings[email]" type="email" value="<?php echo set_value('loadedSettings[email]', ((isset($loadedItem['email']))? $loadedItem['email']:'')); ?>" maxlength="32" /></td>
						<td><?php echo form_error('loadedSettings[email]'); ?></td>
					</tr>
					<tr>
						<td class="input-label">Телефон</td>
						<td class="input-field"><input id="phone" name="loadedSettings[phone]" type="text" value="<?php echo set_value('loadedSettings[phone]', ((isset($loadedItem['phone']))? $loadedItem['phone']:'')); ?>" maxlength="16" /></td>
						<td><?php echo form_error('loadedSettings[phone]'); ?></td>
					</tr>
					<tr>
						<td class="input-label">Телефон 2</td>
						<td class="input-field"><input id="phone2" name="loadedSettings[phone2]" type="text" value="<?php echo set_value('loadedSettings[phone2]', ((isset($loadedItem['phone2']))? $loadedItem['phone2']:'')); ?>" maxlength="16" /></td>
						<td><?php echo form_error('loadedSettings[phone2]'); ?></td>
					</tr>
					<tr>
						<td class="input-label">Адрес</td>
						<td class="input-field"><input id="address" name="loadedSettings[address]" type="text" value="<?php echo set_value('loadedSettings[address]', ((isset($loadedItem['address']))? $loadedItem['address']:'')); ?>" maxlength="256" /></td>
						<td><?php echo form_error('loadedSettings[address]'); ?></td>
					</tr>
					<tr>
						<td class="input-label">Адрес 2</td>
						<td class="input-field"><input id="address2" name="loadedSettings[address2]" type="text" value="<?php echo set_value('loadedSettings[address2]', ((isset($loadedItem['address2']))? $loadedItem['address2']:'')); ?>" maxlength="256" /></td>
						<td><?php echo form_error('loadedSettings[address2]'); ?></td>
					</tr>
					<tr>
						<td class="input-label">Описание</td>
						<td class="input-field"><textarea id="description" name="loadedSettings[description]"><?php echo set_value('loadedSettings[description]', ((isset($loadedItem['description']))? $loadedItem['description']:'')); ?></textarea></td>
						<td><?php echo form_error('loadedSettings[description]'); ?></td>
					</tr>
	    			<tr><td></td><td align="right"><button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button></td><td></td></tr>
	    		</table>
	    	</div>
	    </div>
		<?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	
	$("#frm_edit_settings").validate({
// 		debug: true,
		errorElement: 'span',
        errorClass: 'error',
        rules: {	
        	"loadedSettings[site_name]": {required: true, maxlength:128},
        	"loadedSettings[phone]":{ maxlength:16},
        	"loadedSettings[email]":{ email: true, maxlength:32}
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