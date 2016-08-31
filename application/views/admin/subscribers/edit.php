<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="container-fluid">
		<fieldset class="scheduler-border span6"><legend class="scheduler-border">Change options</legend>
        	<?php echo form_open('', array('id'=>'frm_edit_subs','name'=>'frm_edit_subs','class'=>'form-horizontal')); ?>
        	<input type="hidden" name="subs_id" value="<?php echo set_value('subs_id', ((isset($subscriber->subs_id))? $subscriber->subs_id:'')); ?>" />
              	<div class="control-group">
	                <label class="control-label" for="company_id">Company <em>*</em></label>
	                <div class="controls">
	                  <?php echo form_dropdown('company_id', $select_companies, $default = ((isset($choosedCompany_id))? $choosedCompany_id : 0));?>
	                  <?php echo form_error('company_id');?>
	                </div>
              	</div>
              	<div class="control-group">
	                <label class="control-label" for="branch_id">Branch</label>
	                <div class="controls branch-holder">
	                	<?php echo form_dropdown('branch_id', $select_branches, $default = ((isset($choosedBranch_id))? $choosedBranch_id : 0));?>
	                	<?php echo form_error('branch_id');?>
	                </div>
              	</div>
              	<div class="control-group">
	                <label class="control-label" for="dep_id">Department</label>
	                <div class="controls branch-holder">
	                	<?php echo form_dropdown('dep_id', $select_departments, $default = ((isset($choosedDep_id))? $choosedDep_id : 0));?>
	                	<?php echo form_error('dep_id');?>
	                </div>
              	</div>
              	<div class="control-group staff-asociation-block<?php echo ((!isset($companyInfo->picassigned_type) || (isset($companyInfo->picassigned_type) && $companyInfo->picassigned_type < 1))?' hide':'' )?>">
	                <label class="control-label" for="branch_id">Person in charge</label>
	                <div class="controls pic-holder">
	                	<?php echo form_dropdown('staff_id', $select_staff, $default = ((isset($choosedStaff_id))? $choosedStaff_id : 0));?>
	                	<?php echo form_error('staff_id');?>
	                </div>
              	</div>
              	<div class="control-group">
	                <label class="control-label" for="branch_id">Smart Care Status</label>
	                <div class="controls pic-holder">
	                	<?php echo form_dropdown('sc_status', Subscriber_mod::$sc_status, $default = ((isset($choosedStatus))? $choosedStatus : 1));?>
	                	<?php echo form_error('sc_status');?>
	                </div>
              	</div>
              	<!-- div class="control-group">
                	<label class="control-label" for="password">Password</label>
                	<div class="controls">
                  		<input id="password" name="password" type="text" value="<?php //echo set_value('password', ''); ?>" />
                	</div>
              	</div -->
              	<div class="form-actions">
	                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
	                <a href="<?php echo site_url('admin/subscribers/')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              	</div>
              	<?php echo form_close(); ?> 
            </fieldset>
            <!-- Subscriber details -->
			<fieldset class="scheduler-border span6 pull-right"><legend class="scheduler-border">Subscriber details</legend>
				<table class="table table-hover table-borderless tb-ticket-details">
		          <tr><td style="width: 150px;">Subscriber ID</td><td><?php echo $subscriber->subs_id;?></td></tr>
		          <tr><td>Subscriber name</td><td><?php echo $subscriber->firstname. ' ' . $subscriber->lastname;?></td></tr>
		          <tr><td>Company</td><td><?php echo $subscriber->company_name;?></td></tr>
		          <tr><td>Branch</td><td><?php echo $subscriber->branch_name;?></td></tr>
		          <tr><td>Person in charge</td>
		          	<td><?php 
		          	if ($subscriber->staff_id) {
		          		echo $subscriber->staff_name; 
		          	} elseif (isset($companyPicsArray)) {
		          		echo '<u>All company PIC</u>: ' . implode(', ', $companyPicsArray);
		          	} else {
		          		echo 'PICs not yet set!';
		          	}?></td></tr>
		          <tr><td>Phone</td><td><?php echo $subscriber->phone;?></td></tr>
		          <tr><td>E-mail</td><td><?php echo $subscriber->email;?></td></tr>
		          <tr><td>Imported date</td><td><?php echo $subscriber->imported_at;?></td></tr>
		          <tr><td>Updated date</td><td><?php echo $subscriber->updated_at;?></td></tr>
		          <tr><td>Who changed</td><td><?php echo $subscriber->who_changed;?></td></tr>
		          <tr><td>Smart Care status</td><td><?php echo Subscriber_mod::$sc_status[$subscriber->sc_status];?></td></tr>
			</table>
		</fieldset>
    </div>
