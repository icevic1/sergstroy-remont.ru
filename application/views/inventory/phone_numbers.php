<div id="ticket_filter" class="panel panel-default">
	<div class="panel-heading">
		<button class="btn btn-link padding-0 pull-left" data-toggle="collapse" data-target="#frm_filter"><i class="icomoon icon-binoculars"></i> Filter <b class="caret"></b></button>
		<div class="clearfix"></div>
	</div>
	<?php echo form_open(current_url(), array('id'=>'frm_filter','name'=>'frm_filter','class'=>'form-horizontal tickets-filter collapse in')); ?>
	<div id="filter_holder" class="panel-body">
		<div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="phone_number" class="control-label col-xs-5">Phone Number</label>
					<div class="col-xs-7"><input id="phone_number" name="phone_number" class="form-control input-sm" type="search" value="<?php echo set_value('phone_number', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="phone_status" class="control-label col-xs-5">Status</label>
					<div class="col-xs-7"><?php echo form_dropdown('phone_status', array_replace(array(''=>'---'), Inventory_model::$PHONE_NUMBER_STATUS), (!empty($filter['phone_status'])? $filter['phone_status']:''), 'id="phone_status" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="reason" class="control-label col-xs-5">Reason</label>
					<div class="col-xs-7"><input id="reason" name="reason" class="form-control input-sm" type="search" value="<?php echo set_value('reason', ''); ?>" /></div>
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
	<div class="panel-heading"><i class="fa fa-list-ul"></i> Phone Numbers List</div>
	<div class="table-responsive">
	<?php if (isset($items)) {?>
	<table id="sts_list" class="thead-green display table-condensed table table-bordered- table-striped dataTable">
	<thead>
		<tr><th>ID</th>
			<th class="nosort">Phone Number</th>
			<th class="nosort">Status</th>
			<th class="nosort">Reason</th>
		</tr>
	</thead>   
	<tbody>
	<?php foreach($items as $item) :?>
		<tr><td><?php echo $item['phone_number_id'];?></td>
			<td><?php echo $item['phone_number'];?></td>
			<td><?php echo (isset(Inventory_model::$PHONE_NUMBER_STATUS[$item['phone_status']])? Inventory_model::$PHONE_NUMBER_STATUS[$item['phone_status']]: 'N/A');?></td>
			<td><?php echo $item['reason'];?></td>
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