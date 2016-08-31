<div class="box">
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
        	<thead><th width="20">No</th><th>Subscriber Status</th><th width="70">Actions</th></thead>
            <tbody>
            <?php $i=1; foreach($status as $s){?>
                    <tr>
                    	<td><?php echo $i++?></td>
                        <td><?php echo $s->status_name?></td>
                        <td>
                        	 <?php if($per_page['per_update']==1){?>
                        	<a class="btn" href="<?php echo base_url('admin/status/edit/'.$s->status_id)?>">
                                <i class="cus-page-white-edit"></i>Edit                                            
                            </a>
                            <?php }else{?>
                             <a class="btn disabled" href="#"><i class="cus-page-white-edit"></i>Edit</a>
                            <?php }?>
                        </td>
                    </tr>
            <?php  }?>
            </tbody>
        </table>  
    </div>
</div>