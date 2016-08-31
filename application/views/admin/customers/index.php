<div class="box no-margin">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo site_url('admin/customer/save_customer/')?>" class="btn-link"><i class="cus-add"></i> New Customer</a>
            <?php }else{?>
            <a class="btn-link disabled" href="" onclick="return false;"><i class="cus-add"></i> New Customer</a>
            <?php }?>
        </div>
    </div>
    <div class="box-content">
    	<!--<?php //echo form_open(base_url('admin/customer/'), array('id'=>'frm_filter','name'=>'frm_filter','class'=>'form-horizontal')); ?>
		<input id="searchtext" name="searchtext" type="search" value="<?php echo set_value('searchtext', ''); ?>" placeholder="Customer Name or CustID" />
        <button type="submit" class="btn"><i class="icon-search"></i> Search</button>
    	<?php //echo form_close(); ?> -->
    	
        <table class="table gradient-thead compact table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th style="width: 20px;">CustId</th>
                  <th>Corporation Name</th>
                  <th>Corporation Type</th>
                  <th>Certificate No.</th>
                  <th>Certificate Type</th>
                  <th>Register Date</th>
                  <th>TIN</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          <?php if (isset($customers)) {?>
          	<?php foreach($customers as $item) :?>
            <tr>
                <td><?php echo $item['WebCustId'];?></td>
                <td><?php echo $item['CustName'];?></td>
                <td><?php echo ((isset(Customer_model::$CustTypes[$item['CustType']]))? Customer_model::$CustTypes[$item['CustType']] : '');?></td>
                <td><?php echo $item['CertificateNumId'];?></td>
                <td><?php echo (isset(Customer_model::$CertificateTypes[$item['CertificateTypeId']])? Customer_model::$CertificateTypes[$item['CertificateTypeId']]: '');?></td>
                <td><?php echo $item['RegisterDate'];?></td>
                <td><?php echo $item['TIN'];?></td>
                <td style="text-align:center;">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/customer/save_customer/'.$item['WebCustId'])?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn-link disabled" href="" onclick="return false;"><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/customer/delete_customer/'.$item['WebCustId'])?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }else{?>
                    <a class="btn-link disabled" href="" onclick="return false;"><i class="cus-delete"></i>Delete</a>
                    <?php }?>
                </td>
            </tr>
            <?php endforeach;?>
            <?php } //end if table?>
            </tbody>
         </table>
      </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
		

        $(document).on('change', "select[name='company_id']", function(el) {
			var company_id = $(this).val();
			if (company_id == 0) {
				$("select[name='branch_id']").find("option:gt(0)").remove();
				$("select[name='branch_id']").prop("disabled", true);
				return false;
			}
			$.ajax({
			    url:"<?php echo site_url('admin/masteracc/ajax_get_company_branches/')?>",  
			    type: "get",
			    data: {"company_id":company_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
			    success:function(data) {
			    	$("select[name='branch_id']").parent().html(data); 
			    }
			  });
			
			return true;
		});

    });
</script>
<style>
.no-margin {margin:0;}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}
legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width:auto;
	padding:0 10px;
	border-bottom:none;
	margin-bottom: 0;
}
.default-filter select, .second-filter select {width: auto;}
.default-filter label.checkbox {margin-left:20px;cursor:pointer;}
.second-filter {display: none;}
.second-filter span.select-company, .second-filter span.select-branch {margin-left:20px;}
</style>