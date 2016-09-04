<!-- Projects reviews block -->
<div id="client_reviews" class="container-fluid">
	<div class="row reviews-holder-slider">
		<!-- Wrapper for Slides -->
		<div class="container" id="clientReviews">
			<div class="review-form-holder well">
				<?php echo form_open_multipart('review/store', array('id' => 'form-guest-review', 'name'=>'form-guest-review', 'class' => 'form-horizontal form-review', 'role'=>'form'));?>
				<div class="col-sm-12 error-holder">
					<div class="alert alert-danger none">
						<a href="#" class="close" data-hide="alert">&times;</a>
						<strong>Ошибка!</strong>
						<ul class="errors-list"></ul>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group required">
								<?php echo form_input(array('name'=>'name','value'=>set_value('name'), 'class'=>'form-control','id'=>'review_name','placeholder'=>'Ваше имя'));?>
								<?php echo form_error('name');?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group relative">
								<a class="btn btn-primary" href='javascript:;'>
									Выберите картинку...
									<input type="file" name="userfile" size="40" onchange='$("#upload-file-info").html($(this).val());'>
								</a>
								<span class='label label-info' id="upload-file-info"><?php echo form_error('image')?></span>
								<div class="error"><?php echo form_error('image');?></div>
							</div>
						</div>
					</div>
					<div class="row review-text">
						<div class="col-sm-12">
							<div class="form-group required">
								<?php echo form_textarea(array('name'=>'review','value'=>set_value('review'), 'class'=>'form-control','id'=>'review','rows'=>'3','placeholder'=>'Ваше мнение...'));?>
								<?php echo form_error('review');?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4 q-action">
					<div class="row">
						<div class="col-sm-12">
							<?php echo form_submit(array('name'=>'submit_review', 'value'=> "Отправить", 'class'=>'btn app-btn btn-orange btn-block'));?>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div><!--.review-form-holder-->
			<div class="clearfix"></div>
			<div class="list-holder">
				<?php if(isset($itemsList) && $itemsList) { ?>
				<?php foreach ($itemsList as $item) { ?>
				<div class="item">
					<!-- Set the first background image using inline CSS below. -->
					<div class="row circles-holder">
						<div class="col-sm-3 col-sm-offset-1 review-img-block">
							<div class="circle-block">
								<div class="circle-inner img-review">
									<?php if($item['image']) echo '<img src="'.$item['image'].'" />';?>
								</div>
							</div>
						</div>
						<div class="col-sm-7 review-content-block">
							<h1><?php echo $item['name']; ?></h1>
							<div class="quoted"><p><?php echo $item['review'] ?></p></div>
						</div>
					</div>
				</div>
				<?php } //endforeach ?>
				<?php } //endif ?>
			</div>
			<div class="text-center">
<!--				TODO: Pagination-->
			</div>
		</div><!-- .container -->
	</div>
</div><!-- end reviews slider block -->
<!-- Client question block -->
<?php $this->view('partial/client_question');?>

<!-- Find us on map block-->
<?php $this->view('partial/map');?>