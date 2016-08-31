<!-- Form Service Ticket -->

<?php echo form_open('', array('id' => 'EditTicket', 'name'=>'EditTicket', 'class'=>'form-horizontal')); ?>
<div class="mass-subs-cont"><?php if (isset($choosedMsubchoosed) && count($choosedMsubchoosed)) 
		foreach ($choosedMsubchoosed as $mItem) {?>
		<input type="hidden" name="msubchoosed[]" id="mchoosed_<?php echo $mItem;?>" value="<?php echo $mItem;?>" />
<?php }?>
</div>
<div class="row-fluid">
<div class="span6">
<fieldset class="scheduler-border"><legend class="scheduler-border"><?php echo $action_title;?></legend>

	<input type="hidden" name="TicketType" value="<?php echo set_value("TicketType", ((isset($choosedTicketType))? $choosedTicketType:'')); ?>" />
	<input type="hidden" name="AccountID" value="<?php echo set_value("AccountID", ((isset($choosedAccountID))? $choosedAccountID:'')); ?>" />
	<div class="control-group">
		<label class="control-label" for="TypeID">Request Type <span class="required">*</span></label>
		<div class="controls">
        	<?php echo form_dropdown('type', $TypeOptions, $default = ((isset($choosedTypeId))? $choosedTypeId : ''),'id="TypeID" size="4"'); ?>
        	<?php echo form_error('type'); ?> 
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="GroupID">Service group <span class="required">*</span></label>
		<div class="controls">
        	<?php echo form_dropdown('GroupID', $GroupOptions, $default = ((isset($choosedGroupId))? $choosedGroupId : ''),'id="GroupID" size="5"'); ?>
        	<?php echo form_error('GroupID'); ?> 
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="subject">Service Type <span class="required">*</span></label>
		<div class="controls" style="position: relative;">
	        <?php echo form_dropdown('subject', $SubjectOptions, $default = ((isset($choosedSubjectId))? $choosedSubjectId : ''),'id="subject" size="6"');?>
	        <?php echo form_error('subject'); ?>
	        <div id="ajax-preloader" class="bg_opacity">
				<img class="ajax-loader" alt="waiting..." src="<?php echo base_url('public/images/ajax-loader.gif'); ?>" />
			</div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="severity">Severity <span class="required">*</span></label>
		<div class="controls">
			<?php echo form_dropdown('severity', $SeverityOptions, $default = ((isset($choosedSeverityId))? $choosedSeverityId : ''), 'id="severity"');?>
			<?php echo form_error('severity'); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="description">Description <span class="required">*</span></label>
		<div class="controls">
            <textarea id="description" name="description" rows="5" cols="80"><?php echo set_value('description', ((isset($STicket->Description))? $STicket->Description:'')); ?></textarea>
            <?php echo form_error('description'); ?>
		</div>
	</div>
	<?php if ($this->session->userdata('visitor')) {?>
	<div class="control-group">
		<label class="control-label">Feedback Email <span class="required">*</span></label>
		<div class="controls">
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
		<div class="control-group">
			<label class="control-label"><span class="required">*</span></label>
			<div class="controls">
				<?php echo $QTitle;?><br />
	            <input name="Answers[<?php echo $QuestionID;?>]" type="text" value="<?php echo set_value("Answers[{$QuestionID}]", ((isset($loadedAnswers[$QuestionID]))? $loadedAnswers[$QuestionID]:'')); ?>" />
	            <?php echo form_error("Answers[{$QuestionID}]"); ?>
			</div>
		</div>
		<?php }?>
	</div>
	
	 <div class="form-actions">
		<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
		<a href="<?php echo site_url('servicetickets/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
	</div>
	
