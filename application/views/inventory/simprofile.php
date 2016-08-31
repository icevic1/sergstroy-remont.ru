<div id="" class="panel panel-default">
	<div class="panel-heading"><i class="glyphicon glyphicon-info-sign"></i> SIM Profile</div>
	<div class="panel-body">
		<dl class="sim-profile dl-horizontal">
			<dt>SIM Serial Number:</dt><dd><?php echo $item['serial_number'];?></dd>
			<dt>SIM Type:</dt><dd><?php echo (isset(Inventory_model::$SIM_TYPES[$item['sim_type']])? Inventory_model::$SIM_TYPES[$item['sim_type']]: 'N/A');?></dd>
			<dt>SIM Status:</dt><dd><?php echo $item['status_name'];?></dd>
			<dt>Phone Number:</dt><dd><?php echo ($item['phone_number']?$item['phone_number']: 'N/A');?></dd>
			<dt>Sales ID:</dt><dd><?php echo ($item['salesID']?$item['salesID']: 'N/A');?></dd>
			<dt>Saller Login:</dt><dd><?php echo ($item['login']?$item['login']: 'N/A');?></dd>
			<dt>Dealer:</dt><dd><?php echo ($item['dealer_name']?$item['dealer_name']: 'N/A');?></dd>
			<dt>Status Change Date:</dt><dd><?php echo ($item['changed_at']? date('Y-m-d', strtotime($item['changed_at'])): '');?></dd>
			<dt>Date/Time of creation:</dt><dd><?php echo date('Y-m-d', strtotime($item['created_at']));?></dd>
		</dl>
	</div><!-- end table-responsive -->
</div>