<div class="box no-margin">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/servicetickets/editSubject/')?>" class="btn" style="width:50px;"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width:50px"><i class="cus-add"></i> Add</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/servicetickets/')?>" class="btn" style="width:50px;"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <div class="box-content">
        <table class="table gradient-thead compact table-striped table-bordered bootstrap-datatable datatable-subjects">
          <thead>
              <tr>
                  <th>ID</th>
                  <th class="no-wrap">Service Type</th>
                  <th class="no-wrap select-filter">Request Type</th>
                  <th class="no-wrap select-filter">Service Group</th>
                  <th class="no-wrap">Notifications</th>
                  <th class="no-wrap select-filter">Allow to close</th>
                  <th class="no-wrap" title="Priority/Severity">Defaults</th>
                  <th class="no-wrap select-filter">Kpi</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($subjects as $item) :?>
            <tr>
                <td style="width: 20px;"><?php echo $item->SubjectID;?></td>
                <td class="center"><?php echo $item->SubjectName;?></td>
                <td class="center"><?php echo $item->TypeName;?></td>
                <td class="center"><?php echo $item->GroupName;?></td>
                <td class="center wrap-normal"><?php echo $item->notifications_to;?></td>
                
                <td class="center"><?php echo $item->close_role_name;?></td>
                <td class="center"><?php echo $item->PriorityName . '/'.$item->SeverityName;?></td>
                <td class="center"><?php echo $item->KpiTimeHours;?></td>
                <td align="center">
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/servicetickets/editSubject/'.$item->SubjectID);?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <?php }else{?>
                     <a class="btn-link disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn-link" href="<?php echo base_url('admin/servicetickets/deleteSubject/'.$item->SubjectID)?>" onclick="return confirm('You are sure to delete this record')">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }else{?>
                     <a class="btn-link disabled" href="#">
                        <i class="cus-delete"></i>Delete
                    </a>
                    <?php }?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
         </table>
      </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('.datatable-subjects').DataTable({
    	"bProcessing": true,
		"bSort": false, /*disable sorting by all fields*/
		"sPaginationType": "bootstrap",
// 		"bStateSave": true,
//         "columnDefs": [
//             { "visible": false, "targets": 2 }
//         ],
//         "order": [[ 2, 'asc' ]],
        "displayLength": 50,
        initComplete: function () {
            this.api().columns('.select-filter').every( function () {
                var column = this;
//                 console.log($(column.header()).text());
                var select = $('<select><option value="">-'+$(column.header()).text()+'-</option></select>')
                    .appendTo( $(column.header(':before')).empty() )
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    if (d)
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
            } );
        }

    });

} );
</script>
<style>
table.dataTable.compact.datatable-subjects thead th {padding: 2px;text-align: center;}
table.datatable-subjects th select {width: inherit;height: 18px;padding: 0 2px;min-width: 47px;font-size:11px;}
table.datatable-subjects th.select-filter {text-align: left;}</style>