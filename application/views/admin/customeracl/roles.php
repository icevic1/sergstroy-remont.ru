<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<a href="<?php echo base_url('admin/customeracl/')?>" class="btn" style="width:50px"><i class="cus-arrow-undo"></i> Back</a>
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/customeracl/roleedit/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
    <ul id="group_tabs" class="nav nav-tabs small-tabs">
        <li><a href="<?php echo base_url("admin/customeracl/permissions");?>">Permissions</a></li>
        <li class="active"><a href="">Roles</a></li>
        <li><a href="<?php echo base_url("admin/customeracl/resources/");?>">Resources</a></li>
    </ul>
    <div class="tab-content box-content">
        <div class="tab-pane active">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Role name</th>
                  <th>Description</th>
                  <th>Created</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php $i=1;?>
          	<?php foreach($roles as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->role_id;?></td>
                <td class="center"><?php echo $item->role_name;?></td>
                <td class="center"><?php echo $item->role_description;?></td>
                <td class="center"><?php echo $item->created_at;?></td>
                <td class="center" style="width: 120px;text-align: center;">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/customeracl/roleedit/'.$item->role_id)?>"><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }else{?>
                     <a class="btn-link disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/customeracl/deleteRole/'.$item->role_id)?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }else{?>
                     <a class="btn-link disabled" href="#">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
    </div>
</div>