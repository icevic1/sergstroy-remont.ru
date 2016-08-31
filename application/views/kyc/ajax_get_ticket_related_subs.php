<div class="table-responsive scroll">
<table class="selected-mem-list table compact table-striped table-bordered dataTable">
<thead>
	<tbody>
	<?php 
	$col = 0;
	$max = 3;
	foreach($MassSelectedSubs as $item) {
		if ($col == 0) echo '<tr>';
		echo '<td class="snum">0'.$item['AccountID']. '</td>';
		
		if ($col == $max) { 
			echo '</tr>'; 
			if ($item !== end($MassSelectedSubs)) $col = 0;
		} elseif ($item !== end($MassSelectedSubs)) {
			$col++; 
		}
	} //end foreach

	if ($col < $max) {
		for ($i=0; $i<$max-$col;$i++) {
			echo '<td></td>';
		}
		echo '</tr>';
	}
	?>
	</tbody>
</table>
</div>
<style>
.scroll {
    height: 285px;
}
</style>