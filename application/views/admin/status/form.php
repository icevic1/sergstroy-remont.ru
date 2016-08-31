<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<?php echo form_open(base_url('admin/status/save/'),array('id'=>'frm','name'=>'frm')); ?>
        <input type="hidden" value="<?php echo $status_id?>" name="hd_status_id"/>
       <div class="control-group">
             <div class="controls">
                <ul class="nav nav-tabs">
                  <?php $i=0;foreach($status as $l){
                      $cls=$i==0?'class="active"':'';
                      $i++;
                      ?>
                  <li <?php echo $cls?>>
                    <a href="#<?php echo 'ln'.$l->l_id?>" data-toggle="tab" id="tab_<?php echo $i?>"><?php echo $l->l_name.' ('.$l->l_short_name.')'?></a>
                  </li>
                  <?php }?>
                </ul>
                <div class="tab-content">
                     <?php $i=0;foreach($status as $s){
                          $cls=$i==0?'active':'';
                          $i++;
                      ?>
                    <div class="tab-pane <?php echo $cls?>" id="<?php echo 'ln'.$s->l_id?>">
                    	  <input type="hidden" name="hd_l_id[]" value="<?php echo $s->l_id?>"/>
                          <input type="hidden" name="hd_status_id[]" value="<?php echo $s->status_id?>"/>
                          <div class="control-group">
                          	<label class="control-label"><b>Status</b></label>
                            <div class="controls">
                                <textarea name="txt_translate[]" id="translate_<?php echo $s->l_id?>" class="editor"><?php echo $s->translate?></textarea>
                            </div>
                          </div>
                           <div class="control-group">
                          	<label class="control-label"><b>Description</b></label>
                            <div class="controls">
                                <textarea name="txt_desc_translate[]" id="desc_translate_<?php echo $s->l_id?>" class="editor"><?php echo $s->desc_translate?></textarea>
                            </div>
                          </div>
                    </div>
                    <?php }?>
                </div>
             </div>
          </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
        </div> 
        <?php echo form_close(); ?>
    </div>
</div>