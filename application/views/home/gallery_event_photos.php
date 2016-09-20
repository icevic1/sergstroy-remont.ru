<ol class="carousel-indicators">
	<?php if ($albumPhotos) foreach ($albumPhotos as $index=>$item) { ?>
		<li data-target="#galleryCarousel" data-slide-to="<?php echo $index;?>" class="<?php echo (($index==0)?'active':'')?>"></li>
	<?php } ?>
</ol>
<div class="carousel-inner">
	<?php if ($albumPhotos) foreach ($albumPhotos as $index=>$item) { ?>
		<div class="item<?php echo (($index==0)?' active':'')?>">
			<a href="<?php echo $item['photo'];?>" class="stage-img center-block image-item" >
				<img style="" src="<?php echo $item['photo'];?>" />
			</a>
		</div>
	<?php } ?>
</div>
<a class="left carousel-control" href="#galleryCarousel" data-slide="prev">
	<i class="fa fa-arrow-left"></i>
</a>
<a class="right carousel-control" href="#galleryCarousel" data-slide="next">
	<i class="fa fa-arrow-right"></i>
</a>
<div id="ajax_preloader_gallery" class="bg_opacity">
	<img class="ajax-loader" alt="waiting..." src="<?php echo base_url('public/img/ajax-loader.gif'); ?>" />
</div>