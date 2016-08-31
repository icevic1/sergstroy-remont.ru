<?php $this->view('layout/header_area');?>

<!-- Begin Body conainer block -->
<div id="top" class="container-fluid">
	<div class="row">
		<div class="box-content container-fluid">
			<div class="row action-bar-holder">
				<div class="col-sm-6">
					<?php if (isset($this->breadcrumbs) && is_object($this->breadcrumbs)) echo $this->breadcrumbs->show();?>
				</div>
				<div class="col-sm-6">
					<?php if($this->router->fetch_class() == 'reports' && in_array($this->router->fetch_method(), array('index'))) {?>
					<div class="text-right" style="height: 45px; line-height: 45px;">
						<div class="btn-group btn-group-sm-" role="group" aria-label="Choose a report type">
						  <button type="button" class="btn btn-success">by Dealer</button>
						  <button type="button" class="btn btn-success">by Sales ID</button>
						  <button type="button" class="btn btn-success">by Status</button>
						  <button type="button" class="btn btn-success">by Province</button>
						  <button type="button" class="btn btn-success">by City</button>
						</div>
					</div>
					<?php } else {?>
					<ul class="list-inline text-right action-bar">
						<?php if (isset($navBackLink)) :?>
						<li class="">
							<a class="" href="<?php echo $navBackLink;?>"><i class="icomoon icon-undo-2"></i> Back</a>
						</li><?php endif;?>
						<?php if (isset($navCloseTicket)) :?>
						<li class="">
							<a class="" id="close_ticket" href="<?php echo $navCloseTicket;?>"><i class="fa fa-check-square-o"></i> Close Ticket</a>
						</li>
						<?php endif;?>
						<?php if (isset($navEditLink)) :?>
						<li class="">
							<a class="" href="<?php echo $navEditLink;?>"><i class="fa fa-pencil-square-o"></i> Edit</a>
						</li>
						<?php endif;?>
						<?php if (isset($navAddProgress)) :?>
						<li class="">
							<a class="" href="<?php echo $navAddProgress;?>" data-toggle="modal"><i class="fa fa-pencil-square-o"></i> Add progress</a>
						</li>
						<?php endif;?>
						<?php if (isset($navAdd)) :?>
						<li class="">
							<a class="" href="<?php echo site_url($navAdd['link'])?>"><i class="fa fa-plus-circle"></i> <?php echo $navAdd['title']?></a>
						</li>
						<?php endif;?>
					</ul>
					<?php }?>
				</div>
			</div>
			<?php if(isset($CONTENT))$this->view($CONTENT);?>
		</div>
  	</div><!-- end container row -->
</div><!-- end container -->

<div class="clearfix"></div>
<span id="top-link-block" class="hidden">
    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> <span class="hidden-xs">Back to Top</span>
    </a>
</span><!-- /top-link-block -->

