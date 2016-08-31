<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/masteracc/saveCompany/')?>" class="btn" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/masteracc')?>" class="btn" style="width:50px"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Company name</th>
                  <th>About company</th>
                  <th>Company contacts</th>
                  <th>Legal information</th>
                  <th>Imported</th>
                  <th>Changed</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($companies as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->company_id;?></td>
                <td style="width: 170px;"><?php echo $item->company_name;?></td>
                <td><div class="item-row"><label>Type of client</label><?php echo $item->client_type;?></div>
                	<div class="item-row"><label>Industry</label><?php echo $item->industry;?></div>
                	<div class="item-row"><label>Branches</label><?php echo $this->Masteracc_model->countCompanyBranches($item->company_id);?></div>
                	<div class="item-row"><label>PICs assigned type</label><?php echo Masteracc_model::$picassigned_type[$item->picassigned_type];?></div>
                	<div class="item-row"><label>Smart Care staus</label><?php echo Masteracc_model::$sc_status[$item->sc_status];?></div>
                </td>
                <td><div class="item-row"><label>Contact name</label><?php echo $item->contact_name;?></div>
                	<div class="item-row"><label>Phone</label><?php echo $item->phone;?></div>
                	<div class="item-row"><label>E-mail</label><?php echo $item->email;?></div>
                	<div class="item-row"><label>Address</label><?php echo $item->address;?></div>
                </td>
                <td><div class="item-row"><label>Licence ID</label><?php echo $item->licence_id;?></div>
                	<div class="item-row"><label>Pattern ID</label><?php echo $item->pattern_id;?></div>
                	<div class="item-row"><label>Passport</label><?php echo $item->passport;?></div>
                	<div class="item-row"><label>ID card</label><?php echo $item->card_id;?></div>
                	<div class="item-row"><label>Contract date</label><?php echo $item->contract_date;?></div>
                </td>
                <td class="center"><?php echo $item->created_at;?></td>
                <td class="center"><?php echo $item->updated_at;?></td>
                <td class="center" style="width: 150px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/masteracc/saveCompany/'.$item->company_id)?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
</div>
<style>
table td div.item-row {margin:0;width: 190px;}
table td div label {font-weight:bolder;padding-right: 5px;}
table td div label::after {content:':';}
</style>