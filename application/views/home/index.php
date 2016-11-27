<!--we can use class img-circle and width&height on img instead of my scripts-->
<div class="container">
	<div class="row circles-holder">
		<div class="col-md-4 text-center">
			<div class="circle-block" >
				<div class="circle-inner img-terms"></div>
			</div>
			<div class="title-circle" data-toggle="hover-popover">
				<h2><?php echo label('Terms');?></h2>
				<h4><?php echo label('Terms_short_description');?></h4>
			</div>
			<div class="popover-content fade in" data-position="left">
				<?php echo label('Terms_description_popover');?>
			</div>
		</div>
		<div class="col-md-4 text-center">
			<div class="circle-block">
				<div class="circle-inner img-quality"></div>
			</div>
			<div class="title-circle">
				<h2><?php echo label('Quality');?></h2>
				<h4><?php echo label('Quality_short_description');?></h4>
			</div>
			<div class="popover-content fade in" data-position="center">
				<h2><?php echo label('Quality_description_popover');?></h2>
			</div>
		</div>
		<div class="col-md-4 text-center">
			<div class="circle-block">
				<div class="circle-inner img-result"></div>
			</div>
			<div class="title-circle">
				<h2><?php echo label('Result');?></h2>
				<h4><?php echo label('Result_short_description');?></h4>
			</div>
			<div class="popover-content fade in" data-position="right">
				<?php echo label('Result_description_popover');?>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row design-holder">
		<div class="col-sm-12 title text-center">
			<h1><?php echo label('Состав дизайн проекта');?></h1>
		</div>
	</div>
	<div class="row design-holder-blue">
		<div class="row-md-height">
			<div class="col-xs-12 col-md-5 col-md-height col-middle">
				<div class="inside">
					<div class="content question-block text-center">
						<h1 class="bonuses">+ Бонус</h1>
						<p><?php echo label('2 выезда дизайнера во время работы!');?></p>
						<a href="#clientQuestion" class="app-btn btn-orange"><?php echo label('Задать вопрос');?></a>
					</div>
				</div>
			</div><!--
            --><div class="col-xs-12 col-md-7 col-md-height col-top">
				<div class="inside">
					<div class="content facility-block services-list">
						<?php echo label('Состав дизайн проекта список');?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Projects slider block -->
<div id="projectsCarusel" class="container-fluid">
	<div class="row projects-holder">
		<div class="col-sm-12 title text-center">
			<h1><?php echo label('Projects');?></h1>
		</div>
	</div>
	<div class="row projects-holder-slider">
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
                <?php if ($projectsPhotos) foreach ($projectsPhotos as $index=>$item) { ?>
				<li data-target="#myCarousel" data-slide-to="<?php echo $index;?>" <?php echo (($index==0)?'class="active"':'');?>></li>
                <?php }?>
			</ol>
			<div class="carousel-inner">
                <?php if ($projectsPhotos) foreach ($projectsPhotos as $index=>$item) { ?>
				<div class="item<?php echo (($index==0)?' active':'');?>" >
					<div class="fill" style="background-image:url('<?php echo $item['photo']; ?>');"><img src="<?php echo $item['photo']; ?>" /></div>
					<!--<div class="carousel-caption"><h2>Caption 1</h2></div>-->
				</div>
                <?php }?>
			</div>
			<!-- Controls -->
			<a class="left carousel-control" href="#myCarousel" data-slide="prev">
				<i class="fa fa-arrow-left"></i>
			</a>
			<a class="right carousel-control" href="#myCarousel" data-slide="next">
				<i class="fa fa-arrow-right"></i>
			</a>
		</div><!-- end of carusel-->
	</div>
</div><!-- end Projects slider block -->

<!-- Prices block -->
<div id="priceBlock" class="container-fluid">
	<div class="row preices-holder">
		<div class="col-sm-12 title text-center">
			<h1><?php echo label('title block Цэны');?></h1>
		</div>
	</div>
	<div class="row preices-holder-content">
		<div class="col-md-12">
			<div class="container price-pack-holder services-list">
				<?php echo label('main prices цены');?>
				<!--<div class="row">
					<div class="col-sm-8">
						<ul>
							<li>Обмерный чертеж помещения</li>
							<li>План после перепланировки с экспликацией помещений</li>
							<li>План после перепланировки с размерами, уровнем пола и высотами</li>
							<li>План демонтажа перегородок</li>
						</ul>
					</div>
					<div class="col-sm-4 text-right">
						<h1>800 <span>руб./м<sup>2</sup></span></h1>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-8">
						<ul>
							<li>Обмерный чертеж помещения</li>
							<li>План после перепланировки с экспликацией помещений</li>
							<li>План после перепланировки с размерами, уровнем пола и высотами</li>
							<li>План демонтажа перегородок</li>
						</ul>
					</div>
					<div class="col-sm-4 text-right">
						<h1>1500 <span>руб.</span></h1>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-8">
						<ul>
							<li>Регулярные выезды автора проекта на объект.</li>
							<li>Регулярные выезды автора проекта в магазины.</li>
							<li>Консультации строителей и поставщиков.</li>
							<li>Своевременное внесение в проект изменений с согласия заказчика.</li>
							<li>Консультация заказчика по выбору декоративных и отделочных материалов.</li>
						</ul>
					</div>
					<div class="col-sm-4 text-right">
						<h1>1500 <span>руб.</span></h1>
					</div>
				</div>-->
				<div class="row">
					<div class="col-sm-12 price-list text-right">
						<a class="btn btn-link- text-success" href="<?php echo base_url('assets/upload/price-list.xlsx')?>" title="Скачать полный список цэн"><i class="icon-file-excel"></i> <?php echo label('button label: Прай-лис');?></a>
						<a class="app-btn btn-orange" href="<?php echo site_url('home/price_list')?>">Подробнее</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- end Projects prices block -->
