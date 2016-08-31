<div class="box">
	<div class="box-header well"></div>
        <div class="box-content">
              <?php echo form_open(base_url('admin/page/save/'),array('id'=>'frm_page','name'=>'frm_page','class'=>'form-horizontal')); ?>
                  <div class="control-group">
                    <label class="control-label"><b>Page</b></label>
                    <div class="controls">
                      <input type="hidden" value="<?php if(isset($page['pg_id'])) echo $page['pg_id']?>" name="hd_pg_id"/>
                      <input class="focused" id="txt_pg_name" name="txt_pg_name" type="text" value="<?php if(isset($page['pg_name'])) echo $page['pg_name']?>"/>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label"><b>URL</b></label>
                    <div class="controls">
                      <?php echo base_url()?><input class="focused" id="txt_pg_url" name="txt_pg_url" type="text" value="<?php if(isset($page['pg_url'])) echo $page['pg_url']?>"/>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label"><b>Public</b></label>
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
                  	 <div class="controls">
                     	<ul class="nav nav-tabs">
                          <?php $i=0;foreach($details as $l){
							  $cls=$i==0?'class="active"':'';
							  $i++;
							  ?>
                          <li <?php echo $cls?>>
                            <a href="#<?php echo 'ln'.$l->l_id?>" data-toggle="tab" id="tab_<?php echo $i?>"><?php echo $l->l_name.' ('.$l->l_short_name.')'?></a>
                          </li>
                          <?php }?>
                        </ul>
                        <div class="tab-content">
                        	 <?php $i=0;foreach($details as $l){
								  $cls=$i==0?'active':'';
							  	  $i++;
							  ?>
                            <div class="tab-pane <?php echo $cls?>" id="<?php echo 'ln'.$l->l_id?>">
                            	  <div class="control-group">
                                    <div>
                                    	  <input type="hidden" name="hd_lid[]" value="<?php echo $l->l_id?>"/>
                                     	  <textarea name="txt_content[]" id="content_<?php echo $i?>" class="editor"><?php if($l->pg_content)echo $l->pg_content?></textarea>
                                    </div>
                              	  </div>
                            </div>
                            <?php }?>
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
                    <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                    <a href="<?php echo base_url('admin/page')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
                  </div>
               <?php echo form_close(); ?>  
        </div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_page").validate({
            rules: {	
                txt_pg_name: "required",
				cbo_pg_type:"required"
            }
        }); 
		$('#txt_pg_name').keyup(function(e) {
            $('.sp_pg_name').html($(this).val());
        });
		$('#cbo_pg_type').change(function(e) {
             $('.sp_pg_type').html($(this).val());
        });
		$('#txt_pg_name').keydown(function(e){
			if(e.which==32){ 
				e.preventDefault();
			}
		});
    });
</script>