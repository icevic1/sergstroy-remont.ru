<div id="alert_filter" class="panel panel-default">
	<div class="panel-heading">
		<button class="btn btn-link padding-0 pull-left" data-toggle="collapse" data-target="#frm_filter"><i class="icomoon icon-binoculars"></i> Notification Filter <b class="caret"></b></button>
		<button class="navbar-toggle btn-sm-visible -collapsed" type="button" data-button-label="Toggle filter" data-toggle="collapse" data-target="#frm_filter">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<div class="clearfix"></div>
	</div>
	<?php echo form_open(current_url(), array('id'=>'frm_filter','name'=>'frm_filter','class'=>'form-horizontal alert-filter collapse in')); ?>
	<div id="filter_holder" class="panel-body">
		<div class="row">
            <div class="col-sm-6 col-md-4 col-lg-4">
                <div class="form-group no-gutter">
                    <label for="status" class="control-label col-xs-5">Status </label>
					<div class="col-xs-7"><?php echo form_dropdown('IsViewed', array(''=>'--All--', '0'=>'No Viewed','1'=>'Viewed'), (isset($filter['IsViewed'])?$filter['IsViewed']:''), 'id="status" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-4">
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
            <div class="col-sm-6 col-md-4 col-lg-4">
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
	<div class="panel-heading"><i class="glyphicon glyphicon-list"></i> Notifications List</div>
	<div class="table-responsive">
	<?php if (isset($filteredAlerts)) {?>
	<table id="sts_list" class="thead-green display table-condensed table table-bordered- table-striped dataTable">
	<thead>
		<tr><th>ID</th>
			<?php if($this->acl->can_read(null, 22 /*22 Company name*/)) {?>
			<th class="nosort">Customer Name</th>
			<?php }?>
			<th class="nosort">Notification title</th>
			<th class="nosort">Ticket</th>
			<th class="nosort">Alert status</th>
			<th class="nosort">Email Sent</th>
			<th>Date of creation</th>
			<th class="nosort" style="width: 100px; padding-right: 0;">Actions</th>
		</tr>
	</thead>   
	<tbody>
	<?php foreach($filteredAlerts as $item) :?>
		<tr<?php echo (('0' == $item->IsViewed)? ' class="active"': '');?>>
			<td><?php echo $item->AlertID;?></td>
			<?php if($this->acl->can_read(null, 22 /*22 Company name*/)) {?>
			<td><?php echo $item->CustName;?></td>
			<?php }?>
			<td class="center"><?php echo str_replace('{TicketID}', $item->TicketID, $item->Title);?></td>
            <td class="center"><a href="<?php echo site_url('servicetickets/detailsST/'.$item->TicketID);?>">#<?php echo $item->TicketID;?></a></td>
            <td class="center"><?php echo (($item->IsViewed > 0)? 'Viewed': 'No');?></td>
            <td><?php echo (($item->EmailSent > 0)? 'Yes': 'No');?></td>
            <td><?php echo date('Y-m-d H:i', strtotime($item->CreatedAt));?></td>
            <td class="center">
	        	<a href="<?php echo base_url('servicetickets/detailsAlert/'.$item->AlertID);?>"><i class="cus-application-form-magnify"></i> Details</a>
            </td>
		</tr>
		<?php endforeach;?>
	</tbody>
	</table>
	<?php } //end if table?>
	</div>
</div>