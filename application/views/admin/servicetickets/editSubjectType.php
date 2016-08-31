<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php if (isset($error)) echo '<br /><font color="red"> - '.$error. '</font>';?></p>
        <?php echo form_open('', array('id'=>'frm_edit_subjectTypes','name'=>'frm_edit_subjectTypes','class'=>'form-horizontal')); ?>
          	<input id="TypeID" name="TypeID" type="hidden" value="<?php echo set_value('TypeID', ((isset($SubjectType['TypeID']))? $SubjectType['TypeID']: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="TypeName">Name</label>
                <div class="controls">
                  	<input id="TypeName" name="TypeName" type="text" value="<?php echo set_value('TypeName', ((isset($SubjectType['TypeName']))? $SubjectType['TypeName']:'')); ?>" />
                  	<?php echo form_error('TypeName'); ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="TypeDescription">Description</label>
                <div class="controls">
                  <textarea id="TypeDescription" name="TypeDescription"><?php echo set_value('TypeDescription', ((isset($SubjectType['TypeDescription']))? $SubjectType['TypeDescription']:'')); ?></textarea>
                  <?php echo form_error('TypeDescription'); ?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/servicetickets/subjectTypes/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit_subjectTypes").validate({
        	errorElement: 'span',
            errorClass: 'error',
            rules: {	
            	StatusName: {required: true, minlength:2, maxlength:100},
            }
        }); 
    });
</script>