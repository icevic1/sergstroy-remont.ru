<div class="box no-margin">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/servicetickets/editApproveentity/')?>" class="btn" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/servicetickets/')?>" class="btn" style="width:50px;"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content container-fluid"><p>&nbsp;</p>
        <table class="display compact cell-border stripe datatable-approves">  <!-- table table-striped table-bordered bootstrap-datatable -->
          <thead>
              <tr>
                  <th data-align="center">ID</th>
                  <th>Role/Position</th>
                  <th>Service Type</th>
                  <th>Approve order</th>
                  <th>Created at</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($allItems as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->ID;?></td>
                
                <td data-align="center"><?php echo $item->role_name;?></td><td class="center"><?php echo $item->SubjectName;?></td>
                <td class="center"><?php echo $item->approve_order;?></td>
                <td class="center"><?php echo date('Y-m-d H:i', strtotime($item->CreatedAt));?></td>
                <td class="center" style="width: 150px;">
                	<div class="btn-group">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn" href="<?php echo base_url("admin/servicetickets/editApproveentity/{$item->SubjectID}/{$item->ID}");?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn" href="<?php echo base_url('admin/servicetickets/deleteApproveentity/'.$item->ID)?>" onclick="return confirm('You are sure to delete this record')"><i class="cus-delete"></i>Delete</a>
                    <?php } else {?>
                     <a class="btn disabled" href="#"><i class="cus-delete"></i>Delete</a>
                    <?php }?>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('.datatable-approves').DataTable({
//     	"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
		"bProcessing": true,
		"bSort": false, /*disable sorting by all fields*/
		"sPaginationType": "bootstrap",
		"bStateSave": true,
        "columnDefs": [
            { "visible": false, "targets": 2 }
        ],
        "order": [[ 2, 'asc' ]],
        "displayLength": 50,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5"><b>Service Type</b>: '+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    } );
} );
</script>
<style>
table.dataTable.no-footer {border-bottom: 1px solid #ddd;}
table.dataTable.compact thead th, table.dataTable.compact thead td {border-bottom: 1px solid #ddd;padding:4px;}
thead tr {background-color: #666666;color: #efefef;}
thead tr th {border: 1px solid #ddd;}
tr.group,
tr.group:hover {
    background-color: #ddd !important;
}
</style>