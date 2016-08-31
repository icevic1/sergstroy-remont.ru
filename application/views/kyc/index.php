<div id="ticket_filter" class="panel panel-default">
	<div class="panel-heading">
		<button class="btn btn-link padding-0 pull-left" data-toggle="collapse" data-target="#frm_filter"><i class="icomoon icon-binoculars"></i> Tickets Filter <b class="caret"></b></button>
		<button class="navbar-toggle btn-sm-visible -collapsed" type="button" data-button-label="Toggle filter" data-toggle="collapse" data-target="#frm_filter">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<div class="clearfix"></div>
	</div>
	<?php echo form_open(current_url(), array('id'=>'frm_filter','name'=>'frm_filter','class'=>'form-horizontal tickets-filter collapse out')); ?>
	<div id="filter_holder" class="panel-body">
		<div class="row">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="type" class="control-label col-xs-5">Ticket type</label>
					<div class="col-xs-7"><?php echo form_dropdown('type', $TypesDT, (!empty($filter['TypeID'])?$filter['TypeID']:''), 'id="type" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="type" class="control-label col-xs-5">Service type </label>
					<div class="col-xs-7"><?php echo form_dropdown('subject', $SubjectsDT, (!empty($filter['SubjectID'])?$filter['SubjectID']:''), 'id="subject" class="form-control input-sm"');?></div>
                </div>
            </div>
            <?php if($this->acl->can_read(null, 6 /*6 ST priority*/)) {?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="priority" class="control-label col-xs-5">Priority </label>
					<div class="col-xs-7"><?php echo form_dropdown('priority', $PrioritiesDT, (!empty($filter['PriorityID'])?$filter['PriorityID']:''), 'id="priority" class="form-control input-sm"');?></div>
                </div>
            </div>
            <?php }?>
            <?php if($this->acl->can_read(null, 29 /*29 ST sevirity*/)) {?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="severity" class="control-label col-xs-5">Severity </label>
					<div class="col-xs-7"><?php echo form_dropdown('severity', $SeveritiesDT, (!empty($filter['SeveritiyID'])?$filter['SeveritiyID']:''), 'id="severity" class="form-control input-sm"');?></div>
                </div>
            </div>
            <?php }?>
            <?php if($this->acl->can_read(null, 22 /*22 Company name*/)) {?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="company" class="control-label col-xs-5">Customer Name </label>
					<div class="col-xs-7"><?php echo form_dropdown('company', $companies, (!empty($filter['CompanyID'])?$filter['CompanyID']:''), 'id="company" class="form-control input-sm"');?></div>
                </div>
            </div>
            <?php }?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="status" class="control-label col-xs-5">Status </label>
					<div class="col-xs-7"><?php echo form_dropdown('status', $StatusesDT, (!empty($filter['StatusID'])?$filter['StatusID']:''), 'id="status" class="form-control input-sm"');?></div>
                </div>
            </div>
            <?php if($this->acl->can_read(null, 28 /*28 ST phone number*/)) {?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="phone_number" class="control-label col-xs-5">Phone Number </label>
					<div class="col-xs-7"><input id="phone_number" name="phone_number" class="form-control input-sm" type="search" value="<?php echo set_value('phone_number', ''); ?>" /></div>
                </div>
            </div>
            <?php }?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="date_fromf" class="control-label col-xs-5">Date From</label>
                    <div class="col-xs-7">
	                    <div class="input-group date date_from" id="date_fromf">
							<input type="text" name="date_from" class="form-control input-sm" value="<?php echo set_value('date_from', ''); ?>" placeholder="YYYY-MM-DD" maxlength="10" />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
					</div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group no-gutter">
                    <label for="date_tof" class="control-label col-xs-5">Date To</label>
                    <div class="col-xs-7">
	                    <div class="input-group date date_to" id="date_tof">
							<input type="text" name="date_to" class="form-control input-sm" value="<?php echo set_value('date_to', ''); ?>" placeholder="YYYY-MM-DD" maxlength="10" />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
					</div>
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
	<div class="panel-heading"><i class="glyphicon glyphicon-list"></i> Ticket List</div>
	<div class="table-responsive">
	<?php if (isset($filteredTickets)) {?>
	<table id="sts_list" class="thead-green display table-condensed table table-bordered- table-striped dataTable">
	<thead>
		<tr><th>ID</th>
			<?php if($this->acl->can_read(null, 22 /*22 Company name*/)) {?>
			<th class="nosort">Customer Name</th>
			<?php }?>
			<th class="nosort">Phone Number</th>
			<th class="nosort">Ticket Type</th>
			<th class="nosort">Service Group</th>
			<th class="nosort" title="Subject">Service Type</th>
			<?php if($this->acl->can_read(null, 29 /*29 ST sevirity*/)) {?>
			<th class="nosort">Severity</th>
			<?php }?>
			<th>Date of creation</th>
			<th class="nosort">Status</th>
			<?php if($this->acl->can_read(null, 6 /*6 ST priority*/)) {?>
			<th class="nosort">Priority</th>
			<?php }?>
			<th>Date of Closure</th>
			<?php if($this->acl->can_read(null, 24 /*24 SLA column on ST summary table*/)) {?>
			<th>SLA time, h</th>
			<?php }?>
			<th class="nosort" style="width: 100px; padding-right: 0;">Actions</th>
		</tr>
	</thead>   
	<tbody>
	<?php foreach($filteredTickets as $item) :?>
		<tr><td><a href="<?php echo base_url('servicetickets/detailsST/'.$item->TicketID);?>"><?php echo $item->TicketID;?></a></td>
			<?php if($this->acl->can_read(null, 22 /*22 Company name*/)) {?>
			<td><?php echo $item->CustName;?></td>
			<?php }?>
			<td class="center">
				<?php switch ($item->TicketType){
					case '0': echo '0'.$item->AccountID;break;
					case '1': 
					case '2': echo 'Multiple numbers';break;				
				}?>
			</td>
            <td class="center"><?php echo $item->TypeName;?></td>
            <td class="center"><?php echo $item->GroupName;?></td>
            <td><?php echo $item->SubjectName;?></td>
            <?php if($this->acl->can_read(null, 29 /*29 ST sevirity*/)) {?>
            <td class="<?php echo ( $item->SeverityID == 3)? 'highlight-red':'';?>"><?php echo $item->SeverityName;?></td>
            <?php }?>
            <td class="center"><?php echo date('d-M-Y, H:i', strtotime($item->CreationDateTime));?></td>
            <td class="center"><?php echo (($this->acl->can_view(null, 33 /*33 ST customer status*/)) ? $item->forCustomerStausName:  $item->StatusName);?></td>
            <?php if($this->acl->can_read(null, 6 /*6 ST priority*/)) {?>
            <td class="center"><?php echo $item->PriorityName;?></td>
            <?php }?>
            <td class="center"><?php echo ($item->ClosureDateTime)? date('d-M-Y', strtotime($item->ClosureDateTime)): 'Not closed';?></td>
            <?php if($this->acl->can_read(null, 24 /*24 SLA column on ST summary table*/)) {?>
            <td class="center">
                	<?php $hoursRemained = intval(floor((time() - strtotime($item->CreationDateTime)) / 3600));
	                echo (($hoursRemained > $item->KpiTimeHours)? '<em class="highlight-red">'.$hoursRemained .'</em>': $hoursRemained) . '/'.$item->KpiTimeHours;?>
                </td>
                <?php }?>
            <td class="center" style="width: 100px;">
	        	<a href="<?php echo base_url('servicetickets/detailsST/'.$item->TicketID);?>"><i class="glyphicon glyphicon-open-file"></i> Details</a>
                    <?php if(false) {?><a href="<?php echo base_url('servicetickets/editST/'.$item->TicketID);?>">Edit</a>/<?php }?>
                    <?php if($this->acl->can_modify(null, 5 /*HOE ST escalate*/)) {?>
                     / <a href="<?php echo base_url('servicetickets/escalateST/'.$item->TicketID)?>">Escalate</a>/
                    <?php }?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
         <?php } //end if table?>
	</div>
</div>