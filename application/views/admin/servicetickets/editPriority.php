<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php if (isset($error)) echo '<br /><font color="red"> - '.$error. '</font>';?></p>
        <?php echo form_open('', array('id'=>'frm_edit','name'=>'frm_edit','class'=>'form-horizontal')); ?>
          	<input id="PriorityID" name="PriorityID" type="hidden" value="<?php echo set_value('PriorityID', ((isset($loadedItem['PriorityID']))? $loadedItem['PriorityID']: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="PriorityName">Priority Name</label>
                <div class="controls">
                  	<input id="PriorityName" name="PriorityName" type="text" value="<?php echo set_value('PriorityName', ((isset($loadedItem['PriorityName']))? $loadedItem['PriorityName']:'')); ?>" />
                  	<?php echo form_error('PriorityName'); ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="PriorityDescription">Severity Description</label>
                <div class="controls">
                  <textarea id="PriorityDescription" name="PriorityDescription"><?php echo set_value('PriorityDescription', ((isset($loadedItem['PriorityDescription']))? $loadedItem['PriorityDescription']:'')); ?></textarea>
                  <?php echo form_error('PriorityDescription'); ?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/servicetickets/priorities/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit").validate({
        	errorElement: 'span',
            errorClass: 'error',
            rules: {	
            	PriorityName: {required: true, minlength:2, maxlength:100},
            }
        }); 
    });
</script>