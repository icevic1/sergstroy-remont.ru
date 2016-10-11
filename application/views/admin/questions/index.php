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
                  <th>ID</th>
                  <th>Имя</th>
                  <th>Контактный телефон</th>
                  <th>Вопрос</th>
                  <th>Дата поступления</th>
                  <th style="width: 70px;">Действия</th>
              </tr>
          </thead>   
          <tbody>
          <?php if (isset($itemsList)) {?>
          	<?php foreach($itemsList as $item) :?>
            <tr>
                <td class="text-center"><?php echo $item['id'];?></td>
                <td><?php echo $item['name'];?></td>
                <td><?php echo $item['phone'];?></td>
                <td><?php echo $item['question'];?></td>
                <td><?php echo $item['created_at'];?></td>
                <td><a class="btn btn-block btn-mini btn-danger" href="<?php echo site_url("admin/questions/delete/{$item['id']}")?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись?')">Удалить</a></td>
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