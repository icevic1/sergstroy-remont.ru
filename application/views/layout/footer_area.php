<!-- FOOTER -->
<footer>
	<div class="footer2">
		<div class="container">
			<div class="row">
				<div class="col-xs-6 col-sm-5">
					<a class="footer-logo center-block" href="/"><img src="/public/img/logo/logo.png" class="img-fluid" alt="sertstroy-remont logo" /></a>
				</div>
				<div class="col-xs-6 col-sm-7">
					<div class="footer-links text-right">
						<div class="ion-iphone"> <?php echo $siteSettings['phone']?></div>
						<div class="ion-email"> <a href="mailto:<?php echo $siteSettings['email']?>"> <?php echo $siteSettings['email']?></a></div>
						<div class="copyright">&copy; <?php echo $siteSettings['site_name']?>.</div>
					</div>
				</div>
				<div class="clearfix visible-xs col-xs-12"></div>
			</div>
		</div>
	</div>
</footer>
<?php $this->view('review/add-review.modal.php');?>
<?php $this->view('layout/footer');?>