<!-- Projects feedback block -->
<div id="clientReviews" class="container-fluid">
	<div class="row reviews-holder">
		<div class="col-sm-12 title text-center">
			<h1 class="color-orange"><?php echo label('Отзывы');?></h1>
		</div>
	</div>
	<div class="row reviews-holder-slider">
		<div id="myCarousel2" class="carousel slide" data-ride="carousel">
			<!-- Wrapper for Slides -->
			<div class="container">
				<div class="carousel-inner">
					<?php
						if(isset($reviewItems) && $reviewItems) {
							foreach ($reviewItems as $key=>$item) {
					?>
					<div class="item <?php echo (($key==0)? 'active':'')?>">
						<!-- Set the first background image using inline CSS below. -->
						<div class="row circles-holder">
							<div class="col-sm-3 col-sm-offset-1 review-img-block">
								<div class="circle-block">
									<div class="circle-inner img-review">
										<?php if($item['image']) {?>
											<img src="<?php echo $item['image']?>" />
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-sm-7 review-content-block">
								<h1><?php echo $item['name']?></h1>
								<div class="quoted"><p><?php echo $item['review']?></p></div>
							</div>
						</div>
					</div>
					<?php }}?>
				</div>
			</div>
			<!-- Controls -->
			<a class="left carousel-control" href="#myCarousel2" data-slide="prev">
				<i class="fa fa-arrow-left"></i>
			</a>
			<a class="right carousel-control" href="#myCarousel2" data-slide="next">
				<i class="fa fa-arrow-right"></i>
			</a>
		</div><!-- end of carusel-->
		<div class="container text-right">
			<a href="/review/" class="app-btn btn-orange"><?php echo label('Отзывы клиентов');?></a>
		</div>
	</div>
</div><!-- end Projects slider block -->
<!-- Projects video feedback block -->
<div class="container-fluid bg-gray">
	<div class="row reviews-holder">
		<div class="col-sm-12 title text-center">
			<h1 class="color-orange"><?php echo label('Видео Отзывы');?></h1>
		</div>
	</div>
	<div class="row vreviews-holder-slider">
		<div id="myCarousel3" class="carousel slide" data-ride="carousel">
			<!-- Wrapper for Slides -->
			<div class="container">
				<div class="carousel-inner">
					<?php
					if(isset($reviewVideoItems) && $reviewVideoItems) {
						foreach ($reviewVideoItems as $key=>$item) {
					?>
					<div class="item <?php echo (($key==0)? 'active':'')?>">
						<div class="row circles-holder">
							<div class="col-sm-3 col-sm-offset-1 review-img-block">
								<div class="circle-block">
									<div class="circle-inner img-vreview <?php echo ((isset($item) && $item['video'])? 'is_video': '')?>">
										<?php if($item['image']) {?>
											<img src="<?php echo $item['image']?>" />
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-sm-7 review-content-block">
								<h1><?php echo $item['name']?></h1>
							</h1>
							</div>
						</div>
					</div>
					<?php }}?>
				</div><!-- end carousel-inner -->
			</div><!-- end container -->
			<!-- Controls -->
			<a class="left carousel-control" href="#myCarousel3" data-slide="prev">
				<i class="fa fa-arrow-left"></i>
			</a>
			<a class="right carousel-control" href="#myCarousel3" data-slide="next">
				<i class="fa fa-arrow-right"></i>
			</a>
		</div><!-- end of carusel-->
		<div class="container text-right">
			<a href="/review/#video" class="app-btn btn-orange"><?php echo label('Отзывы клиентов');?></a>
		</div>
	</div>
</div><!-- end Projects slider block -->
<!-- Client question block -->
<?php $this->view('partial/client_question');?>

<!-- Find us on map block-->
<?php $this->view('partial/map');?>

<!-- about block-->
<?php $this->view('partial/about');?>

<!-- Login block -->
<div id="client_login" class="container-fluid stages-holder">
	<div class="row">
		<div class="col-sm-12 title text-center">
			<h1><?php echo label('Block title: Этапы работ - Для клиентов');?></h1>
		</div>
	</div>
	<div class="row u-form">
		<div class="col-md-12">
			<div class="container">
				<div class="row">
					<?php $this->view('account/login_client_form');?>
				</div>
			</div>
		</div>
	</div>
	<?php if (isset($client) && $client) { ?>
	<div class="row stages-content">
		<div class="col-md-12">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-12">
								<!-- Responsive calendar - START -->
								<div class="responsive-calendar">
									<div class="controls">
										<a class="pull-left" data-go="prev"><i class="fa fa-arrow-left"></i></a>
										<h4><span data-head-year></span> <span data-head-month></span></h4>
										<a class="pull-right" data-go="next"><i class="fa fa-arrow-right"></i></a>
									</div><div class="clearfix"></div>
									<div class="day-headers">
										<div class="day header">П</div>
										<div class="day header">В</div>
										<div class="day header">С</div>
										<div class="day header">Ч</div>
										<div class="day header">П</div>
										<div class="day header">С</div>
										<div class="day header">В</div>
									</div>
									<div class="days" data-group="days">

									</div>
				 				</div>
								<!-- Responsive calendar - END -->
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 u-details">
								<h2 class="color-orange"><?php echo $client['name'];?></h2>
								<div class="text-muted"><?php echo $client['address'];?></div>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<!-- <a href="#" class="stage-img center-block"></a>-->
						<div id="galleryCarousel" class="carousel slide relative" data-ride="carousel">
							<?php //$this->view('home/gallery_event_photos', array('albumPhotos'=>$albumPhotos));?>
						</div><!-- end of carusel-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div><!-- end Projects slider block -->