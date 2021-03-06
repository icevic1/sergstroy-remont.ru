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
            <a href="<?php echo base_url('admin/galleries/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Назад</a>
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

        <div class="upload-form-holder well relative">
            <?php echo form_open_multipart('admin/galleries/upload', array('id' => 'form_upload_photo', 'name'=>'form-gallery-photo', 'class' => 'form-horizontal form-review', 'role'=>'form'), array('gallery_id'=>$albumItem['id']));?>
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
                    <input type="file" id="userfiles" name="images[]" size="40" multiple="multiple" /><!--  onchange='console.log($(this));return;$("#upload-file-info").html($(this).val());'-->
                </a>
                <span id="upload-files-info"></span>
                <span class='label label-info'><?php echo form_error('image')?></span>
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="icon-upload icon-white"></i> Загрузить</button>
            </div>
            <?php echo form_close(); ?>
            <div id="ajax_preloader" class="bg_opacity">
                <img class="ajax-loader" alt="waiting..." src="<?php echo base_url('public/img/ajax-loader.gif'); ?>" />
            </div>
        </div><!--.review-form-holder-->
        <div id="photo_container" class="row-fluid">
            <?php if($this->session->userdata('msg')){ ?>
                <div class="alert alert-success">
                  <button data-dismiss="alert" class="close" type="button">×</button>
                  <?php
                  echo $this->session->userdata('msg');
                  $this->session->unset_userdata('msg');
                  ?>
                </div>
            <?php } ?>
            <ul class="thumbnails gallery-photos">
                <?php if ($albumPhotos) foreach ($albumPhotos as $item) { ?>
                <li class="span3 thumbnail">
                    <div class="thumb-head">
                        <a class="btn btn-danger btn-mini delete-photo" data-action="delete-photo<?php //echo $item['photo_id']?>" href="<?php echo site_url("/admin/galleries/delete_photo/{$item['photo_id']}")?>"><i class="icon-trash icon-white"></i></a>
                    </div>
                    <a href="<?php echo $item['photo'];?>" class="selector" ><!--data-imagelightbox="f"-->
                        <img alt="260x180" data-src="holder.js/260x180" style="width: 260px; height: 180px;" src="<?php echo $item['thumb'];?>" />
                    </a>
                    <form class="form-inline controls-row margin-top-5">
                        <div class="row-fluid">
                            <label class="control-label span4">Категория</label>
                            <select class="inline-block span8 category-changer" data-photo_id="<?php echo $item['photo_id']?>">
                                <option value="">---</option>
                                <?php if ($categoryOptions) foreach ($categoryOptions as $option) { ?>
                                <option value="<?php echo $option['category_id']?>" <?php echo ($item['category_id'] == $option['category_id']?' selected="selected"':'')?>><?php echo $option['category_name']?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="row-fluid">
                            <label class="checkbox">
                                <input type="checkbox" class="status-selected" data-photo_id="<?php echo $item['photo_id']?>" value="1" <?php echo ($item['selected']?' checked="checked"':'')?> />
                                Отобразить в галереи
                            </label>
                        </div>
                    </form>
                    <div class="bg_opacity ajax-preloader">
                        <img class="ajax-loader" alt="waiting..." src="<?php echo base_url('public/img/ajax-loader.gif'); ?>" />
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>

      </div>
</div>