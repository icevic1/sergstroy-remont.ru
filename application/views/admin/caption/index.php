<div class="box">
	<div class="box-header well" data-original-title>
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	<div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/caption/edit/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
    <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable data-caption">
            <thead><tr><th>ID</th><th>Caption</th><th>Type</th><th>In Page</th><th width="180">Actions</th></tr></thead>
            <tfoot><tr><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
            <tbody>
            <?php foreach($captions as $capt){?>
                    <tr>
                        <td><?php echo $capt->capt_id?></td>
                        <td><?php echo word_limiter($capt->caption, 6)?></td>
                        <td><?php echo $capt->caption_type?></td>
                        <td><?php echo $capt->in_pages?></td>
                        <td>
                        	<?php if($per_page['per_update']==1){?>
                                <a class="btn" href="<?php echo base_url('admin/caption/edit/'.$capt->id)?>">
                                    <i class="cus-page-white-edit"></i>Edit                                            
                                </a>
                              <?php }else{?>
                                 <a class="btn disabled" href="#"><i class="cus-page-white-edit"></i>Edit</a>
                            <?php }?>
                            <?php if($per_page['per_delete']==1){?>
		                    <a class="btn" href="<?php echo base_url('admin/caption/deleteCaption/'.$capt->capt_id)?>" onclick="return confirm('You are sure to delete this record')">
		                        <i class="cus-delete"></i>Delete
		                    </a>
		                    <?php }else{?>
		                     <a class="btn disabled" href="#">
		                        <i class="cus-delete"></i>Delete
		                    </a>
		                    <?php }?>
                        </td>
                    </tr>
            <?php }?>
            </tbody>
           
        </table>
     </div>        
</div>
<style type="text/css">
	.select_filter{
		width:100px !important;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(e) {
       $('.data-caption').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
			"sPaginationType": "bootstrap",
			"bStateSave": true,
			"bFilter": true
		}).columnFilter({
			aoColumns: [ { type: "text" }, { type: "text" }, { type: "select", values: [ 'Label', 'Button'] },{ type: "select", values: [ 'Main Page', 'Login Page'] }, null]
		});
    });
</script>