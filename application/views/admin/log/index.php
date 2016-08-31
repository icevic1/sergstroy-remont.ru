<div class="box">
    <div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
    </div>
    <div class="box-content">
    <div style="padding-bottom:15px">
    	From
    	<span class="input-append datepicker" data-date="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
            <input type="text" name="from_date" id="from_date" value="<?php echo date('Y-m-d')?>" style="width:100px" />
            <span class="add-on"><i class="icon-th"></i></span>
        </span>
        To
    	<span class="input-append datepicker" data-date="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
            <input type="text" name="to_date" id="to_date" value="<?php echo date('Y-m-d')?>" style="width:100px" />
            <span class="add-on"><i class="icon-th"></i></span>
        </span>
        <input type="button" class="btn btn-primary filter" value="Filter"/>
    </div>
   
     <table class="table table-striped table-bordered bootstrap-datatable data">
      <thead><tr><th width="300">Log</th><th>IP</th><th>Date</th></tr></thead>   
      <tbody></tbody>
     </table>
    </div>
</div>
<style type="text/css">
	table.data {
	  margin: 0 auto;
	  width: 100%;
	  clear: both;
	  border-collapse: collapse;
	  table-layout: fixed;        
	  word-wrap:break-word;        
	}
</style>
<script type="text/javascript">
	$(document).on("click",".cls-detail", function(e) {
		if($(this).next().css('display')=='none'){
			$(this).next().css('display','block');
		}else{
			$(this).next().css('display','none');
		}
		e.stopPropagation();
		return false;
	});
	$(document).ready(function(e) {
		/*var oTable=$('.data').dataTable({
			"bJQueryUI": true,
			"bServerSide": true,
			"sAjaxSource": '<?php echo base_url('admin/log/source')?>',
			"bStateSave": true,
			"fnServerParams": function ( aoData ) {
				aoData.push( { "name": "csrf_school_name", "value": $.cookie('csrf_cookie_name') } );
			}
		});*/
		/*$('.data').dataTable({
			"bProcessing": true,
			"bJQueryUI": true,
			"bServerSide": true,
			"sAjaxSource": '<?php echo base_url('admin/log/source')?>'
		});*/
		$('.filter').click(function(e) {
            $('.data').dataTable().fnDraw(false);
        });
	});
</script>