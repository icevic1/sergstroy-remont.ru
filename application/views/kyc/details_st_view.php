<div class="row">
<!-- Service Ticket Details -->
<div id="ticket_details" class="col-md-4">
	<div id="" class="panel panel-default">
		<div class="panel-heading"><i class="glyphicon glyphicon-info-sign"></i> Ticket Details</div>
		<div class="table-responsive">
			<table class="table table-hover table-borderless tb-ticket-details">
		        <tbody>
		          <tr><td>Ticket ID</td><td><?php echo $STicket->TicketID;?></td></tr>
		          <?php if($this->acl->can_read(null, 22 /*22 Company name*/)) {?>
		          <tr><td>Agreement Number</td><td><?php echo $STicket->AgreementId;?></td></tr>
		          <tr><td>Customer Name</td><td><?php echo $STicket->CustName;?></td></tr>
		          <?php }?>
		          <?php if($STicket->TicketType == '0') {?>
		          <tr><td>Phone Number</td><td>0<?php echo $STicket->AccountID;?></td></tr>
		          <?php } elseif($STicket->TicketType == '1') {?>
		          <tr><td>Phone Number</td><td><a id="mass_all" href="<?php echo site_url("search/ajax_members_list/{$STicket->GroupId}/1");?>">Multiple numbers <span class="badge"><?php echo $countMassSelectedSubs;?></span></a></td></tr>
		          <?php } elseif($STicket->TicketType == '2') {?>
		          <tr><td>Phone Number</td><td><a id="mass_selected" data-ticketid="<?php echo $STicket->TicketID;?>" href="">Multiple numbers <span class="badge"><?php echo $countMassSelectedSubs;?></span></a></td></tr>
		          <?php }?>
		          <tr><td>Ticket Type</td><td><?php echo $STicket->TypeName;?></td></tr>
		          <tr><td>Service Group</td><td><?php echo $STicket->GroupName;?></td></tr>
		          <tr><td>Service Type</td><td><?php echo $STicket->SubjectName;?></td></tr>
		          <tr><td>Severity</td><td><?php echo $STicket->SeverityName;?></td></tr>
		          <?php if($this->acl->can_read(null, 6 /*6 ST priority*/)) {?>
		          <tr><td>Priority</td><td><?php echo $STicket->PriorityName;?></td></tr>
		          <?php }?>
		          <tr><td>Date of Creation</td><td><?php echo date('d-M-Y', strtotime($STicket->CreationDateTime));?></td></tr>
		          <tr><td>Time of Creation</td><td><?php echo date('H:i', strtotime($STicket->CreationDateTime));?></td></tr>
		          <?php if ($STicket->CreatedByID && $this->session->userdata('visitor') == false) {?>
		          <tr><td>Created By</td><td><?php echo $STicket->whoCreateName;?></td></tr>
		          <?php }?>
		           <?php if ($this->acl->can_view(null, 33 /*33 ST customer status*/) == false) {?>
		          <tr><td>Date of last edit</td><td><?php echo date('d-M-Y', strtotime($STicket->LastEditDate));?></td></tr>
		          <tr><td>Time of last edit</td><td><?php echo date('H:i', strtotime($STicket->LastEditDate));?></td></tr>
		          <?php }?>
		          <?php if ($this->acl->can_view(null, 33 /*33 ST customer status*/)) {?>
		          <tr><td>Status</td><td><?php echo $STicket->forCustStatusName;?></td></tr>
		          <?php } else {?>
		          <tr><td>Status</td><td><?php echo $STicket->StatusName;?></td></tr>
		          <?php }?>
		          <?php if ($STicket->ClosedByID && $this->session->userdata('visitor') == false) {?>
		          <tr><td>Closed by</td><td><?php echo $STicket->whoCloseName;?></td></tr>
		          <?php }?>
		          <tr><td>Closed date/time</td><td><?php echo ($STicket->ClosureDateTime ? date("d-M-Y, H:i", strtotime($STicket->ClosureDateTime)): 'Not closed');?></td></tr>
		          <?php if($this->acl->can_read(null, 7 /* 7 ST progress comment*/)) {?>
		          <tr><td>Progress Comment</td><td><?php echo $STicket->ProgressComment;?></td></tr>
		          <?php }?>
		          <?php if($this->acl->can_read(null, 31 /*31 ST Lead Time*/)) {?>
		          <tr><td>Lead Time, h</td><td><?php $hoursRemained = intval(floor((time() - strtotime($STicket->CreationDateTime)) / 3600));
		                echo (($hoursRemained > $STicket->KpiTimeHours)? '<em class="highlight-red">'.$hoursRemained .'</em>': $hoursRemained) . '/'.$STicket->KpiTimeHours;?></td>
		          </tr>
		          <?php }?>
		        </tbody>
		      </table>
		</div><!-- end table-responsive -->
	</div>
