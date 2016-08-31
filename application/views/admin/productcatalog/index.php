<div class="box no-margin">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/productcatalog/edit_offer/')?>" class="btn-link" style="width:50px;"><i class="cus-add"></i> Add offer</a>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/productcatalog/')?>" class="btn-link" style="width:50px;"><i class="cus-arrow-undo"></i> Back</a>
        </div>
    </div>
    <ul id="group_tabs" class="nav nav-tabs small-tabs">
		<li class="active"><a href="">Offers list</a></li>
		<li><a href="<?php echo base_url("admin/productcatalog/groups/");?>">Offer Groups</a></li>
		<li><a href="<?php echo base_url("admin/productcatalog/types/");?>">Offer Types</a></li>
	</ul>
    
    <div class="tab-content box-content">
		<div class="tab-pane active">
        <table class="table gradient-thead compact table-striped table-bordered bootstrap-datatable datatable-subjects">
          <thead>
              <tr>
                  <th>ID</th>
                  <th class="no-wrap">Offering Name</th>
                  <th class="no-wrap">OfferingID</th>
                  <th class="no-wrap">Offering Code</th>
                  <th class="no-wrap">Offer Cost</th>
                  <th class="no-wrap">Credit</th>
                  <th class="no-wrap select-filter">Offer Type</th>
                  <th class="no-wrap select-filter">Offer Group</th>
                  <th>Actions</th>
              </tr>
          </thead>   
          <tbody>
          	<?php foreach($offer_list as $item) {?>
            <tr>
                <td style="width: 20px;"><?php echo $item['web_offer_id'];?></td>
                <td class="center"><?php echo $item['web_name'];?></td>
                <td class="center"><?php echo $item['offering_id'];?></td>
                <td class="center"><?php echo $item['offering_code'];?></td>
                <td class="center"><?php echo $item['offer_cost'];?></td>
                <td class="center"><?php echo $item['offer_credit'];?></td>
                <td class="center"><?php echo $item['type_name'];?></td>
                <td class="center"><?php echo $item['group_name'];?></td>
                <td align="center">
                    <a class="btn-link" href="<?php echo base_url('admin/productcatalog/edit_offer/'.$item['web_offer_id']);?>">
                        <i class="cus-page-white-edit"></i>Edit                                            
                    </a>
                    <a class="btn-link" href="<?php echo base_url('admin/productcatalog/delete_offer/'.$item['web_offer_id'])?>" onclick="return confirm('You are sure to delete this record')">
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
//         "columnDefs": [
//             { "visible": false, "targets": 2 }
//         ],
//         "order": [[ 2, 'asc' ]],
        "displayLength": 25,
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