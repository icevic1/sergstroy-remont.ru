<table class="table table-striped table-bordered bootstrap-datatable datatable1">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Role</th>
			<th>Company</th>
			<th>Branch</th>
			<th>Level</th>
			<th>Created</th>
			<th>Updated</th>
			<th>Actions</th>
		</tr>
	</thead>   
	<tbody>
		<?php foreach($filteredUsers as $item) :?>
		<tr>
		<td style="width: 20px;">
			<?php echo $item->user_id;?>
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][user_id]" value="<?php echo $item->user_id;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][name]" value="<?php echo $item->name;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][role_id]" value="<?php echo $item->role_id;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][company_id]" value="<?php echo $item->company_id;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][branch_id]" value="<?php echo $item->branch_id;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][level]" value="<?php echo $item->level;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][p_0]" value="<?php echo $item->p_0;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][p_1]" value="<?php echo $item->p_1;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][p_2]" value="<?php echo $item->p_2;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][p_3]" value="<?php echo $item->p_3;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][p_4]" value="<?php echo $item->p_4;?>" />
			<input type="hidden" name="choosed_parent[<?php echo $item->user_id;?>][p_5]" value="<?php echo $item->p_5;?>" />
		</td>
		<td class="center"><?php echo $item->name;?></td>
		<td class="center"><?php echo (($item->role_id > 0)? $roles[$item->role_id]: '-');?></td>
		<td class="center"><?php echo (($item->company_id > 0)? $companies[$item->company_id]: '-');?></td>
		<td class="center"><?php echo (($item->branch_id > 0)? $branches[$item->branch_id]: '-');?></td>
		<td class="center"><?php echo $item->level;?></td>
		<td class="center"><?php echo $item->created_at;?></td>
		<td class="center"><?php echo (($item->updated_at)? $item->updated_at: '-');?></td>
		<td class="center" style="width: 100px;">
			<button type="button" class="btn pull-right inline choose_parent" onclick="set_parent('<?php echo $item->user_id;?>')"><i class="cus-accept"></i> Choose</button>
		</td>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>