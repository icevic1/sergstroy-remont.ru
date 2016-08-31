<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<?php echo form_open(base_url('admin/message/save/'),array('id'=>'frm_message','name'=>'frm_message','class'=>'form-horizontal')); ?>
        <div class="control-group">
            <label class="control-label"><b>ID</b></label>
            <div class="controls">
              <input type="hidden" value="<?php echo $msg_id?>" name="hd_msg_id"/>
              <?php echo $msg_id?>
            </div>
        </div>
        <?php foreach($message as $msg){?>
        <div class="control-group">
        	 <label class="control-label"><b><?php echo $msg->l_name?></b></label>
        	<div class="controls">
            	<input type="hidden" name="hd_l_id[]" value="<?php echo $msg->l_id?>"/>
                <input type="hidden" name="hd_msg_id[]" value="<?php echo $msg->msg_id?>"/>
            	<textarea name="txt_translate[]" id="translate_<?php echo $msg->l_id?>" class="editor"><?php echo $msg->translate?></textarea>
            </div>
        </div>
         <?php }?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
        </div> 
        <?php echo form_close(); ?>
    </div>
</div>