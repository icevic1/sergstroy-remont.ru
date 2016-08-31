<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
	 <?php echo form_open(base_url('admin/variable/save/'),array('id'=>'frm_variale','name'=>'frm_variale','class'=>'form-horizontal')); ?>
     <div class="control-group">
            <label class="control-label"><b>Name</b></label>
            <div class="controls">
              <input type="hidden" value="<?php if(isset($variable['var_id'])) echo $variable['var_id']?>" name="hd_var_id"/>
              <input class="focused"  name="txt_var_key" type="text" value="<?php if(isset($variable['var_key'])) echo $variable['var_key']?>"/>
            </div>
      </div>
       <div class="control-group">
            <label class="control-label"><b>Description</b></label>
            <div class="controls">
              <textarea cols="50" rows="5" name="txt_var_desc" class="span6"><?php if(isset($variable['var_desc'])) echo $variable['var_desc']?></textarea>
            </div>
      </div>
      <div class="control-group">
            <label class="control-label"><b>Default Value</b></label>
            <div class="controls">
              <input name="txt_var_val" type="text" value="<?php if(isset($variable['var_val'])) echo $variable['var_val']?>"/>
            </div>
      </div>
       <div class="control-group">
            <label class="control-label"><b>Scope</b></label>
            <div class="controls">
               <?php  
                $scope=isset($variable['scope_id'])?$variable['scope_id']:'';
                echo form_dropdown('cbo_scope_id',$scopes,$scope,'');
				?>
            </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
        <a href="<?php echo base_url('admin/variable')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
      </div>
      <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_variale").validate({
            rules: {	
                txt_var_key: "required"
            }
        }); 
    });
</script>