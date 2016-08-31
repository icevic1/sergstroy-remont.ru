<ul class="breadcrumb">
	<li><a href="<?php echo site_url('home/')?>">Home</a> <span class="divider">/</span></li>
	<li><a href="<?php echo site_url('servicetickets/index/')?>">Service Tickets</a> <span class="divider">/</span></li>
	<?php if (isset($STicket->TicketID)):?><li><a href="<?php echo site_url('servicetickets/detailsST/'.$STicket->TicketID)?>">ST<?php echo $STicket->TicketID;?>: <?php echo $STicket->SubjectName;?></a> <span class="divider">/</span></li><?php endif;?>
	<li class="active">Feedback history</li>
</ul>
<fieldset class="scheduler-border"><legend class="scheduler-border">Feedback history</legend>
	<?php if (isset($feedbackHistory)) {?>
	<table id="sts_lists" class="table table-stripeds table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr><th>ID</th>
				<th>Date / Time</th>
				<th>Owner</th>
				<th>Receiver Name</th>
				<th>Receive Email</th>
				<th>Message</th>
			</tr>
		</thead>   
		<tbody>
			<?php foreach($feedbackHistory as $id=>$item) :?>
			<tr>
				<td><?php echo $item->FeedbackID;?></td>
                <td><?php echo $item->CreatedAt;?></td>
                <td><?php echo $item->CreatedByName;?></td>
                <td><?php echo $item->BackToName;?></td>
                <td><?php echo $item->Email;?></td>
                <td><?php echo $item->Message;?></td>
            </tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php } //end if table?>
</fieldset>