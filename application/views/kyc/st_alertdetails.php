<div id="" class="panel panel-default">
	<div class="panel-heading"><i class="glyphicon glyphicon-info-sign"></i> Notification Details</div>
	<div class="panel-body">
		<dl class="dl-horizontal">
			<dt>ID:</dt><dd><?php echo $STAlert->AlertID;?></dd>
			<dt>Notification title:</dt><dd><?php echo str_replace('{TicketID}', $STAlert->TicketID, $STAlert->Title);?></dd>
			<dt>Customer Name:</dt><dd><?php echo $STAlert->CustName;?></dd>
			<dt>Ticket ID:</dt><dd><a href="<?php echo base_url('servicetickets/detailsST/'. $STAlert->TicketID);?>" title="Show more Ticket details">#<?php echo $STAlert->TicketID;?></a></dd>
			<dt>Ticket Subject:</dt><dd><?php echo $STAlert->SubjectName;?></dd>
			<dt>Ticket Severity:</dt><dd><?php echo $STAlert->SeverityName;?></dd>
			<dt>Ticket Priority:</dt><dd><?php echo $STAlert->PriorityName;?></dd>
			<?php if($STAlert->TicketType == '0') {?>
			<dt>Phone number:</dt><dd>0<?php echo $STAlert->AccountID;?></dd>
			<?php } elseif($STAlert->TicketType == '1' || $STAlert->TicketType == '2') {?>
			<dt>Phone number:</dt><dd>Multiple numbers</dd>
			<?php }?>
			<dt>Viewed status:</dt><dd><?php echo (($STAlert->IsViewed > 0)? 'Yes': 'No');?></dd>
			<?php if ($STAlert->ViewedAt): ?>
			<dt>Viewed Time:</dt><dd><?php echo date('Y-m-d H:i', strtotime($STAlert->ViewedAt));?></dd>
			<?php endif;?>
			<dt>Email Sent:</dt><dd><?php echo (($STAlert->EmailSent > 0)? 'Yes': 'No');?></dd>
			<?php if ($STAlert->SentAt): ?>
			<dt>Sent Time:</dt><dd><?php echo date('Y-m-d H:i', strtotime($STAlert->SentAt));?></dd>
			<?php endif;?>
			<dt>Date of creation:</dt><dd><?php echo date('Y-m-d', strtotime($STAlert->CreatedAt));?></dd>
			<dt>Time of creation:</dt><dd><?php echo date('H:i', strtotime($STAlert->CreatedAt));?></dd>
			<dt>Lead Time, h:</dt><dd><?php $hoursRemained = intval(floor((time() - strtotime($STAlert->CreatedAt)) / 3600));
                echo (($hoursRemained > $STAlert->KpiTimeHours)? '<em class="highlight-red">'.$hoursRemained .'</em>': $hoursRemained) . '/'.$STAlert->KpiTimeHours;?></dd>
		</dl>
	</div><!-- end table-responsive -->
</div>