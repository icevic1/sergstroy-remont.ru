<!-- Projects reviews block -->
<style>
	.grid {
		background: #DDD;
	}

	/* clear fix */
	.grid:after {
		content: '';
		display: block;
		clear: both;
	}

	/* ---- .element-item ---- */

	/* 5 columns, percentage width */
	.grid-item,
	.grid-sizer {
		width: 20%;
	}
	.grid-item {
		float: left;
		/*width: 260px;*/
		height: auto;
		padding: 3px;
		/*background: #0D8;*/
		/*border: 2px solid #333;*/
		/*border-color: hsla(0, 0%, 0%, 0.7);*/
	}
</style>
<!-- Page Content -->
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<div id="filter">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active" onclick="$('#filter ul li.active').removeClass('active'); $(this).addClass('active')"><a data-filter="*" href="" class="selected"><i class="icon icon-reorder"></i> Все</a></li>
					<?php if ($categoryOptions) foreach ($categoryOptions as $option) { ?>
					<li role="presentation" onclick="$('#filter ul li.active').removeClass('active'); $(this).addClass('active')"><a data-filter=".cat<?php echo $option['category_id']?>" href=""><?php echo $option['category_name']?></a></li>
					<?php }?>
				</ul>
			</div>
		</div>
	</div>
	</div>
<div class="container">
	<!--<div class="col-lg-12">
		<h1 class="page-header">Список фотоальбомов</h1>
	</div>-->
<!--	<div class="grid-sizer"></div>-->
	<!--col-lg-3 col-md-4 col-xs-6 -->
	<div class="row thumbs-holder">
		<?php if ($itemsList) foreach ($itemsList as $item) { ?>
			<div class="col-lg-3 col-md-4 col-xs-6 thumb cat<?php echo $item['category_id'];?>">
				<a href="<?php echo $item['photo'];?>" class="thumbnail" >
					<img class="img-responsive" src="<?php echo $item['thumb'];?>" />
				</a>
			</div>
		<?php } ?>
	</div>
</div>

<!--<div class="row thumbs-holder">
	<div class="col-lg-12">
		<h1 class="page-header">Список фотоальбомов</h1>
	</div>
	<div class="grid-sizer"></div>
	<?php /*if ($itemsList) foreach ($itemsList as $item) { */?>
		<div class="col-lg-3 col-md-4 col-xs-6 thumb cat<?php /*echo $item['category_id'];*/?>">
			<a href="<?php /*echo $item['thumb'];*/?>" class="thumbnail" >
				<img class="img-responsive" src="<?php /*echo $item['thumb'];*/?>" />
			</a>
		</div>
	<?php /*} */?>
</div>-->
<!-- Client question block -->
<?php $this->view('partial/client_question');?>

<!-- Find us on map block-->
<?php $this->view('partial/map');?>