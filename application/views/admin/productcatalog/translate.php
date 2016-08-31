<div class="box">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    	 <div class="box-icon">
            <a href="<?php echo base_url('admin/productcatalog')?>" class="btn" style="width:50px"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
    	<?php echo form_open(base_url('admin/productcatalog/save_translate/'),array('class'=>'form-horizontal')); ?>
        <input type="hidden" value="<?php echo $pp_id?>" name="pp_id"/>
        <?php foreach($t_pp as $s){?>
        <div class="control-group">
        	 <label class="control-label"><b><?php echo $s->l_name?></b></label>
        	<div class="controls">
            	<b>WebName</b>
            	<input type="hidden" name="l_id[]" value="<?php echo $s->l_id?>"/>
            	<textarea name="t_web_name[]" id="t_web_name_<?php echo $s->l_id?>" class="editor"><?php echo $s->t_web_name?></textarea>
            </div>
        </div>
         <?php }?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
           <a href="<?php echo base_url('admin/productcatalog')?>" class="btn"><i class="cus-cancel"></i>Cancel</a>
        </div> 
        <?php echo form_close(); ?>
    </div>
</div>