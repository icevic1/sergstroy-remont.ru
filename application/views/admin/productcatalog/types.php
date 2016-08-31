<div class="box no-margin">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/productcatalog/edit_type/')?>" class="btn-link" style="width:50px;"><i class="cus-add"></i> Add Offer Type</a>
        </div>
    </div>
    <ul id="group_tabs" class="nav nav-tabs small-tabs">
		<li><a href="<?php echo base_url("admin/productcatalog/");?>">Offers list</a></li>
		<li><a href="<?php echo base_url("admin/productcatalog/groups/");?>">Offer Groups</a></li>
		<li class="active"><a href="">Offer Types</a></li>
	</ul>
    
    <div class="tab-content box-content">
		<div class="tab-pane active">
        <table class="table gradient-thead compact table-striped table-bordered bootstrap-datatable datatable-subjects">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Type Name</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($list as $item) {?>
            <tr>
                <td style="width: 20px;"><?php echo $item['type_id'];?></td>
                <td class="center"><?php echo $item['type_name'];?></td>
                <td align="center" style="width: 50px;">
                    <a class="btn-link" href="<?php echo base_url('admin/productcatalog/edit_type/'.$item['type_id']);?>"> <i class="cus-page-white-edit"></i>Edit</a>
                    <a class="btn-link" href="<?php echo base_url('admin/productcatalog/delete_type/'.$item['type_id'])?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i>Delete
                    </a>
                </td>
            </tr>
            <?php };?>
            </tbody>
         </table>
      </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('.datatable-subjects').DataTable({
    	"bProcessing": true,
		"bSort": false, /*disable sorting by all fields*/
		"sPaginationType": "bootstrap",
// 		"bStateSave": true,
        "displayLength": 25,
    });

});
</script>
<style>
table.dataTable.compact.datatable-subjects thead th {padding: 2px;text-align: center;}
table.datatable-subjects th select {width: inherit;height: 18px;padding: 0 2px;min-width: 47px;font-size:11px;}
table.datatable-subjects th.select-filter {text-align: left;}</style>