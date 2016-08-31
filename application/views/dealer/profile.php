<div id="" class="panel panel-default">
	<div class="panel-heading"><i class="glyphicon glyphicon-info-sign"></i> Dealer Details</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<dl class="sim-profile dl-horizontal">
					<dt>Dealer Number:</dt><dd><?php echo $item['dealer_name'];?></dd>
					<dt>Sales ID:</dt><dd><?php echo $item['salesID'];?></dd>
					<dt>Dealer Type:</dt><dd><?php echo $item['dealer_type_name'];?></dd>
					<dt>Dealer Category:</dt><dd><?php echo $item['kind_name'];?></dd>
					<dt>Phone Number:</dt><dd><?php echo $item['phone_1'];?></dd>
					<dt>Second Phone #:</dt><dd><?php echo $item['phone_2'];?></dd>
					<?php $salers_array = explode(',', $item['sallers']);
					for ($i=1;$i<=2;$i++) {
						echo '<dt>Login '.$i.':</dt><dd>'.(isset($salers_array[$i-1])?$salers_array[$i-1]:'N/A').'</dd>';
					}
					?>
					<dt>Contact Email:</dt><dd><?php echo $item['email'];?></dd>
					<dt>Status:</dt><dd><?php echo $item['dealer_status_name'];?></dd>
					<dt>Create Date/Time:</dt><dd><?php echo date('Y-m-d H:i', strtotime($item['created_at']));?></dd>
					<dt>Change Date/Time:</dt><dd><?php echo (($item['changed_at'])? date('Y-m-d H:i', strtotime($item['changed_at'])):'');?></dd>
				</dl>
			</div>
			<div class="col-md-6">
				<dl class="sim-profile dl-horizontal">
					<dt>Location:</dt><dd><?php echo $item['location_name'];?></dd>
					<dt>Zone:</dt><dd><?php echo $item['zone_name'];?></dd>
					<dt>Region:</dt><dd><?php echo $item['reg_name'];?></dd>
					<dt>City:</dt><dd><?php echo $item['city_name'];?></dd>
					<dt>District:</dt><dd><?php echo $item['district_name'];?></dd>
					<dt>Commune:</dt><dd><?php echo $item['commune_name'];?></dd>
					<dt>Hause Number:</dt><dd><?php echo $item['house_no'];?></dd>
					<dt>Street:</dt><dd><?php echo $item['street'];?></dd>
					<dt>Village:</dt><dd><?php echo $item['village'];?></dd>
					<dt>Owner:</dt><dd><?php echo $item['owner'];?></dd>
				</dl>
			</div>
		</div><!-- end row -->
	</div><!-- end table-responsive -->
</div>