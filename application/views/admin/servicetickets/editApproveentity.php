<div class="box no-margin">
	<div class="box-header well" data-original-title="<?php echo $per_page['page_name'];?>">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/servicetickets/editApproveentity/'.((isset($loadedSubject['SubjectID']))?$loadedSubject['SubjectID']:''));?>" class="btn" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/servicetickets/')?>" class="btn" style="width:50px;"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="container-fluid">
		<div class="span7">
		<fieldset class="scheduler-border"><legend class="scheduler-border">Editing area</legend>
		    <div class="box-content">
				<?php echo form_open('', array('id'=>'frm_edit_subject','name'=>'frm_edit_subject','class'=>'form-horizontal')); ?>
				<input name="approveentity[ID]" autocomplete="off" type="hidden" value="<?php echo set_value('approveentity[ID]', ((isset($loadedItem['ID']))? $loadedItem['ID'] : '')); ?>" />
				<?php echo form_error('approveentity[ID]'); ?>
				<div class="control-group">
					<label class="control-label" for="SubjectID">Service Type</label>
					<div class="controls">
					<?php if (isset($loadedItem['SubjectID'])) {
						$chosedSubject = $loadedItem['SubjectID'];
					} elseif (!isset($loadedItem['SubjectID']) && isset($loadedSubject['SubjectID'])) {
						$chosedSubject = $loadedSubject['SubjectID'];
					} else {
						$chosedSubject = '';
					}?>
			        	<?php echo form_dropdown('approveentity[SubjectID]', $subjects, $chosedSubject, 'id="SubjectID"');?>
			        	<?php echo form_error('approveentity[SubjectID]'); ?> 
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="role_id">Role <span class="required">*</span></label>
					<div class="controls">
						<?php echo form_dropdown('approveentity[role_id]', $roles, $default = ((isset($loadedItem['role_id']))? $loadedItem['role_id'] : ''), 'id="role_id"');?>
				        <?php echo form_error('approveentity[role_id]'); ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="approve_order">Approve order <span class="required">*</span></label>
					<div class="controls">
						<?php echo form_dropdown('approveentity[approve_order]', range(0, 30), $default = ((isset($loadedItem['approve_order']))? $loadedItem['approve_order'] : ''), 'id="approve_order"');?>
				        <?php echo form_error('approveentity[approve_order]'); ?>
					</div>
				</div>
				<div class="form-actions">
					<div class="stayonpage"><input type="checkbox" name="stayonpage" id="stayonpage" data-handle-width="35" data-size="mini" checked="checked"><label for="stayonpage">Stay on page after save</label></div>
					<button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
					<a href="<?php echo $navBackLink;?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
				</div>
				<?php echo form_close(); ?>
			</div>
			
		</fieldset>
	</div>
	<div class="span5">
		<fieldset class="scheduler-border"><legend class="scheduler-border">Service approve entities</legend>
			<?php if ($relItems) :?>
			<table class="table table-striped table-bordered">
	          <thead>
	              <tr>
	                  <th>ID</th>
	                  <th>Role</th>
	                  <th>Order</th>
	                  <th>Actions</th>
	              </tr>
	          </thead>   
	          <tbody>
	          	<?php foreach($relItems as $item) :?>
	            <tr>
	                <td style="width: 20px;"><?php echo $item->ID;?></td>
	                <td style="width: 170px;"><?php echo $item->role_name;?></td>
	                <td class="center"><?php echo $item->approve_order;?></td>
	                <td class="center" style="width: 50px;text-align: center;">
	                    <?php if($per_page['per_update']==1){?>
	                    <a class="" href="<?php echo base_url("admin/servicetickets/editApproveentity/{$item->SubjectID}/{$item->ID}")?>"><i class="cus-page-white-edit"></i></a>
	                    <?php }else{?>
	                     <a class="disabled" href=""><i class="cus-page-white-edit"></i></a>
	                    <?php }?>
	                    <?php if($per_page['per_delete']==1){?>
	                    <a class="" href="<?php echo base_url('admin/servicetickets/deleteApproveentity/'.$item->ID)?>" onclick="return confirm('You are sure to delete this record')"><i class="cus-delete"></i></a>
	                    <?php } else {?>
	                     <a class="disabled" href="#"><i class="cus-delete"></i></a>
	                    <?php }?>
	                </td>
	            </tr>
	            <?php endforeach;?>
	            </tbody>
	         </table>
	         <?php else:?>
	         <p>Approve entities are not set!</p>
	         <?php endif;?>
		</fieldset>
	</div>
</div>
</div>

<!-- End form -->
<script type="text/javascript">
//<!--
$(document).ready(function () {
	$("[name='stayonpage']").bootstrapSwitch();
});
//-->
</script>
<style>
.form-actions .stayonpage {margin-bottom:10px;}
.form-actions label {display: inline-block;margin-left: 10px;}
</style>