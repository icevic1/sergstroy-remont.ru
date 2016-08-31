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
                    <label for="dealer_name" class="control-label col-xs-5">Dealer Name</label>
					<div class="col-xs-7"><input id="dealer_name" name="dealer_name" class="form-control input-sm" type="search" value="<?php echo set_value('dealer_name', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="saller" class="control-label col-xs-5">Saller login</label>
					<div class="col-xs-7"><input id="saller" name="saller" class="form-control input-sm" type="search" value="<?php echo set_value('saller', ''); ?>" /></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="dealer_kind_id" class="control-label col-xs-5">Dealer Category</label>
					<div class="col-xs-7"><?php echo form_dropdown('dealer_kind_id', $dealer_kinds, (!empty($filter['dealer_kind_id'])? $filter['dealer_kind_id']:''), 'id="dealer_kind_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="dealer_type_id" class="control-label col-xs-5">Dealer Type</label>
					<div class="col-xs-7"><?php echo form_dropdown('dealer_type_id', $dealer_types, (!empty($filter['dealer_type_id'])?$filter['dealer_type_id']:''), 'id="dealer_type_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="reg_id" class="control-label col-xs-5">Region</label>
					<div class="col-xs-7"><?php echo form_dropdown('reg_id', $regions, (!empty($filter['reg_id'])?$filter['reg_id']:''), 'id="reg_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="city_id" class="control-label col-xs-5">City</label>
					<div class="col-xs-7"><?php echo form_dropdown('city_id', $cities, (!empty($filter['city_id'])?$filter['city_id']:''), 'id="city_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="district_id" class="control-label col-xs-5">District</label>
					<div class="col-xs-7"><?php echo form_dropdown('district_id', $districts, (!empty($filter['district_id'])?$filter['district_id']:''), 'id="district_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="zone_id" class="control-label col-xs-5">Zone</label>
					<div class="col-xs-7"><?php echo form_dropdown('zone_id', $zones, (!empty($filter['zone_id'])?$filter['zone_id']:''), 'id="zone_id" class="form-control input-sm"');?></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group no-gutter">
                    <label for="commune_id" class="control-label col-xs-5">Commune</label>
					<div class="col-xs-7"><?php echo form_dropdown('commune_id', $communes, (!empty($filter['commune_id'])?$filter['commune_id']:''), 'id="commune_id" class="form-control input-sm"');?></div>
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
	<div class="panel-heading"><i class="fa fa-list-ul"></i> Dealer List <div class="pull-right"><a href="" onclick="bootbox.alert('This feature is Under Construction');return false;"><i class="icomoon icon-file-excel"></i> Export</a></div></div>
	<div class="table-responsive">
	<?php if (isset($items)) {?>
	<table id="sts_list" class="thead-green display table-condensed table table-bordered table-striped dataTable">
	<thead>
		<tr><th>ID</th>
			<th class="nosort">Dealer Name</th>
			<th class="nosort">Sales ID</th>
			<th class="nosort">Status</th>
			<th class="nosort">Dealer Category</th>
			<th class="nosort">Dealer Type</th>
			
			<th class="nosort">Region</th>
			<th class="nosort">City</th>
			<th class="nosort">District</th>
			<th class="nosort">Login 1</th>
			<th class="nosort">Login 2</th>
			<th class="nosort" style="width: 150px; padding-right: 0;">Actions</th>
		</tr>
	</thead>   
	<tbody>
	<?php foreach($items as $item) :?>
		<tr><td><?php echo $item['dealer_id'];?></td>
			<td><a href="<?php echo site_url('dealer/profile/'.$item['dealer_id']);?>"><?php echo $item['dealer_name'];?></a></td>
			<td><?php echo $item['salesID'];?></td>
			<td><?php echo $item['dealer_status_name'];?></td>
			<td><?php echo $item['kind_name'];?></td>
			<td><?php echo $item['dealer_type_name'];?></td>
			
			<td><?php echo $item['reg_name'];?></td>
			<td><?php echo $item['city_name'];?></td>
			<td><?php echo $item['district_name'];?></td>
			<?php $salers_array = explode(',', $item['sallers']);
				for ($i=1;$i<=2;$i++) {
					echo '<td>'.(!empty($salers_array[$i-1])?$salers_array[$i-1]:'N/A').'</td>';
				}
			?>
            <td class="text-center">
	        	<a href="<?php echo site_url('dealer/profile/'.$item['dealer_id']);?>"><i class="glyphicon glyphicon-open-file"></i> Profile</a> | 
                <a href="<?php echo site_url('dealer/save/'.$item['dealer_id']);?>"><i class="fa fa-pencil-square-o"></i> Edit</a>
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