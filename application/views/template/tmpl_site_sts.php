<?php $this->view('template/tmpl_header_sts')?>
<div class="wraper-header">
	<a href="<?php echo site_url('account/logout/')?>" class="pull-right"><i class="ot-ico ot-ico-logout"></i>Logout</a>
	<span class="pull-right" style="margin-right: 10px;" title="<?php echo (isset($this->session->userdata('staff')['role_name']))? $this->session->userdata('staff')['role_name'] : 'N/A';?>">
		Welcome: <strong>
		<?php if ($this->session->userdata('visitor')) { 
					echo '0' . $this->session->userdata('visitor');
				} else {
					echo (isset($this->session->userdata('staff')['name']))? $this->session->userdata('staff')['name'] : 'N/A';
				}?></strong> 
	</span>
	<a class="main-logo pull-left" href="<?php echo base_url('home')?>"><img src="<?php echo base_url('public/images/logo.png')?>" border="0" /></a>
	<h1 class="sts-hd-logo"><?php echo ((!empty($PAGE_TITLE))? $PAGE_TITLE: 'Service Tickets System')?></h1>
	<div class="clear"></div>
</div>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="box">
	        <?php //var_dump(base_url(), current_url(),$this->router->fetch_class(), $this->router->fetch_method(), $navbar);?>
	        <div class="pull-right" style="margin: 7px 10px 0px 0px;">
	        	<?php if (isset($navBackLink)) :?>
					<a class="btn fixedsize btn-mini" href="<?php echo $navBackLink;?>"><i class="cus-arrow-undo"></i> Back</a>
				<?php endif;?>
	        	<?php if (isset($navCloseTicket)) :?>
					<a class="btn fixedsize btn-success btn-mini" id="close_ticket" href="<?php echo $navCloseTicket;?>"><i class="cus-accept"></i> Close Ticket</a>
				<?php endif;?>
				<?php if (isset($navEditLink) && $this->acl->can_write(null, 3 /*STS*/)) :?>
					<a class="btn btn-warning fixedsize btn-mini" href="<?php echo $navEditLink;?>"><i class="cus-page-white-edit"></i> Edit</a>
				<?php endif;?>
				<?php if (isset($navAddProgress)) :?>
					<a class="btn btn-primary fixedsize btn-mini" href="<?php echo $navAddProgress;?>" data-toggle="modal"><i class="cus-page-white-edit"></i> Add progress</a>
				<?php endif;?>
				<?php if (isset($navAddST) && true == $navAddST && $this->acl->can_write(null, 3 /*STS main resources*/)) :?>
					<a class="btn btn-primary fixedsize btn-mini" href="<?php echo site_url('/servicetickets/newST/')?>"><i class="cus-add"></i> Add Ticket</a>
				<?php endif;?>
			</div>
			<ul class="nav nav-tabs gradient-gray">
			    <li class="<?php echo (($this->router->fetch_method() == 'index')? 'active':'');?>">
			    	<a href="<?php echo site_url("servicetickets/");?>">Tickets</a>
			    </li>
			    <?php if ($this->router->fetch_method() == 'newST') {?>
			    <li class="active">
			    	<a href="<?php echo site_url("servicetickets/newST/");?>">New Ticket</a>
			    </li>
			    <?php }?>
			    <?php if ($this->router->fetch_method() == 'detailsST') {?>
			    <li class="active">
			    	<a href="<?php echo site_url("servicetickets/detailsST/");?>">Ticket Details</a>
			    </li>
			    <?php }?>
			    <?php if ($this->acl->can_view(null, 12 /*12 Alert system*/)) {?>
			    <li class="<?php echo ((in_array($this->router->fetch_method(), array('alerts', 'detailsAlert')))? 'active':'');?>">
			    	<a href="<?php echo site_url("servicetickets/alerts");?>">Alerts <?php echo (isset($countActiveAlerts) && $countActiveAlerts > 0 ? '<span class="active-alert blink_me">('.$countActiveAlerts. ')</span>': '')?></a>
			    </li>
			    <?php }?>
			</ul>
			<?php //echo show_menu();?>
			<div class="box-content container-fluid">
				<?php if(isset($CONTENT))$this->view($CONTENT);?>
			</div>
		</div>
	</div>
</div>
<div class="wraper-footer"></div>
<?php $this->view('template/tmpl_footer')?>