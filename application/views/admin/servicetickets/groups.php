<div class="box no-margin">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/servicetickets/editGroup/')?>" class="btn" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/servicetickets/')?>" class="btn" style="width:50px;"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
        <table class="table gradient-thead compact table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Group Name</th>
                  <th>Description</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php if ($groups) foreach($groups as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->GroupID;?></td>
                <td class="center"><?php echo $item->GroupName;?></td>
                <td class="center"><?php echo $item->GroupDescription;?></td>
                <td align="center" style="width: 100px;">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/servicetickets/editGroup/'.$item->GroupID);?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn-link disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/servicetickets/deleteGroup/'.$item->GroupID)?>" onclick="return confirm('You are sure to delete this record')">
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