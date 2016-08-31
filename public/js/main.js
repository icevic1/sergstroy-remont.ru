$(document).ready(function() {
	$(document).on('submit', "#frm_searh_parent", function(e) {
		e.preventDefault();
		$('#ajax-preloader').show();
		$.ajax({
			url: $('#frm_searh_parent').prop('action'),
			type: 'POST', //or POST
 	        data: $('#frm_searh_parent').serialize(),
 	        success: function(data){
 	       		$('#frm_searh_parent #search_result').html(data);
				$('#ajax-preloader').hide();
 	        }
 	    });
 	});
    	
	$('#add_parent').modal({
            backdrop: true,
            keyboard: true,
            show: false
	}).css({
           'width': function () { 
               return ($(document).width() * .4) + 'px';  
	},
	'margin-left': function () { 
		 return -($(this).width() / 2); 
		}
	});

	$('#sts_list').DataTable({
		"aaSorting": [],/* Disable sort initial */
		"displayLength": 30, /*how meny items per page*/
		"lengthChange": false, /* show dropdown perpage*/
		bFilter: false, /*hide search input*/
		/*bInfo: false,*/
		"sPaginationType": "bootstrap",
	    "aoColumnDefs": [{
	        'bSortable': false,
	        'aTargets': ['nosort']
	    }]
	});
	
	$('#sts_alerts').DataTable({
		"aaSorting": [],/* Disable initial sort */
		"sPaginationType": "bootstrap",
		"aoColumnDefs" : [ {
		    "bSortable" : false,
		    "aTargets" : [ "nosort" ]
		} ]
	});

    $("#type").change(function(){  
        	/*dropdown subjects *///  
            $.ajax({  
    	        url: BASE_URL + "Servicetickets/buildDropSubjects/",  
    	        data: {typeid: $(this).val(), csrf_sc_name : csrf_sc_name},  
    			type: "POST",  
    	        success:function(data){  
            		$("#subject").html(data);  
            	}  
            });  
    	});
});