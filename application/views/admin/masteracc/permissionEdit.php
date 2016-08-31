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
          <?php echo form_open(base_url('admin/customeracl/permissionEdit/'. ((isset($permission->permission_id))? $permission->permission_id : '')), array('id'=>'frm_editpermission','name'=>'frm_editpermission','class'=>'form-horizontal')); ?>
          	<input id="permission_id" name="permission_id" type="hidden" value="<?php if(isset($permission->permission_id)) echo $permission->permission_id?>">
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Role</label>
                <div class="controls">
                  <?php echo form_dropdown('role_id', $roles, $default = ((isset($permission->role_id))? $permission->role_id : 0));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Resource</label>
                <div class="controls">
                  <?php echo form_dropdown('resource_id', $resources, $default = ((isset($permission->resource_id))? $permission->resource_id : 0) );?>
                  <!-- input class="focused" id="permission_name" name="permission_name" type="text" value="<?php //if(isset($permission->permission_name)) echo $permission->permission_name?>" -->
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label" for="per_read">Read access</label>
                <div class="controls">
               		<input type="checkbox" id="per_read" name="read" value="1" <?php echo set_checkbox('read', '1', ((isset($permission->read) && $permission->read > 0)? true : false)); ?> />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for=""per_write"">Write access</label>
                <div class="controls">
                	<input type="checkbox" id="per_write" name="write" value="1" <?php echo set_checkbox('write', '1', ((isset($permission->write) && $permission->write > 0)? true : false)); ?> />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="per_modify">Modify access</label>
                <div class="controls">
                	<input type="checkbox" id="per_modify" name="modify" value="1" <?php echo set_checkbox('modify', '1', ((isset($permission->modify) && $permission->modify > 0)? true : false)); ?> />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="per_delete">Delete access</label>
                <div class="controls">
                	<input type="checkbox" id="per_delete" name="delete" value="1" <?php echo set_checkbox('delete', '1', ((isset($permission->delete) && $permission->delete > 0)? true : false)); ?> />
                </div>
              </div>
               <div class="control-group">
                <label class="control-label" for="txt_pwd">Permission description</label>
                <div class="controls">
                  <textarea id="description" name="description"><?php echo set_value('description', ((isset($permission->description))? $permission->description:'')); ?></textarea>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/customeracl/permissions')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_user").validate({
            rules: {	
            	permission_name: {required: true, minlength:2, maxlength:100},
//             	permission_id:{required: true,minlength:1, maxlength:4}
            }
        }); 
    });
</script>