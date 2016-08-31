<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="container-fluid">
		<fieldset class="scheduler-border span6"><legend class="scheduler-border">Change options</legend>
            <?php echo form_open(base_url('admin/masteracc/saveCompany/'. ((isset($company->company_id))? $company->company_id : '')), array('id'=>'frm_edit_companies','name'=>'frm_edit_companies','class'=>'form-horizontal')); ?>
          	<input id="company_id" name="company_id" type="hidden" value="<?php echo set_value('company_id', ((isset($company->company_id))? $company->company_id: ''))?>" />
              <div class="control-group">
                <label class="control-label">PICs assigned type</label>
                <div class="controls ">
                  	<input id="picassigned_type_0" name="picassigned_type" type="radio" value="0" <?php echo set_radio('picassigned_type', '0', (0 == $company->picassigned_type)?true:false); ?> />
                  		<label class="radio-label" for="picassigned_type_0">Common for all subscribers</label><br />
                  	<input id="picassigned_type_1" name="picassigned_type" type="radio" value="1" <?php echo set_radio('picassigned_type', '1', (1 == $company->picassigned_type)?true:false); ?> /> 
                  		<label class="radio-label" for="picassigned_type_1">Assigned to subscriber</label>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="branch_id">Smart Care Status</label>
                <div class="controls pic-holder">
                	<?php echo form_dropdown('sc_status', Masteracc_model::$sc_status, $default = $company->sc_status);?>
                	<?php echo form_error('sc_status');?>
                	
                	
                </div>
              </div>
              <div class="form-actions">
              	<p style="font-size: 10px; font-style: italic;color: #7e7e7e;">* Re-login "<?php echo $company->company_name;?>" staff after company changed!</p>
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/masteracc/companies/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
			<?php echo form_close(); ?>
        </fieldset>
        <!-- Company details -->
		<fieldset class="scheduler-border span6 pull-right"><legend class="scheduler-border">Company details</legend>
			<table class="table table-hover table-borderless tb-ticket-details">
		        <tbody>
		          <tr><td style="width: 120px;">Company name</td><td><?php echo $company->company_name;?></td></tr>
		          <tr><td>Type of client</td><td><?php echo $company->client_type;?></td></tr>
		          <tr><td>Industry</td><td><?php echo $company->industry;?></td></tr>
		          <tr><td>Contact name</td><td><?php echo $company->contact_name;?></td></tr>
		          <tr><td>Phone</td><td><?php echo $company->phone;?></td></tr>
		          <tr><td>E-mail</td><td><?php echo $company->email;?></td></tr>
		          <tr><td>Address</td><td><?php echo $company->address;?></td></tr>
		          <tr><td>Licence ID</td><td><?php echo $company->licence_id;?></td></tr>
		          <tr><td>Pattern ID</td><td><?php echo $company->pattern_id;?></td></tr>
		          <tr><td>Passport #</td><td><?php echo $company->passport;?></td></tr>
		          <tr><td>ID card #</td><td><?php echo $company->card_id;?></td></tr>
		          <tr><td>Contract date</td><td><?php echo $company->contract_date;?></td></tr>
		          <tr><td>Imported date</td><td><?php echo $company->created_at;?></td></tr>
		          <tr><td>Updated date</td><td><?php echo $company->updated_at;?></td></tr>
		          <tr><td>Status</td><td><?php echo Masteracc_model::$sc_status[$company->sc_status];?></td></tr>
		          
		        </tbody>
			</table>
		</fieldset> 
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_edit_companies").validate({
            rules: {	
            	company_name: {required: true, minlength:2, maxlength:100},
//             	company_id:{required: true,minlength:1, maxlength:4}
            }
        }); 
    });
</script>
<style>
 label.radio-label {display: inline-block;padding-left:5px;}
 #frm_edit_companies input {margin-top:0;}
 .control-group em {color:red;}
</style>