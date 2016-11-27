<!-- price list block -->
<!-- Wrapper for Slides -->
<div class="container" id="clientReviews">
    <div class="row">
        <div class="col-sm-12 price-list text-right">
            <a class="btn btn-link- text-success" href="<?php echo base_url('assets/upload/price-list.xlsx')?>" title="Скачать полный список цэн"><i class="icon-file-excel"></i> <?php echo label('button label: Прай-лис');?></a>
        </div>
    </div>
<!--    <div class="well well-lg">-->

        <div class="table-responsive">
            <?php echo label('таблица с ценами');?>
        </div><!--.review-form-holder-->
<!--    </div>-->
</div><!-- .container -->
<!-- Client question block -->
<?php $this->view('partial/client_question');?>

<!-- Find us on map block-->
<?php $this->view('partial/map');?>