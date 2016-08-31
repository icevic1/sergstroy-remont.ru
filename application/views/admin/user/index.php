<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/user/add/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
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
                  <th>Username</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php $i=1;?>
          	<?php foreach($users as $user):?>
            <tr>
                <td style="width: 20px;"><?php echo $i++?></td>
                <td class="center"><?php echo $user->user_name?></td>
                <td class="center" style="width: 260px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/user/edit/'.$user->user_id)?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/user/permission/'.$user->user_name)?>">
                        <i class="cus-cog-add"></i> Permission                                            
                    </a>
                    <?php }else{?>
                    	 <a class="btn disabled" href="#"><i class="cus-cog-add"></i> Permission</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/user/delete/'.$user->user_id)?>" onclick="return confirm('You are sure to delete this record')">
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
