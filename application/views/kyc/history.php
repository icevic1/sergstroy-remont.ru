<ul class="breadcrumb">
	<li><a href="<?php echo site_url('home/')?>">Home</a> <span class="divider">/</span></li>
	<li><a href="<?php echo site_url('servicetickets/index/')?>">Service Tickets</a> <span class="divider">/</span></li>
	<?php if (isset($STicket->TicketID)):?><li><a href="<?php echo site_url('servicetickets/detailsST/'.$STicket->TicketID)?>">ST<?php echo $STicket->TicketID;?>: <?php echo $STicket->SubjectName;?></a> <span class="divider">/</span></li><?php endif;?>
	<li class="active">Changes Log</li>
</ul>
<fieldset class="scheduler-border"><legend class="scheduler-border">Change history</legend>
	<table id="sts_lists" class="table table-stripeds table-bordered" width="100%" cellspacing="0">
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
			<?php if (isset($cahangesLog)) foreach($cahangesLog as $item) {?>
			<tr>
                <td><?php echo $item['who_change'];?></td>
                <td><?php echo date('H:i, d-M-Y', strtotime($item['modified_at']));?></td>
                <td><?php echo $item['field_name'];?></td>
                <td><?php echo word_limiter(strip_tags($item['old_value']), 20, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
                <td><?php echo word_limiter(strip_tags($item['new_value']), 20, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
            </tr>
			<?php }?>
		</tbody>
	</table>

	<?php if (isset($filteredTickets)) {?>
	<table id="sts_lists" class="table table-stripeds table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr><th>Version</th>
				<th>User</th>
				<th>Date / Time</th>
				<th>Severity</th>
				<th>Request Type</th>
				<th>Service Group</th>
				<th>Service Type</th>
				<th>Company</th>
				<th>Status</th>
				<th>Priority</th>
				<th>Description</th>
				<th>Progress Comment</th>
			</tr>
		</thead>   
		<tbody>
			<tr>
                <td>current</td>
                <td><?php echo $STicket->whoEditeName;?></td>
                <td><?php echo $STicket->LastEditDate;?></td>
               	<td class="<?php echo ( $STicket->SeverityID == 3)? 'highlight-red':'';?>"><?php echo $STicket->SeverityName;?></td>
                <td><?php echo $STicket->TypeName;?></td>
                <td><?php echo $STicket->GroupName;?></td>
                <td><?php echo $STicket->SubjectName;?></td>
                <td><?php echo $STicket->CustName;?></td>
                <td><?php echo $STicket->StatusName;?></td>
                <td><?php echo $STicket->PriorityName;?></td>
                <td><?php echo word_limiter(strip_tags($STicket->Description), 40, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
                <td><?php echo word_limiter(strip_tags($STicket->ProgressComment), 40, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
            </tr>
			<?php 
			foreach($filteredTickets as $id=>$item) :
			?>
			<tr><td style="width:50px;">v<?php echo $item->VersionID;?></td>
                <td><?php echo (isset($item->VersionID) && $item->VersionID == 1? $item->whoCreateName: $item->whoEditeName);?></td>
                <td><?php echo (isset($item->VersionID) && $item->VersionID == 1? $item->CreationDateTime: $item->LastEditDate);?></td>
               	<td class="<?php echo ( $item->SeverityID == 3)? 'highlight-red':'';?>"><?php echo $item->SeverityName;?></td>
                <td><?php echo $item->TypeName;?></td>
                <td><?php echo $item->GroupName;?></td>
                <td><?php echo $item->SubjectName;?></td>
                <td><?php echo $item->CustName;?></td>
                <td><?php echo $item->StatusName;?></td>
                <td><?php echo $item->PriorityName;?></td>
                <td><?php echo word_limiter(strip_tags($item->Description), 20, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
                <td><?php echo word_limiter(strip_tags($item->ProgressComment), 20, '<a data-toggle="modal" href="#ticket_description">&#8230; Read more</a>');?></td>
            </tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php } //end if table?>
</fieldset>