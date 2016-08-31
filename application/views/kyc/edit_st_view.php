<!-- Form Service Ticket -->
<div class="container-fluid">
<fieldset class="scheduler-border span6"><legend class="scheduler-border"><?php echo $action_title;?></legend>
<?php echo form_open('', array('id' => 'EditTicket', 'name'=>'EditTicket', 'class'=>'form-horizontal')); ?>
	<input name="ticket_id" type="hidden" value="<?php echo set_value('ticket_id', ((isset($STicket->TicketID))? $STicket->TicketID:'')); ?>" />
	<div class="control-group">
		<label class="control-label" for="ticket_id">Ticket ID</label>
		<div class="controls">
			<?php echo $STicket->TicketID; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Request Type</label>
		<div class="controls">
			<?php echo $STicket->TypeName; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="type">Service group</label>
		<div class="controls">
			<?php echo $STicket->GroupName; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="subject">Service type</label>
		<div class="controls" style="position: relative;">
			<?php echo $STicket->SubjectName; ?>
		</div>
	</div>
	<div class="control-group hide">
		<label class="control-label" for="subscriber_name">Customer name</label>
		<div class="controls">
			<?php echo $STicket->company_name; ?>
		</div>
	</div>
	<div class="control-group hide">
		<label class="control-label" for="account_id">Subscriber Phone number</label>
		<div class="controls">
			<?php echo $STicket->AccountID; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="description">Description</label>
		<div class="controls">
			<?php echo word_limiter(strip_tags($STicket->Description), 10, '<a id="newFeedBack" data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="severity">Severity <span class="required">*</span></label>
		<div class="controls">
			<?php echo form_dropdown('severity', $SeverityOptions, $default = ((isset($choosedSeverityId))? $choosedSeverityId : ''), 'id="severity"');?>
			<?php echo form_error('severity'); ?>
		</div>
	</div>
	<?php if ($this->acl->can_modify(null, 6 /*ST priority*/)) :?>
	<div class="control-group">
		<label class="control-label" for="priority">Priority</label>
		<div class="controls">
			<?php echo form_dropdown('priority', $PriorityOptions, $default = ((isset($choosedPriorityId))? $choosedPriorityId : ''), 'id="priority"');?>
			<?php echo form_error('priority'); ?>
		</div>
	</div>
	<?php endif;?>

	<div class="control-group">
		<label class="control-label" for="status">Status <span class="required">*</span></label>
		<div class="controls">
	         <?php echo form_dropdown('status', $StatusOptions, $default = ((isset($choosedStatusId))? $choosedStatusId : ''), 'id="status"');
	         ?><?php echo form_error('status'); ?>
		</div>
	</div>
	<div class="control-group reject_reason hide">
		<label class="control-label" for="progress_comment">Reject Reason <span class="required">*</span></label>
		<div class="controls">
	    	<textarea id="reject_reason" name="reject_reason" rows="5" cols="80"><?php echo set_value('reject_reason', ((isset($choosedreject_reason))?$choosedreject_reason:'')); ?></textarea>
	    	<?php echo form_error("reject_reason"); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="progress_comment">Progress Comment</label>
		<div class="controls">
	    	<textarea id="progress_comment" name="progress_comment" rows="5" cols="80"><?php echo set_value('progress_comment', ((isset($STicket->ProgressComment))? $STicket->ProgressComment:'')); ?></textarea>
	    	<?php echo form_error('progress_comment'); ?>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
		<a href="<?php echo site_url('Servicetickets/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
	</div>
	
	<div class="modal hide" id="ticket_description">
		<div class="modal-header">
			<a href="#" class="close pull-right" data-dismiss="modal">×</a>
			<h4>Ticket Description</h4>
		</div>
		<div class="modal-body">
			<fieldset class="filter-holder">
	    		<div class="control-group default-filter">
					<?php echo $STicket->Description;?>
				</div>
            </fieldset>
		</div>
		<div class="modal-footer">
			<a class="btn btn-danger fixedsize" data-dismiss="modal" href="">Cancel</a>
		</div>
	</div>
	<?php echo form_close(); ?>
</fieldset>
<!-- Subscriber details -->
<fieldset class="scheduler-border span6 pull-right"><legend class="scheduler-border">Subscriber details</legend>
	<table class="table table-hover table-borderless tb-ticket-details">
        <tbody>
          <tr><td>Subscriber ID</td><td><?php echo $subscriber->subs_id;?></td></tr>
          <tr><td>Subscriber Name</td><td><?php echo $subscriber->firstname. ' ' . $subscriber->lastname;?></td></tr>
          <tr><td>Company</td><td><?php echo ((isset($subscriber->company_name))? $subscriber->company_name : '');?></td></tr>
          <?php if (isset($subscriber->branch_name)): ?><tr><td>Company branch</td><td><?php echo $subscriber->branch_name;?></td></tr><?php endif;?>
          <tr><td>SIM ID</td><td><?php echo (isset($subscriber->sim))?$subscriber->sim:'n/a';?></td></tr>
          <tr><td>Phone number</td><td><?php echo (isset($subscriber->phone))?$subscriber->phone:'n/a';?></td></tr>
        </tbody>
	</table>
</fieldset>
</div>
<!-- End sts form -->
<script type="text/javascript">  
	/* for Subects DDL built from selected Type */
$(document).ready(function() {  
	$("#TypeID, #GroupID").change(function(){
		var GroupID = $('#GroupID').val();
		var typeid = $('#TypeID').val();

		if (!GroupID) {$('#GroupID').focus(); return;}
		if (!typeid) {$('#TypeID').focus(); return;}
		
		$('#ajax-preloader').show(); 
        $.ajax({  
	        url:"<?php echo site_url('servicetickets/buildDropSubjects/');?>",  
	        data: {typeid: typeid, GroupID: GroupID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "POST",  
	        success:function(data){  
        		$("#subject").html(data); 
        		$('#ajax-preloader').hide(); 
        	}  
        });  
	});

	$("#subject").change(function(){
		var SubjectID = $(this).val();

		if (!SubjectID) {return;}
		
		$('#ajax-preloader').show();
    	/*dropdown post *///  
        $.ajax({  
	        url:"<?php echo site_url('servicetickets/ajax_get_subject/');?>",  
	        data: {SubjectID: SubjectID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "GET",
			dataType: 'JSON', 
	        success:function(response){
		        if (response.DefSeverityID)
		        	$("#severity").val(response.DefSeverityID);
		        else 
		        	$("#severity").val(0);

		        if (response.DefPriorityID)
		        	$("#priority").val(response.DefPriorityID);
		        else 
		        	$("#priority").val(0);
	        	
		        $("#severity").focus();
        		$('#ajax-preloader').hide(); 
        	}  
        });  
	});

	$(document).on("change", "#status", function(e){

		if($(this).val() == '<?php echo Servicetickets_model::$DefaultRejectStatus?>') {
			$(".reject_reason").show();
		} else {
			$(".reject_reason").hide();
		}
	});
	
	 $("#EditTicket").validate({
		 errorElement: 'span',
         errorClass: 'error',
         rules: {	
        	 //customer_name: {required: true, minlength:2, maxlength:100},
        	 subject:{required: true, valueNotEquals: '0'},
	       	 type:{required: true, valueNotEquals: '0'},
	         GroupID:{required: true, valueNotEquals: '0'},
	       	 severity:{required: true, valueNotEquals: '0'}
         }
     });  
});  /*end for Subects DDL built from selected Type */
</script>
<style>.required {color:red;}</style>