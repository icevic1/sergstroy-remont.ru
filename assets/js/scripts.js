$(document).ready(function(e) {

	$(document).on('submit', "#form_guest_question", function(e) {
		e.preventDefault();
		$('#ajax_preloader').show();
		$.ajax({
			url: $('#form_guest_question').prop('action'),
			type: 'POST', //or POST
			data: $('#form_guest_question').serialize(),
			success: function(data){
				$("#clientQuestion form").replaceWith($(data).find('form'));
				$('#ajax_preloader').hide();
			}
		});
	});

	$("#clientQuestion")
		.on('focus', '.q-form input[type="text"], .q-form textarea', function() {
			console.log('focus');
			if ($(".q-text").is(":visible") == false) {
				$(".q-text").slideDown();
			}
		}).on('focusout', '.q-form input[type="text"], .q-form textarea', function(e) {

			setTimeout(function() {
				var count = $('.q-form input[type="text"], .q-form textarea').filter(function () {
					return !!this.value;
				}).length;

				var newTarget = $(':focus')[0];

				if ($(newTarget).is('textarea, input[type="text"]') == false && count == 0)
					$(".q-text").slideUp();
				// console.log('focusout', $(':focus')[0]);
			}, 1);
		});

	/*ymaps.ready(init);
	 var myMap,
	 myPlacemark;

	 function init(){
	 myMap = new ymaps.Map ("map", {
	 center: [55.76, 37.64],
	 zoom: 14
	 });

	 myPlacemark = new ymaps.Placemark([55.76, 37.64], {
	 hintContent: 'Москва!',
	 balloonContent: 'Столица России'
	 });

	 myMap.geoObjects.add(myPlacemark);
	 }*/
	/*---- end map block -------*/

	$("#form_guest_question").validate({
		rules: {
			name: {
				required: true,
				maxlength: 13
			},
			phone: {
				required: true,
				digits: true,
				maxlength: 13
			},
			question: {
				required: true
			}
		}
	});



	//------------------------------------

	$(document).on('click', '[data-show="panel"]', function(e) {
		e.preventDefault();
		console.log($(this).data('target'));
		$($(this).data('target')).show();
	});

	$(document).on('click', '[data-hide="panel"]', function(e) {
		e.preventDefault();
 
		$($(this).data('target')).hide();
	});
	
	$('#tariff_holder').on('show.bs.collapse', function (e) {
		$('#tariff_plans_tabs').tabCollapse();
	});
	
	$('#services_holder').on('show.bs.collapse', function (e) {
		$('#services_tabs').tabCollapse();
	});
	
	$('[data-toggle="toggle-tooltip"]').click(function(e){
		e.preventDefault();
        $(this).tooltip({trigger: 'click', placement: 'auto'});
    }); 
	
	$('[data-toggle="popover"]').popover();
//	$('[data-toggle="checkboxpicker"]').checkboxpicker();
	
	/*
	 * Centred login panel
	 */
	if($('.holder-rows').length && $(window).height() > $('.holder-rows').outerHeight()) {//alert();
		$('.holder-rows').css({
	        'position' : 'absolute',
	        'top' : '50%',
	        'margin-top' : -$('.holder-rows').outerHeight()/2
	    });
	}
	
	$('.date_from, #date_from').datetimepicker({defaultDate: false, format: 'YYYY-MM-DD'});
	$('.date_to, #date_to').datetimepicker({defaultDate: false, format: 'YYYY-MM-DD'});
	$('.datepicker').datetimepicker({defaultDate: false, format: 'YYYY-MM-DD'});
	
	$('.dataTable').DataTable({
		"aaSorting": [],/* Disable sort initial */
		"displayLength": 25, /*how meny items per page*/
		"lengthChange": false, /* show dropdown perpage*/
		"bFilter": false, /*hide search input*/
		/*bInfo: false,*/
		/*"sPaginationType": "bootstrap",*/
		"aoColumnDefs": [{
			'bSortable': false,
			'aTargets': ['nosort']
		}]
	});
	
	$('.dataTable-applicants').DataTable({
		"aaSorting": [],/* Disable sort initial */
		"displayLength": 25, /*how meny items per page*/
		"lengthChange": false, /* show dropdown perpage*/
		"bFilter": false, /*hide search input*/
		/*bInfo: false,*/
		/*"sPaginationType": "bootstrap",*/
		columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
	    aoColumnDefs: [{
	        bSortable: false,
	        aTargets: ['nosort']
	    }]
	});
//	$('#tariff_plans_tabs').tabCollapse();
//	$('#services_tabs').tabCollapse();
	
	$(document).on('click', 'button[type="reset"]', function() {
		$(this).closest('form').find('input:checked, option:selected').removeAttr('checked').removeAttr('selected');
		return true;
	});
	
});//end ready