</div>

<?php //var_dump(isset($subscriber->branch_id),$subscriber->branch_id);?>
<script type="text/javascript">

	$(document).ready(function() {
		$.validator.addMethod("valueNotEquals", function(value, element, arg){
			return arg != value;
	 	}, "Value must not equal arg.");
		 
        $(document).on('change', "select[name='company_id']", function(el) {
        	var company_id = $(this).val();

        	getResponsibleStaff(company_id);

        	$("#frm_edit_subs select[name='dep_id']").find("option:gt(0)").remove();
        	
        	if (company_id == 0) {
        		$("#frm_edit_subs select[name='branch_id']").find("option:gt(0)").remove().attr("readonly", "readonly");
        		$("#frm_edit_subs select[name='staff_id']").find("option:gt(0)").remove().attr("readonly", "readonly");
        		return false;
        	}
        	$.ajax({
        	    url:"<?php echo site_url('admin/masteracc/ajax_get_company_branches/')?>",  
        	    type: "get",
        	    data: {"company_id":company_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
        	    success:function(data) {
        	    	$("#frm_edit_subs select[name='branch_id']").parent().html(data); 
        	    }
        	});
        	
			return true;
		});

        $(document).on('change', "select[name='branch_id']", function(el) {
        	var company_id = $("#frm_edit_subs select[name='company_id']").val();
        	var branch_id = $(this).val();
        	
        	
        	if (branch_id == 0) {
        		$("#frm_edit_subs select[name='dep_id']").find("option:gt(0)").remove();
        		return false;
        	}
        	$.ajax({
        	    url:"<?php echo site_url('admin/masteracc/ajax_get_branch_departments/')?>",  
        	    type: "get",
        	    data: {"company_id":company_id,"branch_id":branch_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
        	    success:function(data) {
        	    	$("#frm_edit_subs select[name='dep_id']").parent().html(data); 
        	    }
        	});
			return true;
		});

        /*$(document).on('change', "select[name='branch_id']", function(el) {
        	var company_id = $("#frm_edit_subs select[name='company_id']").val();
        	var branch_id = $(this).val();
        	
        	
        	if (branch_id == 0) {
        		$("#frm_edit_subs select[name='staff_id']").find("option:gt(0)").remove();
        		$("#frm_edit_subs select[name='staff_id']").attr("readonly", "readonly");
        		return false;
        	}
        	getResponsibleStaff(company_id, branch_id);
			return true;
		});*/

        $("#frm_edit_subs").validate({
        	errorElement: 'span',
            errorClass: 'error',
            rules: {	
            	company_id:{valueNotEquals: '0'},
//             	branch_id: {valueNotEquals: '0'},
//             	password: {required: true}
            },
            messages: {
            	company_id: { valueNotEquals: "Please select an item!" },
//             	branch_id: { valueNotEquals: "Please select an item!" }
			}  
        });


    });

	function getResponsibleStaff (company_id, branch_id) {
		if (company_id == 0) return false;
		branch_id = branch_id || '';
		
		$.ajax({
		    url:"<?php echo site_url('admin/staff/ajax_get_staff/')?>",  
		    type: "post",
		    data: {"company_id":company_id, 'branch_id': branch_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
		    success:function(responseData) {
				$("#frm_edit_subs select[name='staff_id']").find("option:gt(0)").remove();
				
			    /**
			    * PICs assigned type common
			    */
			    if(responseData['companyInfo'].picassigned_type == '0') {
				    $('.staff-asociation-block').hide();
				} else {
					if(responseData['companyPics'].length > 0) {

						$.each(responseData['companyPics'], function(key, value) {   
							$("#frm_edit_subs select[name='staff_id']")
						         .append($("<option></option>")
						         .attr("value", value.staff_id)
						         .text(value.sfirstname + " " + value.slastname + ' ('+ value.role_name +')')); 
						});
					}

					$('.staff-asociation-block').show();
				}
					
		    }
		});
		return true;
	}
   
</script>
<style>
.default-filter select, .default-filter input {width: auto;margin-bottom: 0;}
label.control-label em, span.error {color:red;font-style: italic;}
span.error {display:block; font-size: 11px;}
</style>