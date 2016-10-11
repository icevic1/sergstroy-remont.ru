<div class="box no-margin">
    <div class="box-header well">
        <?php if($per_page['page_name']) echo '<h2><i class="'.$per_page['page_icon'].'"></i> '.$per_page['page_name'].'</h2>'?>
        <div class="box-icon">
            <a href="<?php echo base_url('admin/')?>" class="btn-link"><i class="cus-arrow-undo"></i> Назад</a>
        </div>
    </div>
    <div class="box-content">
    
        <table class="table gray-head gradient-thead compact table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>Аватар</th>
                  <th>Детали отзыва</th>
                  <th>Отзыв</th>
                  <th style="width: 70px;">Действия</th>
              </tr>
          </thead>   
          <tbody>
          <?php if (isset($itemsList)) {?>
          	<?php foreach($itemsList as $item) :?>
            <tr>
                <td class="text-center"><?php if($item['image']) echo '<img width="50" src="'.$item['image'].'" />';?></td>
                <td><dl class="no-margin">
                        <dt>Автор</dt><dd><?php echo $item['name'];?></dd>
                        <dt>Дата создания</dt><dd><?php echo $item['created_at'];?></dd>
                        <dt>IP Адресс</dt><dd><?php echo $item['ip_address'];?></dd>
                        <dt>Тип отзыва</dt><dd><?php echo ($item['is_video']?'Видео':'Пользовательский');?></dd>
                    </dl>
                </td>
                <td><?php echo $item['review'];?></td>
<!--                <td>--><?php //echo ($item['is_video']? 'Просмотреть':'');?><!--</td>-->
                <td>
                    <table style="border: 0 none; height: 100%;"><tr><td>
                            <label><strong>Статус</strong>
                                <select class="input-medium" data-toggle="selected-changer" data-item_id="<?php echo $item['id']; ?>" data-field="published" data-action_url="<?php echo base_url("/admin/reviews/change_field")?>">
                                    <option value="0" <?php echo ($item['published'] == 0?' selected="selected"':'')?>>Скрыт</option>
                                    <option value="1" <?php echo ($item['published'] == 1?' selected="selected"':'')?>>Опубликован</option>
                                </select>
                            </label>
                            <label><strong>На главной</strong>
                                <select class="input-medium"  data-toggle="selected-changer" data-item_id="<?php echo $item['id']; ?>" data-field="on_home" data-action_url="<?php echo base_url("/admin/reviews/change_field")?>">
                                    <option value="0" <?php echo ($item['on_home'] == 0?' selected="selected"':'')?>>Скрыт</option>
                                    <option value="1" <?php echo ($item['on_home'] == 1?' selected="selected"':'')?>>Показан</option>
                                </select>
                            </label>
                        </td></tr>
                        <tr><td></td></tr>
                        <tr><td valign="bottom">
                        <span style="height: auto;">
                            <a class="btn btn-block btn-mini btn-danger" href="<?php echo site_url("admin/reviews/delete/{$item['id']}")?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись?')">Удалить запись</a>
                        </span>
                        </td></tr>
                    </table>
                </td>
            </tr>
            <?php endforeach;?>
            <?php } //end if table?>
            </tbody>
         </table>
         
      </div>
</div>
<script type="text/javascript">
$(document).ready(function() {


});
</script>
<style>

</style>