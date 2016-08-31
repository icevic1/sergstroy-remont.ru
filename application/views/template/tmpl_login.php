<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Welcome to Smart Mobile</title>
    <link rel="shortcut icon" href="<?php echo base_url('public/images/logo.png'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('public/css/bootstrap.min.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/smart/reset.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/smart/layout.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/smart/style.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/smart/icon.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('public/jq-ui/jquery-ui.css')?>" type="text/css" />
    
    <!--[if IE 7]> 
    <link rel="stylesheet" href="<?php echo base_url('public/css/smart/ie-7.css')?>" type="text/css" /> 
    <![endif]-->
    <!--[if IE 8]>
    <link rel="stylesheet" href="<?php echo base_url('public/css/smart/ie-8.css')?>" type="text/css" />
    <![endif]-->
	<script src="<?php echo base_url();?>public/js/jquery.min.js" type="text/javascript"></script>
</head>
<body class="login">
<!-- Begin Main -->
    <div class="ot-page">
        <div class="ot-main">
        	<div class="ot-section">
                <div class="ot-logo">
                    <a href="<?php echo site_url('home')?>" class="sm-logo-link direct-url" title="Smart mobile">
                    	<img src="<?php echo base_url('public/images/telecom_logo.png'); ?>" alt="Smart mobile" />
                    </a>
                    <span class="welcome">Welcome to Smart Care system!</span>
                </div>
            	<?php if(isset($CONTENT)) $this->view($CONTENT)?>
                <?php if(isset($html)) echo $html?>
                <!----------END DIALOG---------->
                <div class="ot-footer">
		        	<p>&copy;<?php echo date('Y');?> Smart Axiata Co., Ltd. All rights reserved.</p>
		            <?php //echo label('lbl_footer')?>
	        	</div>
            </div>
            
        </div>
        
    </div>
    <div class="clearfix"></div>
    <!-- End Main -->
	<script src="<?php echo base_url('public/js/jquery.cookie.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/jquery.inputmask.js'); ?>"></script>
    <script src="<?php echo base_url('public/jq-ui/jquery-ui.js');?>"></script>
    <script src="<?php echo base_url('public/js/bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/bootbox.min.js'); ?>"></script>
    <script type="text/javascript">
		$(document).ready(function(e) {
			$('.close').click(function(e) {
				$(this).closest(".ot-dialog").hide();
			});

			$('[data-toggle="tooltip"]').tooltip(); //{"placement":"top",delay: { show: 400, hide: 200 }}

			<?php if($this->session->userdata('msg')){?>
					bootbox.alert("<?php echo $this->session->userdata('msg');?>",'OK');
					//bootbox.dialog("<?php echo $this->session->userdata('msg')?>", [{"label" : "OK", "class" : "btn-danger btn-mini"}]);
			<?php $this->session->unset_userdata('msg');}?>
			
        }); //end ready()
    </script>
</body>
</html>