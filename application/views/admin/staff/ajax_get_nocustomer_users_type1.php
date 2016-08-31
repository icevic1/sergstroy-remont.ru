<table class="table gradient-thead compact table-striped bootstrap-datatable datatable">
	<thead>
	    <tr>
	        <th>ID</th>
	        <th>Name</th>
	        <th>Email</th>
	        <th>Mobile No.</th>
	        <th>Office No.</th>
	        <th>PIC Type</th>
	        <th>Choose</th>
	    </tr>
	</thead>   
	<tbody>
	<?php if (isset($filteredUsers)) {?>
		<?php foreach($filteredUsers as $item) :?>
	  <tr data-user_id="<?php echo $item['user_id'];?>">
	      <td><?php echo $item['user_id'];?></td>
	      <td><?php echo $item['name'];?></td>
	      <td><?php echo $item['email'];?></td>
	      <td><?php echo $item['mobile_no'];?></td>
	      <td><?php echo $item['office_no'];?></td>
	      <td><?php echo (($item['pic_type'])? Staff_mod::$PICTypes[$item['pic_type']]:'');?></td>
	      <td style="text-align: center;"><input type="checkbox" name="user_id" value="<?php echo $item['user_id'];?>" /></td>
	  </tr>
	  <?php endforeach;?>
	  <?php } //end if table?>
	  <tr class="gray no-record hide"><td colspan="7" style="text-align:center;"><p>No Record.</p></td></tr>
	</tbody>
</table>