</fieldset>
</div>
<!-- end span6 ticket inputs -->
<?php if ($this->acl->can_read(null, 34 /*34 ST Relationship*/)) {?>
<div class="span6 ticket-type">
<fieldset class="scheduler-border"><legend class="scheduler-border">Ticket Relationship</legend>
	<div class="ticket-type-filter">
		<h4 class="ticket-type-category">Individual</h4>
			<div><input type="radio" name="TicketType" data-type="1" value="0" id="current_phone_no" <?php echo ($choosedTicketType == '0' ? 'checked="checked" ':'');?> autocomplete="off" /><label for="current_phone_no">Current subscriber phone number &ndash; <strong><?php echo ((isset($current_subscriber['Subscriber_Info']['PhoneNo']))? '0'.$current_subscriber['Subscriber_Info']['PhoneNo']: '');?></strong></label></div>
			<div><input type="radio" name="TicketType" data-type="3" value="3" id="new_phone_no" <?php echo ($choosedTicketType == 3 ? 'checked="checked"':'');?> autocomplete="off"/><label for="new_phone_no">Other subscriber phone number</label></div>
			<div class="new-phone-no-block<?php echo (set_value('TicketType') == 3 ? '':' hide');?>">
				<input id="new_phone" name="new_phone" type="text" value="<?php echo set_value('new_phone');?>" maxlength="13" /> <em class="error_new_phone red hide">Field is required</em>
			</div>
		<h4 class="ticket-type-category">Mass full customer</h4>
			<div><input type="radio" name="TicketType" data-type="2" value="1" id="current_customer" <?php echo ($choosedTicketType == 1 ? 'checked="checked"':'');?> autocomplete="off"/><label for="current_customer">Current customer &ndash; <strong><?php echo ((isset($current_customer['CustName']))? $current_customer['CustName']. (isset($current_customer['Groups_Info']['GroupDetails']['MemberAmount']) ? " ({$current_customer['Groups_Info']['GroupDetails']['MemberAmount']})":''): '');?></strong></label></div>
		<?php if (isset($current_customer['Groups_Info']['MemberSubscriberList'])) {?>
		<h4 class="ticket-type-category">Mass selected subscribers</h4>
			<div><input type="radio" name="TicketType" data-type="4" value="2" id="mass_phone_no" <?php echo ($choosedTicketType == 2 ? 'checked="checked"':'');?> autocomplete="off"/>
				<label for="mass_phone_no">Selected subscribers in <?php echo ((isset($current_customer['CustName']))? $current_customer['CustName'] : '');?> (<strong class="mchoosed_counter">0</strong>)</label></div>
			<?php echo form_error('msubchoosed[]');?>
			<div id="members_list" class="ot-panel-content relative<?php echo ($choosedTicketType == 2 ? '':' hide');?>">
            	<?php 
            	$this->load->view('search/ajax_members_check', array(
            			'MemberSubscriberList' => $current_customer['Groups_Info']['MemberSubscriberList'],
            			'MemberAmount' => $current_customer['Groups_Info']['GroupDetails']['MemberAmount'], 
            			'page' => $current_memberpage
            	)); ?>
			</div>
		<?php }?>
	</div>
</fieldset>
</div>
<?php } else { ?>
<input type="hidden" name="TicketType" value="0" />
<?php }?>
<?php if (isset($choosedTicketType) && $choosedTicketType == Servicetickets_model::$TicketType_Subscriber) {?>
<div class="span6 subscriber-details">
	<fieldset class="scheduler-border"><legend class="scheduler-border">Subscriber details</legend>
		<table class="table table-hover table-borderless tb-ticket-details">
	        <tbody>
	          <tr><td>Phone number</td><td><?php echo ((isset($subscriber['Subscriber_Info']['PhoneNo']))? $subscriber['Subscriber_Info']['PhoneNo'] : '');?></td></tr>
	          <tr><td>SIM ID</td><td><?php echo (isset($subscriber['Subscriber_Info']['ICCID']))? $subscriber['Subscriber_Info']['ICCID']:'';?></td></tr>
	          <tr><td>Subscription date</td><td><?php echo (isset($subscriber['Subscriber_Info']['EffectiveDate']))? date("d-M-Y", strtotime($subscriber['Subscriber_Info']['EffectiveDate'])):'';?></td></tr>
	          <tr><td>Status</td><td><?php echo (isset($subscriber['Subscriber_Info']['SubStatus']))? $subscriber['Subscriber_Info']['SubStatus']:'';?></td></tr>
	          <tr><td>Outstanding Bill</td><td><?php echo (isset($subscriber['Balance_info']['OutStandingAmount']))? $subscriber['Balance_info']['OutStandingAmount']:'0';?> USD</td></tr>
	          <tr><td>Total Credit Amount</td><td><?php echo (isset($subscriber['Balance_info']['TotalCreditAmount']))? $subscriber['Balance_info']['TotalCreditAmount']:'0';?> USD</td></tr>
	          <tr><td>Available Credit Amount</td><td><?php echo (isset($subscriber['Balance_info']['TotalRemainAmount']))? $subscriber['Balance_info']['TotalRemainAmount']:'0';?> USD</td></tr>
	
	          <tr><td>Tariff</td><td><?php echo (isset($subscriber['Tariff_Info']['OfferingName']))? $subscriber['Tariff_Info']['OfferingName']:'';?></td></tr>
	          <tr><td>Tariff Activation Date</td><td><?php echo (isset($subscriber['Tariff_Info']['EffectiveDate']))? date('d-M-Y', strtotime($subscriber['Tariff_Info']['EffectiveDate'])):'';?></td></tr>
	          
	          <tr><td>Services</td><td>
	          	<?php if (isset($subscriber['Service_Info'])) {
	          			echo implode('<br />', $subscriber['Service_Info']);
				}?></td></tr>
	        </tbody>
		</table>
	</fieldset>
</div>
<?php }?>
<div class="span6 customer-details">
	<fieldset class="scheduler-border"><legend class="scheduler-border">Customer details</legend>
		<table class="table table-hover table-borderless tb-ticket-details">
	        <tbody>
	          <tr><td>Corporate Name</td><td><?php echo ((isset($customer['CustName']))? $customer['CustName'] : '');?></td></tr>
	          <tr><td>Industry</td><td><?php echo (isset($customer['IndustryName']))? $customer['IndustryName']:'';?></td></tr>
	          <tr><td>Account Status</td><td><?php echo (isset($customer['CustStatus'])? $customer['CustStatus']:'');?></td></tr>
	          <tr><td>Billing Medium</td><td><?php echo (isset($customer['BillMediumCode']))? Customer_model::$BillMediumCode[$customer['BillMediumCode']] . ' Bill': '';?></td></tr>
	          <tr><td>Bill due Date</td><td><?php echo (isset($customer['DueDate']))? $customer['DueDate']: '';?></td></tr>
	          <tr><td>Bill Statement Date</td><td><?php echo (isset($customer['BillCycleType']))? $customer['BillCycleType']: '';?></td></tr>
	          <tr><td>Contract Date</td><td><?php echo ((isset($customer['RegisterDate']))? date('d-M-Y', strtotime($customer['RegisterDate'])): '');?></td></tr>
	          <?php if($this->acl->can_read(null, 26 /*26 Customer Passport*/)) {?>
	          <tr><td>Passport</td><td><?php echo (isset($customer['passport']))? $customer['passport']: '';?></td></tr>
	          <?php }?>
	          <?php if($this->acl->can_read(null, 27 /*27 Customer ID card*/)) {?>
	          <tr><td><?php echo label('lbl_card_id')?></td><td><?php echo (isset($customer['card_id']))? $customer['card_id']: '';?></td></tr>
	          <?php }?>
	          <tr><td>Certificate #</td><td><?php echo (isset($customer['CertificateNumId']))? $customer['CertificateNumId']: '';?></td></tr>
	          <tr><td>TIN</td><td><?php echo (isset($customer['tin']))? $customer['tin']: '';?></td></tr>
	          <tr><td>Agreement Number</td><td><?php echo (isset($customer['AgreementId']))? $customer['AgreementId']: '';?></td></tr>
	        </tbody>
		</table>
	</fieldset>
