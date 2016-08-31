<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/servicetickets/editStatus/')?>" class="btn" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/servicetickets/')?>" class="btn" style="width:50px;"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Status name</th>
                  <th>Status description</th>
                  <th>ACL Resource Permission</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($statuses as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->StatusID;?></td>
                <td class="center"><?php echo $item->StatusName;?></td>
                <td class="center"><?php echo $item->StatusDescription;?></td>
                <td class="center"><?php echo $item->acl_resource_id;?></td>
                <td class="center" style="width: 150px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/servicetickets/editStatus/'.$item->StatusID);?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/servicetickets/deleteStatus/'.$item->StatusID)?>" onclick="return confirm('You are sure to delete this record')">
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