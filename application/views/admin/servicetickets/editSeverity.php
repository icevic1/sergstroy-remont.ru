<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php if (isset($error)) echo '<br /><font color="red"> - '.$error. '</font>';?></p>
        <?php echo form_open('', array('id'=>'frm_edit_severity','name'=>'frm_edit_severity','class'=>'form-horizontal')); ?>
          	<input id="SeverityID" name="SeverityID" type="hidden" value="<?php echo set_value('SeverityID', ((isset($loadedItem['SeverityID']))? $loadedItem['SeverityID']: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="SeverityName">Severity Name</label>
                <div class="controls">
                  	<input id="SeverityName" name="SeverityName" type="text" value="<?php echo set_value('SeverityName', ((isset($loadedItem['SeverityName']))? $loadedItem['SeverityName']:'')); ?>" />
                  	<?php echo form_error('SeverityName'); ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="SeverityDescription">Severity Description</label>
                <div class="controls">
                  <textarea id="SeverityDescription" name="SeverityDescription"><?php echo set_value('SeverityDescription', ((isset($loadedItem['SeverityDescription']))? $loadedItem['SeverityDescription']:'')); ?></textarea>
                  <?php echo form_error('SeverityDescription'); ?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/servicetickets/severities/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit_severity").validate({
        	errorElement: 'span',
            errorClass: 'error',
            rules: {	
            	SeverityName: {required: true, minlength:2, maxlength:100},
            }
        }); 
    });
</script>