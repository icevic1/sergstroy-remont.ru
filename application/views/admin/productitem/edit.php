<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
          <?php echo form_open(base_url('admin/productitem/save'),array('id'=>'frm_edit','name'=>'frm_edit','class'=>'form-horizontal')); ?>
            <fieldset>
              <div class="control-group">
                <label class="control-label">Acc Ordinal</label>
                <div class="controls">
                  <input id="h_acc_ordinal" name="h_acc_ordinal" type="hidden" value="<?php if(isset($item['acc_ordinal'])) echo $item['acc_ordinal']?>">
                  <input id="acc_ordinal" name="acc_ordinal" type="text" value="<?php if(isset($item['acc_ordinal'])) echo $item['acc_ordinal']?>">
                </div>
              </div>
               <div class="control-group">
                <label class="control-label">Product Name</label>
                <div class="controls">
                  <input id="pp_name" name="pp_name" type="text" value="<?php if(isset($item['pp_name'])) echo $item['pp_name']?>">
                </div>
              </div>
               <div class="control-group">
                <label class="control-label">Group</label>
                <div class="controls">
                  <?php  
					$group_id=isset($item['group_id'])?$item['group_id']:'';
					echo form_dropdown('group_id',$groups,$group_id,'');
					?>
                </div>
              </div>
               <div class="control-group">
                <label class="control-label">Is Roaming</label>
                <div class="controls">
                   <input type="checkbox" name="is_roaming" <?php if(isset($item['roaming_status'])) if($item['roaming_status']=='true') echo 'checked="checked"'?>/>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/productitem')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit").validate({
            rules: {	
                acc_ordinal: "required",
                pp_name:"required"
            }
        }); 
    });
</script>