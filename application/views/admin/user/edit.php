<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
          <?php echo form_open(base_url('admin/user/save/'),array('id'=>'frm_user','name'=>'frm_user','class'=>'form-horizontal')); ?>
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Username</label>
                <div class="controls">
                  <input name="hd_user_id" type="hidden" value="<?php if(isset($user['user_id'])) echo $user['user_id']?>">
                  <input class="focused" id="txt_user_name" name="txt_user_name" type="text" value="<?php if(isset($user['user_name'])) echo $user['user_name']?>">
                </div>
              </div>
               <div class="control-group">
                <label class="control-label" for="txt_pwd">Password</label>
                <div class="controls">
                  <input id="txt_pwd" name="txt_pwd" type="password" value="<?php if(isset($user['password'])) echo $user['password']?>">
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/user')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_user").validate({
            rules: {	
                txt_user_name: "required",
                txt_pwd:{required: true,minlength:6}
            }
        }); 
    });
</script>