<?php $this->view('layout/header');?>
<div class="head">
    <div class="overlay force-height-full">
        <div class="main-overlay"><!--this container will trik fix the scale div container like background image-->
            <nav class="navbar navbar-default navbar-static-top nav-social">
                <div class="container">
                    <ul class="nav navbar-nav navbar-right social-buttons">
                        <li class="evaluate"><a href=""></a><input type="search" name="evaluate-input" class="evaluate-input" placeholder="Оцени работу" value="" /></li>
                        <li class="fb"><a href="" onclick="fbShare();return false;"></a>
                        <li class="ok"><a href="" onclick="Share.odnoklassniki('<?php echo site_url();?>','<?php echo $siteSettings['description']?>');return false;"></a></li>
                        <li class="vk"><a href="" onclick="Share.vkontakte('<?php echo site_url();?>','<?php echo $siteSettings['title']?>','<?php echo base_url('/public/img/logo/logo.png');?>','<?php echo $siteSettings['description']?>');return false;"></a></li>
                        <li class="btn-google"><a href="" onclick="Share.google('<?php echo site_url();?>','<?php echo $siteSettings['description']?>');return false;"></a></li>
                        <li class="btn-twitter"><a href="" onclick="Share.twitter('<?php echo site_url();?>','<?php echo $siteSettings['description']?>');return false;"></a></li>
                    </ul>
<!--                    <a class="twitter-timeline" href="https://twitter.com/TwitterDev/likes">Tweets Liked by @TwitterDev</a>-->
                </div>
            </nav>
            <div class="clearfix"></div>
            <div class="container">
                <h1 class="contact-phone text-right" title="Контактный телефон"><?php echo $siteSettings['phone']?></h1>
            </div>
            <nav class="navbar navbar-default navbar-static-top navbar-custom-menu">
                <div class="container">
                    <div class="navbar-header">
                        <div class="row no-gutter">
                            <div class="col-xs-9 col-sm-12 brand-wrapper"><a class="navbar-brand block" href="/"><img src="<?php echo base_url('/public/img/logo/logo.png');?>" alt="Lavalite" class="img-responsive" /></a></div>
                            <div class="col-xs-3 visible-xs toggle-menu-wrapper">
                                <a href="#client_login"><img src="/public/img/key-icon.jpg" class="img-responsive" /></a>
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse"><img src="/public/img/toggle-menu-icon.jpg" class="img-responsive" /></button>
                            </div>

                        </div>
                    </div>
                    <div class="menu-wrapper">
                        <ul class="nav navbar-nav navbar-right nav-login hidden-xs">
                            <li class="login-btn" data-toggle="tooltip" data-placement="left" title="Доступ к разделу ежедневного фотоотчета с объектов"><a href="<?php echo site_url("/#client_login")?>"><img src="<?php echo base_url("/public/img/key-icon.jpg")?>" class="img-responsive" /></a></li>
                            <li class="collapse-btn" data-toggle="tooltip" data-placement="left" title="Разварачивание меню при просмотре с мобильного или планшета"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse"><img src="<?php echo base_url("/public/img/toggle-menu-icon.jpg")?>" class="img-responsive" /></button></li>
                        </ul>
                        <div class="collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo site_url("/#about")?>">О компании</a></li>
                                <li><a href="<?php echo site_url("/#priceBlock")?>">Цены</a></li>
                                <li><a href="<?php echo site_url("/galleries/")?>">Фотогалерея</a></li>
                                <li><a href="<?php echo site_url("/#clientReviews")?>"">Отзывы</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <?php if(isset($titlePage) && $titlePage) { ?>
            <div class="page-title">
                <div class="container">
                    <div class="title-text"><?php echo $titlePage ?></div>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</div>
