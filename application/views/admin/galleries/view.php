<div class="box no-margin">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
        	<?php if($per_page['per_save']==1){?>
            <a href="<?php echo base_url('admin/galleries/edit/')?>" class="btn-link"><i class="cus-add"></i> Дабавить</a>
            <?php }else{?>
            <a class="btn disabled" href="" ><i class="cus-add"></i> Дабавить</a>
            <?php }?>
        </div>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Назад</a>
        </div>
    </div>
    <div class="box-content">
        <!-- Миниатюры Сетка для картинок, видео, текста и т.д.-->
        <div class="page-header">
            <h1 itemprop="name"><?php echo $albumItem['name'];?><?php echo ($albumItem['published']?'<sup><span class="label label-success">Опубликован</span></sup>':'<span class="label label-important">Не публикован</span>');?>
                <abbr title="attribute"><small><?php echo $albumItem['user_name'] . ", {$albumItem['address']}, (тел. {$albumItem['mobile_no']})";?></small></abbr>
            </h1>
        </div>
        <blockquote>
            <?php echo $albumItem['description'];?>
        </blockquote>

        <div class="upload-form-holder well">
            <?php echo form_open_multipart('admin/galleries/upload', array('id' => 'form_upload_photo', 'name'=>'form-gallery-photo', 'class' => 'form-horizontal form-review', 'role'=>'form'), array('gallery_id'=>$albumItem['id']));?>
            <?php //echo form_hidden(array('name'=>'gallery_id','value'=>set_value('gallery_id')));?>

            <div class="row-fluid error-holder hidden">
                <div class="alert alert-danger none">
                    <a href="#" class="close" data-hide="alert">&times;</a>
                    <strong>Ошибка!</strong>
                    <ul class="errors-list"></ul>
                </div>
            </div>

            <div class="form-group relative">
                <a class="btn btn-primary-" href="javascript:void(0);"><!--$('#userfile').click()-->
                    Выберите картинки...
                    <input type="file" id="userfiles" name="userfile" size="40"  /><!-- multiple="multiple" onchange='console.log($(this));return;$("#upload-file-info").html($(this).val());'-->
                </a>
                <span id="upload-files-info"></span>
                <span class='label label-info'><?php echo form_error('image')?></span>
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="icon-upload icon-white"></i> Загрузить</button>
            </div>
            <?php echo form_close(); ?>
        </div><!--.review-form-holder-->


          <div class="row-fluid">
            <ul class="thumbnails gallery-photos">
<!--                <li class="span3" style="display:none"></li>-->
                <?php if ($albumPhotos) foreach ($albumPhotos as $item) { ?>
                <li class="span3">
                    <a class="thumbnail" href="<?php echo $item['photo'];?>">
                        <img alt="260x180" data-src="holder.js/260x180" style="width: 260px; height: 180px;" src="<?php echo $item['thumb'];?>" />
                    </a>
                    <div class="thump-head">
                        <a class="btn btn-danger btn-mini" href="#"><i class="icon-trash icon-white"></i></a>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>

      </div>
</div>
<script type="text/javascript">
$(document).ready(function() {


});
</script>
<style>

</style>