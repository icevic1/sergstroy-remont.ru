<?php echo form_open('', array('id' => 'EditTicket', 'name'=>'EditTicket', 'class'=>'form-horizontal row-border')); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
	        <div class="panel-heading clearfix"><i class="icomoon icon-ticket"></i> <?php echo $ACTION_TITLE;?></div>
	        <div class="panel-body">
				<input type="hidden" name="serial_id" value="<?php echo set_value("serial_id", ((isset($choosed_serial_id))? $choosed_serial_id:'')); ?>" />
				<div class="form-group">
					<label class="col-md-4 control-label" for="serial_number">SIM Serial Number <span class="required">*</span></label>
					<div class="col-md-4">
			        	<input id="serial_number" class="form-control" name="serial_number" type="text" value="<?php echo set_value("serial_number", ((isset($choosed_serial_number))? $choosed_serial_number:'')); ?>" />
			        	<?php echo form_error('serial_number'); ?> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label" for="serial_number">SIM Type <span class="required">*</span></label>
					<div class="col-md-4">
			        	<div class="radio radio-success radio-inline">
				        	<input id="sim_type_0" class="" name="sim_type" type="radio" value="0"<?php echo ((isset($choosed_sim_type) && $choosed_sim_type == '0')? ' checked="checked"':'')?> onchange="$('#sim_phone_number').addClass('hidden');" />
				        	<label for="sim_type_0">Blank SIM</label>
						</div>
						<div class="radio radio-success radio-inline">
				        	<input id="sim_type_1" class="" name="sim_type" type="radio" value="1"<?php echo ((isset($choosed_sim_type) && $choosed_sim_type == '1')? ' checked="checked"':'')?> onchange="$('#sim_phone_number').removeClass('hidden');" />
				        	<label for="sim_type_1">SIM Kit</label>
						</div>
			        	<?php echo form_error('sim_type'); ?> 
					</div>
				</div>
				<?php //var_dump($choosed_sim_type);	?>
				<div id="sim_phone_number" class="form-group<?php echo ((isset($choosed_sim_type) && $choosed_sim_type == '0')? ' hidden':'')?>">
					<label class="col-md-4 control-label" for="phone_number">Assigned Phone Number</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('phone_number', $phone_numbers, $default = ((isset($choosed_phone_number))? $choosed_phone_number : ''), 'id="phone_number" class="form-control input-sm"');?>
			        	<?php echo form_error('phone_number'); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label" for="dealer_id">Dealer Name</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer_id', $dealers, $default = ((isset($choosed_dealer_id))? $choosed_dealer_id : ''), 'id="dealer_id" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer_id'); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label" for="sales_id">Sales ID</label>
					<div class="col-md-4">
						<?php echo form_dropdown('sales_id', $sallers_list, $default = ((isset($choosed_sales_id))? $choosed_sales_id : ''),'id="sales_id" class="form-control"'); ?>
			        	<?php echo form_error('sales_id'); ?> 
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-md-4 control-label" for="serial_status_id">Status</label>
					<div class="col-md-4">
			        	<?php echo form_dropdown('serial_status_id', $serial_statuses, $default = ((isset($choosed_status_id))? $choosed_status_id : ''),'id="serial_status_id" class="form-control"'); ?>
			        	<?php echo form_error('serial_status_id'); ?> 
					</div>
				</div>
		
		</div><!-- end panel-body -->
		<div class="panel-footer text-right">
			<a class="btn btn-default" href="<?php echo site_url('inventory/')?>"><i class="icomoon icon-cancel-circle"></i> Cancel</a>
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> OK</button>
		</div>
	</div>
</div>
<!-- end col-md-6 ticket inputs -->

</div>
<?php echo form_close(); ?>
<!-- End form -->
<style>
.required {color:red;}
</style>