<div id="" class="panel panel-default">
	<div class="panel-heading"><i class="fa fa-list-ul"></i> Applications List <div class="pull-right"><a href="" onclick="bootbox.alert('This feature is Under Construction');return false;"><i class="icomoon icon-file-excel"></i> Export</a></div></div>
	<div class="table-responsive">
	<?php if (isset($items)) {?>
	<table id="app_list" class="thead-green display table-condensed table table-bordered table-striped dataTable-applicants">
	<thead>
		<tr>
			<th class="nosort">SIM Number</th>
			<th class="nosort fit-size">Phone Number</th>
			<th class="fit-size">Status</th>
			<th class="nosort">Date</th>
			<th class="nosort fit-size">Dealer Name</th>
			<th class="nosort fit-size">Sales ID</th>
			<th class="nosort fit-size">Login</th>
			<th class="nosort fit-size">Subcriber Type</th>
			<th class="nosort fit-size">Contact Person</th>
			<th class="nosort fit-size">Date of Birth</th>
			
			<th class="nosort">No.</th>
			<th class="nosort">St.</th>
			<th class="nosort">Sangkat/Commune</th>
			<th class="nosort">Khan/District</th>
			<th class="nosort">Citiy/Province</th>
			
			<th class="nosort">Contact Phone</th>
			<th class="nosort">Fax Number</th>
			<th class="nosort">E-mail</th>
			
			<th class="nosort">Cambodian/Foreigner</th>
			<th class="nosort">Attached Document</th>
			<th class="nosort">File 1</th>
			<th class="nosort">File 2</th>
			<th class="nosort">Document Number</th>
			<th class="nosort">Document Issue</th>
			<th class="nosort">GPS</th>
		</tr>
	</thead>   
	<tbody>
	<?php foreach($items as $item) :?>
		<tr><td><a href="<?php echo site_url('home/save/'.$item['applicant_id']);?>"><?php echo $item['serial_number'];?></a></td>
			<td><?php echo $item['phone_number'];?></td>
			<td class="text-center">
				<?php if ($item['applicant_status_id'] == '1') {?>
				<div class="text-warning"><i class="icon32 icon-gray icon-clock"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php } elseif ($item['applicant_status_id'] == '2') { ?>
				<div class="text-success"><i class="fa fa-check"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php } elseif ($item['applicant_status_id'] == '3') { ?>
				<div class="text-danger"><i class="fa fa-check"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php } elseif ($item['applicant_status_id'] == '4') { ?>
				<div class="text-info"><i class="fa fa-check"></i> <?php echo $item['applicant_status_name'];?></div>
				<?php }?>
			</td>
			<td><?php echo date('d-M-Y', strtotime($item['created_at']));?></td>
			<td><a href="<?php echo site_url('dealer/profile/'.$item['dealer_id']);?>"><?php echo $item['dealer_name'];?></a></td>
			<td><?php echo $item['salesID'];?></td>
			<td><?php echo $item['saller_name'];?></td>
			<td><?php echo (isset(Applicant_model::$SUBSCRIBER_TYPES[$item['subscriber_type']])? Applicant_model::$SUBSCRIBER_TYPES[$item['subscriber_type']]: '');?></td>
			<td><?php echo ucfirst($item['gender']) . ' ' . $item['contact_name'];?></td>
			<td><?php echo $item['date_of_birth'];?></td>
			
			<td><?php echo $item['house_number'];?></td>
			<td><?php echo $item['street'];?></td>
			<td><?php echo $item['commune_name'];?></td>
			<td><?php echo $item['district_name'];?></td>
			<td><?php echo $item['city_name'];?></td>
			
			<td><?php echo $item['contact_number'];?></td>
			<td><?php echo $item['fax_number'];?></td>
			<td><?php echo $item['email'];?></td>
			
			<td><?php echo ((isset(Applicant_model::$FOREIGNER_TYPES[$item['is_foreigner']]))? Applicant_model::$FOREIGNER_TYPES[$item['is_foreigner']]: 'N/A');?></td>
			<td><?php echo ((isset(Applicant_model::$DOCUMENT_TYPES[$item['document_type']]))? Applicant_model::$DOCUMENT_TYPES[$item['document_type']]: 'N/A');?></td>
			<td><?php 
				if ($item['photo_1']) {
					$img_src = base_url("assets/upload/{$item['photo_1']}");
					echo '<a href="'.$img_src.'" target="_blank"><img width="60" height="36" src="'.$img_src.'" />';
				}
				?>
			</td>
			<td><?php if ($item['photo_2']) {
					$img_src = base_url("assets/upload/{$item['photo_2']}");
					echo '<a href="'.$img_src.'" target="_blank"><img width="60" height="36" src="'.$img_src.'" />';
				}?></td>
			<td><?php echo $item['document_number'];?></td>
			<td><?php echo date('d-M-Y', strtotime($item['document_issue_date']));?></td>
			
			<td class="center"><?php echo $item['GPS_Lat']. ', '.$item['GPS_Lon'];?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
	</table>
	<?php } //end if table?>
	</div>
</div>