<!-- Form Service Ticket -->

<?php echo form_open('', array('id' => 'EditTicket', 'name'=>'EditTicket', 'class'=>'form-horizontal row-border')); ?>
<div class="mass-subs-cont"><?php if (isset($choosedMsubchoosed) && count($choosedMsubchoosed)) 
		foreach ($choosedMsubchoosed as $mItem) {?>
		<input type="hidden" name="msubchoosed[]" id="mchoosed_<?php echo $mItem;?>" value="<?php echo $mItem;?>" />
<?php }?>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
	        <div class="panel-heading clearfix"><i class="icomoon icon-ticket"></i> <?php echo $action_title;?></div>
	        <div class="panel-body">
				<input type="hidden" name="TicketType" value="<?php echo set_value("TicketType", ((isset($choosedTicketType))? $choosedTicketType:'')); ?>" />
				<input type="hidden" name="AccountID" value="<?php echo set_value("AccountID", ((isset($choosedAccountID))? $choosedAccountID:'')); ?>" />
				<div class="form-group">
					<label class="col-md-3 control-label" for="TypeID">Ticket Type <span class="required">*</span></label>
					<div class="col-md-9">
			        	<?php echo form_dropdown('type', $TypeOptions, $default = ((isset($choosedTypeId))? $choosedTypeId : ''),'id="TypeID" size="4" class="form-control"'); ?>
			        	<?php echo form_error('type'); ?> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label" for="GroupID">Service group <span class="required">*</span></label>
					<div class="col-md-9">
			        	<?php echo form_dropdown('GroupID', $GroupOptions, $default = ((isset($choosedGroupId))? $choosedGroupId : ''),'id="GroupID" size="5" class="form-control"'); ?>
			        	<?php echo form_error('GroupID'); ?> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label" for="subject">Service Type <span class="required">*</span></label>
					<div class="col-md-9">
				        <?php echo form_dropdown('subject', $SubjectOptions, $default = ((isset($choosedSubjectId))? $choosedSubjectId : ''),'id="subject" size="6" class="form-control"');?>
				        <?php echo form_error('subject'); ?>
					</div>
				</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="severity">Severity <span class="required">*</span></label>
				<div class="col-md-9">
					<?php echo form_dropdown('severity', $SeverityOptions, $default = ((isset($choosedSeverityId))? $choosedSeverityId : ''), 'id="severity" class="form-control"');?>
					<?php echo form_error('severity'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="description">Description <span class="required">*</span></label>
				<div class="col-md-9">
		            <textarea id="description" class="form-control" name="description" rows="5" cols="80"><?php echo set_value('description', ((isset($STicket->Description))? $STicket->Description:'')); ?></textarea>
		            <?php echo form_error('description'); ?>
				</div>
			</div>
			<?php if ($this->session->userdata('visitor')) {?>
			<div class="form-group">
				<label class="col-md-3 control-label">Feedback Email <span class="required">*</span></label>
				<div class="col-md-9">
					<div>
		            	<input name="InitiatorEmail" type="text" value="<?php echo set_value("InitiatorEmail", ((isset($choosedInitiatorEmail))? $choosedInitiatorEmail:'')); ?>" />
		            	<?php echo form_error("InitiatorEmail"); ?>
		            </div>
		            <em style="color: #999;font-size: 10px;">* please specify e-mail address to receive the feedback from Smart</em>
				</div>
			</div>
			<?php }?>
			<div class="subject_pull">
				<?php if (isset($Questions) && $Questions) foreach ($Questions as $QuestionID => $QTitle) {?>
				<div class="form-group">
					<label class="col-md-3 control-label"><span class="required">*</span></label>
					<div class="col-md-9">
						<?php echo $QTitle;?><br />
			            <input name="Answers[<?php echo $QuestionID;?>]" type="text" class="form-control" value="<?php echo set_value("Answers[{$QuestionID}]", ((isset($loadedAnswers[$QuestionID]))? $loadedAnswers[$QuestionID]:'')); ?>" />
			            <?php echo form_error("Answers[{$QuestionID}]"); ?>
					</div>
				</div>
				<?php }?>
			</div>
		
		</div><!-- end panel-body -->
		<div class="panel-footer text-right">
			<a class="btn btn-default" href="<?php echo site_url('servicetickets/')?>"><i class="icomoon icon-cancel-circle"></i> Cancel</a>
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
		</div>
	</div>
</div>
<!-- end col-md-6 ticket inputs -->
<?php if ($this->acl->can_read(null, 34 /*34 ST Relationship*/)) {?>
<div class="col-md-6 ticket-type">
	<div class="panel panel-default">
        <div class="panel-heading clearfix"><i class="fa fa-search-plus"></i> Ticket Relationship</div>
        <div class="panel-body">
			<div class="ticket-type-filter">
				<h4 class="ticket-type-category">Individual</h4>
					<div class="radio radio-success"><input type="radio" name="TicketType" data-type="1" value="0" id="current_phone_no" <?php echo ($choosedTicketType == '0' ? 'checked="checked" ':'');?> autocomplete="off" /><label for="current_phone_no">Current subscriber phone number &ndash; <strong><?php echo ((isset($current_subscriber['Subscriber_Info']['PhoneNo']))? '0'.$current_subscriber['Subscriber_Info']['PhoneNo']: '');?></strong></label></div>
					<div class="radio radio-success"><input type="radio" name="TicketType" data-type="3" value="3" id="new_phone_no" <?php echo ($choosedTicketType == 3 ? 'checked="checked"':'');?> autocomplete="off" /><label for="new_phone_no">Other subscriber phone number</label></div>
					<div id="new_phone_hold" class="new-phone-no-block<?php echo (set_value('TicketType') == 3 ? '':' none');?>">
						<input id="new_phone" name="new_phone" type="text" value="<?php echo set_value('new_phone');?>" maxlength="13" /> <em class="error_new_phone red hide">Field is required</em>
					</div>
				<h4 class="ticket-type-category">Mass full customer</h4>
					<div class="radio radio-success"><input type="radio" name="TicketType" data-type="2" value="1" id="current_customer" <?php echo ($choosedTicketType == 1 ? 'checked="checked"':'');?> autocomplete="off"/><label for="current_customer">Current customer &ndash; <strong><?php echo ((isset($current_customer['CustName']))? $current_customer['CustName']. (isset($current_customer['Groups_Info']['GroupDetails']['MemberAmount']) ? " ({$current_customer['Groups_Info']['GroupDetails']['MemberAmount']})":''): '');?></strong></label></div>
				<?php if (isset($current_customer['Groups_Info']['MemberSubscriberList'])) {?>
				<h4 class="ticket-type-category">Mass selected subscribers</h4>
					<div class="radio radio-success">
						<input type="radio" name="TicketType" data-type="4" value="2" id="mass_phone_no" <?php echo ($choosedTicketType == 2 ? 'checked="checked"':'');?> autocomplete="off"/>
						<label for="mass_phone_no">Selected subscribers in <?php echo ((isset($current_customer['CustName']))? $current_customer['CustName'] : '');?> (<strong class="mchoosed_counter">0</strong>)</label>
					</div>
					<?php echo form_error('msubchoosed[]');?>
					<div id="members_list" class="ot-panel-content relative<?php echo ($choosedTicketType == 2 ? '':' none');?>">
		            	<?php 
		            	$this->load->view('search/ajax_members_check', array(
		            			'MemberSubscriberList' => $current_customer['Groups_Info']['MemberSubscriberList'],
		            			'MemberAmount' => $current_customer['Groups_Info']['GroupDetails']['MemberAmount'], 
		            			'page' => $current_memberpage
		            	)); ?>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
<input type="hidden" name="TicketType" value="0" />
<?php }?>

</div>
<?php echo form_close(); ?>
<!-- End sts form -->
<script type="text/javascript">  
/* for Subects DDL built from selected Type */
$(document).ready(function() {  

	$("#members_list").on('click', '.member-paging ul li a', function(e) {
		e.preventDefault();

// 		console.log($(this).attr("href"));return;
		
		$('#ajax-preloader_member').show();
		//search/ajax_members/3
		$.get($(this).attr("href"),
			{type:1, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				$("#members_list").html(response);

				$('.mass-subs-cont input[name="msubchoosed[]"]').each(function () {
					$('#members_list #snum_'+this.value).propAttr('checked', true);
				});
				$('#ajax-preloader_member').hide();
		}).error(function(x, status, error) {console.log(error);});
	});
	
	$(document).on('change', 'input[name="SubsNum"]', function(){
		var SubsNum = $(this).val();

		if (SubsNum && true == this.checked && $('input#mchoosed_'+SubsNum).length == 0) {
			$('.mass-subs-cont').append($("<input>").attr("type", "hidden").attr("name", "msubchoosed[]").attr("id", "mchoosed_"+SubsNum).val(SubsNum));
		} else {
			$('.mass-subs-cont #mchoosed_'+SubsNum).remove(); 
		}

		$('.mchoosed_counter').html($('.mass-subs-cont input[name="msubchoosed[]"]').length);
	});

	
	$(document).on('change', "#TypeID", function() {
		var typeid = $('#TypeID').val();

		$('.subject_pull').html('');
		$("select#subject").find("option:gt(0)").remove();
		$('#GroupID').parent().toggleClass('ajax-loader');
 
        $.ajax({  
	        url:"<?php echo site_url('servicetickets/buildDropGroups/');?>",  
	        data: {typeid: typeid, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "POST",  
	        success:function(response){  
        		$("#GroupID").replaceWith(response);
        		$('#GroupID').parent().toggleClass('ajax-loader');
        		$('#GroupID').focus();
        	}  
        });  
	});

	$(document).on('change', "#GroupID", function() {
		var GroupID = $('#GroupID').val();
		var typeid = $('#TypeID').val();

		$('.subject_pull').html('');
		if (!GroupID) {$('#GroupID').focus(); return;}
		if (!typeid) {$('#TypeID').focus(); return;}
		$('#subject').parent().toggleClass('ajax-loader');
    	/*dropdown post *///  
        $.ajax({  
	        url:"<?php echo site_url('servicetickets/buildDropSubjects/');?>",  
	        data: {typeid: typeid, GroupID: GroupID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "POST",  
	        success:function(data){  
        		$("#subject").html(data);
        		$('#subject').parent().toggleClass('ajax-loader');
        	}  
        });  
	});
	
	$("#subject").change(function(){
		var SubjectID = $(this).val();

		if (!SubjectID) {return;}
		
		$('.subject_pull').toggleClass('ajax-loader');
    	/*dropdown post *///  
        $.ajax({  
	        url:"<?php echo site_url('servicetickets/ajax_get_subject/');?>",  
	        data: {SubjectID: SubjectID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "GET",
			dataType: 'JSON', 
	        success:function(response){
		        if (response.subject.DefSeverityID)
		        	$("#severity").val(response.DefSeverityID);
		        else 
		        	$("#severity").val(0);
	        	
		        $("#severity").focus();

				$('.subject_pull').html(response.subject_pull);
				$('.subject_pull').toggleClass('ajax-loader');
        	}  
        });  
	});

	/*$("#EditTicket").validate({
		errorElement: 'span',
        errorClass: 'error',
        rules: {	
         InitiatorEmail: {required: true, email: true},
         description: {required: true},
       	 subject:{required: true, valueNotEquals: '0'},
       	 type:{required: true, valueNotEquals: '0'},
         GroupID:{required: true, valueNotEquals: '0'},
       	 severity:{required: true, valueNotEquals: '0'}
        }
    });
*/
	$(document).on('change', 'input[name="TicketType"]', function(){
		var dataType = $(this).data('type');
		//console.log(dataType);
		
		switch (dataType) {
			case 1: //Current subscriber phone number
				$('.ticket-type-filter .new-phone-no-block').slideUp('fast');
				$('.ticket-type-filter #members_list').slideUp('fast');
// 				$('.subscriber-details').show();
// 				$('.customer-details').show();
				break;
			case 2: //Current customer
				$('.ticket-type-filter .new-phone-no-block').slideUp('fast');
				$('.ticket-type-filter #members_list').slideUp('fast');
// 				$('.subscriber-details').hide();
// 				$('.customer-details').show();
				break;
			case 3: //Other subscriber phone number
				$('.ticket-type-filter .new-phone-no-block').slideDown('fast');
				$('.ticket-type-filter #members_list').slideUp('fast');
// 				$('.subscriber-details').hide();
// 				$('.customer-details').hide();
				break;
			case 4: //Selected subscribers in current customer
				$('.ticket-type-filter .new-phone-no-block').slideUp('fast');
				$('.ticket-type-filter #members_list').slideDown('fast');
// 				$('.subscriber-details').hide();
// 				$('.customer-details').show();
				break;
		}
		
	});
	
	$(document).on('keydown', '#EditTicket #new_phone', function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        //console.log(e.keyCode);
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40) || 
            // Allow: CTRL+V or C, not work, need add onKeyUp to check 2 key in one time
            (e.keyCode == 17 && (e.keyCode == 86 || e.keyCode == 67)) )
             {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
        $('.error_new_phone').hide();
    });


    
});  /*end for Subects DDL built from selected Type */
</script> 
<style>
.required {color:red;}
/*.form-horizontal .control-label {width: 115px;}
.form-horizontal .controls {margin-left: 120px;}*/
.ticket-type-category {text-decoration: underline;}
.ticket-type-filter div:not(#ajax-preloader_member) {margin:5px 0 5px 20px;}
.ticket-type-filter label {display: inline-block;padding-left:5px;}
.new-phone-no-block {padding-left:20px;}
.ticket-type fieldset.scheduler-border {}
.ticket-type-filter input {margin-top: 0;}
.ticket-type-filter label {margin-bottom: 0;}
</style>