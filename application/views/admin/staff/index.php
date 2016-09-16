<div class="box no-margin">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/staff/edit/')?>" class="btn-link"><i class="cus-add"></i> New User</a>
            <?php }else{?>
            <a class="btn disabled" href="" ><i class="cus-add"></i> New User</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
    
        <table class="table gray-head gradient-thead compact table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Имя</th>
                  <th>Емэйл</th>
                  <th>Роль</th>
                  <th>Телефон</th>
                  <th>Адрес</th>
                  <th>Created</th>
                  <th style="width: 100px;">Действия</th>
              </tr>
          </thead>   
          <tbody>
          <?php if (isset($filteredUsers)) {?>
          	<?php foreach($filteredUsers as $item) :?>
            <tr>
                <td><?php echo $item->user_id;?></td>
                <td><?php echo $item->name;?></td>
                <td><?php echo $item->email;?></td>
                <td><?php echo $item->role_name;?></td>
                <td><?php echo $item->mobile_no;?></td>
                <td><?php echo $item->address;?></td>
                <td><?php echo $item->created_at;?></td>
                <td>
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn-link" href="<?php echo site_url('admin/staff/edit/'.$item->user_id)?>"><i class="cus-page-white-edit"></i> Edit</a>
                    <?php }else{?>
                    <a class="btn-link disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn-link" href="<?php echo site_url("admin/staff/delete_user/{$item->user_id}")?>" onclick="return confirm('You are sure to delete this record')"><i class="cus-delete"></i>Delete</a>
                    <?php }else{?>
                    <a class="btn-link disabled" href="#"><i class="cus-delete"></i>Delete</a>
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


});
</script>
<style>

</style>