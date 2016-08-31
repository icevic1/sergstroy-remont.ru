<div class="table-responsive">
<table class="rel-sim table">
<thead>
	<tbody>
	<?php 
	$col = 0;
	$max = 3;
	$MemberSubscriberList = array_keys($MemberSubscriberList);
	
	foreach($MemberSubscriberList as $item) {

		if ($col == 0) echo '<tr>';
		
		echo '<td class="chk"><input type="checkbox" name="SubsNum" value="'.$item.'" id="snum_'.$item.'" autocomplete="off" /> </td>';
		echo '<td class="snum"><label for="snum_'.$item.'">'.preg_replace('/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]*)/', '0$1 $2 $3$4', $item).'</label></td>';
	
		if ($col == $max) { 
			echo '</tr>'; 
			if ($item !== end($MemberSubscriberList)) $col = 0;
		} elseif ($item !== end($MemberSubscriberList)) {
			$col++;
		}
	} //end foreach
	
	if ($col < $max) {
		for ($i=0; $i<$max-$col;$i++) {
			echo '<td></td><td></td>';
		}
		echo '</tr>';
	}
	?>
	</tbody>
</table>
</div>
<div class="member-paging"><?php //var_dump($per_page);
$pagination = pagination_bootstrap('search/ajax_members', $page, $MemberAmount, isset($per_page)?$per_page:48);

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
<style>
.rel-sim {width: 100%;border:1px solid #d8d8d8;}
.rel-sim td {border:1px solid #d8d8d8;}
.rel-sim .snum {padding:5px 10px;}
.rel-sim .snum label {padding:0;margin:0;cursor: pointer;}
.rel-sim .chk {padding:5px;}
.rel-sim .chk input {padding:0;margin:0;}
#members_list .member-paging .pagination {
    float: right;
    margin-bottom: 0;
    margin-top: 10px;
}
#members_list .member-paging .paging-details {
    color: #999;
    float: right;
    font-size: 11px;
    font-style: oblique;
    line-height: 30px;
    margin-right: 15px;
    margin-top: 10px;
}
</style>