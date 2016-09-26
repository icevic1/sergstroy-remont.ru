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
				<ul class="nav nav-pills" role="tablist">
					<li role="presentation"><a class="" data-filter="*" href=""><i class="icon icon-reorder"></i> Все</a></li>
					<li role="presentation"><a data-filter=".cat1" href="" class="selected"><i class="icon icon-th-large"></i> Дизайн</a></li>
					<li role="presentation"><a data-filter=".cat2" href="" class=""><i class="icon icon-th-list"></i> Демонтаж</a></li>
					<li role="presentation"><a data-filter=".cat3" href="" class=""><i class="icon icon-th-list"></i> Ход работы</a></li>
					<li role="presentation"><a data-filter=".cat4" href="" class=""><i class="icon icon-th"></i> Результат</a></li>
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