<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<a href="<?php echo base_url('admin/customeracl/')?>" class="btn" style="width:50px"><i class="cus-arrow-undo"></i> Back</a>
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/customeracl/resourceEdit/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
    <ul id="group_tabs" class="nav nav-tabs small-tabs">
        <li><a href="<?php echo base_url("admin/customeracl/");?>">Permissions</a></li>
        <li><a href="<?php echo base_url("admin/customeracl/roles/");?>">Roles</a></li>
        <li class="active"><a href="">Resources</a></li>
    </ul>
    <div class="tab-content box-content">
        <div class="tab-pane active">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Resource name</th>
                  <th>Description</th>
                  <th>Inherit from</th>
                  <th>Created</th>
                  <th>Who create</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php $i=1;?>
          	<?php foreach($resources as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->resource_id;?></td>
                <td class="center"><?php echo $item->resource_name;?></td>
                <td class="center"><?php echo $item->resource_description;?></td>
                <td class="center"><?php echo (($item->parent_id > 0)? $resourceOptions[$item->parent_id]: ' - ');?></td>
                <td class="center"><?php echo $item->created_at;?></td>
                <td class="center"><?php echo $item->who_create;?></td>
                <td class="center" style="width: 150px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/customeracl/resourceEdit/'.$item->resource_id)?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/customeracl/deleteResource/'.$item->resource_id)?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href="#">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }?>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
    </div>
</div>
