<div class="box">
	<div class="box-header well">
    	<?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
        <table class="table table-striped table-bordered bootstrap-datatable data-message">
        	<thead><th>ID</th><th>Message</th><th>Type</th><th>Icon</th><th>On Page</th><th width="80">Actions</th></thead>
             <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
            <tbody>
            <?php foreach($message as $msg){?>
                    <tr>
                        <td><?php echo $msg->msg_id?></td>
                        <td><?php echo htmlspecialchars_decode($msg->msg)?></td>
                        <td><?php echo $msg->msg_type?></td>
                        <td><?php echo $msg->msg_icon?></td>
                        <td><?php echo $msg->in_pages?></td>
                        <td>
                        	 <?php if($per_page['per_update']==1){?>
                        	<a class="btn" href="<?php echo base_url('admin/message/edit/'.$msg->msg_id)?>">
                                <i class="cus-page-white-edit"></i>Edit                                            
                            </a>
                            <?php }else{?>
                            	<a class="btn disabled" href="#"><i class="cus-page-white-edit"></i>Edit</a>
                            <?php }?>
                        </td>
                    </tr>
            <?php  }?>
            </tbody>
        </table>  
    </div>
</div>
<style type="text/css">
	.select_filter{
		width:100px !important;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(e) {
       $('.data-message').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
			"sPaginationType": "bootstrap",
			"bStateSave": true
		}).columnFilter({
			aoColumns: [ { type: "text" }, { type: "text" }, { type: "select", values: [ 'Popup', 'Normal','Tool Tip'] },null, { type: "select", values: [ 'Main Page', 'Login Page'] },null]
		});
    });
</script>