var preloading;
preloading = preloading || (function () {
	var modalHTML = '<div id="pleaseWaitDialog" class="modal" data-keyboard="false" data-backdrop="static">'+
				        '<div class="modal-dialog">'+
							'<div class="modal-content">'+
						        '<div class="modal-header"><h4 class="modal-title">Processing...</h4></div>'+
						        '<div class="modal-body"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">100% Complete</span></div></div></div>'+
						    '</div>'+
						'</div>'+
					'</div>';
    var pleaseWaitDiv = $(modalHTML);
    return {
        show: function() {
            pleaseWaitDiv.modal('show');
        },
        hide: function () {
            pleaseWaitDiv.modal('hide');
        },

    };
})();

var myAlert;
myAlert = myAlert || (function () {
    var myAlertDiv = $('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong><ul></ul></div>');
    return {
        notify: function(target, message) {
            if ($(target + ' .alert').length > 0) {
            	$(target + ' .alert ul').append('<li>' + message + '</li>');
    		} else {
    			myAlertDiv.children('ul').empty().append('<li>' + message + '</li>');
    			$(target).append(myAlertDiv);
    		}
            //$(target + ' .alert').alert(); // show alert
//            $(target + ' .alert').delay(15000).fadeIn(500, function() { $(this).remove();/*$(this).alert('close');*/  });
            $(target + ' .alert').fadeTo(15000, 500).fadeOut(500, function() { $(this).remove();/*$(this).alert('close');*/  });
        },
    };
})();

var generalPopUp;
generalPopUp = generalPopUp || (function () {

	var progressLoader = '<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">100% Complete</span></div></div>';
	var progressTitle = 'Processing...';
	var defaultSize = "modal-md";
	
	var modalHTML = '<div id="pop_up" class="modal" data-keyboard="false" data-backdrop="static">'+
				        '<div class="modal-dialog modal-md">'+
							'<div class="modal-content">'+
						        '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div>'+
						        '<div class="modal-body"></div>'+
						        '<div class="modal-footer"><button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button></div>'+
						    '</div>'+
						'</div>'+
					'</div>';
    var modalPopUp = $(modalHTML);
    return {
    	set: function(title, body, size) {
    		var title = title || '';
    		var body = body || '';
        	var size = "modal-" + size || defaultSize;
        	//var newClasses = modalPopUp.find('.modal-dialog').prop("class").replace(/\s(modal-[xs|sm|md|lg]+)/, " "+size);
//        	console.log(modalPopUp.find('.modal-dialog').prop("class"));
//        	console.log(size);
//        	console.log(modalPopUp.find('.modal-dialog').prop("class").replace(/\s(modal-[xs|sm|md|lg]+)/, " "+size));
        	
        	modalPopUp.find('.modal-dialog').prop("class", modalPopUp.find('.modal-dialog').prop("class").replace(/\s(modal-[xs|sm|md|lg]+)/, " "+size));
        	
    		modalPopUp.find('.modal-header .modal-title').html(title);
    		modalPopUp.find('.modal-body').html(body);
        },
        show: function(processing) {
        	var processing = processing || false;
        	
        	if (true == processing) {
        		modalPopUp.find('.modal-header .modal-title').html(progressTitle);
        		modalPopUp.find('.modal-body').html(progressLoader);
//        		console.log(modalPopUp.find('.modal-header .modal-title'));
        	}
        	modalPopUp.modal('show');
        },
        hide: function () {
        	modalPopUp.modal('hide');
        },

    };
})();

//Only enable if the document has a long scroll bar
//Note the window height + offset
if ( ($(window).height() + 100) < $(document).height() ) {
 $('#top-link-block').removeClass('hidden').affix({
     // how far to scroll down before link "slides" into view
     offset: {top:100}
 });
}