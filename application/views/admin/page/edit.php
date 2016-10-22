<div class="box">
	<div class="box-header well"></div>
        <div class="box-content">
              <?php echo form_open(base_url('admin/page/save/'),array('id'=>'frm_page','name'=>'frm_page','class'=>'form-horizontal')); ?>
                  <div class="control-group">
                    <label class="control-label"><b>Название</b></label>
                    <div class="controls">
                      <input type="hidden" value="<?php if(isset($page['pg_id'])) echo $page['pg_id']?>" name="hd_pg_id"/>
                      <input class="focused" id="txt_pg_name" name="txt_pg_name" type="text" value="<?php if(isset($page['pg_name'])) echo $page['pg_name']?>"/>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label"><b>Аддресс</b></label>
                    <div class="controls">
                      <?php echo base_url()?><input class="focused" id="txt_pg_url" name="txt_pg_url" type="text" value="<?php if(isset($page['pg_url'])) echo $page['pg_url']?>"/>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label"><b>Опубликован</b></label>
                    <div class="controls">
                      <?php $checked='';
					  	if(isset($page['is_public'])){
							if($page['is_public']==1){
								$checked='checked="checked"';
							}
						}
					  ?>
                       <input type="checkbox" name="chk_public" <?php echo $checked?>/>
                    </div>
                  </div>
                  <div class="control-group">
                      <label class="control-label"><b>Содержимое текста</b></label>
                  	 <div class="controls">
                      <div class="control-group">
                        <div>
                              <textarea name="txt_content" id="content_1" class="editor"><?php if(isset($page['pg_content']))echo $page['pg_content']?></textarea>
                        </div>
                      </div>
                     </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label"><b>Javascript</b></label>
                    <div class="controls">
                        <p><b>&lt;script type="text/javascript"&gt;</b></p>
                    	<textarea cols="200" rows="20" name="txt_pg_script" class="span12" placeholder="Enter javascript" style=" display: table-cell;">
                        	<?php if(isset($page['pg_script'])) echo htmlspecialchars_decode(trim($page['pg_script']))?>
                        </textarea>
						<p><b>&lt;/script&gt;</b></p>
                    </div>
                  </div>
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Сохронить</button>
                    <a href="<?php echo base_url('admin/page')?>" class="btn"><i class="cus-cancel"></i> Отмена</a>
                  </div>
               <?php echo form_close(); ?>  
        </div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_page").validate({
            rules: {	
                txt_pg_name: "required"
            }
        }); 
		$('#txt_pg_name').keyup(function(e) {
            $('.sp_pg_name').html($(this).val());
        });

		$('#txt_pg_name').keydown(function(e){
			if(e.which==32){ 
				e.preventDefault();
			}
		});
    });
</script>