<script type="text/javascript">
$(document).ready(function(e) {

	/*========== member list with paginations =============*/
	$("#members_list").on('click', '.paging-holder ul li a', function(e) {
		e.preventDefault();

// 		console.log($(this).attr("href"));return;
		
		$('#ajax-preloader_member').show();
		//search/ajax_members/3
		$.get($(this).attr("href"),
			{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				$('#ajax-preloader_member').hide();
				$("#members_list").html(response);
				
		}).error(function(x, status, error) {console.log(error);});
	});

	/*======= CDR ==========*/
	$(document).on('click', '#cdr_loader, .paging-holder ul.pagination li.page a, a#submit_cdr', function(e) {
		e.preventDefault();
		if ($(this).attr('id') == 'cdr_loader') {
			generalPopUp.show(true);
		} else {
			generalPopUp.show();
			$('#ajax_modal_loading').show();
		}
		var subwebcustid = $(this).data('webcustid');
		var date_from = $('form#frm_cdr_search input[name="date_from"]').val();
		var date_to = $('form#frm_cdr_search input[name="date_to"]').val();

		$.post($(this).attr("href"),
			{"WebCustId": subwebcustid, "date_from": date_from,"date_to": date_to, '<?php  echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				$('#ajax_modal_loading').hide();
				generalPopUp.set('View Traffic details and Charges', response, "lg");
		}).error(function(x, status, error) {});
	});

	/*======= PIC details =============*/
	$(document).on('click', '.pic-item', function(e) {
		e.preventDefault();
		var staff_id = $(this).data('staffid');
		generalPopUp.show(true);
		$.post("<?php echo site_url('home/ajax_pic_details/')?>",
			{"staff_id": staff_id, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Person in Charge details', response);
		}).error(function(x, status, error) {});
	});

	/*========== KAM details ==============*/
	$(document).on('click', '.kam-item, #cust_kam_details', function(e) {
		e.preventDefault();
		var staffid = $(this).data('staffid');
		generalPopUp.show(true);
		$.post("<?php echo site_url('home/ajax_kam_details/')?>",
			{"user_id": staffid,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Key Account Manager', response);
		}).error(function(x, status, error) {});
	});
	
	/*======= Customer details =============*/
	$(document).on('click', 'a.customer-info', function(e) {
		e.preventDefault();
		var staff_id = $(this).data('staffid');
		generalPopUp.show(true);
		$.post($(this).attr('href'),
			{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Customer details', response);
		}).error(function(x, status, error) {});
	});

	/* show detail offer information about selected offer from popup or sub ino block */
	$(document).on('click', '.tariff-plan a.offer-name, #current_sub_pp', function(e) {
		e.preventDefault();
		generalPopUp.show(true);
		var webofferid = $(this).data('webofferid');
		if (!webofferid) {alert('No data');return false;}
		$.get("<?php echo site_url('home/ajax_offer_details/')?>",
			{"webofferid": webofferid, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Offer details', response);		
		}).error(function(x, status, error) {console.log(error);});
	});

	$(document).on('click', 'a.pp-addition', function(e) {
		e.preventDefault();
		generalPopUp.show(true);
		var groupid = $(this).data('groupid');
		
		$.get("<?php echo site_url('home/ajax_offer_groupdetails/')?>",
			{"groupid": groupid, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				generalPopUp.set('Offer details', response);
		}).error(function(x, status, error) {console.log(error);});
	});
	
	
	$('.close').click(function(e) {
		$(this).closest(".ot-dialog").hide();
	});

	$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

	<?php if($this->session->userdata('msg')){?>
			bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
			//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
	<?php $this->session->unset_userdata('msg');}?>

		//document.oncontextmenu = document.body.oncontextmenu = function () { return false; }
			/* $('.ot-section a, .ot-user-pay a, .ot-dialog a, .ot-panel-nav a').click(function(e) {
				return false;
			}); */

	/*Get list of customers group counter */
	if ($('ul#customer_list_group').length > 0) {			
		ajax_gmem_counter();
	}
			
	
			
	$(document).on('click', '#outstanding_bill, .bills_history_button', function(e) {
		e.preventDefault();
		var subwebcustid = $(this).data('webcustid');
		var billtype = $(this).data('billtype');
		generalPopUp.show(true);
		$.post("<?php echo site_url('home/ajax_bills_details/')?>",
			{"WebCustId": subwebcustid, billtype: billtype,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, 
			function(response) {
				if (billtype == 1) {
					generalPopUp.set('Outstanding Bill', response);
				} else if (billtype == 2) {
					generalPopUp.set('Bill history', response);
				} else if (billtype == 3) {
					generalPopUp.set('Master Invoiece', response);
				}
		}).error(function(x, status, error) {console.log(error);});
	});

	
			
			$('a.direct-url').click(function(e) {
				window.location=$(this).attr('href');
				return true;
			});
			$('a.direct-url-new-window').click(function(e) {
				window.open($(this).attr('href'),'_new');
				return true;
			});
            // ot-dialog-help
			$('.ot-link-feedback').click(function() {
				$('.ot-feedback').show();
			});

			$( ".ot-dialog-CDR a" ).click(function() {
				$('.ot-dialog-CDR').dialog("option", "position", "center");
			});
			$( ".ot-dialog-help a" ).click(function() {
				$( ".ot-feedback" ).hide();
			});
			// ot-dialog-payment-history
			$('.ot-link-payment-list').click(function() {
				var sub_number="<?php if(isset($default_subs['SubscriberNumber'])) echo $default_subs['SubscriberNumber']; else echo ''?>";
				load_tdr(1,1,sub_number);
				$('.ot-dialog-payment-history').show();
			});
			$( ".ot-dialog-payment-history a" ).click(function() {
				$( ".ot-dialog-payment-history" ).hide();
			});
			// ot-dialog-operations-history
			$('.ot-link-operations-history').click(function() {
				$('.ot-dialog-operations-history').show();
			});
			$( ".ot-dialog-operations-history .close" ).click(function() {
				$( ".ot-dialog-operations-history" ).hide();
			}); 
			$('.tdr').change(function(e) {
				var sub_number="<?php if(isset($default_subs['SubscriberNumber'])) echo $default_subs['SubscriberNumber']; else echo ''?>";
                load_tdr($(this).val(),1,sub_number);
            });
			$('.due-detail').click(function(e) {
				var cct = $.cookie('csrf_cookie_name');
			    var top=$(this).position().top;
				var left=$(this).position().left-20;
				$('#due_detail').css('top',top);
				$('#due_detail').css('left',left);
				$.post('<?php echo base_url('home/get_due_detail')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(html) {
					$('#due_detail table tbody').html(html);
					$('#due_detail').show();
				});
				e.preventDefault();
            });
			$('#due_detail .ot-dialog-close').click(function(e) {
				$('#due_detail').hide();
			});
			

			$('.ot-btn-update-balance').click(function(e) {
                $('.ot-dialog-topup').show();
				return false;
            });
			//check_current_pp();
			// payment date tooltip
			var t;
			$('.ot-date-cont').hover(function() {
				clearTimeout(t);
				var cct = $.cookie('csrf_cookie_name');
				$.post('<?php echo base_url('home/get_due_detail')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(html) {
					$('.ot-tooltip-payment-date table tbody').html(html);
				});
				$('.ot-tooltip-payment-date').show();
			}, function() {
				t = setTimeout(function() {$('.ot-tooltip-payment-date').hide();}, 20);
			});
			
			$('#ot-btn-tariff-1').click(function(e) {
				var cct = $.cookie('csrf_cookie_name');
				var start_date=$('#hd_pdr_from').val();
				var to_date=$('#hd_pdr_to').val();
				var subnumber=$('#pdr_subnumber').val();
				var opt=$('[name="type-operations[]"]:checked').val();
				//alert(opt);
				$.post('<?php echo base_url('home/get_pdr')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',"start_date":start_date,"end_date":to_date,"page_num":"1","sub_number":subnumber}, function(html) {
					$('#pdr_list tbody').html(html);
					//alert(html);
				});
				return false;
			});
			$('#lnk_readmore').click(function(e) {
				$('#ot_news_detail').show();
			});
			$('.close').click(function(e) {
				$(this).closest(".ot-dialog").hide();
			});
			$('.coperate-subscriber').change(function(e) {
				if($(this).val()==-1) return;
				var cct = $.cookie('csrf_cookie_name');
                //alert($(this).val());
				$.post('<?php echo base_url('home/customer_selection')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',"cust_number":$(this).val()}, function(data) {
					if(data){
						location.reload(true);
					}
				},'json');
            });

			/*----- smart functions -------*/

			$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

			<?php if($this->session->userdata('msg')){?>
					bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
					//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
			<?php $this->session->unset_userdata('msg');}?>
			
        }); //end ready()
        
		
		function check_current_pp(){
			var cost=$('#current_pp_cost').text();
			if (cost.indexOf("-") >= 0){
				$('#current_pp_cost').parent().addClass('remak');
			}
		}

function ajax_gmem_counter()
{
	var cct = $.cookie('csrf_cookie_name');
	$.get('<?php echo base_url('home/ajax_gmem_counter')?>',{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'}, function(response) {
		$.each(response, function (key, data) {
			$('ul#customer_list_group li[data-webcustid="'+ key +'"] .m-count').html(data);
		});
	},"json");
}
</script>

<?php $this->view('layout/footer_area');?>