<?php $this->view('layout/header');?>

<header class="navbar navbar-default- navbar-inverse navbar-static-top navbar-fixed-top-">
  <div class="container<?php echo (isset($BODY_CLASS) && $BODY_CLASS == 'sts'? '-fluid': '');?>">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#hd_navbar_collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo site_url('home')?>"><img border="0" style="vertical-align: middle;" src="<?php echo base_url('public/images/telecom_logo.png');?>" /></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="hd_navbar_collapse">
      <ul class="nav navbar-nav">
      	<?php if($this->acl->can_view(null, 1 /*1 Main page*/)) {?>
      	<li class="<?php echo (($this->router->fetch_class() == 'home' && in_array($this->router->fetch_method(), array('index', 'save', 'profile')) )? 'active':'');?>">
        	<a href="<?php echo site_url("home/");?>" data-toggle="popover" data-placement="auto" data-html="true" data-container="body" data-trigger="hover focus" data-content="Applications Form Management"><i class="icomoon icon-home"></i> Applications Management</a>
        </li>
        <?php }?>
      	<li class="<?php echo (($this->router->fetch_class() == 'inventory' && in_array($this->router->fetch_method(), array('index', 'simprofile', 'save')))? 'active':'');?>">
	    	<a href="<?php echo site_url("inventory/");?>" data-toggle="popover" data-placement="auto" data-html="true" data-container="body" data-trigger="hover focus" data-content="Inventory Management System">IMS</a>
	    </li>
        <li class="<?php echo (($this->router->fetch_class() == 'dealer' && in_array($this->router->fetch_method(), array('index', 'save', 'profile')) )? 'active':'');?>">
        	<a href="<?php echo site_url("dealer/");?>" data-toggle="popover" data-placement="auto" data-html="true" data-container="body" data-trigger="hover focus" data-content="Dealer Registration Platform">DRP</a>
        </li>
        <li class="<?php echo (($this->router->fetch_class() == 'inventory' && in_array($this->router->fetch_method(), array('phone_numbers')) )? 'active':'');?>">
        	<a href="<?php echo site_url("inventory/phone_numbers");?>" data-toggle="popover" data-placement="auto" data-html="true" data-container="body" data-trigger="hover focus" data-content="Customer Relationship Management">CRM</a>
        </li>
        
        <li class="<?php echo (($this->router->fetch_class() == 'kyc' && in_array($this->router->fetch_method(), array('index', 'newST', 'detailsST')) )? 'active':'');?>">
        	<a href="" onclick="bootbox.alert('This feature is Under Construction');return false;" data-toggle="popover" data-placement="auto" data-html="true" data-container="body" data-trigger="hover focus" data-content="Content Management System">CMS</a>
        </li>
        <li class="dropdown<?php echo (($this->router->fetch_class() == 'reports' && in_array($this->router->fetch_method(), array('index')) )? ' active':'');?>">
        	<a href="<?php echo site_url("reports/");?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
        	<ul class="dropdown-menu">
	          <li><a href="<?php echo site_url("reports/");?>">by Dealer</a></li>
	          <li><a href="<?php echo site_url("reports/");?>">by Sales ID</a></li>
	          <li><a href="<?php echo site_url("reports/");?>">by Status</a></li>
	          <li><a href="<?php echo site_url("reports/");?>">by Province</a></li>
	          <li><a href="<?php echo site_url("reports/");?>">by City</a></li>
	        </ul>
	    </li>
	    <li class="<?php echo ((in_array($this->router->fetch_method(), array('index1', 'profile1', 'save1')))? 'active':'');?>">
	    	<a class="" href="<?php echo site_url("alert/");?>" onclick="bootbox.alert('This feature is Under Construction');return false;">Alerts 
	    		<?php echo (isset($countActiveAlerts) && $countActiveAlerts > 0 ? '<span class="active badge blink_me">'.$countActiveAlerts. '</span>': '<span class="badge">0</span>')?>
	    	</a>
	    </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li><a class="middle" href="#" data-toggle="popover" data-placement="auto" data-html="true" data-container="body" data-trigger="hover focus" data-content="<?php echo (isset($this->session->userdata('staff')['roles'])? implode(', ', $this->session->userdata('staff')['roles']):'N/A');?>"><span class="glyphicon glyphicon-user"></span> 
	        	<?php if ($this->session->userdata('staff')) {?>
	    		<?php echo (isset($this->session->userdata('staff')['name']))? $this->session->userdata('staff')['name']: '';?>
	    		<?php } ?>
    		</a>
    	</li>
        <li><a class="middle" href="<?php echo site_url('account/logout');?>"><span class="glyphicon glyphicon-log-out"></span> <span class="title hidden-xxs"><?php echo label('lbl_logout');?></span></a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</header>
