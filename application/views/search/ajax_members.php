<table class="rel-sim table thead-green table-bordered table-striped">
<thead><tr><th>Phone Number</th><th><?php echo label('lbl_status')?></th></tr></thead>
	<tbody>
	<?php foreach($MemberSubscriberList as $MemberServiceNumber => $MemberStatus) {
		$icon='';
		if($MemberServiceNumber == $CurrentServiceNumber){
			$cls_status = 'class="ot-status-active"';
			$icon = '<i class="ot-ico ot-ico-checked-m"></i>';
		} else {
			$cls_status = '';
		}
		?>
		<tr class="<?php echo ($MemberServiceNumber == $CurrentServiceNumber ? 'active':'');?>">
			<td>
				<?php if ($MemberServiceNumber == $CurrentServiceNumber) {?>
				<?php echo preg_replace('/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]*)/', '0$1 $2 $3$4', $MemberServiceNumber);?> 
				<span aria-hidden="true" class="glyphicon glyphicon-flag"></span>
				<?php } else { ?>
				<a href="<?php echo site_url('home/?subs_id='.$MemberServiceNumber);?>" class="direct-url"><?php echo preg_replace('/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]*)/', '0$1 $2 $3$4', $MemberServiceNumber);?></a>
				<?php }?>
			</td>
			<td class="subs_status"><?php echo $this->subscriber->getSubscribersStatus('MemberStatus', $MemberStatus);?>   </td>
		</tr>
	<?php } //end foreach?>
	</tbody>
</table>
<div class="paging-holder"><?php 
	$pagination = pagination_bootstrap('search/ajax_members', $page, $MemberAmount, Subscriber_mod::$def_members_perpage);
	
	if ($pagination->total_rows > $pagination->per_page) {
		$start = ($pagination->cur_page > 1)? (($pagination->cur_page-1)*$pagination->per_page) : 1;
		$end = ($pagination->total_rows > $pagination->per_page) ? ($start + $pagination->per_page ): ($start + $pagination->total_rows);
		$end = ($pagination->cur_page < 2) ? $end - 1: $end;
		$total = $pagination->total_rows;
		
		echo $pagination->create_links();
		echo '<div class="paging-details">Showing '.$start.' - '. $end.' of '. $total.' entries</div>';
	}
	?>
	<div class="clearfix"></div>
</div>
<div id="ajax-preloader_member" class="bg_opacity">
	<img class="ajax-loader" alt="waiting..." src="<?php echo base_url('public/images/ajax-loader.gif'); ?>" />
</div>