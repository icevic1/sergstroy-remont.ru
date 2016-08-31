<div class="box">
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	<div class="box-icon">
        	 <?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/faq/add/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a href="#" class="btn disabled" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
    </div>
	<?php if($this->session->flashdata('msg')){?>
        <div style="color:#FF0000; font-weight:bold"><?php echo $this->session->flashdata('msg')?></div>
    <?php }?> 
    <div class="box-content">
    	<table class="table table-striped table-bordered bootstrap-datatable datatable">
            <thead><tr><th>Question</th><th>Answer</th><th width="150">Action</th></tr></thead>
            <tbody>
            <?php foreach($faqs as $f){
                 // if($msg->l_id==$l->l_id){
                ?>
                    <tr>
                        <td><?php echo $f->question?></td>
                        <td><?php echo $f->answer?></td>
                        <td>
                        	<div class="btn-group">
                        	<a href="<?php echo site_url('admin/faq/edit/'.$f->faq_id)?>" class="btn"><i class="cus-page-white-edit"></i>Edit</a>
                            <a href="<?php echo site_url('admin/faq/delete/'.$f->faq_id)?>" class="btn"><i class="cus-delete"></i>  Delete</a>
                        	</div>
                        </td>
                    </tr>
            <?php 
			//}
            }?>
            </tbody>
       </table>
    </div>
</div>