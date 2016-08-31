<?php echo form_open('', array('id' => 'EditTicket', 'name'=>'EditTicket', 'class'=>'form-horizontal row-border')); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
	        <div class="panel-heading clearfix"><i class="icomoon icon-ticket"></i> <?php echo $ACTION_TITLE;?></div>
	        <div class="panel-body">
				<input type="hidden" name="dealer[dealer_id]" value="<?php echo set_value("dealer[dealer_id]", ((isset($choosed['dealer_id']))? $choosed['dealer_id']:'')); ?>" />
				<div class="form-group<?php echo (form_error('dealer[dealer_name]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="dealer_name">Dealer Name <span class="required">*</span></label>
					<div class="col-md-4">
			        	<input id="dealer_name" class="form-control" name="dealer[dealer_name]" type="text" value="<?php echo set_value("dealer[dealer_name]", ((isset($choosed['dealer_name']))? $choosed['dealer_name']:'')); ?>" />
			        	<?php echo form_error('dealer[dealer_name]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[salesID]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="salesID">Sales ID</label>
					<div class="col-md-4">
			        	<input id="salesID" class="form-control" name="dealer[salesID]" type="text" value="<?php echo set_value("dealer[salesID]", ((isset($choosed['salesID']))? $choosed['salesID']:'')); ?>" />
			        	<?php echo form_error('dealer[salesID]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[dealer_type_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="dealer_type_id">Dealer Type</label>
					<div class="col-md-4">
			        	<?php echo form_dropdown('dealer[dealer_type_id]', $dealer_types, $default = ((isset($choosed['dealer_type_id']))? $choosed['dealer_type_id'] : ''),'id="dealer_type_id" size="4" class="form-control"'); ?>
			        	<?php echo form_error('dealer[dealer_type_id]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[dealer_kind_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="dealer_kind_id">Dealer Category</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer[dealer_kind_id]', $dealer_kinds, $default = ((isset($choosed['dealer_kind_id']))? $choosed['dealer_kind_id'] : ''), 'id="dealer_kind_id" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer[dealer_kind_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[status]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="status">Status</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer[status]', $dealer_statuses, $default = ((isset($choosed['status']))? $choosed['status'] : ''), 'id="status" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer[status]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[location_name]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="location_name">Location</label>
					<div class="col-md-4">
			        	<input id="location_name" class="form-control" name="dealer[location_name]" type="text" value="<?php echo set_value("dealer[location_name]", ((isset($choosed['location_name']))? $choosed['location_name']:'')); ?>" />
			        	<?php echo form_error('dealer[location_name]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[zone_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="zone_id">Zone</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer[zone_id]', $zones, $default = ((isset($choosed['zone_id']))? $choosed['zone_id'] : ''), 'id="zone_id" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer[zone_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[reg_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="reg_id">Region</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer[reg_id]', $regions, $default = ((isset($choosed['reg_id']))? $choosed['reg_id'] : ''), 'id="reg_id" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer[reg_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[city_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="city_id">City</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer[city_id]', $cities, $default = ((isset($choosed['city_id']))? $choosed['city_id'] : ''), 'id="city_id" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer[city_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[district_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="district_id">District</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer[district_id]', $districts, $default = ((isset($choosed['district_id']))? $choosed['district_id'] : ''), 'id="district_id" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer[district_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[commune_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="commune_id">Commune</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('dealer[commune_id]', $communes, $default = ((isset($choosed['commune_id']))? $choosed['commune_id'] : ''), 'id="commune_id" class="form-control input-sm"');?>
			        	<?php echo form_error('dealer[commune_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[house_no]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="house_no">House Number</label>
					<div class="col-md-4">
			        	<input id="house_no" class="form-control" name="dealer[house_no]" type="text" value="<?php echo set_value("dealer[house_no]", ((isset($choosed['house_no']))? $choosed['house_no']:'')); ?>" />
			        	<?php echo form_error('dealer[house_no]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[street]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="street">Street</label>
					<div class="col-md-4">
			        	<input id="street" class="form-control" name="dealer[street]" type="text" value="<?php echo set_value("dealer[street]", ((isset($choosed['street']))? $choosed['street']:'')); ?>" />
			        	<?php echo form_error('dealer[street]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[village]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="village">Village</label>
					<div class="col-md-4">
			        	<input id="village" class="form-control" name="dealer[village]" type="text" value="<?php echo set_value("dealer[village]", ((isset($choosed['village']))? $choosed['village']:'')); ?>" />
			        	<?php echo form_error('dealer[village]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[owner]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="owner">Owner</label>
					<div class="col-md-4">
			        	<input id="owner" class="form-control" name="dealer[owner]" type="text" value="<?php echo set_value("dealer[owner]", ((isset($choosed['owner']))? $choosed['owner']:'')); ?>" />
			        	<?php echo form_error('dealer[owner]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[phone_1]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="phone_1">Phone Number</label>
					<div class="col-md-4">
			        	<input id="phone_1" class="form-control" name="dealer[phone_1]" type="text" value="<?php echo set_value("pdealer[hone_1]", ((isset($choosed['phone_1']))? $choosed['phone_1']:'')); ?>" />
			        	<?php echo form_error('phone_1'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[phone_2]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="phone_2">Second Phone Number</label>
					<div class="col-md-4">
			        	<input id="phone_2" class="form-control" name="dealer[phone_2]" type="text" value="<?php echo set_value("dealer[phone_2]", ((isset($choosed['phone_2']))? $choosed['phone_2']:'')); ?>" />
			        	<?php echo form_error('dealer[phone_2]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('dealer[email]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="email">Email</label>
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon">@</span>
				        	<input id="email" class="form-control" name="dealer[email]" type="text" value="<?php echo set_value("dealer[email]", ((isset($choosed['email']))? $choosed['email']:'')); ?>" />
			        	</div>
			        	<?php echo form_error('dealer[email]'); ?> 
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