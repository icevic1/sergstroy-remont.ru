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
        <?php echo form_open(base_url('admin/masteracc/saveMacc/'. ((isset($macc->macc_id))? $macc->macc_id : '')), array('id'=>'frm_edit_macc','name'=>'frm_edit_macc','class'=>'form-horizontal')); ?>
          	<input id="macc_id" name="macc_id" type="hidden" value="<?php echo set_value('macc_id', ((isset($macc->macc_id))? $macc->macc_id: ''))?>" />
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="maccBillID">Master account ID</label>
                <div class="controls">
                  	<input id="maccBillID" name="maccBillID" type="text" value="<?php echo set_value('maccBillID', ((isset($macc->maccBillID))? $macc->maccBillID:'')); ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Master acount name</label>
                <div class="controls">
                  	<input id="macc_name" name="macc_name" type="text" value="<?php echo set_value('macc_name', ((isset($macc->macc_name))? $macc->macc_name:'')); ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Company</label>
                <div class="controls">
                  <?php echo form_dropdown('company_id', $companies, $default = ((isset($macc->company_id))? $macc->company_id : 0));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Branch</label>
                <div class="controls branch-holder">
                  <?php echo form_dropdown('branch_id', $branches, $default = ((isset($macc->branch_id))? $macc->branch_id : 0));?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="txt_pwd">Description</label>
                <div class="controls">
                  <textarea id="macc_description" name="macc_description"><?php echo set_value('macc_description', ((isset($macc->macc_description))? $macc->macc_description:'')); ?></textarea>
                </div>
              </div>
              
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/masteracc/maccList/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<?php //var_dump(isset($macc->branch_id),$macc->branch_id);?>
<script type="text/javascript">
    $(document).ready(function() {

    	<?php if (!(isset($macc->company_id) && $macc->company_id > 0) && !(isset($macc->branch_id) && $macc->branch_id > 0)) :?>
    	$("select[name='branch_id']").attr("readonly", "readonly");
        <?php endif;?>
        
        $("#frm_edit_macc").validate({
            rules: {	
            	macc_name: {required: true, minlength:2, maxlength:100},
            	company_id:{required: true,minlength:1, maxlength:4}
            }
        });

        $(document).on('change', "select[name='company_id']", function(el) {
			var company_id = $(this).val();
			if (company_id == 0) {
				$("select[name='branch_id']").find("option:gt(0)").remove();
				$("select[name='branch_id']").attr("readonly", "readonly");
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
    });
</script>