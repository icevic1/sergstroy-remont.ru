<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if(isset($PAGE_TITLE)) echo $PAGE_TITLE?></title>
    <link rel="shortcut icon" href="<?php echo base_url('public/images/logo.png'); ?>">
    <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/bootstrap-cerulean.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/charisma-app.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/jquery-ui-1.8.21.custom.css'); ?>" rel="stylesheet">
    <link href='<?php echo base_url('public/css/chosen.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/uniform.default.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/colorbox.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/jquery.cleditor.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/jquery.noty.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/noty_theme_default.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/elfinder.min.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/elfinder.theme.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/jquery.iphone.toggle.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/opa-icons.css'); ?>' rel='stylesheet'>
    <link href='<?php echo base_url('public/css/uploadify.css'); ?>' rel='stylesheet'>
    <link href="<?php echo base_url('public/css/cus-icons.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/css/bootstrap-datetimepicker.min.css')?>" rel="stylesheet"/>
    <link href="<?php echo base_url('public/css/bootstrap-timepicker.min.css')?>" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo base_url('public/css/admin.css'); ?>" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url('public/css/msgBoxLight.css')?>" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/1.10.7.jquery.dataTables.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/bootstrap3/bootstrap-switch.min.css'); ?>">
    <script src="<?php echo base_url('public/js/jquery-1.7.2.min.js'); ?>"></script>
    <script>
    	var BASE_URL = '<?php echo base_url();?>';
        var csrf_sc_name = '<?php echo $this->security->get_csrf_hash(); ?>';
    </script>
</head>
<body>