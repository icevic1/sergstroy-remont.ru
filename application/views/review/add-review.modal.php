<div id="review_cnt" class="container-fluid hide">
	<div class="review-form-holder">
		<?php echo form_open_multipart('review/store', array('id' => 'form-guest-review', 'name'=>'form-guest-review', 'class' => 'form-horizontal form-review', 'role'=>'form'));?>
		<div class="row-fluid">
			<div class="col-sm-12">
				<div class="form-group required">
					<?php echo form_input(array('name'=>'name','value'=>set_value('name'), 'class'=>'form-control','id'=>'review_name','placeholder'=>'Ваше имя'));?>
					<?php echo form_error('name');?>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="col-sm-12">
				<div class="form-group relative">
					<a class="btn btn-primary" href='javascript:;'>
						Выберите картинку...
						<input type="file" class="file-choose-btn" name="userfile" size="40" onchange='$(this).parent("a").next(".label-info").html($(this).val());' />
					</a>
					<span class='label label-info' id="upload-file-info"><?php echo form_error('image')?></span>
					<div class="error"><?php echo form_error('image');?></div>
				</div>
			</div>
		</div>
		<div class="row-fluid review-text">
			<div class="col-sm-12">
				<div class="form-group required">
					<?php echo form_textarea(array('name'=>'review','value'=>set_value('review'), 'class'=>'form-control','id'=>'review','rows'=>'3','placeholder'=>'Ваше мнение...'));?>
					<?php echo form_error('review');?>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="col-sm-12">
				<div class="form-group text-right">
					<?php echo form_submit(array('name'=>'submit_review', 'value'=> "Отправить", 'class'=>'btn app-btn btn-orange'));?>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div><!--.review-form-holder-->
</div>