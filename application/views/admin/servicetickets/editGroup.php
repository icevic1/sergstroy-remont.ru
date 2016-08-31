<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php if (isset($error)) echo '<br /><font color="red"> - '.$error. '</font>';?></p>
        <?php echo form_open('', array('id'=>'frm_edit_group','name'=>'frm_edit_group','class'=>'form-horizontal')); ?>
          	<input id="GroupID" name="GroupID" type="hidden" value="<?php echo set_value('GroupID', ((isset($loadedItem['GroupID']))? $loadedItem['GroupID']: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="GroupName">Group Name</label>
                <div class="controls">
                  	<input id="SeverityName" name="GroupName" type="text" value="<?php echo set_value('GroupName', ((isset($loadedItem['GroupName']))? $loadedItem['GroupName']:'')); ?>" />
                  	<?php echo form_error('GroupName'); ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="GroupDescription">Description</label>
                <div class="controls">
                  <textarea id="GroupDescription" name="GroupDescription"><?php echo set_value('GroupDescription', ((isset($loadedItem['GroupDescription']))? $loadedItem['GroupDescription']:'')); ?></textarea>
                  <?php echo form_error('GroupDescription'); ?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/servicetickets/groups/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit_group").validate({
        	errorElement: 'span',
            errorClass: 'error',
            rules: {	
            	GroupName: {required: true, minlength:2, maxlength:100},
            }
        }); 
    });
</script>