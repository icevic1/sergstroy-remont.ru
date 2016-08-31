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
        <?php echo form_open('', array('id'=>'frm_edit_dep','name'=>'frm_edit_dep','class'=>'form-horizontal')); ?>
          	<input id="dep_id" name="dep_id" type="hidden" value="<?php echo set_value('dep_id', ((isset($department->dep_id))? $department->dep_id: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Department name</label>
                <div class="controls">
                	<?php //echo form_error('branch_name'); ?>
                  	<input id="dep_name" name="dep_name" type="text" value="<?php echo set_value('dep_name', ((isset($department->dep_name))? $department->dep_name:'')); ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="company_id">Company</label>
                <div class="controls">
                  <?php echo form_dropdown('company_id', $companies, $default = ((isset($department->company_id))? $department->company_id : 0));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="branch_id">Branch</label>
                <div class="controls">
                  <?php echo form_dropdown('branch_id', $companyBranches, $default = ((isset($department->branch_id))? $department->branch_id : 0));?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/masteracc/departments/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
    	$(document).on('change', "select[name='company_id']", function(el) {
			var company_id = $(this).val();
			if (company_id == 0) {
				$("select[name='branch_id']").find("option:gt(0)").remove();
				$("select[name='branch_id']").prop("disabled", true);
				return false;
			}
			$.ajax({
			    url:"<?php echo site_url('admin/masteracc/ajax_get_company_branches/')?>",  
			    type: "get",
			    data: {"company_id":company_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
			    success:function(data) {
			    	$("select[name='branch_id']").parent().html(data); 
			    }
			  });
			
			return true;
		});
        
        $("#frm_edit_branch").validate({
            rules: {	
            	dep_name: {required: true, minlength:2, maxlength:100},
            	company_id:{required: true,minlength:1, maxlength:4}
            }
        }); 
    });
</script>