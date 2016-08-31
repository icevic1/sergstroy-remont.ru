<?php echo form_open_multipart('', array('id' => 'edit_applicantion_form', 'name'=>'edit_applicantion_form', 'class'=>'form-horizontal row-border')); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
	        <div class="panel-heading clearfix">
	        	<i class="icomoon icon-profile"></i> <?php echo $ACTION_TITLE;?>
	        	<div class="pull-right"><a id="toggle_disable_form" class="btn btn-danger btn-xs" href=""><i class="glyphicon glyphicon-edit"></i> Edit</a></div>
				<div class="clearfix"></div>
	        </div>
	        <div class="panel-body">
				<input type="hidden" name="applicant[applicant_id]" value="<?php echo set_value("applicant[dealer_id]", ((isset($choosed['applicant_id']))? $choosed['applicant_id']:'')); ?>" />
				
				<div class="form-group margin-y-0">
					<h4 class="col-md-4 control-label text-muted">SIM Information:</h4>
				</div>
				<hr class="featurette-divider" style="margin-top:0;" />
				
				<div class="form-group<?php echo (form_error('applicant[serial_number]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="serial_number">Serial Number</label>
					<div class="col-md-4">
			        	<?php echo form_dropdown('applicant[serial_number]', $serial_numbers, $default = ((isset($choosed['serial_number']))? $choosed['serial_number'] : ''),'id="serial_number" class="form-control"'); ?>
			        	<?php echo form_error('applicant[serial_number]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[phone_number]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="phone_number">Phone Number</label>
					<div class="col-md-4">
			        	<?php echo form_dropdown('applicant[phone_number]', $phone_numbers, $default = ((isset($choosed['phone_number']))? $choosed['phone_number'] : ''),'id="phone_number" class="form-control"'); ?>
			        	<?php echo form_error('applicant[phone_number]'); ?> 
					</div>
				</div>
				
				<div class="form-group<?php echo (form_error('applicant[dealer_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="dealer_id">Dealer</label>
					<div class="col-md-4">
			        	<?php echo form_dropdown('applicant[dealer_id]', $dealers, $default = ((isset($choosed['dealer_id']))? $choosed['dealer_id'] : ''),'id="dealer_id" class="form-control"'); ?>
			        	<?php echo form_error('applicant[dealer_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[sales_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="sales_id">Sales ID</label>
					<div class="col-md-4">
			        	<input id="sales_id" class="form-control" name="applicant[sales_id]" type="text" value="<?php echo set_value("applicant[sales_id]", ((isset($choosed['sales_id']))? $choosed['sales_id']:'')); ?>" />
			        	<?php echo form_error('applicant[sales_id]'); ?> 
					</div>
				</div>
				
				<div class="form-group margin-y-0">
					<h4 class="col-md-4 control-label text-muted">Subscriber Information:</h4>
				</div>
				<hr class="featurette-divider" style="margin-top:0;" />
				
				<div class="form-group<?php echo (form_error('applicant[gender]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="gender">Salutation</label>
					<div class="col-md-4">
						<div class="radio radio-success radio-inline">
				        	<input id="gender_type_1" class="form-control-" name="applicant[gender]" type="radio" value="mr"<?php echo ((isset($choosed['gender']) && $choosed['gender'] == 'mr')? ' checked="checked"':'')?> />
				        	<label for="gender_type_1">Mr</label>
						</div>
						<div class="radio radio-success radio-inline">
				        	<input id="gender_type_2" class="form-control-" name="applicant[gender]" type="radio" value="mrs"<?php echo ((isset($choosed['gender']) && $choosed['gender'] == 'mrs')? ' checked="checked"':'')?> />
				        	<label for="gender_type_2">Mrs</label>
						</div>
						<div class="radio radio-success radio-inline">
				        	<input id="gender_type_3" class="form-control-" name="applicant[gender]" type="radio" value="ms"<?php echo ((isset($choosed['gender']) && $choosed['gender'] == 'ms')? ' checked="checked"':'')?> />
				        	<label for="gender_type_3">Ms</label>
						</div>
			        	<?php echo form_error('applicant[gender]'); ?> 
					</div>
				</div>
				
				<div class="form-group<?php echo (form_error('applicant[subscriber_name]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="subscriber_name">Subscriber Name (individual only)</label>
					<div class="col-md-4">
			        	<input id="subscriber_name" class="form-control" name="applicant[subscriber_name]" type="text" value="<?php echo set_value("applicant[subscriber_name]", ((isset($choosed['subscriber_name']))? $choosed['subscriber_name']:'')); ?>" />
			        	<?php echo form_error('applicant[subscriber_name]'); ?> 
					</div>
				</div>
				
                <div class="form-group<?php echo (form_error('applicant[date_of_birth]') ? ' has-error':'')?>">
                    <label for="date_of_birth" class="control-label col-md-4">Date of Birth</label>
                    <div class="col-md-4">
	                    <div class="input-group date datepicker" id="date_of_birth">
							<input type="text" name="applicant[date_of_birth]" class="form-control input-sm" value="<?php echo set_value("applicant[date_of_birth]", ((isset($choosed['date_of_birth']))? $choosed['date_of_birth']:'')); ?>" placeholder="YYYY-MM-DD" maxlength="10" />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
						<?php echo form_error('applicant[date_of_birth]'); ?>
					</div>
                </div>
				
				<div class="form-group<?php echo (form_error('applicant[subscriber_company]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="subscriber_company">Name of Company Subscriber</label>
					<div class="col-md-4">
			        	<input id="subscriber_company" class="form-control" name="applicant[subscriber_company]" type="text" value="<?php echo set_value("applicant[subscriber_company]", ((isset($choosed['subscriber_company']))? $choosed['subscriber_company']:'')); ?>" />
			        	<?php echo form_error('applicant[subscriber_company]'); ?> 
					</div>
				</div>
				
				<div class="form-group<?php echo (form_error('applicant[contact_name]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="contact_name">Contact Name (if legal entity only)</label>
					<div class="col-md-4">
			        	<input id="contact_name" class="form-control" name="applicant[contact_name]" type="text" value="<?php echo set_value("applicant[contact_name]", ((isset($choosed['contact_name']))? $choosed['contact_name']:'')); ?>" />
			        	<?php echo form_error('applicant[contact_name]'); ?> 
					</div>
				</div>
				
				<div class="form-group<?php echo (form_error('applicant[contact_number]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="contact_number">Contact Phone Number</label>
					<div class="col-md-4">
			        	<input id="contact_number" class="form-control" name="applicant[contact_number]" type="text" value="<?php echo set_value("applicant[contact_number]", ((isset($choosed['contact_number']))? $choosed['contact_number']:'')); ?>" />
			        	<?php echo form_error('applicant[contact_number]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[fax_number]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="fax_number">Fax Number</label>
					<div class="col-md-4">
			        	<input id="contact_number" class="form-control" name="applicant[fax_number]" type="text" value="<?php echo set_value("applicant[fax_number]", ((isset($choosed['fax_number']))? $choosed['fax_number']:'')); ?>" />
			        	<?php echo form_error('applicant[fax_number]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[email]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="email">Contact Email</label>
					<div class="col-md-4">
						<div class="input-group">
				        	<input id="email" class="form-control" name="applicant[email]" type="text" value="<?php echo set_value("applicant[email]", ((isset($choosed['email']))? $choosed['email']:'')); ?>" />
				        	<span class="input-group-addon">@</span>
			        	</div>
			        	<?php echo form_error('applicant[email]'); ?> 
					</div>
				</div>
				
				<div class="form-group margin-y-0">
					<h4 class="col-md-4 control-label text-muted">Address:</h4>
				</div>
				<hr class="featurette-divider" style="margin-top:0;" />
				
				<div class="form-group<?php echo (form_error('applicant[house_number]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="house_number">House No.</label>
					<div class="col-md-4">
			        	<input id="house_number" class="form-control" name="applicant[house_number]" type="text" value="<?php echo set_value("applicant[house_number]", ((isset($choosed['house_number']))? $choosed['house_number']:'')); ?>" />
			        	<?php echo form_error('applicant[house_number]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[street]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="street">Street</label>
					<div class="col-md-4">
			        	<input id="street" class="form-control" name="applicant[street]" type="text" value="<?php echo set_value("applicant[street]", ((isset($choosed['street']))? $choosed['street']:'')); ?>" />
			        	<?php echo form_error('applicant[street]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[commune_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="commune_id">Sangkat/Commune</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('applicant[commune_id]', $communes, $default = ((isset($choosed['commune_id']))? $choosed['commune_id'] : ''), 'id="commune_id" class="form-control input-sm"');?>
			        	<?php echo form_error('applicant[commune_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[district_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="district_id">Khan/District</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('applicant[district_id]', $districts, $default = ((isset($choosed['district_id']))? $choosed['district_id'] : ''), 'id="district_id" class="form-control input-sm"');?>
			        	<?php echo form_error('applicant[district_id]'); ?>
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[city_id]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="city_id">City/Province</label>
					<div class="col-md-4">
				        <?php echo form_dropdown('applicant[city_id]', $cities, $default = ((isset($choosed['city_id']))? $choosed['city_id'] : ''), 'id="city_id" class="form-control input-sm"');?>
			        	<?php echo form_error('applicant[city_id]'); ?>
					</div>
				</div>
				
				<div class="form-group margin-y-0">
					<h4 class="col-md-4 control-label text-muted">Attached documents (copy):</h4>
				</div>
				<hr class="featurette-divider" style="margin-top:0;" />
				
				<div class="form-group<?php echo (form_error('applicant[owner]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label">Subscriber Type</label>
					<div class="col-md-4">
			        	<div class="checkbox checkbox-success checkbox-inline">
				        	<input id="subscriber_type_1" class="form-control-" name="applicant[subscriber_type]" type="radio" value="individual"<?php echo ((isset($choosed['subscriber_type']) && $choosed['subscriber_type'] == 'individual')? ' checked="checked"':'')?> />
				        	<label for="subscriber_type_1">Individual</label>
						</div>
						<div class="checkbox checkbox-success checkbox-inline">
				        	<input id="subscriber_type_2" class="form-control-" name="applicant[subscriber_type]" type="radio" value="corporate"<?php echo ((isset($choosed['subscriber_type']) && $choosed['subscriber_type'] == 'corporate')? ' checked="checked"':'')?> />
				        	<label for="subscriber_type_2">Corporate</label>
						</div>
			        	<?php echo form_error('applicant[subscriber_type]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[is_foreigner]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label">Resident Status</label>
					<div class="col-md-4">
			        	<div class="checkbox checkbox-success checkbox-inline">
				        	<input onchange="$('.cambodian').removeClass('hidden');$('.foreigner').addClass('hidden');" id="is_foreigner_1" class="" name="applicant[is_foreigner]" type="radio" value="0"<?php echo ((isset($choosed['is_foreigner']) && $choosed['is_foreigner'] == '0')? ' checked="checked"':'')?> />
				        	<label for="is_foreigner_1">CAMBODIAN</label>
						</div>
						<div class="checkbox checkbox-success checkbox-inline">
				        	<input onchange="$('.cambodian').addClass('hidden');$('.foreigner').removeClass('hidden');" id="is_foreigner_2" class="" name="applicant[is_foreigner]" type="radio" value="1"<?php echo ((isset($choosed['is_foreigner']) && $choosed['is_foreigner'] == '1')? ' checked="checked"':'')?> />
				        	<label for="is_foreigner_2">FOREIGNER</label>
						</div>
			        	<?php echo form_error('applicant[is_foreigner]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[document_type]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label">Document Type</label>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-6">
								<div class="cambodian ">
									<div class="radio radio-success">
							        	<input id="document_type_1" class="form-control" name="applicant[document_type]" type="radio" value="1"<?php echo ((isset($choosed['document_type']) && $choosed['document_type'] == '1')? ' checked="checked"':'')?> />
							        	<label for="document_type_1">Cambodian ID Card</label>
									</div>
									<div class="radio radio-success">
							        	<input id="document_type_2" class="form-control" name="applicant[document_type]" type="radio" value="2"<?php echo ((isset($choosed['document_type']) && $choosed['document_type'] == '2')? ' checked="checked"':'')?> />
							        	<label for="document_type_2">Government ID Card</label>
									</div>
									<div class="radio radio-success">
							        	<input id="document_type_3" class="form-control" name="applicant[document_type]" type="radio" value="3"<?php echo ((isset($choosed['document_type']) && $choosed['document_type'] == '3')? ' checked="checked"':'')?> />
							        	<label for="document_type_3">Valid Passport</label>
									</div>
									<div class="radio radio-success">
							        	<input id="document_type_4" class="form-control" name="applicant[document_type]" type="radio" value="4"<?php echo ((isset($choosed['document_type']) && $choosed['document_type'] == '4')? ' checked="checked"':'')?> />
							        	<label for="document_type_4">Monk ID Card</label>
									</div>
								</div>
								<div class="foreigner hidden">
									<div class="radio radio-success">
							        	<input id="document_type_5" class="form-control" name="applicant[document_type]" type="radio" value="5"<?php echo ((isset($choosed['document_type']) && $choosed['document_type'] == '5')? ' checked="checked"':'')?> />
							        	<label for="document_type_5">Passport with valid visa</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio radio-success">
						        	<input id="document_type_6" class="form-control" name="applicant[document_type]" type="radio" value="6"<?php echo ((isset($choosed['document_type']) && $choosed['document_type'] == '6')? ' checked="checked"':'')?> />
						        	<label for="document_type_6">Registration Certificate</label>
								</div>
							</div>
						</div>
					<?php echo form_error('applicant[document_type]'); ?> 
					</div>
					
				</div>
				
				<div class="form-group<?php echo (form_error('applicant[document_number]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="document_number">Document Number</label>
					<div class="col-md-4">
			        	<input id="document_number" class="form-control" name="applicant[document_number]" type="text" value="<?php echo set_value("applicant[document_number]", ((isset($choosed['document_number']))? $choosed['document_number']:'')); ?>" />
			        	<?php echo form_error('applicant[document_number]'); ?> 
					</div>
				</div>
				
				
				<div class="form-group<?php echo (form_error('applicant[document_issue_date]') ? ' has-error':'')?>">
                    <label for="document_issue_date" class="control-label col-md-4">Date of Issue</label>
                    <div class="col-md-4">
	                    <div class="input-group date datepicker" id="document_issue_date">
							<input type="text" name="applicant[document_issue_date]" class="form-control input-sm" value="<?php echo set_value("applicant[document_issue_date]", ((isset($choosed['document_issue_date']))? $choosed['document_issue_date']:'')); ?>" placeholder="YYYY-MM-DD" maxlength="10" />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
						<?php echo form_error('applicant[document_issue_date]'); ?>
					</div>
                </div>
				
				<div class="form-group<?php echo (form_error('applicant[photo_1]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="photo_1">Photo 1</label>
					<div class="col-md-4">
						<?php 
							if (isset($choosed['photo_1']) && $choosed['photo_1']) {
								$img_src = base_url("assets/upload/{$choosed['photo_1']}");
								echo '<a href="'.$img_src.'" target="_blank"><img style="width:100%;" src="'.$img_src.'" alt="phont 2" /></a>';
							}
							?>
			        	<input id="photo_1" class="form-control-" name="applicant[photo_1]" type="file" value="<?php echo set_value("applicant[photo_1]", ((isset($choosed['photo_1']))? $choosed['photo_1']:'')); ?>" />
			        	<?php echo form_error('applicant[photo_1]'); ?> 
					</div>
				</div>
				<div class="form-group<?php echo (form_error('applicant[photo_2]') ? ' has-error':'')?>">
					<label class="col-md-4 control-label" for="photo_2">Photo 2</label>
					<div class="col-md-4">
						<?php 
							if (isset($choosed['photo_2']) && $choosed['photo_2']) {
								$img_src = base_url("assets/upload/{$choosed['photo_2']}");
								echo '<a href="'.$img_src.'" target="_blank"><img style="width:100%;" src="'.$img_src.'" alt="phont 2" /></a>';
							}
							?>
			        	<input id="photo_2" class="form-control-" name="applicant[photo_2]" type="file" value="<?php echo set_value("applicant[photo_2]", ((isset($choosed['photo_2']))? $choosed['photo_2']:'')); ?>" />
			        	<?php echo form_error('applicant[photo_2]'); ?> 
					</div>
				</div>
				
				<?php if (isset($choosed['applicant_id']) && $choosed['applicant_id']) {?>
				<div class="form-group margin-y-0">
					<h4 class="col-md-4 control-label text-muted">Change status:</h4>
				</div>
				<hr class="featurette-divider" style="margin-top:0;" />
				
				<div class="form-group">
					<label class="col-md-4 control-label">Status</label>
					<div class="col-md-4">
			        	<?php if ($choosed['applicant_status_id'] == '1') {?>
						<div class="text-warning"><i class="icon32 icon-gray icon-clock"></i> <?php echo $choosed['applicant_status_name'];?></div>
						<?php } elseif ($choosed['applicant_status_id'] == '2') { ?>
						<div class="text-success"><i class="fa fa-check"></i> <?php echo $choosed['applicant_status_name'];?></div>
						<?php } elseif ($choosed['applicant_status_id'] == '3') { ?>
						<div class="text-danger"><i class="fa fa-check"></i> <?php echo $choosed['applicant_status_name'];?></div>
						<?php } elseif ($choosed['applicant_status_id'] == '4') { ?>
						<div class="text-info"><i class="fa fa-check"></i> <?php echo $choosed['applicant_status_name'];?></div>
						<?php }?>
					</div>
				</div>
				<?php if ($choosed['reject_time']) {?>
				<div class="form-group">
					<label class="col-md-4 control-label">Change date</label>
					<div class="col-md-4">
						<div class="text-info"><?php echo date('d-M-Y H:i:s', strtotime($choosed['reject_time']));?></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Change owner</label>
					<div class="col-md-4">
						<div class="text-info"><?php echo $choosed['reject_user'];?></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Reject Comment</label>
					<div class="col-md-4">
						<div class="text-info"><?php echo $choosed['reason'];?></div>
					</div>
				</div>
				<?php } else {?>
				<div class="form-group">
					<label class="col-md-4 control-label">Change date</label>
					<div class="col-md-4">
						<div class="text-info"><?php echo date('d-M-Y H:i:s', strtotime($choosed['changed_at']));?></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">Change owner</label>
					<div class="col-md-4">
						<div class="text-info"><?php echo $choosed['change_user'];?></div>
					</div>
				</div>
				<?php }} ?>
		</div><!-- end panel-body -->
		<div class="panel-footer text-right">
			<a class="btn btn-default" href="<?php echo site_url('home/')?>"><i class="icomoon icon-cancel-circle"></i> Cancel</a>
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Submit</button>
		</div>
	</div>
</div>
<!-- end col-md-6 ticket inputs -->
</div>
<?php echo form_close(); ?>
<!-- End form -->
<script type="text/javascript">
$(document).ready(function(e) {
	$('form#edit_applicantion_form').find(':input,:button,select,button').prop("disabled", true); 
	$('a#toggle_disable_form').addClass("is-disabled");
	
	$('a#toggle_disable_form').on('click', function(e) {
		e.preventDefault();
		if ($(this).hasClass("is-disabled")) {
			$('form#edit_applicantion_form').find(':input,:button,select,button').prop("disabled", false);
			$(this).removeClass("is-disabled");
		} else {
			$('form#edit_applicantion_form').find(':input,:button,select,button').prop("disabled", true);
			$(this).addClass("is-disabled");
		}
	});
	
}); //end ready()
</script>
<style>
.required {color:red;}
</style>