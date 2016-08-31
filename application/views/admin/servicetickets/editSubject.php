<div class="box no-margin"><?php //var_dump(Customer::input_hidden_addresses());?>
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/servicetickets/editSubject/')?>" class="btn-link"><i class="cus-add"></i> New Subject</a>
		</div>
    	<div class="box-icon">
        	<a href="<?php echo site_url('admin/servicetickets/subjects/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Back</a>
		</div>
    </div>
    <div class="box-content">
		<?php echo form_open('admin/servicetickets/editSubject/'. ((isset($subject['SubjectID']))? $subject['SubjectID'] : ''), array('id'=>'frm_edit_subject','name'=>'frm_edit_subject','class'=>'form-horizontal')); ?>
		<input type="hidden" name="SubjectID" value="<?php echo set_value('SubjectID', ((isset($subject['SubjectID']))? $subject['SubjectID']:'')); ?>" />
	    <div class="cust-holder no-margin">
	    	<h3 class="box-hd">General Service Type Information</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<tr><td class="input-label required">Service Type name</td><td class="input-field"><input id="name" name="SubjectName" type="text" value="<?php echo set_value('SubjectName', ((isset($subject['SubjectName']))? $subject['SubjectName']:'')); ?>" /></td><td><?php echo form_error('SubjectName'); ?></td></tr>
	    			<tr><td class="input-label required">Request Type</td><td class="input-field"><?php echo form_dropdown('TypeID', $subjectTypes, $default = ((isset($subject['TypeID']))? $subject['TypeID']:''), 'id="TypeID"');?></td><td><?php echo form_error('TypeID'); ?></td></tr>
	    			<tr><td class="input-label required">Service Group</td><td class="input-field"><?php echo form_dropdown('GroupID', $subjectGroups, $default = ((isset($subject['GroupID']))? $subject['GroupID']:''), 'id="GroupID"');?></td><td><?php echo form_error('GroupID'); ?></td></tr>
	    			<tr><td class="input-label required">Allow Closure</td><td class="input-field"><?php echo form_dropdown('close_role_id', $roles, ((isset($subject['close_role_id']))? $subject['close_role_id']:''));?></td><td><?php echo form_error('close_role_id'); ?></td></tr>
	    			<tr><td class="input-label">Kpi Time Hours</td><td class="input-field"><?php echo form_dropdown('KpiTimeHours', Servicetickets_model::$KpiTimeHours, $default = ((isset($subject['KpiTimeHours']))? $subject['KpiTimeHours']:''), 'id="KpiTimeHours"');?></td><td><?php echo form_error('KpiTimeHours'); ?></td></tr>
	    			<tr><td class="input-label">Default Priority</td><td class="input-field"><?php echo form_dropdown('DefPriorityID', $subjectPriorities, $default = ((isset($subject['DefPriorityID']))? $subject['DefPriorityID']:''), 'id="DefPriorityID"');?></td><td><?php echo form_error('DefPriorityID'); ?></td></tr>
	    			<tr><td class="input-label">Default Severity</td><td class="input-field"><?php echo form_dropdown('DefSeverityID', $subjectSeverities, $default = ((isset($subject['DefSeverityID']))? $subject['DefSeverityID']:''), 'id="DefSeverityID"');?></td><td><?php echo form_error('DefSeverityID'); ?></td></tr>
	    		</table>
	    	</div>
	    </div>
		<?php if (isset($subject['SubjectID'])) {?>
		<div class="cust-holder">
	    	<h3 class="box-hd">Notifications<a href="<?php echo site_url("admin/servicetickets/set_notify_recipients/{$subject['SubjectID']}");?>" class="btn-link pull-right"><i class="cus-add"></i> New Notification</a></h3>
	    	<div class="box-content-no">
	    		<table id="subject_approve" class="gradient-thead compact table-striped bootstrap-datatable">
	    			<thead>
		              <tr>
		                  <th style="width: 20px;">ID</th>
		                  <th>Title</th>
		                  <th>Notification Type</th>
		                  <th>Recipients</th>
		                  <th style="width: 110px;">Actions</th>
		              </tr>
		          </thead>   
		          <tbody>
		          	<?php if (isset($notifications) && $notifications) {?>
		          	<?php foreach($notifications as $item) :?>
		            <tr>
		                <td><?php echo $item['ID'];?></td>
		                <td class="center"><?php echo $item['Title'];?></td>
		                <td class="center"><?php echo Servicetickets_model::$NotificationType[$item['Type']];?></td>
		                <td class="center"><?php echo $item['role_name'];?></td>
		                <td class="center">
		                    <?php if($per_page['per_update']==1){?>
		                    <a class="btn-link" href="<?php echo site_url("admin/servicetickets/set_notify_recipients/{$subject['SubjectID']}/{$item['ID']}");?>"><i class="cus-page-white-edit"></i>Edit</a>
		                    <?php }else{?>
		                     <a class="btn-link disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
		                    <?php }?>
		                    <?php if($per_page['per_delete']==1){?>
		                    <a class="btn-link delete-notify" data-norifyid="<?php echo $item['ID'];?>" data-subjectid="<?php echo $subject['SubjectID'];?>" href="<?php echo site_url('admin/servicetickets/deleteNotify/'.$item['ID'])?>"><i class="cus-delete"></i>Delete</a>
		                    <?php } else {?>
		                     <a class="btn-link disabled" href="#"><i class="cus-delete"></i>Delete</a>
		                    <?php }?>
		                </td>
		            </tr>
		            <?php endforeach;?>
		            <?php } else {?>
		            <tr class="gray no-record"><td colspan="6" align="center">No Record.</td></tr>
		            <?php }?>
		            </tbody>
	    		</table>
	    	</div>
	    </div>
	    <?php }?>
		<div class="cust-holder">
	    	<h3 class="box-hd">Approval entities</h3>
	    	<div class="box-content-no">
	    		<table id="subject_approve" class="gradient-thead compact table-striped bootstrap-datatable">
	    			<thead>
	    				<tr><th>Approve 1</th><th>Approve 2</th><th>Approve 3</th><th>Approve 4</th><th>Approve 5</th><th>Approve 6</th></tr>
	    			</thead>
	    			<tbody>
	    				<tr><?php for ($i=0;$i<6;$i++) {?>
							<td><?php echo form_dropdown('approveentities[]', $roles, ((isset($approveentities[$i]->role_id))? $approveentities[$i]->role_id:''));?></td>
							<?php }?>
						</tr>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    
		<div class="cust-holder">
	    	<h3 class="box-hd">Executive entities</h3>
	    	<div class="box-content-no">
	    		<table id="subject_approve" class="gradient-thead compact table-striped bootstrap-datatable">
	    			<thead>
	    				<tr><th>Executant 1</th><th>Executant 2</th><th>Executant 3</th><th>Executant 4</th><th>Executant 5</th><th>Executant 6</th></tr>
	    			</thead>
	    			<tbody>
	    				<tr><?php for ($i=0;$i<6;$i++) {?>
							<td><?php echo form_dropdown('executiveentities[]', $roles, ((isset($executiveentities[$i]->role_id))? $executiveentities[$i]->role_id:''));?></td>
							<?php }?>
						</tr>
	    			</tbody>
	    		</table>
	    	</div>
	    </div>
	    
	    
	    <div class="cust-holder">
	    	<h3 class="box-hd">Required fields</h3>
	    	<div class="box-content-no">
	    		<table class="cust-inputs">
	    			<?php for ($i=0;$i<5;$i++){?>
	    			<tr><td class="input-label">Question <?php echo $i+1;?></td><td class="input-field"><input name="Questions[]" type="text" value="<?php echo set_value('Questions[]', ((isset($loadedQuestions[$i]))? $loadedQuestions[$i]:'')); ?>" /></td><td><?php echo form_error('Questions[]'); ?></td></tr>
	    			<?php }?>
	    		</table>
	    	</div>
	    </div>
	    
	    
	    <div class="submit-block">
	    	<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
	    	<a href="<?php echo site_url('admin/servicetickets/subjects/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
	    </div>
	    <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	
	 $("#frm_edit_subject").validate({
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

// 	 delete-notify
	$(document).on('click', '.delete-notify', function(e) {
		e.preventDefault();
		var $this = $(this);
		if (false == confirm('You are sure to delete notification!')) return false;
		var ID = $(this).data('norifyid');
		var SubjectID = $(this).data('subjectid');
		
		if (ID) {
			$.ajax({
			    url: "<?php echo site_url('admin/servicetickets/deleteNotify/')?>",  
			    type: "post",
			    data: {"ID":ID, "SubjectID":SubjectID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
			    success: function(data) {
			    	$this.parent('td').parent('tr').remove();
			    }
			});
		}
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

table#subject_approve tbody select {width: 100%;border-color: #bfcecc; border-radius: 0; font-size: 12px;  margin: 5px 0; height: 20px; padding: 0;}
table#subject_approve thead th {padding: 5px;}
.submit-block {margin:10px;}
.cust-holder {margin-top:10px;}
table.gradient-thead tbody tr td {border: 1px solid #ccc;}
.no-margin {margin:0;}
</style>