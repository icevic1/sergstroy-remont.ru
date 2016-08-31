<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/masteracc/saveMacc/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/masteracc')?>" class="btn" style="width:50px"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Master Acc ID</th>
                  <th>Master Acc name</th>
                  <th>Company</th>
                  <th>Branch</th>
                  <th>Master description</th>
                  <th>Created</th>
                  <th>Who create</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($maccList as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->macc_id;?></td>
                <td class="center"><?php echo $item->maccBillID;?></td>
                <td class="center"><?php echo $item->macc_name;?></td>
                <td class="center"><?php echo ((isset($item->company_id))? $companies[$item->company_id]: '-');?></td>
                <td class="center"><?php echo ((isset($item->branch_id))? $branches[$item->branch_id]: '-');?></td>
                <td class="center"><?php echo $item->macc_description;?></td>
                <td class="center"><?php echo $item->created_at;?></td>
                <td class="center"><?php echo $item->who_create;?></td>
                <td class="center" style="width: 150px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/masteracc/saveMacc/'.$item->macc_id)?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/masteracc/deleteMacc/'.$item->macc_id)?>" onclick="return confirm('You are sure to delete this record')">
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