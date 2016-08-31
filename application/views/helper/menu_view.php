<ul class="nav nav-tabs gradient-gray">
	<?php foreach ($menu_items as $item) {
			if ($this->acl->can_read(null, $item->acl_resource_id) == false) continue;
			
			$class = '';
			if ($active_item && is_null($active_item->parent_id) && $item->menu_id == $active_item->menu_id) {
				$class = 'active';
			} elseif ($active_item && !is_null($active_item->parent_id) && $item->menu_id == $active_item->parent_id) {
				$class = 'active';
			} elseif (!$active_item && $item->isMain == '1') {
				$class = 'active';
			}?>
	    <li class="<?php echo $class;?>">
	    	<a href="<?php echo site_url("{$item->controller}/".((!empty($item->action))? $item->action: ''));?>"><?php echo $item->menu_title;?></a>
	    </li>
    <?php }?>
</ul>