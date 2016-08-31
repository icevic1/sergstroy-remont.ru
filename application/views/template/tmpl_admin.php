<?php $this->view('template/tmpl_header')?>
	<div class="wraper-header">
    	<a href="<?php echo base_url('admin/account/logout/')?>" class="btn" style="float:right; padding-top:5px"><i class="cus-lock-open"></i>Logout</a>
    	<a class="admin-logo" href="<?php echo base_url('admin/home')?>"><img src="<?php echo base_url('public/images/logo.png')?>" border="0"/></a>
    </div>
    <div class="container-fluid">  
        <div class="row-fluid">    
            <div class="span2">  
                 <div class="well nav-collapse sidebar-nav">
                    <ul class="nav nav-tabs nav-stacked main-menu">
                        <li class="nav-header hidden-tablet">Main</li>
                        <li><a class="ajax-link" href="<?php echo base_url('admin/home/')?>">
                            <i class="cus-house"></i><span class="hidden-tablet"> Dashboard </span></a>
                        </li>
                        <?php if($menus){?>
                            <?php foreach($menus as $menu):?>
                            <li><a class="ajax-link" href="<?php echo base_url($menu->page_url)?>">
                                <i class="<?php echo $menu->page_icon?>"></i><span class="hidden-tablet"> <?php echo $menu->page_name?> </span></a>
                            </li>
                            <?php endforeach?>
                        <?php }?>
                    </ul>
                </div><!--/.well -->
            </div>
            <div class="span10"> 
                 <div class="row-fluid">		
                        <?php if(isset($CONTENT))$this->view($CONTENT);?>
                 </div>     
            </div>
         </div>
    </div>
    <div class="wraper-footer"></div>
<?php $this->view('template/tmpl_footer')?>