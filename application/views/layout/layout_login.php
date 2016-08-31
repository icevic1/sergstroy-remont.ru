<?php $this->view('layout/header');?>
<div class="container-fluid main-container">
    <div class="row row-full-height">
    	<div class="row-full-height">
	    	<!-- center block -->
	    	<div class="col-lg-8 col-lg-offset-2 cnt-center fill-height">
				<div class="holder-rows container-fluid-">
				<div class="row hd-wraper no-gutter">
					<div class="col-sm-8 col-sm-offset-2 no-gutter display-table- valign-parent">
						<div class="col-xs-1 col-sm-1 col-md-3 hd-logo vertical-align-bottom">
							<img style="width: 100%;" src="<?php echo base_url("public/images/telecom_logo.png"); ?>" />
						</div>
						<div class="col-xs-5 col-sm-5 col-md-9 col-md-table-cell hd-text vertical-align-bottom">
							<div class="text-right color-white">eKYC BUSINESS ADMINISTRATION PLATFORM</div>
						</div>
					</div>
				</div>
				<div class="row relative">
					<?php if(isset($CONTENT)) $this->view($CONTENT);?>
					<?php if(isset($html)) echo $html;?>
				</div><!-- end row content -->
				<!-- FOOTER -->
				<footer>
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2">
							<p>&copy;<?php echo date('Y');?> Telecom Co., Ltd. All rights reserved.</p>
						</div>
					</div>
				</footer><!-- end FOOTER -->
			</div><!-- end big center column -->
			</div>
		</div>
		</div><!-- end row -->
</div><!-- end container-fluid -->

<div class="clearfix"></div>
<script type="text/javascript">
	$(document).ready(function(e) {
		$('.close').click(function(e) {
			$(this).closest(".ot-dialog").hide();
		});

		$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

		<?php if($this->session->userdata('msg')){?>
				bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
				//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
		<?php $this->session->unset_userdata('msg');}?>
			
        }); //end ready()
</script>
<?php $this->view('layout/footer');?>