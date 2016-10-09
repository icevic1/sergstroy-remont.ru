<?php $this->view('layout/header_area');?>

<?php if(isset($CONTENT))$this->view($CONTENT);?>

<div class="clearfix"></div>
<div id="top-link-block" class="hidden" title="Нажмите, чтобы вернуться к началу странице" data-toggle="tooltip" data-placement="left">
    <a href="#top" class="btn btn-primary btn-lg" onclick="$('html,body').animate({scrollTop:0},'slow');" ><!--well well-sm-->
        <span class="hidden-xs">Вверх</span> <i class="glyphicon glyphicon-chevron-up"></i>
    </a>
</div><!-- /top-link-block -->
<!--<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left">
	<span class="glyphicon glyphicon-chevron-up"></span> <span class="hidden-xs">Верх</span>
</a>-->

<script type="text/javascript">
$(document).ready(function(e) {

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

	
	
	$('.close').click(function(e) {
		$(this).closest(".ot-dialog").hide();
	});

	$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

	<?php if($this->session->userdata('msg')){?>
			bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
			//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
	<?php $this->session->unset_userdata('msg');}?>

		/*----- smart functions -------*/

			$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

			<?php if($this->session->userdata('msg')){?>
					bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
					//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
			<?php $this->session->unset_userdata('msg');}?>
			
        }); //end ready()

</script>

<?php $this->view('layout/footer_area');?>