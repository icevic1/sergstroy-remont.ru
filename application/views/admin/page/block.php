<div class="box">
	<div class="box-header well"></div>
    <div class="box-content">
    	<?php echo form_open(base_url('admin/page/save_block/'),array('id'=>'frm_page','name'=>'frm_page')); ?>
        <input type="hidden" value="<?php echo $pg_id?>" name="pg_id"/>
    	 <table class="table table-striped table-bordered bootstrap-datatable datatable">
              <thead>
                  <tr>
                      <th width="20"><input type="checkbox" id="chk_all"/></th>
                      <th>Block</th>
                      <th>Remark</th>
                  </tr>
              </thead>   
              <tbody>
              	<?php $i=0;foreach($blocks as $b){?>
                	<tr>
                    	<td><input type="checkbox" value="<?php echo $b->block_id?>" class="block" name="block_id[]" <?php echo $b->chk?>/></td>
                        <td><?php echo $b->block_name?></td>
                        <td><?php echo $b->remark?></td>
                    </tr>
                <?php }?>
              </tbody>
          </table>
          <button type="submit" class="btn btn-primary"><i class="cus-disk"></i> Save</button>
          <?php echo form_close(); ?>  
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
       $('#chk_all').change(function(e) {
        	$('.block').attr('checked', this.checked);
    	});
    });
</script>