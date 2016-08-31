<div class="box">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/productitem/edit')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a> 
			<?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
    <div class="box-content">
    	<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>ACC Ordinal</th>
                  <th>Product Name</th>
                  <th>Group</th>
                  <th>Is Roaming</th>
                  <th align="center" style="width: 150px;">Actions</th>
              </tr>
          </thead>   
          <tbody>
            <?php $i=1;?>
            <?php foreach($items as $item):?>
            <tr>
                <td class="center"><?php echo $item->acc_ordinal?></td>
                <td class="center"><?php echo $item->pp_name?></td>
                <td class="center"><?php echo $item->group_name?></td>
                <td class="center"><?php echo $item->roaming_status?></td>
                <td class="center">
                    <div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/productitem/edit/'.$item->acc_ordinal)?>">
                        <i class="cus-page-white-edit"></i>  
                        Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href="#">
                        <i class="cus-page-white-edit"></i>  
                        Edit                                            
                    </a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/productitem/delete/'.$item->acc_ordinal)?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i> 
                        Delete
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href="#">
                        <i class="cus-delete"></i> 
                        Delete
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