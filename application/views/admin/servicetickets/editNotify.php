<div class="box no-margin">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	 <div class="box-icon">
        	<a href="<?php echo site_url('admin/servicetickets/editSubject/')?>" class="btn-link"><i class="cus-add"></i> New subject</a>
		</div>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/servicetickets/editSubject/'.((isset($loadedItem['SubjectID']))? $loadedItem['SubjectID']: ''))?>" class="btn-link"><i class="cus-arrow-undo"></i> Back</a>
		</div>
    </div>
    <div class="box-content">
    	<?php echo form_open('', array('id'=>'frm_edit','name'=>'frm_edit','class'=>'form-horizontal')); ?>
		<input type="hidden" name="ID" value="<?php echo set_value('ID', ((isset($loadedItem['ID']))? $loadedItem['ID']: ''))?>" />
		<input type="hidden" name="SubjectID" value="<?php echo set_value('SubjectID', ((isset($loadedItem['SubjectID']))? $loadedItem['SubjectID']: ''))?>" />
		<input type="hidden" name="AlertSite" value="1" />
        <input type="hidden" name="AlertEmail" value="1" />
	    <div class="cust-holder no-margin">
	    	<h3 class="box-hd">Notification</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Notification Title</td><td class="input-field"><input id="Title" name="Title" type="text" value="<?php echo set_value('Title', ((isset($loadedItem['Title']))? $loadedItem['Title']:'')); ?>" /></td><td><?php echo form_error('Title'); ?></td></tr>
	    			<tr><td class="input-label required">Notification Type</td><td class="input-field"><?php echo form_dropdown('Type', Servicetickets_model::$NotificationType, $default = ((isset($loadedItem['Type']))? $loadedItem['Type'] : 0), 'id="Type"');?></td><td><?php echo form_error('Type'); ?></td></tr>
	    			<tr><td class="input-field" colspan="3" style="padding-top: 20px;"><strong>Mail message body</strong><br /><?php echo form_error('MailText'); ?>
                  			<textarea id="MailText" name="MailText" class="editor"><?php echo set_value('MailText', ((isset($loadedItem['MailText']))? $loadedItem['MailText']:'')); ?></textarea></td>
                  	</tr>
	    			<tr><td class="input-field" colspan="3" style="padding-top: 20px;"><strong>Notify site content</strong><br /><?php echo form_error('SiteText'); ?>
                  			<textarea id="SiteText" name="SiteText" class="editor"><?php echo set_value('SiteText', ((isset($loadedItem['SiteText']))? $loadedItem['SiteText']:'')); ?></textarea></td>
                  	</tr>
	    		</table>
	    	</div>
	    </div>
	    
	    <div class="submit-block">
	    	<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
	    	<a href="<?php echo site_url('admin/servicetickets/editSubject/'.((isset($loadedItem['SubjectID']))? $loadedItem['SubjectID']: ''))?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
	    </div>
	    <?php echo form_close(); ?> 
    
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit").validate({
        	errorElement: 'span',
            errorClass: 'error',
            rules: {	
            	Title: {required: true, minlength:2, maxlength:200},
            }
        }); 
    });
</script>
<style>
.control-group div.controls.alert-type {line-height: 13px;}
.control-group .controls label {display: inline-block;line-height: 13px;margin-right: 15px;margin-bottom: 0;}
.control-group .controls input[type="checkbox"] {margin: 0;}
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

table#subject_approve tbody select {width: 100%;border-color: #bfcecc; border-radius: 0; font-size: 12px;  margin: 5px 0; height: 20px; padding: 0;}
table#subject_approve thead th {padding: 5px;}
.submit-block {margin:10px;}
.cust-holder {margin-top:10px;}
table.gradient-thead tbody tr td {border: 1px solid #ccc;}
.no-margin {margin:0;}
</style>