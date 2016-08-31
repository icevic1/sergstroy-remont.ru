<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/variable/add/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            	<a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
    <div class="box-content">
       <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Default value</th>
                  <th>Scope</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php $i=1;?>
          	<?php foreach($variables as $var):?>
            <tr>
                <td style="width: 20px;"><?php echo $i++?></td>
                <td class="center"><?php echo $var->var_key?></td>
                <td class="center"><?php echo $var->var_desc?></td>
                <td class="center"><?php echo $var->var_val?></td>
                <td class="center"><?php echo $var->scope?></td>
                 <td class="center" style="width: 150px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/variable/edit/'.$var->var_id)?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href="#">
                        <i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/variable/delete/'.$var->var_id)?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href="#"><i class="cus-delete"></i>Delete</a>
                    <?php }?>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
</div>