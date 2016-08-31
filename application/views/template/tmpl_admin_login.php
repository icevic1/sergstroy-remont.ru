<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if(isset($PAGE_TITLE)) echo $PAGE_TITLE?></title>
    <link rel="shortcut icon" href="<?php echo base_url('public/images/logo.png'); ?>">
    <link href="<?php echo base_url('public/css/bootstrap-cerulean.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('public/css/admin.css'); ?>" type="text/css"/>
    <script src="<?php echo base_url('public/js/jquery-1.7.2.min.js'); ?>"></script>
</head>
<body>     
 <?php if(isset($CONTENT)) $this->view($CONTENT)?>
 <!-- jQuery UI -->
<!-- application script for validation -->
<script src="<?php echo base_url('public/js/jquery.validate.js');?>"></script>
</body>
</html>