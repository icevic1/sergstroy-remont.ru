<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php
    		$errors = $this->form_validation->error_array();
    	 	if( !empty($errors)) {
    	 		foreach ($errors as $errorItem) {
    	 			echo '<br /><font color="red"> - '.$errorItem. '</font>'; 
    	 		}
    	 	}
    	 	if (isset($error)) echo '<br /><font color="red"> - '.$error. '</font>';
    		?>
    	</p>
        <?php echo form_open(base_url('admin/masteracc/saveBranch/'. ((isset($branch->branch_id))? $branch->branch_id : '')), array('id'=>'frm_edit_branch','name'=>'frm_edit_branch','class'=>'form-horizontal')); ?>
          	<input id="branch_id" name="branch_id" type="hidden" value="<?php echo set_value('branch_id', ((isset($branch->branch_id))? $branch->branch_id: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Branch name</label>
                <div class="controls">
                	<?php //echo form_error('branch_name'); ?>
                  	<input id="branch_name" name="branch_name" type="text" value="<?php echo set_value('branch_name', ((isset($branch->branch_name))? $branch->branch_name:'')); ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Branch address</label>
                <div class="controls">
                  <input id="branch_name" name="branch_address" type="text" value="<?php echo set_value('branch_address', ((isset($branch->branch_address))? $branch->branch_address:'')); ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Company</label>
                <div class="controls">
                  <?php echo form_dropdown('company_id', $companies, $default = ((isset($branch->company_id))? $branch->company_id : 0));?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/masteracc/branches/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit_branch").validate({
            rules: {	
            	branch_name: {required: true, minlength:2, maxlength:100},
            	company_id:{required: true,minlength:1, maxlength:4}
            }
        }); 
    });
</script>