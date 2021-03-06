/**
 * Front-End JS
 */
$(document).ready(function(e) {

	/* open modal popup where allow user to leave a review message */
	$(document).on('click', '.evaluate-input', function(e) {
		console.log('test', generalPopUp);
		var review_cnt = review_cnt || $('#review_cnt').clone().removeClass('hide').get(0).outerHTML;

		generalPopUp.set('Оцените работу', review_cnt);
		generalPopUp.show();
	});

    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    $('#back-to-top').tooltip('show');

    var t;
    $('.ot-item-account .ot-ico-help, .ot-tooltip-help-phone-number').hover(function() {
        clearTimeout(t);
        $('.ot-tooltip-help-phone-number').show();
    }, function() {
        t = setTimeout(function() {$('.ot-tooltip-help-phone-number').hide();}, 20);
    });

    $('.circle-block').on('mousemove', function(e) {
        var element = $(this).parent().children('.popover-content');
        // console.log(element.data('position'));

        if (element.data('position') == 'right') {
           var left = e.pageX - $(this).offset().left - 400;
        } else if (element.data('position') == 'center') {
            var left = e.pageX - $(this).offset().left - 200;
        } else
            var left = e.pageX - $(this).offset().left;

        element.css({top: e.pageY - $(this).offset().top + 20, left: left }).show();
    });

    $('.circle-block').on('mouseleave', function(e) {
        $('.popover-content').hide()
    });

    $(document).on( 'scroll', function(){
        $('.popover-content').hide()
    });

	// init Isotope
	var $grid = $('.thumbs-holder').isotope({
		itemSelector: '.thumb',
		percentPosition: true,
		masonry: {
			// use element for option
			// columnWidth: '.grid-sizer'
			columnWidth: '.thumb'
			// columnWidth: 260
		}
	});

	// filter items on button click
	$('#filter').on( 'click', 'a', function(e) {
		e.preventDefault();
		var filterValue = $(this).attr('data-filter');
		// console.log(filterValue);
		$grid.isotope({ filter: filterValue });
	});

	// to top
	$('.totop').click(function(){
		$('html, body').animate({ scrollTop: 0 }, "slow");
	});

    if($(window.location.hash).length > 0){
        $('html, body').animate({ scrollTop: $(window.location.hash).offset().top},  "slow");
    }

	$('.carousel-inner, .thumbs-holder').magnificPopup({
		delegate: 'a.image-item, .thumb:not(:hidden) .thumbnail',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			/*titleSrc: function(item) {
			 return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
			 }*/
		}
	});

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

    /*---- start map block -------*/
    /*ymaps.ready(init);
    var myMap, myPlacemark;

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

	var checkMode = function () {

		if ($(window).width() < 768) {
			return 'xs';
		}
		else if ($(window).width() >= 768 && $(window).width() < 992) {
			return 'sm';
		}
		else if ($(window).width() >= 992 && $(window).width() < 1200) {
			return 'md';
		}
		else {
			return 'lg';
		}
	};

	if ($(".responsive-calendar").length > 0) {
		$(".responsive-calendar").responsiveCalendar({
			//time: '2013-05',
			allRows: false,
			translateMonths: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
			events: {}, /*"2016-09-03": {"number": 5, "url": "http://w3widgets.com/responsive-slider"}*/
			onDayClick: function(events) {
				if ($(this).parent('div').hasClass('active')) {
					getEventGallery($(this).attr('href'));
					$(".responsive-calendar .days .day.active a.selected").removeClass('selected');
					$(this).addClass('selected');
				}
				return false;
			}
		});
	}

	function loadEvents()
	{
		if ($(".responsive-calendar").length > 0) {
			$.ajax({
				url: "/home/get_events",
				dataType: "json",
				// data: {typeid: $(this).val(), csrf_sc_name : csrf_sc_name},
				type: "GET",
				success: function (response) {
					if (_.keys(response).length < 1) return;
					$('.responsive-calendar').responsiveCalendar('edit', response);
					var firsUrl = response[_.first(_.keys(response))].url;
					getEventGallery(firsUrl)
				}
			});
		}
	}
	loadEvents();

	function getEventGallery(url)
	{
		if (!url) return false;
		$("#ajax_preloader_gallery").show();
		$.ajax({
			url: url, /*/home/gallery_photos/2*/
			// dataType: "json",
			// data: {typeid: $(this).val(), csrf_sc_name : csrf_sc_name},
			type: "GET",
			success: function(html){
				$("#ajax_preloader_gallery").hide();
				$('#galleryCarousel').empty().append($(html));
				$('a[href="'+url+'"]').addClass('selected');
				$('#galleryCarousel .carousel-inner').magnificPopup({
					delegate: 'a.image-item',
					type: 'image',
					tLoading: 'Loading image #%curr%...',
					mainClass: 'mfp-img-mobile',
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0,1] // Will preload 0 - before current, and 1 after the current image
					},
					image: {
						tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
					}
				});
				// console.log(response );
			}
		});
	}

	$("#form_client_login").validate({
		rules: {
			user_name: {
				required: true,
				maxlength: 64
			},
			user_phone: {
				required: true,
				digits: true,
				maxlength: 13
			}
		}
	});

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

    $('.twitter-share-button').click(function(event) {
        var width  = 575,
            height = 400,
            left   = ($(window).width()  - width)  / 2,
            top    = ($(window).height() - height) / 2,
            url    = this.href,
            opts   = 'status=1' +
                ',width='  + width  +
                ',height=' + height +
                ',top='    + top    +
                ',left='   + left;

        window.open(url, 'twitter', opts);

        return false;
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

(function() {
    if (window.__twitterIntentHandler) return;
    var intentRegex = /twitter\.com\/intent\/(\w+)/,
        windowOptions = 'scrollbars=yes,resizable=yes,toolbar=no,location=yes',
        width = 550,
        height = 420,
        winHeight = screen.height,
        winWidth = screen.width;

    function handleIntent(e)
    {
        e = e || window.event;
        var target = e.target || e.srcElement,
            m, left, top;

        while (target && target.nodeName.toLowerCase() !== 'a') {
            target = target.parentNode;
        }

        if (target && target.nodeName.toLowerCase() === 'a' && target.href) {
            m = target.href.match(intentRegex);
            if (m) {
                left = Math.round((winWidth / 2) - (width / 2));
                top = 0;

                if (winHeight > height) {
                    top = Math.round((winHeight / 2) - (height / 2));
                }

                window.open(target.href, 'intent', windowOptions + ',width=' + width +
                    ',height=' + height + ',left=' + left + ',top=' + top);
                e.returnValue = false;
                e.preventDefault && e.preventDefault();
            }
        }
    }

    if (document.addEventListener) {
        document.addEventListener('click', handleIntent, false);
    } else if (document.attachEvent) {
        document.attachEvent('onclick', handleIntent);
    }
    window.__twitterIntentHandler = true;
}());