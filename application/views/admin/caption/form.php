<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <?php echo validation_errors(); ?>
    <div class="box-content">
    	<?php echo form_open(base_url('admin/caption/edit/'.(isset($id)?$id:'')),array('id'=>'frm_label','name'=>'frm_label','class'=>'form-horizontal'), array('id' => '123')); ?>
        <div class="control-group">
            <label class="control-label"><b>ID</b></label>
            <div class="controls">
              <input id="capt_id" name="capt_id" type="text" value="<?php echo set_value('capt_id', ((isset($capt_id))? $capt_id:'')); ?>" />
            </div>
        </div>
        <?php foreach($languages as $item){?>
        <div class="control-group">
        	<label class="control-label"><b><?php echo $item->l_name?></b></label>
        	<div class="controls">
            	<input type="hidden" name="hd_l_id[]" value="<?php echo set_value('hd_l_id[]', ((isset($item->l_id))? $item->l_id:'')); ?>" />
                <input type="hidden" name="hd_capt_id[]" value="<?php echo set_value('hd_capt_id[]', ((isset($caption[$item->l_id]))? $caption[$item->l_id]->capt_id:'')); ?>" />
            	<textarea name="txt_translate[]" id="translate_<?php echo $item->l_id?>" class="editor"><?php echo set_value('txt_translate[]', ((isset($caption[$item->l_id]))? $caption[$item->l_id]->translate:'')); ?></textarea>
            </div>
        </div>
         <?php }?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
            <a href="<?php echo site_url('admin/caption/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
        </div> 
        <?php echo form_close(); ?>
    </div>
</div>