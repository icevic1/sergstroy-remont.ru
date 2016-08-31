<div class="box">
	<div class="box-header well">
    	 <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    	<p><?php if( !empty($this->form_validation->error_array())) foreach ($this->form_validation->error_array() as $error) echo '<br /><font color="red"> - '.$error. '</font>'; ?>
    	<?php 
		if($this->session->flashdata('msg')){
			echo "<script>alert('".$this->session->flashdata('msg')."')</script>";
		}
		?></p>
          <?php echo form_open(base_url('admin/customeracl/resourceEdit/'. ((isset($customer_resource->resource_id))? $customer_resource->resource_id : '')), array('id'=>'frm_editresource','name'=>'frm_editresource','class'=>'form-horizontal')); ?>
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="txt_user_name">Resource name</label>
                <div class="controls">
                  <input id="resource_id" name="resource_id" type="hidden" value="<?php if(isset($customer_resource->resource_id)) echo $customer_resource->resource_id?>">
                  <input class="focused" id="resource_name" name="resource_name" type="text" value="<?php if(isset($customer_resource->resource_name)) echo $customer_resource->resource_name?>">
                </div>
              </div>
               <div class="control-group">
                <label class="control-label" for="txt_pwd">Resource description</label>
                <div class="controls">
                  <textarea id="resource_description" name="resource_description"><?php echo set_value('resource_description', ((isset($customer_resource->resource_description))? $customer_resource->resource_description:'')); ?></textarea>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="parent_id">Parent</label>
                <div class="controls">
                  <?php echo form_dropdown('parent_id', $resourceOptions, $default = ((isset($customer_resource->parent_id))? $customer_resource->parent_id : '0'));?>
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
                <a href="<?php echo site_url('admin/customeracl/resources')?>" class="btn"><i class="cus-cancel"></i> Cancel</a>
              </div>
            </fieldset>
           <?php echo form_close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm_user").validate({
            rules: {	
            	resource_name: {required: true, minlength:2, maxlength:100},
//             	resource_id:{required: true,minlength:1, maxlength:4}
            }
        }); 
    });
</script>