<div id="ticket_filter" class="panel panel-default">
	<div class="panel-heading">
		<button class="btn btn-link padding-0 pull-left" data-toggle="collapse" data-target="#frm_filter"><i class="icomoon icon-binoculars"></i> Filter <b class="caret"></b></button>
		<div class="clearfix"></div>
	</div>
	<?php echo form_open(current_url(), array('id'=>'frm_filter','name'=>'frm_filter','class'=>'form-horizontal tickets-filter collapse in')); ?>
	<div id="filter_holder" class="panel-body">
		<div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="serial_number" class="control-label col-xs-5">Serial Number</label>
					<div class="col-xs-7"><input id="serial_number" name="serial_number" class="form-control input-sm" type="search" value="<?php echo set_value('serial_number', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="phone_number" class="control-label col-xs-5">Phone Number</label>
					<div class="col-xs-7"><input id="phone_number" name="phone_number" class="form-control input-sm" type="search" value="<?php echo set_value('phone_number', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="applicant_status_id" class="control-label col-xs-5">Status</label>
					<div class="col-xs-7"><?php echo form_dropdown('applicant_status_id', $applicant_statuses, (!empty($filter['applicant_status_id'])? $filter['applicant_status_id']:''), 'id="applicant_status_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="dealer_id" class="control-label col-xs-5">Dealer</label>
					<div class="col-xs-7"><?php echo form_dropdown('dealer_id', $dealers, (!empty($filter['dealer_id'])? $filter['dealer_id']:''), 'id="dealer_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="subscriber_name" class="control-label col-xs-5">Subscriber Name</label>
					<div class="col-xs-7"><input id="subscriber_name" name="subscriber_name" class="form-control input-sm" type="search" value="<?php echo set_value('subscriber_name', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="subscriber_type" class="control-label col-xs-5">Subscriber Type</label>
					<div class="col-xs-7"><?php echo form_dropdown('subscriber_type', array_replace(array(''=>'---'), Applicant_model::$SUBSCRIBER_TYPES), (!empty($filter['subscriber_type'])? $filter['subscriber_type']:''), 'id="subscriber_type" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="city_id" class="control-label col-xs-5">City</label>
					<div class="col-xs-7"><?php echo form_dropdown('city_id', $cities, (!empty($filter['city_id'])?$filter['city_id']:''), 'id="city_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group no-gutter">
                    <label for="sales_id" class="control-label col-xs-5">Saller ID</label>
                    
					<div class="col-xs-7"><?php echo form_dropdown('sales_id', $sallers_list, $default = (!empty($filter['sales_id'])?$filter['sales_id']:''), 'id="sales_id" class="form-control"'); ?></div>
                </div>
            </div>
        </div>
	</div><!-- end filter panel body -->
	<div class="panel-footer text-right">
		<button type="reset" class="btn btn-sm btn-link"><i class="fa fa-times"></i> Reset</button>
		<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-search"></i> Search</button>
	</div>
	<?php echo form_close(); ?> 
</div><!-- end panel filter tickets -->

<div id="" class="panel panel-default">
	<div class="panel-heading"><i class="fa fa-list-ul"></i> Applications List <div class="pull-right"><a href="" onclick="bootbox.alert('This feature is Under Construction');return false;"><i class="icomoon icon-file-excel"></i> Export</a></div></div>
	<div class="table-responsive">
	<?php if (isset($items)) {?>
	 <a class="btn btn-success btn-xs" href="<?php echo site_url('home/approve_ajax/');?>" class="text-success" onclick="bootbox.alert('This feature is Under Construction');return false;"><i class="fa fa-check"></i> Approve</a>
     <a class="btn btn-danger btn-xs" data-toggle="modal" data-rejectappid="-1" href="#" class="text-danger" onclick="bootbox.alert('This feature is Under Construction');return false;"><i class="glyphicon glyphicon-remove"></i> Reject</a>
                
	<table id="app_list" class="thead-green display table-condensed table table-bordered table-striped dataTable-applicants">
	<thead>
		<tr><th class="nosort text-center" style="width:20px;"><input id="checkAll" type="checkbox" class="mass-action" name="applicants[-1]" value="-1" /> <label for="checkAll">All/No</label></th>
			<th class="nosort">SIM Number</th>
			<th class="nosort fit-size">Phone Number</th>
			<th class="fit-size">Status</th>
			<th class="nosort">Date</th>
			<th class="nosort fit-size">Dealer Name</th>
			<th class="nosort fit-size">Sales ID</th>
			<th class="nosort fit-size">Subcriber Type</th>
			<th class="nosort fit-size">Contact Person</th>
			<th class="nosort fit-size">Date of Birth</th>
			
			<th class="nosort">No.</th>
			<th class="nosort">St.</th>
			<th class="nosort">Sangkat/Commune</th>
			<th class="nosort">Khan/District</th>
			<th class="nosort">Citiy/Province</th>
			
			<th class="nosort">Contact Phone</th>
			<th class="nosort">Fax Number</th>
			<th class="nosort">E-mail</th>
			
			<th class="nosort">Cambodian/Foreigner</th>
			<th class="nosort">Attached Document</th>
			<th class="nosort">File 1</th>
			<th class="nosort">File 2</th>
			<th class="nosort">Document Number</th>
			<th class="nosort" title="Document Date of Issue">Document Issue</th>
			
			<th class="nosort">GPS</th>
			<th class="nosort" style="width: 250px; padding-right: 0;">Actions</th>
		</tr>
	</thead>   
	<tbody>
	<?php foreach($items as $item) :?>
		<tr><td class="text-center"><input type="checkbox" class="mass-action" name="applicants[<?php echo $item['applicant_id'];?>]" value="<?php echo $item['applicant_id'];?>" /></td>
			<td><a href="<?php echo site_url('home/save/'.$item['applicant_id']);?>"><?php echo $item['serial_number'];?></a></td>
			<td><?php echo $item['phone_number'];?></td>
			<td class="text-center">
				<?php if ($item['applicant_status_id'] == '1') {?>
				<div class="text-warning"><i class="icon32 icon-gray icon-clock"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php } elseif ($item['applicant_status_id'] == '2') { ?>
				<div class="text-success"><i class="fa fa-check"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php } elseif ($item['applicant_status_id'] == '3') { ?>
				<div class="text-danger"><i class="fa fa-check"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php } elseif ($item['applicant_status_id'] == '4') { ?>
				<div class="text-info"><i class="fa fa-check"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php }?>
			</td>
			<td><?php echo date('d-M-Y', strtotime($item['created_at']));?></td>
			<td><a href="<?php echo site_url('dealer/profile/'.$item['dealer_id']);?>"><?php echo $item['dealer_name'];?></a></td>
			<td><?php echo $item['salesID'];?></td>
			<td><?php echo (isset(Applicant_model::$SUBSCRIBER_TYPES[$item['subscriber_type']])? Applicant_model::$SUBSCRIBER_TYPES[$item['subscriber_type']]: '');?></td>
			<td><?php echo ucfirst($item['gender']) . ' ' . $item['contact_name'];?></td>
			<td><?php echo $item['date_of_birth'];?></td>
			
			<td><?php echo $item['house_number'];?></td>
			<td><?php echo $item['street'];?></td>
			<td><?php echo $item['commune_name'];?></td>
			<td><?php echo $item['district_name'];?></td>
			<td><?php echo $item['city_name'];?></td>
			
			<td><?php echo $item['contact_number'];?></td>
			<td><?php echo $item['fax_number'];?></td>
			<td><?php echo $item['email'];?></td>
			
			<td><?php echo ((isset(Applicant_model::$FOREIGNER_TYPES[$item['is_foreigner']]))? Applicant_model::$FOREIGNER_TYPES[$item['is_foreigner']]: 'N/A');?></td>
			<td><?php echo ((isset(Applicant_model::$DOCUMENT_TYPES[$item['document_type']]))? Applicant_model::$DOCUMENT_TYPES[$item['document_type']]: 'N/A');?></td>
			<td><?php 
				if ($item['photo_1']) {
					$img_src = base_url("assets/upload/{$item['photo_1']}");
					echo '<a href="'.$img_src.'" target="_blank"><img width="60" height="36" src="'.$img_src.'" />';
				}
				?>
			</td>
			<td><?php if ($item['photo_2']) {
					$img_src = base_url("assets/upload/{$item['photo_2']}");
					echo '<a href="'.$img_src.'" target="_blank"><img width="60" height="36" src="'.$img_src.'" />';
				}?></td>
			<td><?php echo $item['document_number'];?></td>
			<td><?php echo (!empty($item['document_issue_date'])? date('d-M-Y', strtotime($item['document_issue_date'])): 'N/A');?></td>
			
			<td class="center"><?php echo $item['GPS_Lat']. ', '.$item['GPS_Lon'];?></td>
            <td class="center fit-size" style="text-align: center;" data-appid="<?php echo $item['applicant_id'];?>">
            	<?php if ($item['applicant_status_id'] == '1' || $item['applicant_status_id'] == '4') {?>
                <a class="approve_form btn btn-success btn-xs action-btn" href="<?php echo site_url('home/approve_ajax/'.$item['applicant_id']);?>" class="text-success"><i class="fa fa-check"></i> Approve</a>
                <a class="reject_form btn btn-danger btn-xs action-btn" data-toggle="modal" data-rejectappid="<?php echo $item['applicant_id'];?>" href="#" class="text-danger"><i class="glyphicon glyphicon-remove"></i> Reject</a><br />
                <?php }?>
                <a href="<?php echo site_url('home/save/'.$item['applicant_id']);?>"><i class="fa fa-pencil-square-o"></i> Edit</a>
            </td>
		</tr>
		<?php endforeach;?>
	</tbody>
	</table>
	<?php } //end if table?>
	</div>
</div>
<div id="new_reject" class="modal" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title text-warning">Please specify reject reason</h4>
			</div>
			<form method="post" action="<?php echo site_url('/home/reject_ajax/')?>" id="frm_reject" name="frm_reject" class="form">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<input type="hidden" name="reject_applicant_id" value="" />
				<div class="modal-body">
					<div class="form-group">
						<label for="type" class="control-label">Message:</label>
						<textarea id="reason" name="reason"></textarea>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancel</button>
					<button type="submit" id="send_button" class="btn btn-success btn-xs"><i class="cus-email-go"></i> OK</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(e) {

	$("#checkAll").change(function () {
	    $("input:checkbox.mass-action").prop('checked', $(this).prop("checked"));
	});
	
	$("form#frm_reject").validate({
        rules: {	
        	reason:{required: true,minlength:2, maxlength:10000}
        },
        errorElement: 'span',
        errorClass: 'text-danger',
    });
    	
	$('.reject_form').click(function(e){
		e.preventDefault();
		var rejectappid = $(this).data('rejectappid');
		$('input[name="reject_applicant_id"]').val(rejectappid);
		$('#new_reject').modal('show');
    });

	$('.approve_form').click(function(e){
		e.preventDefault();
		var applicant_id = $(this).parent('td').data('appid');
		var $this = $(this);
		console.log(applicant_id);

		bootbox.confirm("Are You sure want to approve this applicantion form?", function(result) {
			if (result == true) {
				generalPopUp.show(true);
		        $.ajax({
			        url: $this.attr("href"),  
			        data: {applicant_id: applicant_id},  
					type: "POST",  
			        success:function(data){
				        $('td[data-appid="'+applicant_id+'"]').children('.action-btn').remove();
			        	generalPopUp.hide();
		        	}  
		        });
			}
		});
		
    });
	
	$(document).on("submit", 'form#frm_reject', function(e){ 
// 		if (confirm('You are sure to reject this ticket?') == false) return false;
		e.preventDefault();
		var $this = $(this);
		bootbox.confirm("You are sure to reject this applicantion form?", function(result) {
			if (result == true) {
				$('#new_reject').modal('hide');
				generalPopUp.show(true);
		        $.ajax({
			        url: $this.attr("action"),  
			        data: $this.serialize(),  
					type: "POST",  
			        success:function(data){
				        var reject_applicant_id = $('input[name="reject_applicant_id"]').val();
				        $('td[data-appid="'+reject_applicant_id+'"]').children('.action-btn').remove();

			        	$('textarea[name="reason"]').val('');
			        	$('input[name="reject_applicant_id"]').val('');
			        	generalPopUp.hide();
		        	}  
		        });
			}
		});
	});
}); //end ready()
</script>
<style>
form textarea {width: 100%;height:100px;}
form {margin:0;}
</style>