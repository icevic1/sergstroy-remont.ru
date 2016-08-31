<?php $this->view('template/tmpl_header')?>
	<div class="wraper-header">
    	<a href="<?php echo base_url('admin/account/logout/')?>" class="btn" style="float:right; padding-top:5px"><i class="cus-lock-open"></i>Logout</a>
    	<a href="<?php echo base_url('admin/home')?>"><img src="<?php echo base_url('public/images/logo.png')?>" border="0"/></a>
    </div>
    <div class="container-fluid">  
         <div class="row-fluid">		
                <?php if(isset($CONTENT))$this->view($CONTENT);?>
         </div>     
    </div>
    <div class="wraper-footer"></div>
<?php $this->view('template/tmpl_footer')?>