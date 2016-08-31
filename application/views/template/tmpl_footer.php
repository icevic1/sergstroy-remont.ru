<!-- jQuery UI -->
<script src='<?php echo base_url('public/js/1.10.7.jquery.dataTables.min.js'); ?>'></script>
<script src="<?php echo base_url('public/js/jquery.dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('public/js/jquery.dataTables.columnFilter.js')?>"></script>
<script src="<?php echo base_url('public/js/jquery-ui-1.8.21.custom.min.js'); ?>"></script>
<!-- transition / effect library -->
<script src="<?php echo base_url('public/js/bootstrap-transition.js'); ?>"></script>
<!-- alert enhancer library -->
<script src="<?php echo base_url('public/js/bootstrap-alert.js'); ?>"></script>
<!-- modal / dialog library -->
<script src="<?php echo base_url('public/js/bootstrap-modal.js'); ?>"></script>
<!-- library for creating tabs -->
<script src="<?php echo base_url('public/js/bootstrap-tab.js'); ?>"></script>
<!-- library for advanced tooltip -->
<script src="<?php echo base_url('public/js/bootstrap-tooltip.js'); ?>"></script>
<!-- popover effect library -->
<script src="<?php echo base_url('public/js/bootstrap-popover.js'); ?>"></script>
<!-- button enhancer library -->
<script src="<?php echo base_url('public/js/bootstrap-button.js'); ?>"></script>
<!-- accordion library (optional, not used in demo) -->
<script src="<?php echo base_url('public/js/bootstrap-collapse.js'); ?>"></script>
<!-- autocomplete library -->
<script src="<?php echo base_url('public/js/bootstrap-typeahead.js'); ?>"></script>
<!-- library for cookie management -->
<script src="<?php echo base_url('public/js/jquery.cookie.js'); ?>"></script>
<!-- chart libraries end -->
<!-- select or dropdown enhancer -->
<!-- for iOS style toggle switch -->
<script src="<?php echo base_url('public/js/jquery.iphone.toggle.js'); ?>"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="<?php echo base_url('public/js/jquery.history.js'); ?>"></script>
<!-- application script for Charisma demo -->
<script src="<?php echo base_url('public/js/charisma.js'); ?>"></script>
<!-- application script for validation >
<script src="<?php //echo base_url('public/js/bootstrap-datepicker.js')?>"></script-->
<script src="<?php echo base_url('public/js/jquery.validate.js');?>"></script>
<script src="<?php echo base_url('public/js/jquery.numeric.js');?>"></script>
<script src="<?php echo base_url('public/js/page.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('public/js/bootbox.min.js'); ?>"></script>
<script src="<?php echo base_url('public/js/bootstrap-switch.min.js'); ?>"></script>
<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
		<?php if($this->session->userdata('msg')){?>
				bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
				//bootbox.dialog("<?php //echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
		<?php $this->session->unset_userdata('msg');}?>
		
        $('.calendar').datepicker();
		$('.editor').each(function(){
			CKEDITOR.replace( $(this).attr('id'), {
				 fullPage : false,
				 allowedContent : true,
				 fillEmptyBlocks :false
			});
		});
		$('.datatable').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
			"bProcessing": true,
			"bSort": false, /*disable sorting by all fields*/
			"displayLength": 50, /*how meny items per page*/
			"lengthChange": false, /* show dropdown perpage*/
			"sPaginationType": "bootstrap",
			"bStateSave": false
		});
		$('.data').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
			"bProcessing": true,
			"sPaginationType": "bootstrap",
			"bServerSide": true,
			"sAjaxSource": '<?php echo base_url('admin/log/source')?>',
			"fnServerData": function ( sSource, aoData, fnCallback,oSettings) {
				aoData.push( 
					{ "name": "from_date", "value": $('#from_date').val()},
					{ "name": "to_date", "value": $('#to_date').val()},
					{ "name": '<?php echo $this->security->get_csrf_token_name(); ?>', "value": '<?php echo $this->security->get_csrf_hash(); ?>'} 
				);
            	$.getJSON( sSource, aoData, function (json) { 
					fnCallback(json);
				});
				/*oSettings.jqXHR = $.ajax( {
					"dataType": 'json',
					"type": "GET",
					"url": sSource,
					"data": aoData,
					"success": fnCallback
				});*/
			},
			"aoColumns": [
            { sWidth: '80%' },
            { sWidth: '10%' },
            { sWidth: '10%' } ]
		});
    });
	function show_message(msg){
		$.msgBox({
			content:msg,
			type:"info"
		});
	}
</script>
<style type="text/css">
	/*
 * Filter
 */
.dataTables_filter {
        float: right;
        text-align: right;
}
/*
 * Table information
 */
.dataTables_info {
	clear: both;
	float: left;
}
/*
 * Pagination
 */
.dataTables_paginate {
        float: right;
        text-align: right;
}

/* Two button pagination - previous / next */
.paginate_disabled_previous,
.paginate_enabled_previous,
.paginate_disabled_next,
.paginate_enabled_next {
        height: 19px;
        float: left;
        cursor: pointer;
        *cursor: hand;
        color: #111 !important;
}
.paginate_disabled_previous:hover,
.paginate_enabled_previous:hover,
.paginate_disabled_next:hover,
.paginate_enabled_next:hover {
        text-decoration: none !important;
}
.paginate_disabled_previous:active,
.paginate_enabled_previous:active,
.paginate_disabled_next:active,
.paginate_enabled_next:active {
        outline: none;
}

.paginate_disabled_previous,
.paginate_disabled_next {
        color: #666 !important;
}
.paginate_disabled_previous,
.paginate_enabled_previous {
        padding-left: 23px;
}
.paginate_disabled_next,
.paginate_enabled_next {
        padding-right: 23px;
        margin-left: 10px;
}

.paginate_enabled_previous { background: image-url('dataTables/back_enabled.png') no-repeat top left; }
.paginate_enabled_previous:hover { background: image-url('dataTables/back_enabled_hover.png') no-repeat top left; }
.paginate_disabled_previous { background: image-url('dataTables/back_disabled.png') no-repeat top left; }

.paginate_enabled_next { background: image-url('dataTables/forward_enabled.png') no-repeat top right; }
.paginate_enabled_next:hover { background: image-url('dataTables/forward_enabled_hover.png') no-repeat top right; }
.paginate_disabled_next { background: image-url('dataTables/forward_disabled.png') no-repeat top right; }

/* Full number pagination */
.paging_full_numbers {
        height: 22px;
        line-height: 22px;
}
.paging_full_numbers a:active {
        outline: none
}
.paging_full_numbers a:hover {
        text-decoration: none;
}

.paging_full_numbers a.paginate_button,
.paging_full_numbers a.paginate_active {
        border: 1px solid #aaa;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        padding: 2px 5px;
        margin: 0 3px;
        cursor: pointer;
        *cursor: hand;
        color: #333 !important;
}

.paging_full_numbers a.paginate_button {
        background-color: #ddd;
}

.paging_full_numbers a.paginate_button:hover {
        background-color: #ccc;
        text-decoration: none !important;
}

.paging_full_numbers a.paginate_active {
        background-color: #99B3FF;
}


/*
 * Processing indicator
 */
.dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 250px;
        height: 30px;
        margin-left: -125px;
        margin-top: -15px;
        padding: 14px 0 2px 0;
        border: 1px solid #ddd;
        text-align: center;
        color: #999;
        font-size: 14px;
        background-color: white;
}
</style>
</body>
</html>