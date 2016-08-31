<style>
	.control-label{
		width:200px !important;
	}
	.controls{
		padding-left:50px !important;
	}
	.controls ul{
		list-style:none;
	}
	.controls ul li{
		padding:5px 0;
	}
	.controls ul li span{
		margin-left:5px;
	}
	.error{
		color:#FF0000;
	}
</style>
<div class="box">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/productcatalog')?>" class="btn" style="width:50px"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
    	 <?php echo form_open('',array('id'=>'frm','name'=>'frm','class'=>'form-horizontal')); ?>
         <input type="hidden" name="pp_id" value="<?php echo set_value('pp[pp_id]', ((isset($loadedItem['pp_id']))? $loadedItem['pp_id'] : '')); ?>" />
         <div class="control-group">
                <label class="control-label"><b>Group</b></label>
                <div class="controls">
					<?php echo form_dropdown('pp[group_id]', $pp_groups, ((isset($loadedItem['group_id']))? $loadedItem['group_id'] : ''), 'id="group_id"');?>
	        		<?php echo form_error('pp[group_id]'); ?>
                </div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>Promotion Name</b></label>
			<div class="controls">
				<input name="pp[web_name]" type="text" placeholder="Price Plan name" value="<?php echo set_value('pp[web_name]', ((isset($loadedItem['web_name']))? $loadedItem['web_name'] : '')); ?>" />
				<?php echo form_error('pp[web_name]'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>Billing PP id</b></label>
			<div class="controls">
				<input name="pp[acc_pp_id]" type="text" placeholder="Huawei PP id" value="<?php echo set_value('pp[acc_pp_id]', ((isset($loadedItem['acc_pp_id']))? $loadedItem['acc_pp_id'] : '')); ?>" />
				<?php echo form_error('pp[acc_pp_id]'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>Network calls</b></label>
			<div class="controls">
				<input name="pp[free_network_calls]" type="text" value="<?php echo set_value('pp[free_network_calls]', ((isset($loadedItem['free_network_calls']))? $loadedItem['free_network_calls'] : '')); ?>" />  min/day
				<?php echo form_error('pp[free_network_calls]'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>CUG calls</b></label>
			<div class="controls">
				<input name="pp[free_cug_calls]" type="text" value="<?php echo set_value('pp[free_cug_calls]', ((isset($loadedItem['free_cug_calls']))? $loadedItem['free_cug_calls'] : '')); ?>" />  min/day
				<?php echo form_error('pp[free_cug_calls]'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>Traffic Data</b></label>
			<div class="controls">
				<input name="pp[free_traffic_data]" type="text" value="<?php echo set_value('pp[free_traffic_data]', ((isset($loadedItem['free_traffic_data']))? $loadedItem['free_traffic_data'] : '')); ?>" /> GB
				<?php echo form_error('pp[free_traffic_data]'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>Included SMS</b></label>
			<div class="controls">
				<input name="pp[free_sms]" type="text" value="<?php echo set_value('pp[free_sms]', ((isset($loadedItem['free_sms']))? $loadedItem['free_sms'] : '')); ?>" />
				<?php echo form_error('pp[free_sms]'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>Cost</b></label>
			<div class="controls">
				<input name="pp[pp_cost]" type="text" value="<?php echo set_value('pp[pp_cost]', ((isset($loadedItem['pp_cost']))? $loadedItem['pp_cost'] : '')); ?>" />
				<?php echo form_error('pp[pp_cost]'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><b>Duration</b></label>
			<div class="controls">
				<input name="pp[duration]" type="text" value="<?php echo set_value('pp[duration]', ((isset($loadedItem['duration']))? $loadedItem['duration'] : '')); ?>" />
				<?php echo form_error('pp[duration]'); ?>
			</div>
		</div>
		<div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
            <a href="<?php echo base_url('admin/productcatalog')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
		</div>
		<?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function(e) {
		$.validator.addMethod("isAfterStartDate", function(value, element) {
			var startDate = $('#activation_date').val();
            return Date.parse(startDate) <= Date.parse(value) || value == "";
    	}, "End date should be after start date");
		$("#frm").validate({
            rules: {	
                group_id: "required",
                acc_ordinal:"required",
				web_name:"required",
				city_id:"required",
				cosid:"required",
				pp_cost:{required: true, digits: true},
				activation_date:"required",
				expiration_date:{ required: true,isAfterStartDate: true},
				version:{required: true, digits: true}
            },
			submitHandler: function(form) {
				if($('#group_id').val()==1){
			  	 	var num=$('[name="buyinbulk[]"]:checked').length;
					if(num>3){
						show_message('maximum 3 for buy in bulk');
					}else{
						form.submit();
					}
				}else{
					form.submit();
				}
			}
        }); 
    });
</script>