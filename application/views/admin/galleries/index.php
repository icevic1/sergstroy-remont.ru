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
    
        <table class="table gray-head gradient-thead compact table-striped table-bordered bootstrap-datatable datatable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Название</th>
                  <th>Количество</th>
                  <th>Клиент</th>
                  <th>Описание</th>
                  <th>Просмотры</th>
                  <th>Опубликован</th>
                  <th>Дата создания</th>
                  <th style="width: 100px;">Actions</th>
              </tr>
          </thead>   
          <tbody>
          <?php if (isset($itemsList)) {?>
          	<?php foreach($itemsList as $item) :?>
            <tr>
                <td><?php echo $item['id'];?></td>
                <td><a href="<?php echo site_url('admin/galleries/view/'.$item['id'])?>"><?php echo $item['name'];?></a></td>
                <td class="text-center"><?php echo $item['uploaded'];?></td>
                <td><?php echo $item['user_name'];?></td>
                <td><?php echo $item['description'];?></td>
                <td><?php echo $item['views'];?></td>
                <td><?php echo ($item['published']?'Да':"Нет");?></td>
                <td><?php echo $item['created_at'];?></td>
                <td>
                    <?php if($per_page['per_update']==1){?>
                    <a class="btn-link" href="<?php echo site_url('admin/galleries/edit/'.$item['id'])?>"><i class="cus-page-white-edit"></i> Edit</a>
                    <?php } else {?>
                    <a class="btn-link disabled" href=""><i class="cus-page-white-edit"></i>Edit</a>
                    <?php }?>
                    <?php if($per_page['per_delete']==1){?>
                    <a class="btn-link" href="<?php echo site_url("admin/galleries/delete/{$item['id']}")?>" onclick="return confirm('You are sure to delete this record')"><i class="cus-delete"></i>Delete</a>
                    <?php }else{?>
                    <a class="btn-link disabled" href="#"><i class="cus-delete"></i>Delete</a>
                    <?php }?>
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