<div class="box">
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<?php echo form_open(base_url('admin/faq/save/'),array('id'=>'frm_faq','name'=>'frm_faq','class'=>'form-horizontal')); ?>
       <input type="hidden" name="hd_faq_id" value="<?php if(isset($faq_id)) echo $faq_id?>"/>
        <div>
             <ul class="nav nav-tabs">
             	<?php foreach($languages as $l):?>
                <li <?php if($l->is_default==1) echo 'class="active"'?>><a href="#<?php echo 'tab'.$l->l_id?>" data-toggle="tab"><?php echo $l->l_name?></a></li>
                <?php endforeach?>
             </ul>
              <div class="tab-content">
              	<?php foreach($languages as $l):?>
                	<?php
						$question='';
						$answer='';
                    	if(isset($faq)){
							foreach($faq as $f){
								if($f->l_id==$l->l_id){
									$question=$f->question;
									$answer=$f->answer;
								}
							}
						}
					?>
                	<div class="tab-pane <?php if($l->is_default==1) echo 'active'?>" id="<?php echo 'tab'.$l->l_id?>">
                    	<div class="control-group">
                            <label class="control-label">Questioin</label>
                            <div class="controls">
                                <textarea name="txt_question[]" class="editor" id="txt_question_<?php echo $l->l_id?>"><?php echo $question?></textarea>
                            </div>
                        </div> 
                        <div class="control-group">
                            <label class="control-label">Answer</label>
                            <div class="controls">
                                <textarea name="txt_answer[]" class="editor" id="txt_answer_<?php echo $l->l_id?>"><?php echo $answer?></textarea>
                            </div>
                        </div>
                        <div style="display:none">
                            <input type="hidden" value="<?php echo $l->l_id?>" name="hd_lid[]"/>
                        </div>
                    </div>
                <?php endforeach?>
              </div>
         </div>
         <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
          </div> 
         <?php echo form_close(); ?>  
    </div>
</div>