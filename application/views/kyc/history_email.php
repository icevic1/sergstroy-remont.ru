<table class="table table-stripeds table-bordered" width="100%" border="1" cellspacing="0" style="border:1px solid #dddddd;border-collapse:collapse;">
	<thead>
		<tr style="background-color: green;color:white;">
			<th style="border:1px solid #dddddd;border-collapse:collapse;">User</th>
			<th style="border:1px solid #dddddd;border-collapse:collapse;">Date / Time</th>
			<th style="border:1px solid #dddddd;border-collapse:collapse;width: 1%;white-space: nowrap;padding:0 10px;">Changed Item</th>
			<th style="border:1px solid #dddddd;border-collapse:collapse;">Old value</th>
			<th style="border:1px solid #dddddd;border-collapse:collapse;">New value</th>
		</tr>
	</thead>   
	<tbody>
	<?php if ($filteredTickets) foreach($filteredTickets as $item) :?>
		<tr><td style="border:1px solid #dddddd;border-collapse:collapse;width: 1%;white-space: nowrap;padding:0 10px;"><?php echo $item['who_change'];?></td>
			<td style="border:1px solid #dddddd;border-collapse:collapse;width: 1%;white-space: nowrap;padding:0 10px;"><?php echo date('H:i, d-M-Y', strtotime($item['modified_at']));?></td>
            <td style="border:1px solid #dddddd;border-collapse:collapse;padding:0 10px;"><?php echo $item['field_name'];?></td>
            <td style="border:1px solid #dddddd;border-collapse:collapse;padding:0 10px;"><?php echo word_limiter(strip_tags($item['old_value']), 20);?></td>
            <td style="border:1px solid #dddddd;border-collapse:collapse;padding:0 10px;"><?php echo word_limiter(strip_tags($item['new_value']), 20);?></td>
            </tr>
	<?php endforeach;?>
	</tbody>
</table>