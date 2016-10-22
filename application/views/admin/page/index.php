<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	 <?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/page/add/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a href="#" class="btn disabled" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
     <?php 
		if($this->session->flashdata('msg')){
			echo "<script>alert('".$this->session->flashdata('msg')."')</script>";
		}
	?>
    <div class="box-content">
    	 <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Page</th>
                  <th>URL</th>
                  <th>Status</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php $i=1;?>
          	<?php foreach($tmpls as $tmpl):?>
            <tr>
                <td style="width: 20px;"><?php echo $i++?></td>
                <td class="center"><?php echo $tmpl->pg_name?></td>
                <td class="center"><a href="<?php echo base_url($tmpl->pg_url)?>" target="_new"><?php echo base_url($tmpl->pg_url)?></a></td>
                <td class="center"><?php echo $tmpl->is_public==1?'Public':'UnPublic'?></td>
                 <td class="center" style="width: 220px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/page/edit/'.$tmpl->pg_id)?>">
                        <i class="cus-page-white-edit"></i>  
                        Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href="#" onclick="return false">
                        <i class="cus-page-white-edit"></i>  
                        Edit                                            
                    </a>
                    <?php }?>
                     <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/page/block/'.$tmpl->pg_id)?>">
                        <i class="cus-page-white-edit"></i>  
                        Block                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href="#" onclick="return false">
                        <i class="cus-page-white-edit"></i>  
                        Block                                            
                    </a>
                    <?php }?>
                    <?php if($tmpl->pg_id>4){?>
                    <a class="btn" href="<?php echo base_url('admin/page/view/'.$tmpl->pg_id)?>" target="_new">
                        <i class="cus-zoom"></i>  
                        View                                            
                    </a>
                    <?php }else{?>
                    	<a class="btn disabled" href="#" onclick="return false"><i class="cus-zoom"></i>View</a>
                    <?php }?>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
    </div>
</div>