</div>

<?php $canReject = false;  if (isset($TicketApproveStatus) && $TicketApproveStatus) {?>
<div id="ticket_details" class="col-md-3">
	<div id="" class="panel panel-default">
		<div class="panel-heading"><i class="glyphicon glyphicon-flag"></i> Approval Flow &amp; Status</div>
			<table id="approve_flow" class="table compact table-stripeds table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<?php foreach ($TicketApproveStatus as $approveItem) :?>
						<th valign="middle"><?php echo $approveItem['role_name'];?><i class="icon32 icon-color icon-arrowthick-e"></i></th>
						<?php endforeach;?>
					</tr>
				</thead>
				<tbody>
					<tr>
					<?php foreach ($TicketApproveStatus as $approveItem) :?>
		                <td class="text-center">
		                	<?php if($approveItem['approve_status'] == 0 && $approveItem['flag'] == 1 && $approveItem['user_id'] == $this->staff['user_id']) : $canReject = true;?>
		                	<a class="btn btn-success btn-xs" href="<?php echo site_url("servicetickets/approveST/{$approveItem['TicketID']}");?>" style="width:60px;margin-bottom: 5px;">Approve</a>
		                	<a class="btn btn-danger btn-xs" id="reject_ticket" data-toggle="modal" data-approvehistoryid="<?php echo $approveItem['ID'];?>" href="" style="width:60px;">Reject</a>
		                	<?php elseif($approveItem['approve_status'] == 1) :?>
		                	<h3 class="text-success margin-y-0" title="Approved"><i class="fa fa-check"></i></h3>
		                	<?php elseif($approveItem['approve_status'] == 2) :?>
		                	<h3 class="text-danger margin-y-0" title="Rejected"><i class="glyphicon glyphicon-remove"></i></h3>
		                	<?php elseif($approveItem['approve_status'] == 0 && $approveItem['flag'] == 1) :?>
		                	<h3 class="text-muted margin-y-0" title="Waiting approve"><i class="glyphicon glyphicon-time"></i></h3>
		                	<?php elseif($approveItem['approve_status'] == 0 && $approveItem['flag'] == 0) :?>
		                	<h3 class="text-muted margin-y-0" title="Waiting approve"><i class="glyphicon glyphicon-time"></i></h3>
		                	<?php endif;?>
		                </td>
					<?php endforeach;?>
					</tr>
				</tbody>		
			</table>
			<?php if ($canReject) {?>
			<div id="new_reject" class="modal" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title"><i class="glyphicon glyphicon-remove"></i> Please specify reject reason</h4>
						</div>
						<form method="post" action="<?php echo site_url('/servicetickets/rejectST/')?>" id="frm_reject_st" name="frm_reject_st" class="form">
							<input type="hidden" name="RejectTicketID" value="<?php echo $STicket->TicketID;?>" />
							<input type="hidden" name="ApproveHistoryID" value="" />
							<div class="modal-body">
								<div class="form-group">
									<label for="type" class="control-label">Message:</label>
									<textarea id="reason" name="reason"></textarea>
								</div>
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancel</button>
								<button type="submit" id="send_button" class="btn btn-success btn-xs"><i class="cus-email-go"></i> Send</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php }?>
	</div>
</div>
<?php }?>
<div id="ticket_description_" class="col-md-<?php echo ((isset($TicketApproveStatus) && $TicketApproveStatus) ? '5':'8');?>">
	<div id="" class="panel panel-default">
		<div class="panel-heading"><i class="icomoon icon-info"></i> Ticket Description</div>
		<div class="panel-body">
			<?php echo word_limiter(strip_tags($STicket->Description), 40, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?>
		</div>
	</div>
</div>

<?php if (isset($cahangesLog)) {?>
<div id="ticket_description_" class="col-md-8">
	<div id="" class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-history"></i> Change log</div>
		<div class="table-responsive container-scroll-y">
			<table class="table-scroll table compact table-stripeds table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>User</th>
						<th>Date / Time</th>
						<th>Changed Item</th>
						<th>Old value</th>
						<th>New Value</th>
					</tr>
				</thead>   
				<tbody>
					<?php foreach($cahangesLog as $item) {?>
					<tr>
		                <td class="fit-size"><?php echo $item['who_change'];?></td>
		                <td class="fit-size"><?php echo date('d-M-Y, H:i', strtotime($item['modified_at']));?></td>
		                <td class="fit-size"><?php echo $item['field_name'];?></td>
		                <td><?php echo word_limiter(strip_tags($item['old_value']), 20, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
		                <td><?php echo word_limiter(strip_tags($item['new_value']), 20, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
		            </tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php }?>

<?php if($this->acl->can_read(null, 25 /*#25 Feed Back module*/)) {?>
<!-- Feedback panel -->
<div id="ticket_description_" class="col-md-8">
	<div id="" class="panel panel-default">
		<div class="panel-heading"><i class="glyphicon glyphicon-envelope"></i> Feedback to customer
			<?php if($this->acl->can_write(null, 25 /*#25 Feed Back module*/)) {?>
			<a class="pull-right" id="newFeedBack" data-toggle="modal" href="#new_feedback"><i class="fa fa-envelope-o"></i> New</a>
			<?php }?>
		</div>
		<div class="table-responsive container-scroll-y">
			<table class="table-scroll table compact table-stripeds table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Date / Time</th>
						<th>Owner</th>
						<th class="fit-size">Receiver Name</th>
						<th>Receive Email</th>
						<th>Message</th>
					</tr>
				</thead>   
				<tbody>
				<?php if (isset($feedbackHistory)) {?>
					<?php foreach($feedbackHistory as $id => $item) :?>
					<tr>
		                <td class="fit-size"><?php echo $item->CreatedAt;?></td>
		                <td class="fit-size"><?php echo $item->CreatedByName;?></td>
		                <td><?php echo $item->BackToName;?></td>
		                <td class="fit-size"><?php echo $item->Email;?></td>
		                <td><a class="feedback-details" data-toggle="modal" data-feedbackid="<?php echo $item->FeedbackID;?>" href="#full_feedback"><?php echo character_limiter(strip_tags($item->Message), 30);?></a></td>
		            </tr>
					<?php endforeach;?>
					<?php } //end if table?>
				</tbody>
			</table>
			
			<div id="new_feedback" class="modal" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title"><i class="glyphicon glyphicon-envelope"></i> Send Feedback</h4>
						</div>
						<form method="post" action="<?php echo site_url('/servicetickets/ajax_send_feedback/')?>" id="frm_send_feedback" name="frm_send_feedback" class="form">
							<div class="modal-body">
								<div class="form-group">
									<input type="hidden" name="feedbackTicketID" value="<?php echo $STicket->TicketID;?>" />
									<label for="type" class="control-label">Message:</label>
									<textarea id="feedback_message" name="feedback_message"></textarea>
								</div>
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
								<button type="submit" id="send_button" class="btn btn-success btn-xs"><i class="cus-email-go"></i> Send</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div><!-- end feedback panel -->
<?php }?>

</div><!-- end row -->
			
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
			<a class="btn btn-danger btn-mini fixedsize" data-dismiss="modal" href="">Cancel</a>
		</div>
</div>

<?php if (isset($navAddProgress) && $navAddProgress) {?>
<div id="add_progress" class="modal" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Please specify progress comment</h4>
			</div>
			<form method="post" action="<?php echo site_url('/servicetickets/add_progress/')?>" id="frm_add_progress" name="frm_add_progress" class="form">
				<input type="hidden" name="TicketID" value="<?php echo $STicket->TicketID;?>" />
				<div class="modal-body">
					<div class="form-group">
						<label for="type" class="control-label">Progress comment:</label>
						<textarea id="ProgressComment" name="ProgressComment"></textarea>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancel</button>
					<button type="submit" id="send_button" class="btn btn-success btn-xs"><i class="cus-email-go"></i> Send</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php }?>
<!-- End sts form -->
<script type="text/javascript">  
	/* for Subects DDL built from selected Type */
$(document).ready(function() {
//     $('#new_reject').modal('show');

/*********** Mass selected subscribers COMMENT ****************/
	$(document).on("click", 'a#mass_selected', function(e){ 
		e.preventDefault();
		generalPopUp.show(true);
		var $TicketID = $(this).data('ticketid');
        $.ajax({  
	        url: "<?php echo site_url('servicetickets/ajax_get_ticket_related_subs/');?>",  
	        data: {"TicketID": $TicketID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "GET",  
	        success: function(response){
		        generalPopUp.set('List of selected phone numbers', response);
        	}  
        });  
	});
	//a#mass_all
	
	$(document).on('click', 'a#mass_all, .member-paging ul li a', function(e) {
		e.preventDefault();

		generalPopUp.show(true);
		//search/ajax_members_list/{group_id}/3
		$.get($(this).attr("href"),
			{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
		        generalPopUp.set('List of selected phone numbers', response);
		}).error(function(x, status, error) {console.log(error);});
	});
	
/*********** CLOSE TICKET ****************/
	$(document).on("click", '#close_ticket', function(e){ 
		e.preventDefault();
		//if (confirm('You are sure to close this ticket?') == false) return false;
		$this = $(this);
		bootbox.confirm("You are sure to close this ticket?", function(result) {
			if (result == true) {
				generalPopUp.show(true);
				$.ajax({  
			        url: $this.attr('href'),  
			        data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
					type: "get",  
			        success:function(data){
			        	generalPopUp.set('Please wait!', 'Refreshing page...');	
			        	location.reload();
		        	}  
		        });
			}
		}); 
	});

/*********** PROGRESS COMMENT ****************/
	$(document).on("submit", 'form#frm_add_progress', function(e){ 
		e.preventDefault();
		$('#add_progress').modal('hide');
		generalPopUp.show(true);
		$this = $(this);
        $.ajax({  
	        url: $this.attr("action"),  
	        data: {"TicketID": $('form#frm_add_progress input[name="TicketID"]').val(), "ProgressComment": $('form#frm_add_progress textarea[name="ProgressComment"]').val(), '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "POST",  
	        success:function(data){
	        	$('form#frm_add_progress textarea[name="ProgressComment"]').val('');
	        	generalPopUp.set('Please wait!', 'Refreshing page...');
	        	location.reload();
        	}  
        });  
	});

	$("#frm_add_progress").validate({
        rules: {	
        	ProgressComment:{required: true,minlength:2, maxlength:10000}
        },
        errorElement: 'span',
        errorClass: 'highlight-red',
    });

	$(document).on('click', 'a.full_progress', function(e){
		e.preventDefault();
		
		var HistoryID = $(this).data('historyid');
		generalPopUp.show(true);
		$.ajax({
		    url:"<?php echo site_url('servicetickets/ajax_get_hprogress_message/')?>",  
		    type: "get",
		    //dataType: "json",
		    data: {"HistoryID":HistoryID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
		    success:function(response) {
		    	generalPopUp.set('Full message', response);		    	
		    }
		});
    });

    /*********** FEEDBACK ****************/
	$(document).on('click', 'a.feedback-details', function(e){
		e.preventDefault();
		
		var FeedbackID = $(this).data('feedbackid');
		generalPopUp.show(true);
		$.ajax({
		    url:"<?php echo site_url('servicetickets/ajax_get_feedback_message/')?>",  
		    type: "get",
		    //dataType: "json",
		    data: {"FeedbackID":FeedbackID, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
		    success:function(response) {
		    	generalPopUp.set('Feedback Details', response);
		    }
		});
    });


	$(document).on("submit", 'form#frm_send_feedback', function(e){ 
		e.preventDefault();
		$this = $(this);
		$('#new_feedback').modal('hide');
		generalPopUp.show(true);
        $.ajax({  
	        url: $this.attr("action"),  
	        data: {"TicketID": $('input[name="feedbackTicketID"]').val(), "Message": $("#feedback_message").val(), '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
			type: "POST",  
	        success:function(data){
	        	generalPopUp.set('Please wait!', 'Refreshing page...');
	        	location.reload();
        	}  
        });  
	});

	/*** REJECT SERVICE TICKET ***/
	
	<?php if ($canReject && $this->input->get('reject') == 'reason') :?>
	//$("#reject_ticket").click();
	$('input[name="ApproveHistoryID"]').val($("#reject_ticket").data('approvehistoryid'));
	$('#new_reject').modal('show');
	<?php endif;?>

	$(document).on('click', 'a#reject_ticket', function(e){
		e.preventDefault();
		var ApproveHistoryID = $(this).data('approvehistoryid');
		$('input[name="ApproveHistoryID"]').val(ApproveHistoryID);
		$('#new_reject').modal('show');
    });
	
	$(document).on("submit", 'form#frm_reject_st', function(e){ 
// 		if (confirm('You are sure to reject this ticket?') == false) return false;
		e.preventDefault();

		bootbox.confirm("You are sure to reject this ticket?", function(result) {
			if (result == true) {
				$('#new_reject').modal('hide');
				generalPopUp.show(true);
				$this = $(this);
		        $.ajax({
			        url: $this.attr("action"),  
			        data: {"TicketID": $('input[name="RejectTicketID"]').val(), "ApproveHistoryID": $('input[name="ApproveHistoryID"]').val(), "reason": $('textarea[name="reason"]').val(), '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},  
					type: "POST",  
			        success:function(data){
			        	$('textarea[name="reason"]').val('');
			        	
			        	generalPopUp.set('Please wait!', 'Refreshing page...');
			        	location.reload();
		        	}  
		        });
			}
		});
	});

/*	
	$('.table-scroll-').DataTable( {
        "scrollY":        "200px",
        "scrollCollapse": true,
        "paging":         false,
        "bSort": false,
        bFilter: false, bInfo: false
    } );*/
});  /*end for Subects DDL built from selected Type */
</script> 
<style>
form textarea {width: 100%;height:100px;}
form {margin:0;}
table#approve_flow th {text-align: center;vertical-align: middle;padding: 0;width: 33%;}
table#approve_flow td {padding: 5px;}

</style>