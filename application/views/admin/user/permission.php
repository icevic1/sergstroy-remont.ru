<?php echo form_open(base_url('admin/user/save_per_page/'),array('id'=>'frm_permission','name'=>'frm_permission','class'=>'form-horizontal')); ?>
<div class="box">
    <div class="box-header well">
        <h2><i class="icon-user"></i> Permission</h2>
        <div class="box-icon">
            <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
        </div>
    </div>
    <div class="box-content">
    	<input type="hidden" name="hd_user_name" value="<?php if(isset($user_name)) echo $user_name?>"/>
        <table class="table table-striped table-bordered">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Page</th>
                  <th style="width:20px">Show</th>
                  <th style="width:20px">New</th>
                  <th style="width:20px">Update</th>
                  <th style="width:20px">Delete</th>
              </tr>
          </thead>   
          <tbody>
          	<?php $i=1;?>
          	<?php foreach($perms as $per):?>
            <tr>
                <td style="width: 20px;"><?php echo $i++?></td>
                <td class="center"><input type="hidden" value="<?php echo $per->page_id?>" name="hd_pg_id[]"/><?php echo $per->page_name?></td>
                <td>
                    <input type="checkbox" name="<?php echo 'chkshow_'.$per->page_id?>" <?php echo $per->per_show?>/>
                </td>
                <td>
                	<input type="checkbox" name="<?php echo 'chksave_'.$per->page_id?>" <?php echo $per->per_save?>/>
                </td>
                <td>
                	<input type="checkbox" name="<?php echo 'chkupdate_'.$per->page_id?>" <?php echo $per->per_update?>/>
                </td>
                <td>
                	<input type="checkbox" name="<?php echo 'chkdelete_'.$per->page_id?>" <?php echo $per->per_delete?>/>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
</div>
<?php echo form_close(); ?>    