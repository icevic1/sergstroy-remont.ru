<div id="ticket_filter" class="panel panel-default">
	<div class="panel-heading">
		<button class="btn btn-link padding-0 pull-left" data-toggle="collapse" data-target="#frm_filter"><i class="icomoon icon-binoculars"></i> Filter <b class="caret"></b></button>
		<div class="pull-right"><a href="" onclick="bootbox.alert('This feature is Under Construction');return false;"><i class="icomoon icon-file-excel"></i> Import</a></div>
		<div class="clearfix"></div>
	</div>
	<?php echo form_open(current_url(), array('id'=>'frm_filter','name'=>'frm_filter','class'=>'form-horizontal tickets-filter collapse in')); ?>
	<div id="filter_holder" class="panel-body">
		<div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="serial_number" class="control-label col-xs-5">Serial Number</label>
					<div class="col-xs-7"><input id="serial_number" name="serial_number" class="form-control input-sm" type="search" value="<?php echo set_value('serial_number', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="serial_status_id" class="control-label col-xs-5">Status</label>
					<div class="col-xs-7"><?php echo form_dropdown('serial_status_id', $serial_statuses, (!empty($filter['serial_status_id'])? $filter['serial_status_id']:''), 'id="serial_status_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="dealer_id" class="control-label col-xs-5">Dealer Name</label>
					<div class="col-xs-7"><?php echo form_dropdown('dealer_id', $dealers, (!empty($filter['dealer_id'])?$filter['dealer_id']:''), 'id="dealer_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="sales_id" class="control-label col-xs-5">Login</label>
					<div class="col-xs-7"><?php echo form_dropdown('sales_id', $sallers, (!empty($filter['sales_id'])?$filter['sales_id']:''), 'id="sales_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="salesID" class="control-label col-xs-5">Sales ID</label>
					<div class="col-xs-7"><input id="salesID" name="salesID" class="form-control input-sm" type="search" value="<?php echo set_value('salesID', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="sim_type" class="control-label col-xs-5">SIM Type</label>
					<div class="col-xs-7"><?php echo form_dropdown('sim_type', array_replace(array(''=>'---'), Inventory_model::$SIM_TYPES), (!empty($filter['sim_type'])?$filter['sim_type']:''), 'id="sim_type" class="form-control input-sm"');?></div>
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
	<div class="panel-heading"><i class="fa fa-list-ul"></i> Serial Numbers List <div class="pull-right"><a href="" onclick="bootbox.alert('This feature is Under Construction');return false;"><i class="icomoon icon-file-excel"></i> Export</a></div></div>
	<div class="table-responsive">
	<?php if (isset($items)) {?>
	<table id="sts_list" class="thead-green display table-condensed table table-bordered table-striped dataTable">
	<thead>
		<tr>
			<th class="nosort">Serial Number</th>
			<th class="nosort">SIM Type</th>
			<th class="nosort">Phone Number</th>
			<th class="nosort">Status</th>
			<th class="nosort">Status Change Date</th>
			<th class="nosort">Dealer Name</th>
			<th class="nosort">Sales ID</th>
			<th class="nosort" style="width: 150px; padding-right: 0;">Actions</th>
		</tr>
	</thead>   
	<tbody>
	<?php foreach($items as $item) :?>
		<tr>
			<td><a href="<?php echo site_url('inventory/simprofile/'.$item['serial_id']);?>"><?php echo $item['serial_number'];?></a></td>
			<td><?php echo (isset(Inventory_model::$SIM_TYPES[$item['sim_type']])? Inventory_model::$SIM_TYPES[$item['sim_type']]: 'N/A');?></td>
			<td><?php echo ($item['phone_number']? $item['phone_number']: 'N/A');?></td>
			<td><?php echo $item['status_name'];?></td>
			<td class="center"><?php echo ($item['changed_at']? date('d-M-Y, H:i', strtotime($item['changed_at'])) : 'N/A');?></td>
			<td><a href="<?php echo site_url('dealer/profile/'.$item['dealer_id']);?>"><?php echo $item['dealer_name'];?></a></td>
			<td><?php echo $item['salesID'];?></td>
            <td class="text-center">
	        	<a href="<?php echo site_url('inventory/simprofile/'.$item['serial_id']);?>"><i class="glyphicon glyphicon-open-file"></i> Profile</a> | 
                <a href="<?php echo site_url('inventory/save/'.$item['serial_id']);?>"><i class="fa fa-pencil-square-o"></i> Edit</a>
            </td>
		</tr>
		<?php endforeach;?>
	</tbody>
	</table>
	<?php } //end if table?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
// 	bootbox.alert('This feature is Under Construction');
}); //end ready()
</script>