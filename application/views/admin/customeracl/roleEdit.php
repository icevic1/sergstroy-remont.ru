<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php 
    	$formErrors = $this->form_validation->error_array();
    	if( !empty($formErrors)) 
    		foreach ($formErrors as $error) 
    			echo '<br /><font color="red"> - '.$error. '</font>'; 
    		?>
    	<?php 
		if($this->session->flashdata('msg')){
			echo "<script>alert('".$this->session->flashdata('msg')."')</script>";
		}
		?></p>
          <?php echo form_open(base_url('admin/customeracl/roleedit/'. ((isset($customer_role->role_id))? $customer_role->role_id : '')), array('id'=>'frm_editrole','name'=>'frm_editrole','class'=>'form-horizontal')); ?>
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Role name</label>
                <div class="controls">
                  <input id="role_id" name="role_id" type="hidden" value="<?php if(isset($customer_role->role_id)) echo $customer_role->role_id?>">
                  <input class="focused" id="role_name" name="role_name" type="text" value="<?php if(isset($customer_role->role_name)) echo $customer_role->role_name?>">
                </div>
              </div>
               <div class="control-group">
                <label class="control-label" for="txt_pwd">Role description</label>
                <div class="controls">
                  <textarea id="role_description" name="role_description"><?php echo set_value('role_description', ((isset($customer_role->role_description))? $customer_role->role_description:'')); ?></textarea>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Parent</label>
                <div class="controls">
                  <?php echo form_dropdown('parent_id', $roleOptions, $default = ((isset($customer_role->parent_id))? $customer_role->parent_id : '0'));?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/customeracl/roles')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_user").validate({
            rules: {	
            	role_name: {required: true, minlength:2, maxlength:100},
//             	role_id:{required: true,minlength:1, maxlength:4}
            }
        }); 
    });
</script>