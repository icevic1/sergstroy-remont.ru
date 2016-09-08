<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<a href="<?php echo base_url('admin/customeracl/')?>" class="btn" style="width:50px"><i class="cus-arrow-undo"></i> Back</a>
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/customeracl/permissionEdit/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
    <ul id="group_tabs" class="nav nav-tabs small-tabs">
        <li class="active"><a href="">Permissions</a></li>
        <li><a href="<?php echo base_url("admin/customeracl/roles/");?>">Roles</a></li>
        <li><a href="<?php echo base_url("admin/customeracl/resources/");?>">Resources</a></li>
    </ul>
    <div class="tab-content box-content">
        <div class="tab-pane active">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Role name</th>
                  <th>Resource name</th>
                  <th>Read</th>
                  <th>Write</th>
                  <th>Modify</th>
                  <th>Delete</th>
                  <th>Descritpion</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($permissions as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->permission_id;?></td>
                <td class="center"><?php echo "#{$item->role_id} ".$item->role_name;?></td>
                <td class="center"><?php echo "#{$item->resource_id} ".$item->resource_name;?></td>
                <td align="center" class="center"><strong><?php echo (($item->read_access == 1)? '<font color="green">+</font>':'<font color="red">-</font>');?></strong></td>
                <td align="center" class="center"><strong><?php echo (($item->write_access == 1)? '<font color="green">+</font>':'<font color="red">-</font>');?></strong></td>
                <td align="center" class="center"><strong><?php echo (($item->modify_access == 1)? '<font color="green">+</font>':'<font color="red">-</font>');?></strong></td>
                <td align="center" class="center"><strong><?php echo (($item->delete_access == 1)? '<font color="green">+</font>':'<font color="red">-</font>');?></strong></td>
                <td class="center"><?php echo $item->description;?></td>
                <td class="center" style="width: 150px;">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/customeracl/permissionEdit/'.$item->permission_id)?>"><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }else{?>
                     <a class="btn-link disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/customeracl/deletePermission/'.$item->permission_id)?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }else{?>
                     <a class="btn-link disabled" href="#"><i class="cus-delete"></i>Delete</a>
                    <?php }?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
    </div>
</div>
