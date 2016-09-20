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
		<?php echo form_open('', array('id'=>'frm_edit_album','name'=>'frm_edit_album','class'=>'form-horizontal')); ?>
		<input type="hidden" name="album[id]" value="<?php echo set_value('album[id]', ((isset($loadedItem['id']))? $loadedItem['id']:'')); ?>" />
	    <div class="cust-holder">
	    	<h3 class="box-hd">Информация о фотоальбоме</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Название</td><td class="input-field"><input id="name" name="album[name]" type="text" value="<?php echo set_value('album[name]', ((isset($loadedItem['name']))? $loadedItem['name']:'')); ?>" maxlength="64" /></td><td><?php echo form_error('album[name]'); ?></td></tr>
	    			<tr><td class="input-label required">Клиент</td><td class="input-field"><?php echo form_dropdown('album[user_id]', $usersOptions, $default = ((isset($loadedItem['user_id']))? $loadedItem['user_id']:''), 'id="user_id"');?></td><td><?php echo form_error('album[user_id]'); ?></td></tr>
					<tr><td class="input-label">Дата События</td><td class="input-field"><input id="event_date" name="album[event_date]" type="text" value="<?php echo set_value('album[event_date]', ((isset($loadedItem['event_date']))? date('Y-m-d', strtotime($loadedItem['event_date'])):'')); ?>" class="datepicker" /></td><td><?php echo form_error('album[event_date]'); ?></td></tr>
					<tr><td class="input-label">Опубликован</td><td class="input-field"><input id="published" name="album[published]" type="checkbox" value="1" <?php echo ((isset($loadedItem['published']))? 'checked="checked"':''); ?> /></td><td><?php echo form_error('album[published]'); ?></td></tr>
					<tr><td class="input-label">Описание</td><td class="input-field"><textarea id="description" name="album[description]"><?php echo set_value('album[description]', ((isset($loadedItem['description']))? $loadedItem['description']:'')); ?></textarea></td><td><?php echo form_error('album[description]'); ?></td></tr>
	    			<tr><td></td><td align="right"><button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button></td><td></td></tr>
	    		</table>
	    	</div>
	    </div>
		<?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	
	$("#frm_edit_album").validate({
// 		debug: true,
		errorElement: 'span',
        errorClass: 'error',
        rules: {	
        	"album[name]": {required: true, maxlength:64},
        	"album[user_id]":{required: true, digits: true},
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
		buttonImage: "<?php echo base_url('public/img/calendar.gif');?>",
		dateFormat: "yy-mm-dd",
		defaultTime: 'now',
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
table.cust-inputs input[type="text"] {height: 12px;padding: 3px;}
table.cust-inputs select {height: 20px;padding: 0;}
table.cust-inputs input[type="text"], table.cust-inputs select {margin:0;font-size: 12px; border-color:#BFCECC; width:-moz-available;width:-webkit-fill-available; width:fill-available;border-radius: 0;}
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