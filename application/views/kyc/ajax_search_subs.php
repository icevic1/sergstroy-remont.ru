<table class="table table-striped table-bordered bootstrap-datatable datatable1">
	<thead>
		<tr>
			<th>Subs ID</th>
			<th>Name</th>
			<th>Company/Branch</th>
			<th>Phone number</th>
			<th>SIM ID</th>
			<th>Email</th>
			<!-- th>Status</th> -->
			<th>Actions</th>
		</tr>
	</thead>   
	<tbody>
		<?php foreach($filteredUsers as $item) :?>
		<tr>
		<td class="center"><?php echo $item->subs_id;?></td>
		<td class="center"><?php echo $item->firstname . ' ' . $item->lastname;?></td>
		<td class="center"><?php echo $item->company_name . (($item->branch_name)?'<br />'.$item->branch_name:'');?></td>
		<td class="center"><?php echo $item->phone;?></td>
		<td class="center"><?php echo $item->sim;?></td>
		<td class="center"><?php echo $item->email;?></td>
		<!-- td class="center"><?php echo Subscriber_mod::$sc_status[$item->sc_status];?></td> -->
		<td class="center" style="width: 100px;">
			<a class="btn pull-right inline choose_parent" href="<?php echo site_url('/servicetickets/newST/'.$item->subs_id);?>"><i class="cus-accept"></i> Choose</a>
		</td>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>