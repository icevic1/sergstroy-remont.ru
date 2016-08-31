<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php if (isset($error)) echo '<br /><font color="red"> - '.$error. '</font>';?></p>
        <?php echo form_open('', array('id'=>'frm_edit_status','name'=>'frm_edit_status','class'=>'form-horizontal')); ?>
          	<input id="StatusID" name="StatusID" type="hidden" value="<?php echo set_value('StatusID', ((isset($status['StatusID']))? $status['StatusID']: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="StatusName">Status name</label>
                <div class="controls">
                  	<input id="StatusName" name="StatusName" type="text" value="<?php echo set_value('StatusName', ((isset($status['StatusName']))? $status['StatusName']:'')); ?>" />
                  	<?php echo form_error('StatusName'); ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="StatusDescription">Description</label>
                <div class="controls">
                  <textarea id="StatusDescription" name="StatusDescription"><?php echo set_value('StatusDescription', ((isset($status['StatusDescription']))? $status['StatusDescription']:'')); ?></textarea>
                  <?php echo form_error('StatusDescription'); ?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/servicetickets/statuses/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit_status").validate({
        	errorElement: 'span',
            errorClass: 'error',
            rules: {	
            	StatusName: {required: true, minlength:2, maxlength:100},
            }
        }); 
    });
</script>