</div>
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
		$('#ajax-preloader').show();
    	/*dropdown post *///  
        $.ajax({  
	        url:"<?php echo site_url('servicetickets/buildDropGroups/');?>",  
	        data: {typeid: typeid, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "POST",  
	        success:function(response){  
        		$("#GroupID").replaceWith(response);
        		$('#ajax-preloader').hide();
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
		$('#ajax-preloader').show();
    	/*dropdown post *///  
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
		        if (response.subject.DefSeverityID)
		        	$("#severity").val(response.DefSeverityID);
		        else 
		        	$("#severity").val(0);
	        	
		        $("#severity").focus();

				$('.subject_pull').html(response.subject_pull);
		        
        		$('#ajax-preloader').hide(); 
        	}  
        });  
	});

	$("#EditTicket").validate({
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

	$(document).on('change', 'input[name="TicketType"]', function(){
		var dataType = $(this).data('type');
		//console.log(dataType);
		
		switch (dataType) {
			case 1: //Current subscriber phone number
				$('.ticket-type-filter .new-phone-no-block').slideUp('fast');
				$('.ticket-type-filter #members_list').slideUp('fast');
				$('.subscriber-details').show();
				$('.customer-details').show();
				break;
			case 2: //Current customer
				$('.ticket-type-filter .new-phone-no-block').slideUp('fast');
				$('.ticket-type-filter #members_list').slideUp('fast');
				$('.subscriber-details').hide();
				$('.customer-details').show();
				break;
			case 3: //Other subscriber phone number
				$('.ticket-type-filter .new-phone-no-block').slideDown('fast');
				$('.ticket-type-filter #members_list').slideUp('fast');
				$('.subscriber-details').hide();
				$('.customer-details').hide();
				break;
			case 4: //Selected subscribers in current customer
				$('.ticket-type-filter .new-phone-no-block').slideUp('fast');
				$('.ticket-type-filter #members_list').slideDown('fast');
				$('.subscriber-details').hide();
				$('.customer-details').show();
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
.form-horizontal .control-label {width: 115px;}
.form-horizontal .controls {margin-left: 120px;}
.ticket-type-category {text-decoration: underline;}
.ticket-type-filter {padding-left:20px;}
.ticket-type-filter div:not(#ajax-preloader_member) {margin:5px 0 5px 20px;}
.ticket-type-filter label {display: inline-block;padding-left:5px;}
.new-phone-no-block {padding-left:20px;}
.ticket-type fieldset.scheduler-border {}
.ticket-type-filter input {margin-top: 0;}
.ticket-type-filter label {margin-bottom: 0;}
</style>