<div class="box">
	<div class="box-header well" data-original-title>
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/staff/edit/')?>"
				class="btn" style="width: 50px;"><i class="cus-add"></i> Add</a>
            <?php }else{?>
            <a class="btn disabled" href="#" style="width: 50px"><i
				class="cus-add"></i> Add</a>
            <?php }?>
        </div>
		<div class="box-icon">
			<a href="<?php echo base_url('admin/masterAcc')?>" class="btn"
				style="width: 50px"><i class="cus-arrow-undo"></i> Back</a>
		</div>
	</div>
	<div class="box-content">
    	<?php echo form_open('', array('id'=>'frm_filter','name'=>'frm_filter','class'=>'form-horizontal')); ?>
    	<div class="step-1 step-filter">

			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Filter</legend>
				<div class="control-group default-filter">
					<input id="searchtext" name="searchtext" type="search" value="<?php echo set_value('searchtext', ''); ?>" />
					In <?php echo form_dropdown('search_field', Subscriber_mod::$search_fields, ((isset($choosedFilters['search_field']))?$choosedFilters['search_field']:0), 'id="search_field"');?>
					Status <?php echo form_dropdown('sc_status', Subscriber_mod::$sc_status, ((isset($choosedFilters['sc_status']))?$choosedFilters['sc_status']:0), 'id="sc_status"');?>
				</div>
				<div class="control-group second-filter hide">
					<span class="select-company inline"><?php echo form_dropdown('company_id', $select_companies, ((isset($choosedFilters['company_id']))?$choosedFilters['company_id']:0), 'id="company_id"');?></span>
					<span class="select-branch inline"><?php echo form_dropdown('branch_id', $select_branches, ((isset($choosedFilters['branch_id']))?$choosedFilters['branch_id']:0), 'id="branch_id"');?></span>
				</div>

				<button type="submit" class="btn btn-primary search-button">
					<i class="icon-search icon-white"></i> Search
				</button>
				<a class="reset-filter" href="">Reset</a>

			</fieldset>

		</div>

		<fieldset class="scheduler-border"><legend class="scheduler-border">Result</legend>
    	<?php if (isset($filteredUsers)) {?>
    	<div class="pagination hide">
				<ul>
		        <?php //echo $pagination->create_links();?>
		    </ul>
			</div>
			<table
				class="table table-striped table-bordered bootstrap-datatable datatable-subs">
				<thead>
					<tr>
						<th>Sub ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Company/Branch/Dep.</th>
						<th>PIC</th>
						<th>Phone</th>
						<th>Status</th>
						<th>Imported</th>
						<th>Updated</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
         <?php } //end if table?>
         </fieldset>
         <?php echo form_close(); ?> 
      </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

    	if ($("select[name='sc_status']").val() != '0') {
    		$("div.second-filter").show();
        }
        
    	$(document).on('change', "select[name='sc_status']", function(el) {
    		
    		if($(this).val() == '0') {
    			$("div.second-filter").slideUp();
    			$("select[name='company_id']").prop('selectedIndex',0);
    			$("select[name='branch_id']").prop('selectedIndex',0);
    		} else {
    			$("div.second-filter").slideDown();
    		}
		});
		

        $(document).on('change', "select[name='company_id']", function(el) {
			var company_id = $(this).val();
			if (company_id == 0) {
				$("select[name='branch_id']").find("option:gt(0)").remove();
				$("select[name='branch_id']").prop("disabled", true);
				return false;
			}
			$.ajax({
			    url:"<?php echo site_url('admin/masteracc/ajax_get_company_branches/')?>",  
			    type: "get",
			    data: {"company_id":company_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
			    success:function(data) {
			    	$("select[name='branch_id']").parent().html(data); 
			    }
			  });
			
			return true;
		});


        $('.datatable-subs').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
			"sEmptyTable": "Loading data from server",
			"bProcessing": true,
			"bFilter": false,
			"bSort": false, /*disable sorting by all fields*/
			"bPaginate": true,
// 			"iDisplayLength": 1, /*how meny items per page*/
			"bLengthChange": true, /* show dropdown perpage*/
			"sPaginationType": "bootstrap",
			"sAjaxDataProp": "aaData",
			"bServerSide": true,
			//"bStateSave": true,
			"dom": 'Rlfrtip',
			"sAjaxSource": '<?php echo base_url('admin/subscribers/ajax_filter_source')?>',
			"fnServerData": function ( sSource, aoData, fnCallback, oSettings) { /*,oSettings*/
				aoData.push( 
					{ "name": "searchtext", "value": $('#searchtext').val()},
					{ "name": "search_field", "value": $('#search_field').val()}, 
					{ "name": "sc_status", "value": $('#sc_status').val()},
					{ "name": "company_id", "value": $('#company_id').val()},
					{ "name": '<?php echo $this->security->get_csrf_token_name(); ?>', "value": '<?php echo $this->security->get_csrf_hash(); ?>'},
					{ "name": "branch_id", "value": $('#branch_id').val()}
				);
//             	$.getJSON( sSource, aoData, function (json) { 
// 					fnCallback(json);
// 				});

            	oSettings.jqXHR = $.ajax({
            		  "dataType": "json",
            		  "type": "POST",
            		  "url": sSource,
            		  "data": aoData,
            		  "success": function(data) { fnCallback(data); },
            		  "error": function(jqXHR, textStatus, errorThrown) {
//             		    console.log(txtStatus); // use alert() if you prefer
            		    console.log(errorThrown); 
            		  }
            		});

				
			}
			,"aoColumnDefs": [
                  { 'bSortable': false, 'aTargets': [ 1 ] }
            ]
            , "aoColumns": [     
                            { "sName": "subs_id", "sType": "string", 
                                fnRender: function( oObj ) {
                                    return oObj.aData[0];}
                            },  
                            { "sName": "subs_name", "sType": "string", 
                                fnRender: function( oObj ) {
                                    return oObj.aData[1];}
                            },
                            { "sName": "email", "sType": "string", 
                                fnRender: function( oObj ) {
                                    return oObj.aData[2];}
                            },
                            { "sName": "company_branch", "sType": "string", 
                                fnRender: function( oObj ) { 
                                    return 'Com: '+oObj.aData[3] + "<br />\n Br: " + oObj.aData[4] + "<br />\n Dep: " + oObj.aData[5];}
                            },
                            { "sName": "staff_name", "sType": "string", sClass: "center", 
                                fnRender: function( oObj ) { 
                                    return ("" != oObj.aData[6] ? oObj.aData[6] : "-");}
                            },
                            { "sName": "phone", "sType": "string", 
                                fnRender: function( oObj ) { 
                                    return oObj.aData[7];}
                            },
                            { "sName": "sc_status", "sType": "string", 
                                fnRender: function( oObj ) {
                                    return oObj.aData[8];}
                            },
                            { "sName": "imported_at", "sType": "string", 
                                fnRender: function( oObj ) {
                                    return oObj.aData[9];}
                            },
                            { "sName": "updated_at", "sType": "string", 
                                fnRender: function( oObj ) {
                                    return oObj.aData[10];}
                            },
                            { "sName": "action", "bSortable": false, "bSearchable": false, 
                                fnRender: function( oObj ) { 
                                    return '<a class="btn" href="'+ BASE_URL+ 'admin/subscribers/edit/' + oObj.aData[0] +'"><i class="cus-page-white-edit"></i>Edit</a>';}
                            },
                        ],

// 			, aoColumns: [
//             	{ mData: 'subs_name', /*sWidth": "56px", sClass: "center",*/ "fnRender": function( oObj ) { /*console.log(oObj.aData.subs_name);*/ return oObj.aData.subs_name.toString();}},
//             ]
// 			,aoColumnDefs   : [
//                   {
//                       sTitle  : "Name",
//                       sType   : "string",
//                       mData   : "subs_name",
//                       bSortable: false,             // Column is not sortable
//                       mRender  : function (data, type) {
//                           return '';
//                       },
//                       aTargets: [ 0 ] // Column number which needs to be modified
//                   }
//               ]
			
		});

        $('.search-button').click(function(e) {
        	e.preventDefault();
			var oTable = $('.datatable-subs').dataTable();
//         	oTable.fnClearTable();
//         	oTable.fnDraw(true);
        	oTable.fnPageChange("first");
        });

      //reset form 
        $(".reset-filter").click(function(e){
        	e.preventDefault();
        	$('#frm_filter').get(0).reset();
        	$("div.second-filter").slideUp();
        	var oTable = $('.datatable-subs').dataTable();
			oTable.fnFilter('');
        	oTable.fnPageChange("first");
//         	oTable.fnClearTable();
//         	oTable.fnDraw(true);
//          $("#myform").find('input:text, input:password, input:file, select, textarea').val('');
//          $("#myform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        });

    });
</script>
<style>
fieldset.scheduler-border {
	border: 1px groove #ddd !important;
	padding: 0 1.4em 1.4em 1.4em !important;
	margin: 0 0 1.5em 0 !important;
	-webkit-box-shadow: 0px 0px 0px 0px #000;
	box-shadow: 0px 0px 0px 0px #000;
	position: relative;
}

legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width: auto;
	padding: 0 10px;
	border-bottom: none;
	margin-bottom: 0;
}

.default-filter select, .second-filter select {
	width: auto;
}
/* .default-filter label.checkbox {margin-left:20px;cursor:pointer;}
.second-filter span.select-company, .second-filter span.select-branch {margin-left:20px;} */
</style>