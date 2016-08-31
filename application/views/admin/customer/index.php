<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/customer/saveCustomer/')?>" class="btn" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/masteracc')?>" class="btn" style="width:50pxp;"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <?php 
		if($this->session->flashdata('msg')){
			echo "<script>alert('".$this->session->flashdata('msg')."')</script>";
		}
	?>
    <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
              	  <th>No</th>
                  <th>Customer Number</th>
              </tr>
          </thead>   
          <tbody>
          	<?php $i=1;?>
          	<?php foreach($customers as $c):?>
            <tr>
                <td style="width: 20px;"><?php echo $i++?></td>
                <td><?php echo $c->cust_number?></td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
</div>